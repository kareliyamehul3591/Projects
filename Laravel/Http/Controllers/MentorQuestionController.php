<?php

namespace App\Http\Controllers;

use App\Models\Mentor_category;
use App\Models\Mentor_question;
use App\Models\Mentor_question_answer;
use App\Models\User;
use Illuminate\Http\Request;

class MentorQuestionController extends Controller
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
        return view('mentor_question.list',compact('status'));
    }

    public function mentorQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Mentor_question::join('users', 'mentor_questions.user_id', '=', 'users.id')
                                ->join('mentor_categories', 'mentor_questions.mentor_category_id', '=', 'mentor_categories.id')
                                ->join('mentor_sub_categories', 'mentor_questions.mentor_sub_category_id', '=', 'mentor_sub_categories.id')
                                ->join('mentor_topics', 'mentor_questions.mentor_topic_id', '=', 'mentor_topics.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "mentor_category_name") {
                    $values = $values->where('mentor_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "mentor_sub_category_name") {
                    $values = $values->where('mentor_sub_categories.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "mentor_topic") {
                    $values = $values->where('mentor_topics.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('mentor_questions.' . $opt, 'LIKE', "%$q[$key]%");
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
                        'mentor_questions.*',
                        'users.name',
                        'mentor_categories.mentor_category_name',
                        'mentor_sub_categories.mentor_sub_category_name',
                        'mentor_topics.mentor_topic',
                    ])->map(function ($recode) {
                $recode->show = route('mentor_question.show', [$recode->id]);
                $recode->edit = route('mentor_question.edit', [$recode->id]);
                $recode->delete = route('mentor_question.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $mentor_categories = Mentor_category::where('status', '1')->get();
        return view('mentor_question.create', compact('mentor_categories'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required',
            'mentor_category_id' => 'required',
            'mentor_sub_category_id' => 'required',
            'mentor_topic_id' => 'required',
            'tag' => 'required',
            'sticky_question' => 'nullable',
            'lock_question' => 'nullable',
        ]);

        $data['user_id'] = auth()->user()->id;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Mentor_question::create($data);

        return redirect()->route('mentor_question.index')->with('success', 'Mentor Question created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Mentor_question $mentor_question)
    {
        return view('mentor_question.show', compact('mentor_question'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Mentor_question $mentor_question)
    {
        $mentor_categories = Mentor_category::where('status', '1')->get();

        return view('mentor_question.edit', compact('mentor_question', 'mentor_categories'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Mentor_question $mentor_question)
    {
        $data = $request->validate([
            'mentor_answer' => 'nullable',
            'is_correct.*' => 'nullable',
        ]);
        foreach($mentor_question->mentor_question_answer as $answer){
            $answer->is_correct = in_array($answer->id, isset($data['is_correct']) ? $data['is_correct'] : [] ) ? 1 : 0;
            $answer->save();
        }

        if($data['mentor_answer'] != null){
            $mentor_question->mentor_question_answer()->create([
                'user_id' => auth()->id(),
                'mentor_answer' => $data['mentor_answer'],
                'mentor_question_id' => $mentor_question->id
            ]);
        }

        $user = User::where('id',$mentor_question->user_id)->first();
        $queData = [
            'institute_name' => auth()->user()->institutes->institute_name,
            'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
            'student_name' => $user->name,
            'student_email' => $user->email,
            'question_title' => $mentor_question->question,
            'created_date' => $mentor_question->created_at,
            'response_date' => $mentor_question->updated_at,
        ];
        $user->questionResponse($queData);
        return redirect()->route('mentor_question.index')->with('success', 'Mentor Question updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Mentor_question $mentor_question)
    {
        $mentor_question->delete();

        return redirect()->route('mentor_question.index')->with('success', 'Mentor Question deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function mentorQuestionMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Mentor_question::where('id', $id)->delete();
        }

        return redirect()->route('mentor_question.index')->with('success', 'Mentor Question deleted successfully');
    }

    public function commentDelete(Mentor_question_answer $mentor_question_answer)
    {
        $mentor_question_answer->delete();

        return redirect()->route('mentor_question.index')->with('success', 'Mentor Question Comment deleted successfully');
    }
}
