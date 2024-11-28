<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reported_question;
use Illuminate\Http\Request;

class ReportedQuestionController extends Controller
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
        return view('reported_question.list');
    }

    public function reportedQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Reported_question::join('questions', 'reported_questions.question_id', '=', 'questions.id')
        ->join('users', 'reported_questions.user_id', '=', 'users.id')
        ->join('institutes', 'users.institute_id', '=', 'institutes.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "question_title") {
                    $values = $values->where('questions.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                }else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('reported_questions.' . $opt, 'LIKE', "%$q[$key]%");
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
                'reported_questions.*',
                'questions.question_title',
                'users.name',
                'institutes.institute_name'
            ])->map(function ($recode) {
            $recode->show = route('reported_question.show', [$recode->id]);
            $recode->edit = route('question.edit', [$recode->question_id]);
            $recode->delete = route('reported_question.delete', [$recode->id]);
            return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Reported_question $reported_question)
    {
        $question = Question::where('id', $reported_question->question_id)->first();
        return view('reported_question.show', compact('reported_question','question'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Reported_question $reported_question)
    {
        $reported_question->delete();

        return redirect()->route('reported_question.index')->with('success', 'Reported question deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function reportedQuestionMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Reported_question::where('id', $id)->delete();
        }

        return redirect()->route('reported_question.index')->with('success', 'Reported question deleted successfully');
    }
}
