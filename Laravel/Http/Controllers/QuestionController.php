<?php

namespace App\Http\Controllers;

use App\Models\Difficulty_level;
use App\Models\Institutes;
use App\Models\Question;
use App\Models\Question_paragraph;
use App\Models\Question_type;
use App\Models\Subject;
use Illuminate\Http\Request;
use NumberFormatter;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $difficulty_levels = Difficulty_level::where('status', 1)->get();
        $question_types = Question_type::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $institutes = Institutes::where('status', 1)->get();
        return view('question.list', compact('status','institutes', 'difficulty_levels', 'question_types', 'subjects'));
    }

    public function questionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'questions.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'questions.chapter_id', '=', 'chapters.id')
            ->join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id');

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('difficulty_levels.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "created_from") {
                    $values = $values->whereDate('questions.created_at', '>=', $q[$key]);
                } else if ($opt == "created_to") {
                    $values = $values->whereDate('questions.created_at', '<=', $q[$key]);
                } else if ($opt == "difficulty_level_id" || $opt == "question_type_id") {
                    if ($q[$key]) {
                        $values = $values->whereIn('questions.' . $opt, explode(",", $q[$key]));
                    }
                } else {
                    $values = $values->where('questions.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'questions.*',
                    'subjects.subject_name',
                    'subject_grades.subject_grade_name',
                    'chapters.chapter_name',
                    'concepts.concept_name',
                    'difficulty_levels.name',
                ])->map(function ($recode) {
                $recode->show = route('question.show', [$recode->id]);
                $recode->edit = route('question.edit', [$recode->id]);
                $recode->delete = route('question.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create($id , Request $request)
    {
        $question_types = Question_type::where('id', $id)->first();
        if (!$question_types) {
            return redirect()->route('question.index');
        }
        $subjects = Subject::where('status', 1)->get();
        $difficulty_levels = Difficulty_level::where('status', 1)->get();
        return view('question.create', compact('request','question_types', 'subjects', 'difficulty_levels'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $validate = [
            'question_type_id' => 'required',
            'question_title' => 'required',
            'question_description' => 'nullable',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'concept_id' => 'required',
            'difficulty_level_id' => 'required',
            'explanation' => 'nullable',
            'is_published' => 'nullable',
            'is_approved' => 'nullable',
            'assertion' => 'nullable',
            'match' => 'nullable',
            'answer.*' => 'required',
            'question.*' => 'required',
            'q_answer.*.*' => 'required',
            'shortcut_solution' => 'nullable',
            'shortcut' => 'nullable',
            'language' => 'required',
        ];
        if ($request->question_type_id == 1 || $request->question_type_id == 2 || $request->question_type_id == 3) {
            $validate['isanswer'] = 'required';
        } else if ($request->question_type_id == 6) {
            foreach ($request->question as $key => $p_question) {
                $validate['isanswer.' . $key] = 'required';
            }
        }
        $data = $request->validate($validate, [
            'answer.*.required' => 'The answer field is required.',
            'isanswer.*.required' => 'The isanswer field is required.',
            'question.*.required' => 'The Question field is required.',
            'q_answer.*.*.required' => 'The answer field is required.',
        ]);

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        if (isset($request->is_approved)) {
            $data['approved_by'] = auth()->user()->id;
            $data['approved_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_approved'] = 0;
        }
        if (isset($request->assertion)) {
            $data['assertion'] = 1;
        } else {
            $data['assertion'] = 0;
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $data['institute_id'] = auth()->user()->institute_id;

        if ($data['question_type_id'] == 6) {
            $explanation = $data['explanation'];

            $question_paragraph = Question_paragraph::create([
                'question_paragraph' => $data['question_title'],
            ]);

            foreach ($data['question'] as $key => $p_question) {
                $data['explanation'] = $explanation[$key];
                $data['question_title'] = $p_question;
                $question = $question_paragraph->question()->create($data);

                $isanswer = '1';
                if (isset($request->isanswer[$key])) {
                    $isanswer = $request->isanswer[$key];
                }

                foreach ($data['q_answer'][$key] as $keys => $answer) {
                    $isanswers = (($keys == $isanswer) ? '1' : '0');
                    $question->option()->create([
                        'answer' => $answer,
                        'isanswer' => $isanswers,
                    ]);
                }
            }
        } else {
            $question = Question::create($data);
            if ($question->question_type_id == 2) {
                $isanswer = [];
            } else {
                $isanswer = '1';
            }
            if (isset($request->isanswer)) {
                $isanswer = $request->isanswer;
            }

            foreach ($data['answer'] as $key => $answer) {
                if ($question->question_type_id == 2) {
                    $isanswers = ((in_array($key, $isanswer)) ? '1' : '0');
                } else {
                    $isanswers = (($key == $isanswer) ? '1' : '0');
                }
                $question->option()->create([
                    'answer' => $answer,
                    'isanswer' => $isanswers,
                ]);
            }
        }
        return redirect()->route('question.create.id', [ 'id' => $question->question_type_id,'subject' => $question->subject_id,'subject_grade' => $question->subject_grade_id,'chapter' => $question->chapter_id])->with('success', 'Question created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Question $question)
    {
        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        $questions = Question::where('question_type_id',6)->get();
        $number = $previous = $next = $i = $j = 0;
        foreach ($questions as $key => $que) {
            if ($j != 0) {
                $next = $que->id;
                $j = 0;
            }
            if ($que->id == $question->id) {
                $number = $key + 1;
                $i++;
                $j++;
            }
            if ($i == 0) {
                $previous = $que->id;
            }
        }
        return view('question.show', compact('question','digit','next','previous'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Question $question)
    {
        $question_paragraph = null;
        if ($question->question_type_id == 6) {
            $question->question_title = $question->question_paragraph->question_paragraph;
            $question_paragraph = $question->question_paragraph;
        }

        $subjects = Subject::where('status', 1)->get();
        $difficulty_levels = Difficulty_level::where('status', 1)->get();

        return view('question.edit', compact('difficulty_levels', 'subjects', 'question', 'question_paragraph'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Question $question)
    {
        $validate = [
            'question_title' => 'required',
            'question_description' => 'nullable',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'concept_id' => 'required',
            'difficulty_level_id' => 'required',
            'explanation' => 'nullable',
            'is_published' => 'nullable',
            'is_approved' => 'nullable',
            'assertion' => 'nullable',
            'match' => 'nullable',
            'answer.*' => 'required',
            'question.*' => 'required',
            'q_answer.*.*' => 'required',
            'shortcut_solution' => 'nullable',
            'shortcut' => 'nullable',
            'language' => 'required',
        ];
        if ($question->question_type_id == 1 || $question->question_type_id == 2 || $question->question_type_id == 3) {
            $validate['isanswer'] = 'required';
        } else if ($question->question_type_id == 6) {
            foreach ($request->question as $key => $p_question) {
                $validate['isanswer.' . $key] = 'required';
            }
            $validate['question_id'] = 'required';
        }
        $data = $request->validate($validate, [
            'answer.*.required' => 'The answer field is required.',
            'isanswer.*.required' => 'The isanswer field is required.',
            'question.*.required' => 'The Question field is required.',
            'q_answer.*.*.required' => 'The answer field is required.',
        ]);

        // dd($data);
        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        if (isset($request->is_approved)) {
            $data['approved_by'] = auth()->user()->id;
            $data['approved_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_approved'] = 0;
        }
        if (isset($request->assertion)) {
            $data['assertion'] = 1;
        } else {
            $data['assertion'] = 0;
        }

        $data['updated_by'] = auth()->user()->id;
        $data['institute_id'] = auth()->user()->institute_id;

        if ($question->question_type_id == 6) {
            $explanation = $data['explanation'];

            $question_paragraph = Question_paragraph::where('id', $question->question_paragraph_id)->first();
            $question_paragraph->question_paragraph = $data['question_title'];
            $question_paragraph->save();

            foreach ($data['question_id'] as $key => $id) {
                $data['explanation'] = $explanation[$key];
                $data['question_title'] = $data['question'][$key];
                $question = $question_paragraph->question()->find($id);
                $question->update($data);

                $isanswer = '1';
                if (isset($request->isanswer[$key])) {
                    $isanswer = $request->isanswer[$key];
                }
                foreach ($data['q_answer'][$key] as $keys => $answer) {
                    $isanswers = (($keys == $isanswer) ? '1' : '0');

                    $option = $question->option()->where('id', $keys)->first();
                    $option->answer = $answer;
                    $option->isanswer = $isanswers;
                    $option->save();
                }
            }
        } else {
            $question->update($data);
            if ($question->question_type_id == 2) {
                $isanswer = [];
            } else {
                $isanswer = '1';
            }
            if (isset($request->isanswer)) {
                $isanswer = $request->isanswer;
            }

            foreach ($data['answer'] as $key => $answer) {
                if ($question->question_type_id == 2) {
                    $isanswers = ((in_array($key, $isanswer)) ? '1' : '0');
                } else {
                    $isanswers = (($key == $isanswer) ? '1' : '0');
                }
                $option = $question->option()->where('id', $key)->first();
                $option->answer = $answer;
                $option->isanswer = $isanswers;
                $option->save();
            }
        }

        return redirect()->route('question.index')->with('success', 'Question Updated successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('question.index')->with('success', 'Question deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function questionMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Question::where('id', $id)->delete();
        }

        return redirect()->route('question.index')->with('success', 'Question deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function questionMultiplePublish(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            $question = Question::where('id', $id)->first();
            if ($request->type == 'published') {
                $question->is_published = $request->status;
                if ($request->status == "1") {
                    $question->published_by = auth()->user()->id;
                    $question->published_at = date('Y-m-d H:i:s');
                }
            }
            if ($request->type == 'approved') {
                $question->is_approved = $request->status;
                if ($request->status == "1") {
                    $question->approved_by = auth()->user()->id;
                    $question->approved_at = date('Y-m-d H:i:s');
                }
            }
            if ($request->type == 'un_published') {
                $question->is_published = $request->status;
                if ($request->status == "0") {
                    $question->published_by = auth()->user()->id;
                    $question->published_at = date('Y-m-d H:i:s');
                }
            }
            $question->save();
        }

        return redirect()->route('question.index')->with('success', 'Question Change successfully');
    }

    public function order(Request $request)
    {
        $subjects = Subject::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'subject_id' => 'required',
                'subject_grade_id' => 'required',
                'chapter_id' => 'required',
                'concept_id' => 'required',
            ]);
            $questions = Question::where('subject_id', $data['subject_id'])
                ->where('subject_grade_id', $data['subject_grade_id'])
                ->where('chapter_id', $data['chapter_id'])
                ->where('concept_id', $data['concept_id'])
                ->where('question_type_id', 4)
                ->orderBy('order')->get();
        } else {
            $data = [];
            $questions = [];
        }
        return view('question.order', compact('data', 'questions', 'subjects'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Question::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('question.order')->with('success', 'Subjective Question Order successfully');
    }

    public function rejectReasonStore(Request $request)
    {
        $questions = Question::whereIn('id', explode(',',$request->ids))->get();
        foreach($questions as $question)
        {
            $que = Question::where('id',$question->id)->first();
            $que->is_rejected = 1;
            $que->rejected_by = auth()->user()->id;
            $que->rejected_at = date('Y-m-d H:i:s');
            $que->rejected_reason = $request->rejected_reason;
            $que->save();
        }
        return redirect()->route('question.index')->with('success', 'Question Rejected successfully');
    }
}
