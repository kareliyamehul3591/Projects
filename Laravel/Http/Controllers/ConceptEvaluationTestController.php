<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Concept_evaluation_test;
use App\Models\Question;
use App\Models\Sms_template;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\Subject_grade;

class ConceptEvaluationTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index()
    {
        return view('concept_evaluation_test.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function conceptEvaluationList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Concept_evaluation_test::join('subjects', 'concept_evaluation_tests.subject_id', '=', 'subjects.id')
        ->join('subject_grades', 'concept_evaluation_tests.subject_grade_id', '=', 'subject_grades.id')
        ->join('chapters', 'concept_evaluation_tests.chapter_id', '=', 'chapters.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('concept_evaluation_tests.' . $opt, 'LIKE', "%$q[$key]%");
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
                'concept_evaluation_tests.*',
                'subjects.subject_name',
                'subject_grades.subject_grade_name',
                'chapters.chapter_name',
                ])->map(function ($recode) {
                $recode->show = route('concept_evaluation_test.show', [$recode->id]);
                $recode->edit = route('concept_evaluation_test.edit', [$recode->id]);
                $recode->delete = route('concept_evaluation_test.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $subjects = Subject::where('status', 1)->get();
        return view('concept_evaluation_test.create', compact('subjects'));
    }

    public function conceptEvaluationTestList(Request $request,$id)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->where('questions.question_type_id',1)
            ->where('questions.is_published', 1)
            ->where('questions.is_approved', 1);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('difficulty_levels.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "difficulty_level_id") {
                    if ($q[$key]) {
                        $values = $values->whereIn('questions.' . $opt, explode(",", $q[$key]));
                    }
                } else if ($opt == "evaluation_test_id") {
                    $test = Concept_evaluation_test::find($q[$key]);
                    $ids = $test->questions()->pluck('question_id')->toArray();
                    $values = $values->whereIn('questions.id', $ids);
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
                    'concepts.concept_name',
                    'difficulty_levels.name',
                ])->map(function ($recode, $key) use ($id) {
                    $recode->allid = null;
                    
                    $test = Concept_evaluation_test::find($id);
                    
                    if($test){
                        $questions = $test->questions()->where('question_id',$recode->id)->first();
                        if($questions){
                            $recode->allid = $recode->id;
                        }
                    }
                    $recode->edit = route('question.edit', [$recode->id]);
                    $recode->key = $key+1;
                    return $recode;
                }),
        ]);
    }

    /**
     * Add the records in storage.
     */
    public function store(Request $request, Concept_evaluation_test $concept_evaluation_test)
    {
        $data = $request->validate([
            'concept_evaluation_test_name' => 'required',
            'duration' => 'required',
            'mark' => 'required',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'questions' => 'required',
            'is_published' => 'nullable'
        ]);

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $concept_evaluation_test = Concept_evaluation_test::create($data);

        foreach(explode(',',$data['questions']) as $questions){
            $concept_evaluation_test->questions()->create([
                'question_id' => $questions
            ]);
        }

        return redirect()->route('concept_evaluation_test.index')->with('success', 'Concept Evaluation Test created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Concept_evaluation_test $concept_evaluation_test)
    {
        $subjects = Subject::where('status', 1)->get();
        return view('concept_evaluation_test.edit', compact('concept_evaluation_test','subjects'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Concept_evaluation_test $concept_evaluation_test)
    {
        $data = $request->validate([
            'concept_evaluation_test_name' => 'required',
            'duration' => 'required',
            'mark' => 'required',
            'subject_id' => 'required',
            'subject_grade_id' => 'required',
            'chapter_id' => 'required',
            'questions' => 'required',
            'is_published' => 'nullable'
        ]);

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        $data['updated_by'] = auth()->user()->id;

        $concept_evaluation_test->update($data);

        $questions_id = [];
        foreach(explode(',',$data['questions']) as $questions){
            $questions = $concept_evaluation_test->questions()->create([
                'question_id' => $questions
            ]);
            $questions_id[] = $questions->id;
        }
        $concept_evaluation_test->questions()->whereNotIn('id', $questions_id)->delete();

        return redirect()->route('concept_evaluation_test.index')->with('success', 'Concept Evaluation Test updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Concept_evaluation_test $concept_evaluation_test)
    {
        $subject = Subject::where('id', $concept_evaluation_test->subject_id)->first();
        $subject_grade = Subject_grade::where('id', $concept_evaluation_test->subject_grade_id)->first();
        $chapter = Chapter::where('id', $concept_evaluation_test->chapter_id)->first();
        return view('concept_evaluation_test.show',compact('subject','concept_evaluation_test','subject_grade','chapter'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Concept_evaluation_test $concept_evaluation_test)
    {
        $concept_evaluation_test->delete();
        return redirect()->route('concept_evaluation_test.index')->with('success', 'Concept Evaluation Test deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function conceptEvaluationTestMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Concept_evaluation_test::where('id', $id)->delete();
        }

        return redirect()->route('concept_evaluation_test.index')->with('success', 'Concept Evaluation Test deleted successfully');
    }
}
