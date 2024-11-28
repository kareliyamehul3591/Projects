<?php

namespace App\Http\Controllers;

use App\Models\Daily_question;
use App\Models\Question;
use App\Models\Subject_grade;
use Illuminate\Http\Request;

class DailyQuestionController extends Controller
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
        $subject_grades = Subject_grade::where('status', 1)->get();
        return view('daily_question.list',compact('subject_grades'));
    }

    public function dailyQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'questions.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'questions.chapter_id', '=', 'chapters.id')
            ->leftjoin('daily_questions', 'questions.id', '=', 'daily_questions.question_id')
            ->where('questions.is_published', '1')
            ->where('questions.is_approved', '1');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        $day = 0;
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('difficulty_levels.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "difficulty_level_id") {
                    if ($q[$key]) {
                        $values = $values->whereIn('questions.' . $opt, explode(",", $q[$key]));
                    }
                } else if ($opt == "day") {
                    if($q[$key] == 'all'){
                        $values = $values->whereNotNull('daily_questions.day');
                    }else{
                        $values = $values->where('daily_questions.' . $opt, $q[$key]);
                    }
                    $day++;
                } else if ($opt == "subject_grade_id") {
                    $values = $values->whereIn('questions.' . $opt, explode(',',$q[$key]));
                } else {
                    $values = $values->where('questions.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }
        if($day == 0){
            $values = $values->whereNull('daily_questions.day');
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
                    'subjects.subject_name',
                    'subject_grades.subject_grade_name',
                    'chapters.chapter_name',
                    'daily_questions.day',
                ])->map(function ($recode) {
                    $recode->day = '<input class="form-control questions-day" data-id="'.$recode->id.'" name="day" type="text" value="'.$recode->day.'"  >';
                    $recode->edit = route('question.edit', [$recode->id]);
                    return $recode;
                }),
        ]);
    }

    /**
     * Add the multiple records in storage.
     */
    public function dailyQuestionMultipleAdd(Request $request)
    {
        $data = $request->validate([
            'questions' => 'required',
            'day' => 'required',
            'subject_grade_id' => 'required',
            'is_published' => 'nullable',
        ]);
        foreach (explode(',',$data['questions']) as $id) {
            $question = Question::find($id);
            $datas = [
                'subject_grade_id' => $question->subject_grade_id,
                'question_id' => $question->id,
                'created_by'=> auth()->user()->id,
                'day' => $request->input('day'),
                'updated_by'=> auth()->user()->id,
                'is_published'=> $data['is_published']
            ];
            if (isset($request->is_published)) {
                $datas['published_by'] = auth()->user()->id;
                $datas['published_at'] = date('Y-m-d H:i:s');
            } else {
                $datas['is_published'] = 0;
            }

            Daily_question::create($datas);
        }

        return redirect()->route('daily_question.index')->with('success', 'Daily Question Add successfully');
    }

    /**
     * Display a listing of all records.
     */
    public function bulk()
    {
        $subject_grades = Subject_grade::where('status', 1)->get();
        return view('daily_question.create',compact('subject_grades'));
    }

    /**
     * Add the multiple records in storage.
     */
    public function bulkQuestionMultipleAdd(Request $request)
    {
        $data = $request->validate([
            'questions' => 'required',
            'days' => 'required',
            'subject_grade_id' => 'required',
            'is_published' => 'nullable',
        ]);
        $days = [];
        foreach(explode('&',$data['days']) as $day){
            $val = explode('=',$day);
            if(count($val) == 2 && $val[1] != ''){
                $days[$val[0]] = $val[1];
            }
        }
        foreach (explode(',',$data['questions']) as $id) {
            $question = Question::find($id);
            $datas = [
                'subject_grade_id' => $question->subject_grade_id,
                'question_id' => $question->id,
                'created_by'=> auth()->user()->id,
                'day' => ((isset($days[$id]))?$days[$id]:0),
                'updated_by'=> auth()->user()->id
            ];
            if (isset($request->is_published)) {
                $datas['published_by'] = auth()->user()->id;
                $datas['published_at'] = date('Y-m-d H:i:s');
            } else {
                $datas['is_published'] = 0;
            }
            Daily_question::create($datas);
        }

        return redirect()->route('daily_question.index')->with('success', 'Bulk Question Add successfully');
    }

    /**
     * Display a listing of all records.
     */
    public function listing()
    {
        $subject_grades = Subject_grade::where('status', 1)->get();
        return view('daily_question.listing',compact('subject_grades'));
    }
}
