<?php

namespace App\Http\Controllers;

use App\Models\Advanced_study_question;
use App\Models\Benchmark_test;
use App\Models\Benchmark_test_question;
use App\Models\Concept_evaluation_test_question;
use App\Models\Detailed_study_question;
use App\Models\Institutes;
use App\Models\Package;
use App\Models\Question;
use App\Models\Reference_exam;
use App\Models\Sms_template;
use App\Models\Student;
use App\Models\Student_benchmark_test;
use App\Models\Student_package;
use App\Models\Subject_grade;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BenchmarkTestController extends Controller
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
        return view('benchmark_test.list');
    }

    public function benchmarkTestList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Benchmark_test::join('packages', 'benchmark_tests.package_id', '=', 'packages.id')
            ->leftJoin('institutes', 'benchmark_tests.institute_id', '=', 'institutes.id');

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_list") {
                    $values = $values->where('packages.package_name', 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_list") {
                    $values = $values->where('institutes.institute_name', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('benchmark_tests.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'benchmark_tests.*',
            ])->map(function ($recode) {
                $recode->show = route('benchmark_test.show', [$recode->id]);
                $recode->edit = route('benchmark_test.edit', [$recode->id]);
                $recode->delete = route('benchmark_test.delete', [$recode->id]);

                $package = Package::whereIn('id', explode(',', $recode->package_id))->pluck('package_name')->toArray();
                $recode->package_list = implode(', ', $package);

                $institute = Institutes::whereIn('id', explode(',', $recode->institute_id))->pluck('institute_name')->toArray();
                $recode->institute_list = implode(', ', $institute);

                $recode->marking_scheme = '+'.$recode->correct_mark.' , '.'-'.$recode->negative_mark;

                $recode->total_student = Student_benchmark_test::where('benchmark_test_id',$recode->id)->count();

                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Benchmark_test $benchmark_test)
    {
        return view('benchmark_test.show', compact('benchmark_test'));
    }

    public function benchmarkQuestionPaperTestList(Request $request, $id)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('concepts', 'questions.concept_id', '=', 'concepts.id')
            ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
            ->join('question_types', 'questions.question_type_id', '=', 'question_types.id')
            ->join('subjects', 'questions.subject_id', '=', 'subjects.id')
            ->join('subject_grades', 'questions.subject_grade_id', '=', 'subject_grades.id')
            ->join('chapters', 'questions.chapter_id', '=', 'chapters.id')
            ->where('questions.is_published', '1')
            ->where('questions.is_approved', '1');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_name") {
                    $values = $values->where('subjects.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "chapter_name") {
                    $values = $values->where('chapters.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "q_name") {
                    $values = $values->where('question_types.name', 'LIKE', "%$q[$key]%");
                } else if ($opt == "d_name") {
                    $values = $values->where('difficulty_levels.name', 'LIKE', "%$q[$key]%");
                } else if ($opt == "difficulty_level_id") {
                    if ($q[$key]) {
                        $values = $values->whereIn('questions.' . $opt, explode(",", $q[$key]));
                    }
                } else if ($opt == "benchmark_test_id") {
                    $test = Benchmark_test::find($q[$key]);
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
                    'difficulty_levels.name as d_name',
                    'question_types.name as q_name',
                    'subjects.subject_name',
                    'subject_grades.subject_grade_name',
                    'chapters.chapter_name',
                ])->map(function ($recode) use ($id) {
                $recode->allid = null;
                $test = Benchmark_test::find($id);
                if ($test) {
                    $questions = $test->questions()->where('question_id', $recode->id)->first();
                    if ($questions) {
                        $recode->allid = $recode->id;
                    }
                }
                $recode->edit = route('question.edit', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $institutes = Institutes::where('status', 1)->get();
        $packages = Package::where('status', 1)->get();
        $reference_exams = Reference_exam::where('status', 1)->get();
        return view('benchmark_test.create', compact('institutes', 'packages', 'reference_exams'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request, Benchmark_test $benchmark_test)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'duration' => 'required',
            'correct_mark' => 'required',
            'negative_mark' => 'required',
            'available_from' => 'required',
            'available_to' => 'required',
            'view_answer_from' => 'required',
            'total_question' => 'required',
            'add_question' => 'required',
            'subject_grade_id' => 'nullable',
            'questions' => 'required',
            'institute_id' => 'nullable',
            'package_id' => 'required',
            'for' => 'required',
            'result' => 'required',
            'reference_exam_id' => 'nullable',
            'is_published' => 'nullable',
        ]);
        if (count(explode(',', $data['questions'])) != $data['total_question']) {
            $request->validate([
                'total_question' => 'required|between:1000,1001',
            ], [
                'total_question.between' => 'Total questions and selected question must be same',
            ]);
        }
        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        $data['available_from'] = Carbon::parse($data['available_from'])->format('Y-m-d H:i:s');
        $data['available_to'] = Carbon::parse($data['available_to'])->format('Y-m-d H:i:s');
        $data['view_answer_from'] = Carbon::parse($data['view_answer_from'])->format('Y-m-d H:i:s');

        if (isset($data['institute_id'])) {
            $data['institute_id'] = implode(',', $data['institute_id']);
        }
        $data['package_id'] = implode(',', $data['package_id']);
        if (isset($data['subject_grade_id'])) {
            $data['subject_grade_id'] = implode(',', $data['subject_grade_id']);
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $benchmark_test = Benchmark_test::create($data);

        foreach (explode(',', $data['questions']) as $questions) {
            $benchmark_test->questions()->create([
                'question_id' => $questions,
            ]);
        }

        $templateData = [
            'name' => $benchmark_test->name,
            'test_name' => $benchmark_test->name,
        ];

        $student_packages = Student_package::whereIn('package_id', explode(',', $benchmark_test->package_id))->groupBy('user_id')->pluck('user_id')->toArray();
        $users = User::whereIn('id', $student_packages)->get();
        foreach ($users as $user) {
            $stestData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'test_name' => $benchmark_test->name,
                'valid_from' => $benchmark_test->available_from,
                'valid_to' => $benchmark_test->available_to,
            ];
            $user->sendTestEmailStudent($stestData);

            $template = Sms_template::find(8);
            $template->sendMessage($templateData);

            $student = Student::where('user_id', $user->id)->first();
            $puser = User::where('id', $student->parents()->user_id)->first();
            $testData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'test_name' => $benchmark_test->name,
                'valid_from' => $benchmark_test->available_from,
                'valid_to' => $benchmark_test->available_to,
                'parent_name' => $puser->name,
                'parent_email' => $puser->name,
            ];
            $puser->sendTestEmailParents($testData);

            $template = Sms_template::find(9);
            $template->sendMessage($templateData);
        }

        return redirect()->route('benchmark_test.index')->with('success', 'Benchmark Test created successfully.');
    }

    /**
     * Show the form for editing a record.
     */
    public function edit(Benchmark_test $benchmark_test)
    {
        $institutes = Institutes::where('status', 1)->get();
        $packages = Package::where('status', 1)->get();
        $reference_exams = Reference_exam::where('status', 1)->get();
        return view('benchmark_test.edit', compact('benchmark_test', 'institutes', 'packages', 'reference_exams'));
    }

    /**
     * Update a created record.
     */
    public function update(Request $request, Benchmark_test $benchmark_test)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'duration' => 'required',
            'correct_mark' => 'required',
            'negative_mark' => 'required',
            'available_from' => 'required',
            'available_to' => 'required',
            'view_answer_from' => 'required',
            'total_question' => 'required',
            'add_question' => 'nullable',
            'subject_grade_id' => 'nullable',
            'questions' => 'nullable',
            'institute_id' => 'nullable',
            'package_id' => 'required',
            'for' => 'required',
            'result' => 'required',
            'reference_exam_id' => 'nullable',
            'is_published' => 'nullable',
        ]);

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }
        $data['institute_id'] = implode(',', ((isset($data['institute_id'])) ? $data['institute_id'] : []));
        $data['package_id'] = implode(',', $data['package_id']);
        if (isset($data['subject_grade_id'])) {
            $data['subject_grade_id'] = implode(',', $data['subject_grade_id']);
        }
        $data['updated_by'] = auth()->user()->id;
        $data['available_from'] = Carbon::parse($data['available_from'])->format('Y-m-d H:i:s');
        $data['available_to'] = Carbon::parse($data['available_to'])->format('Y-m-d H:i:s');
        $data['view_answer_from'] = Carbon::parse($data['view_answer_from'])->format('Y-m-d H:i:s');

        $benchmark_test->update($data);

        $questions_id = [];
        foreach (explode(',', $data['questions']) as $questions) {
            $questions = $benchmark_test->questions()->create([
                'question_id' => $questions,
            ]);
            $questions_id[] = $questions->id;
        }
        $benchmark_test->questions()->whereNotIn('id', $questions_id)->delete();

        return redirect()->route('benchmark_test.index')->with('success', 'Benchmark Test updated successfully.');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Benchmark_test $benchmark_test)
    {
        $benchmark_test->delete();

        return redirect()->route('benchmark_test.index')->with('success', 'Benchmark Test deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function benchmarkTestMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Benchmark_test::where('id', $id)->delete();
        }

        return redirect()->route('benchmark_test.index')->with('success', 'Benchmark Test deleted successfully');
    }

    public function questionsBy(Request $request)
    {
        $type = $request->type;
        $id = $request->id;
        $data = [];
        $benchmark = Benchmark_test::find($request->BQid);
        if ($type == 'add_question') {
            $data = Subject_grade::where('status', 1)->get();
        } else if ($type == 'chapter') {
            $data = Subject_grade::find($id);
            // dd($data);
        }

        return view('benchmark_test.questionsBy', compact('type', 'id', 'data', 'benchmark'));
    }

    public function questionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        if($opts[0] == 'question_description')
        {
            $values = Question::join('subjects', 'questions.subject_id', '=', 'subjects.id')
                ->join('concepts', 'questions.concept_id', '=', 'concepts.id')
                ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
                ->join('question_types', 'questions.question_type_id', '=', 'question_types.id')
                ->whereIn('questions.question_type_id', [1, 5])
                ->where('questions.is_published', 1)
                ->where('questions.is_approved', 1);
        } else {
            $values = Question::join('subjects', 'questions.subject_id', '=', 'subjects.id')
                ->join('concepts', 'questions.concept_id', '=', 'concepts.id')
                ->join('difficulty_levels', 'questions.difficulty_level_id', '=', 'difficulty_levels.id')
                ->join('question_types', 'questions.question_type_id', '=', 'question_types.id')
                ->where('questions.chapter_id',$q[0])
                ->whereIn('questions.question_type_id', [1, 5])
                ->where('questions.is_published', 1)
                ->where('questions.is_approved', 1);

        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "benchmark_id") {
                    $question_id = Benchmark_test_question::where('benchmark_test_id', $q[$key])->pluck('question_id')->toArray();
                    $values = $values->whereIn('questions.id', $question_id);
                } else if ($opt == "concept_name") {
                    $values = $values->where('concepts.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "name") {
                    $values = $values->where('difficulty_levels.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "difficulty_level_id" || $opt == "question_type_id") {
                    if ($q[$key]) {
                        $values = $values->whereIn('questions.' . $opt, explode(",", $q[$key]));
                    }
                } else if ($opt == "code") {
                    $values = $values->where('question_types.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'question_types.code',
                ])->map(function ($recode) {
                $cet = Concept_evaluation_test_question::where('question_id', $recode->id)->first();
                $recode->cet = (($cet) ? 1 : 0);
                $ds = Detailed_study_question::where('question_id', $recode->id)->first();
                $recode->ds = (($ds) ? 1 : 0);
                $asq = Advanced_study_question::where('question_id', $recode->id)->first();
                $recode->asq = (($asq) ? 1 : 0);
                $recode->edit = route('question.edit', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    public function viewList()
    {
        return view('benchmark_test.testlist');
    }

    public function viewEvaluationTestList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Benchmark_test::join('packages', 'benchmark_tests.package_id', '=', 'packages.id')
            ->leftJoin('institutes', 'benchmark_tests.institute_id', '=', 'institutes.id')
            ->where('benchmark_tests.created_by', '>', 1);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_list") {
                    $values = $values->where('packages.package_name', 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_list") {
                    $values = $values->where('institutes.institute_name', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('benchmark_tests.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'benchmark_tests.*',
            ])->map(function ($recode) {
                $recode->show = route('view_evaluation.show', [$recode->id]);

                $package = Package::whereIn('id', explode(',', $recode->package_id))->pluck('package_name')->toArray();
                $recode->package_list = implode(', ', $package);

                $institute = Institutes::whereIn('id', explode(',', $recode->institute_id))->pluck('institute_name')->toArray();
                $recode->institute_list = implode(', ', $institute);

                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function showList(Benchmark_test $benchmark_test)
    {
        return view('benchmark_test.showlist', compact('benchmark_test'));
    }
}
