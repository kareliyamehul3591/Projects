<?php

namespace App\Http\Controllers;

use App\Exports\QuestionExport;
use App\Imports\QuestionImport;
use App\Models\Difficulty_level;
use App\Models\Institutes;
use App\Models\Question;
use App\Models\Question_type;
use App\Models\Subject;
use App\Models\User;
use Excel;
use Illuminate\Http\Request;
use Str;

class MyQuestionController extends Controller
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
        $difficulty_levels = Difficulty_level::where('status', 1)->get();
        $question_types = Question_type::where('status', 1)->get();
        $subjects = Subject::where('status', 1)->get();
        $institutes = Institutes::where('status', 1)->get();
        $users = User::where('group_id', 2)->get();
        return view('myquestion.list', compact('difficulty_levels', 'question_types', 'subjects', 'institutes', 'users'));
    }

    public function my_questionList(Request $request)
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
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->where('questions.created_by', auth()->user()->id);

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
                    if ($q) {
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
            "Records" => $values->offset($start)->limit($limit)->get(['questions.*', 'subjects.subject_name', 'subject_grades.subject_grade_name', 'chapters.chapter_name', 'concepts.concept_name', 'difficulty_levels.name'])->map(function ($recode) {
                $recode->show = route('question.show', [$recode->id]);
                $recode->edit = route('question.edit', [$recode->id]);
                $recode->delete = route('question.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    public function bulkUpload()
    {
        return view('myquestion.bulk_upload');
    }

    public function bulkUploadExport()
    {
        //return Excel::download(new UsersExport, 'users.xlsx');
        return Excel::download(new QuestionExport, 'Question.xlsx');
    }

    public function bulkUploadImport(Request $request)
    {
        $mimes = 'xlsx';
        /*
        foreach (config_data('xlsx') as $xlsx => $icon) {
        $mimes .= $xlsx . ',';
        }
         */
        $request->validate([
            'import' => 'required|mimes:' . $mimes . '|max:2048',
        ]);

        $is_published = ((isset($request->is_published))?1:0);
        $import_name = 'test.xlsx';
        if ($request->hasFile('import')) {
            $import_name = Str::uuid() . '.' . $request->import->getClientOriginalExtension();
            $request->import->storeAs('public/import/question', $import_name);
        }
        Excel::import(new QuestionImport($is_published), storage_path('app/public/import/question/' . $import_name));
        return redirect()->back()->with('success', 'Question Data has been added successfully!');
    }
}
