<?php

namespace App\Http\Controllers;

use App\Models\Advance_study_question;
use App\Models\Advanced_study;
use App\Models\Advanced_study_question;
use App\Models\Concept_evaluation_test_question;
use App\Models\Detailed_study_question;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;

class AdvanceStudyQuestionController extends Controller
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
        return view('advance_study_question.list');
    }

    public function advancedStudyIndexList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Advanced_study::join('subjects', 'advanced_studies.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'advanced_studies.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'advanced_studies.chapter_id', '=', 'chapters.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('advanced_studies.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'advanced_studies.*',
                    'subjects.subject_name',
                    'subject_grades.subject_grade_name',
                    'chapters.chapter_name',
                ])->map(function ($recode) {
                $recode->edit = route('advanced_study.edit', [$recode->id]);
                $recode->delete = route('advanced_study.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Display a listing of all records.
     */
    public function create()
    {
        $subjects = Subject::where('status', 1)->get();
        return view('advance_study_question.create', compact('subjects'));
    }

    public function advanceQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->whereIn('questions.question_type_id',[1,2,5,6])
            ->where('questions.is_published', '1')
            ->where('questions.is_approved', '1');

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
                ])->map(function ($recode) {
                    $recode->allid = null;
                    $question = Advanced_study_question::where('question_id', $recode->id)->first();
                    if ($question) {
                        $recode->allid = $recode->id;
                    }
                    $cet = Concept_evaluation_test_question::where('question_id', $recode->id)->first();
                    $recode->cet = (($cet) ? 1 : 0);
                
                    $dsq = Detailed_study_question::where('question_id', $recode->id)->first();
                    $recode->dsq = (($dsq) ? 1 : 0);
                    $recode->edit = route('question.edit', [$recode->id]);
                    return $recode;
                }),
        ]);
    }

    /**
     * Add the multiple records in storage.
     */
    public function advanceQuestionMultipleAdd(Request $request)
    {
        $ids = $request->input('ids');

        $advancedStudy = Advanced_study::create([
            'subject_id' => $request->input('subject_id'),
            'subject_grade_id' => $request->input('subject_grade_id'),
            'chapter_id' => $request->input('chapter_id'),
            'total_question' => count($ids),
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        foreach ($ids as $id) {
            $question = Question::find($id);
            $advancedStudy->advanced_study_question()->create([
                'subject_id' => $question->subject_id,
                'subject_grade_id' => $question->subject_grade_id,
                'chapter_id' => $question->chapter_id,
                'question_id' => $question->id,
            ]);
        }

        return redirect()->route('advanced_study.index')->with('success', 'Advance Study Add successfully');
    }

    public function order(Request $request)
    {
        $subjects = Subject::where('status', 1)->get();
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'subject_id' => 'required',
                'subject_grade_id' => 'required',
                'chapter_id' => 'required',
            ]);
            $study_questions = Advanced_study_question::where('subject_id', $data['subject_id'])
                ->where('subject_grade_id', $data['subject_grade_id'])
                ->where('chapter_id', $data['chapter_id'])
                ->orderBy('order')->get();
        } else {
            $data = [];
            $study_questions = [];
        }
        return view('advance_study_question.order', compact('data', 'study_questions', 'subjects'));
    }

    public function rowOrder(Request $request)
    {
        foreach (explode(",", $request->row_order) as $key => $id) {
            $key++;
            $data = Advanced_study_question::where('id', $id)->first();
            $data->order = $key;
            $data->save();
        }
        return redirect()->route('advanced_study.order')->with('success', 'Advanced Study Order successfully');
    }

    /**
     * Display a listing of all records.
     */
    public function edit(Advanced_study $advanced_study)
    {
        return view('advance_study_question.edit', compact('advanced_study'));
    }

    public function advancedStudyQuestionIndexEdit(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->whereIn('questions.question_type_id', [1, 2])
            ->where('questions.is_published', '1')
            ->where('questions.is_approved', '1');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('difficulty_levels.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_id") {
                    $values = $values->where('questions.chapter_id', $q[$key]);
                } else if ($opt == "difficulty_level_id") {
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
                    'concepts.concept_name',
                    'difficulty_levels.name',
                ])->map(function ($recode) {
                $recode->allid = null;
                $question = Advanced_study_question::where('question_id', $recode->id)->first();
                if ($question) {
                    $recode->allid = $recode->id;
                }
                $cet = Concept_evaluation_test_question::where('question_id', $recode->id)->first();
                $recode->cet = (($cet) ? 1 : 0);
                
                $dsq = Detailed_study_question::where('question_id', $recode->id)->first();
                $recode->dsq = (($dsq) ? 1 : 0);
                $recode->edit = route('question.edit', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Add the multiple records in storage.
     */
    public function advancedStudyQuestionMultipleUpdate(Request $request, Advanced_study $advanced_study)
    {
        $ids = $request->input('ids');

        $advanced_study->update([
            'total_question' => count($ids),
            'updated_by' => auth()->user()->id,
        ]);

        foreach ($ids as $id) {
            $question = Question::find($id);
            $advanced_study->advanced_study_question()->updateOrcreate([
                'subject_id' => $question->subject_id,
                'subject_grade_id' => $question->subject_grade_id,
                'chapter_id' => $question->chapter_id,
                'question_id' => $question->id,
            ]);
        }
        $advanced_study->advanced_study_question()->whereNotIn('question_id', $ids)->delete();
        return redirect()->route('advanced_study.index')->with('success', 'Advanced Study Update successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Advanced_study $advanced_study)
    {
        $advanced_study->delete();

        return redirect()->route('advanced_study.index')->with('success', 'Advanced Study deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function advancedStudyQuestionMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Advanced_study::where('id', $id)->delete();
        }

        return redirect()->route('advanced_study.index')->with('success', 'Advanced Study deleted successfully');
    }
}
