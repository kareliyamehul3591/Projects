<?php

namespace App\Http\Controllers;

use App\Models\Advanced_test;
use App\Models\App_test;
use App\Models\Benchmark_test;
use App\Models\Benchmark_test_question;
use App\Models\Chapter;
use App\Models\Concept_evaluation_test;
use App\Models\Difficulty_level;
use App\Models\Evaluation_test;
use App\Models\Evaluation_test_question;
use App\Models\General_test;
use App\Models\Institutes;
use App\Models\Mock_test;
use App\Models\Occupation;
use App\Models\Package;
use App\Models\Package_price;
use App\Models\Package_subject_grade;
use App\Models\Question;
use App\Models\Question_type;
use App\Models\Refund;
use App\Models\Student;
use App\Models\Student_app_test;
use App\Models\Student_benchmark_test;
use App\Models\Student_benchmark_test_question;
use App\Models\Student_concept_evaluation_test;
use App\Models\Student_daily_test;
use App\Models\Student_detailed_practice;
use App\Models\Student_evaluation_test;
use App\Models\Student_evaluation_test_question;
use App\Models\Student_mock_test;
use App\Models\Student_package;
use App\Models\Subject_grade;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function studentProgress()
    {
        $institutes = Institutes::where('status', 1)->get();
        return view('student_report.view', compact('institutes'));
    }

    public function reportProgressList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('group_id', 4);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute') {
                    $values = $values->where('users.institute_id', $q[$key]);
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $mt = Student_mock_test::where('user_id', $recode->id)->count();
                $recode->mt = $mt;

                $st = Student_mock_test::where('user_id', $recode->id)->where('non_nta_type', 'split')->count();
                $recode->st = $st;

                $bt = Student_benchmark_test::where('user_id', $recode->id)->count();
                $recode->bt = $bt;

                $et = Student_evaluation_test::where('user_id', $recode->id)->count();
                $recode->et = $et;

                $at = Student_app_test::where('user_id', $recode->id)->count();
                $recode->at = $at;

                $dt = Student_daily_test::where('user_id', $recode->id)->count();
                $recode->dt = $dt;

                $stu = Student::where('user_id', $recode->id)->first();
                $teacher = Teacher::where('id', $stu->teacher_id)->first();
                $recode->teacher_name = $teacher->user->name;

                return $recode;
            }),
        ]);
    }

    public function chapterProgressList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('group_id', 4);
        $chapter_id = 0;
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else if ($opt == 'teacher_id') {
                    $teacherUser = Student::where('teacher_id', $q[$key])->first();
                    if ($teacherUser) {
                        $teacher_id = $teacherUser->teacher_id;
                        $values = $values->whereHas('student', function ($query) use($teacher_id) {
                            $query->where('teacher_id',$teacher_id);
                        });
                    }
                } else if ($opt == 'chapter_id') {
                    $chapter_id = $q[$key];
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) use($chapter_id) {

                $cet_id = Concept_evaluation_test::where('chapter_id',$chapter_id)->pluck('id')->toArray();
                $cet = Student_concept_evaluation_test::whereIn('concept_evaluation_test_id', $cet_id)->where('user_id', $recode->id)->count();
                $recode->cet = $cet;

                $ds = Student_detailed_practice::where('user_id', $recode->id)->where('chapter_id',$chapter_id)->count();
                $recode->ds = $ds;

                $stu = Student::where('user_id', $recode->id)->first();
                $teacher = Teacher::where('id', $stu->teacher_id)->first();
                $recode->teacher_name = $teacher->user->name;

                return $recode;
            }),
        ]);
    }

    public function studentProgressList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Chapter;

        $user_id = 0;
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_ids') {
                    $values = $values->where('users.institute_id',$q[$key]);
                } else if ($opt == 'student_ids') {
                    $user_id = $q[$key];
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) use ($user_id) {

                $recode->cet = '<i class="fa fa-ban text-red"></i>';
                if ($recode->concept_evolution_test_id) {
                    $scet = Student_concept_evaluation_test::where('concept_evaluation_test_id', $recode->concept_evolution_test_id)->where('user_id', $user_id)->count();
                    if ($scet > 0) {
                        $recode->cet = '<i class="fa fa-check text-green"></i>';
                    }
                }

                $recode->dp = 0;

                return $recode;
            }),
        ]);
    }

    public function targetProgressList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Student_package::join('users','student_packages.user_id','=','users.id')
            ->join('packages','student_packages.package_id','=','packages.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute') {
                    $values = $values->where('users.institute_id', $q[$key]);
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $recode->question = 0;

                $package = Package::where('id',$recode->package_id)->first();
                $recode->package_name = $package->package_name;

                $package_subject_grades = Package_subject_grade::where('package_id', $recode->package_id)
                    ->pluck('subject_grade_id')
                    ->toArray();
                $chapter = Chapter::whereIn('subject_grade_id', $package_subject_grades)
                    ->pluck('concept_evaluation_test_id')
                    ->toArray();

                $mt = Mock_test::where('package_id', $recode->package_id)->pluck('id')->toArray();
                $bt = Benchmark_test::where('package_id', $recode->package_id)->pluck('id')->toArray();

                $recode->cet = Student_concept_evaluation_test::whereIn('concept_evaluation_test_id',$chapter)->where('user_id',$recode->user_id)->count();
                $recode->mock = Student_mock_test::whereIn('mock_test_id',$mt)->where('user_id',$recode->user_id)->count();
                $recode->benchmark = Student_benchmark_test::whereIn('benchmark_test_id',$bt)->where('user_id',$recode->user_id)->count();

                return $recode;
            }),
        ]);
    }

    public function benchmarkTestAnalysis()
    {
        $institutes = Institutes::where('status', 1)->get();
        return view('student_report.benchmark', compact('institutes'));
    }

    public function benchmarkStudentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = User::join('student_benchmark_tests', 'users.id', '=', 'student_benchmark_tests.user_id')
            ->Join('institutes', 'users.institute_id', '=', 'institutes.id')
            ->where('group_id', 4);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else if ($opt == 'benchmark_test_id') {
                    $values = $values->where('student_benchmark_tests.benchmark_test_id', $q[$key]);
                } else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'users.*',
                    'student_benchmark_tests.*',
                    'institutes.institute_name',
                ])->map(function ($recode) {

                $total = $recode->correct_question + $recode->incorrect_question;
                $recode->attempt_que = $total;

                $bt = Benchmark_test::where('id', $recode->benchmark_test_id)->first();
                $correct_mark = $recode->correct_question * $bt->correct_mark;
                $incorrect_mark = $recode->incorrect_question * $bt->negative_mark;

                $recode->correct_mark = $correct_mark;
                $recode->incorrect_mark = $incorrect_mark;

                return $recode;
            }),
        ]);
    }

    public function benchmarkQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = Benchmark_test_question::join('benchmark_tests', 'benchmark_test_questions.benchmark_test_id', '=', 'benchmark_tests.id')
            ->join('questions', 'benchmark_test_questions.question_id', '=', 'questions.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->where('benchmark_tests.institute_id', $q[$key]);
                    $values = $values->where('questions.institute_id', $q[$key]);
                } else if ($opt == 'benchmark_test_id') {
                    $values = $values->where('benchmark_test_questions.benchmark_test_id', $q[$key]);
                }
            }
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get([
                'questions.*',
                'benchmark_test_questions.benchmark_test_id',
            ])->map(function ($recode) {
                $test = Student_benchmark_test_question::join('student_benchmark_tests', 'student_benchmark_test_questions.student_benchmark_test_id', '=', 'student_benchmark_tests.id')
                    ->where('student_benchmark_test_questions.question_id', $recode->id)
                    ->where('student_benchmark_tests.benchmark_test_id', $recode->benchmark_test_id)
                    ->get();
                $recode->right = $test->where('is_correct', 1)->count();
                $recode->wrong = $test->where('is_correct', 0)->where('status', 1)->count();
                $recode->not_attempted = $test->where('status', 0)->count();
                $recode->edit = route('question.edit', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    public function evaluationTestAnalysis()
    {
        $institutes = Institutes::where('status', 1)->get();
        return view('student_report.evaluation', compact('institutes'));
    }

    public function evaluationStudentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = User::join('student_evaluation_tests', 'users.id', '=', 'student_evaluation_tests.user_id')
            ->Join('institutes', 'users.institute_id', '=', 'institutes.id')
            ->where('group_id', 4);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->where('users.institute_id', $q[$key]);
                } else if ($opt == 'evaluation_test_id') {
                    $values = $values->where('student_evaluation_tests.evaluation_test_id', $q[$key]);
                } else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'users.*',
                    'student_evaluation_tests.*',
                    'institutes.institute_name',
                ])->map(function ($recode) {

                $total = $recode->correct_question + $recode->incorrect_question;
                $recode->attempt_que = $total;

                $bt = Evaluation_test::where('id', $recode->evaluation_test_id)->first();
                $correct_mark = $recode->correct_question * $bt->correct_mark;
                $incorrect_mark = $recode->incorrect_question * $bt->negative_mark;

                $recode->correct_mark = $correct_mark;
                $recode->incorrect_mark = $incorrect_mark;

                return $recode;
            }),
        ]);
    }

    public function evaluationQuestionList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = Evaluation_test_question::join('evaluation_tests', 'evaluation_test_questions.evaluation_test_id', '=', 'evaluation_tests.id')
            ->join('questions', 'evaluation_test_questions.question_id', '=', 'questions.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->where('evaluation_tests.institute_id', $q[$key]);
                    $values = $values->where('questions.institute_id', $q[$key]);
                } else if ($opt == 'evaluation_test_id') {
                    $values = $values->where('evaluation_test_questions.evaluation_test_id', $q[$key]);
                }
            }
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get([
                'questions.*',
                'evaluation_test_questions.evaluation_test_id',
            ])->map(function ($recode) {
                $test = Student_evaluation_test_question::join('student_evaluation_tests', 'student_evaluation_test_questions.student_evaluation_test_id', '=', 'student_evaluation_tests.id')
                    ->where('student_evaluation_test_questions.question_id', $recode->id)
                    ->where('student_evaluation_tests.evaluation_test_id', $recode->evaluation_test_id)
                    ->get();
                $recode->right = $test->where('is_correct', 1)->count();
                $recode->wrong = $test->where('is_correct', 0)->where('status', 1)->count();
                $recode->not_attempted = $test->where('status', 0)->count();
                $recode->edit = route('question.edit', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    public function listing()
    {
        return view('student_report.listing');
    }

    public function teacherReportList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $created_user = User::get();
        $values = User::where('group_id', 3);

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
            $created_user = $created_user->where('institute_id', auth()->user()->institute_id);
        }

        $status = [];
        $created_by = [];
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "from") {
                    $values = $values->whereDate('users.created_at', '>=', $q[$key]);
                } else if ($opt == "to") {
                    $values = $values->whereDate('users.created_at', '<=', $q[$key]);
                } else if ($opt == "status") {
                    $status[] = $q[$key];
                } else if ($opt == "created_by") {
                    $user = $created_user->where('group_id', $q[$key])->pluck('id')->toArray();
                    $created_by = array_merge($created_by, $user);
                }
            }
        }
        if (count($status) > 0) {
            $values = $values->whereIn('users.status', $status);
        }
        if (count($created_by) > 0) {
            $values = $values->whereIn('users.created_by', $created_by);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $user = User::where('id', $recode->created_by)->first();
                if (!$user) {
                    dd($recode);
                }
                $name = $user->name;
                $recode->created = $name . '/' . $recode->created_at->format('d-m-Y');

                $recode->last_login = $recode->last_login_at->format('d-m-Y H:i:s') . '/' . $recode->ip_address;

                $teacher = Teacher::where('user_id', $recode->id)->first();
                $stu_count = Student::where('teacher_id', $teacher->id)->count();
                $recode->student = $stu_count;

                $occupation = Occupation::where('id', $teacher->occupation_id)->first();
                $recode->occupation_name = $occupation->occupation_name;

                $que = Question::get();
                $creat_que = $que->where('created_by', $recode->id)->count();
                $approve_que = $que->where('approved_by', $recode->id)->count();
                $publish_que = $que->where('published_by', $recode->id)->count();
                $rejected_que = $que->where('rejected_by', $recode->id)->count();
                $recode->created_que = $creat_que;
                $recode->approved_que = $approve_que;
                $recode->published_que = $publish_que;
                $recode->rejected_que = $rejected_que;

                return $recode;
            }),
        ]);
    }

    public function studentReportList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $created_user = User::get();
        $values = User::where('group_id', 4);

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
            $created_user = $created_user->where('institute_id', auth()->user()->institute_id);
        }

        $status = [];
        $created_by = [];
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "from") {
                    $values = $values->whereDate('users.created_at', '>=', $q[$key]);
                } else if ($opt == "to") {
                    $values = $values->whereDate('users.created_at', '<=', $q[$key]);
                } else if ($opt == "status") {
                    $status[] = $q[$key];
                } else if ($opt == "created_by") {
                    $user = $created_user->where('group_id', $q[$key])->pluck('id')->toArray();
                    $created_by = array_merge($created_by, $user);
                }
            }
        }
        if (count($status) > 0) {
            $values = $values->whereIn('users.status', $status);
        }
        if (count($created_by) > 0) {
            $values = $values->whereIn('users.created_by', $created_by);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $institutes = Institutes::where('id', $recode->institute_id)->first();
                $recode->institute_name = $institutes->institute_name;

                $package = Student_package::where('user_id', $recode->id)->count();
                $recode->package = $package;

                $payment = Student_package::where('user_id', $recode->id)->sum('package_price');
                $recode->payment = $payment;

                $user = User::where('id', $recode->created_by)->first();
                if (!$user) {
                    dd($recode);
                }
                $name = $user->name;
                $recode->created = $name . '/' . $recode->created_at->format('d-m-Y');

                $recode->last_login = $recode->last_login_at->format('d-m-Y H:i:s') . '/' . $recode->ip_address;

                return $recode;
            }),
        ]);
    }

    public function listingChart(Request $request)
    {
        if ($request->report == 'teacher')
        {
            if (auth()->user()->institute_id != 1) {
                $user = User::where('institute_id', auth()->user()->institute_id)->where('group_id', 3)->get();
            } else {
                $user = User::where('group_id', 3)->get();
            }
            $active = $user->where('status', 1)->count();
            $inactive = $user->where('status', 0)->count();

            return response()->json([
                'name' => 'Teacher',
                'active' => $active,
                'inactive' => $inactive,
                'data' => $request->all(),
            ]);
        } else if ($request->report == 'student')
        {
            if (auth()->user()->institute_id != 1) {
                $user = User::where('institute_id', auth()->user()->institute_id)->where('group_id', 4)->get();
            } else {
                $user = User::where('group_id', 4)->get();
            }
            $active = $user->where('status', 1)->count();
            $inactive = $user->where('status', 0)->count();

            return response()->json([
                'name' => 'Student',
                'active' => $active,
                'inactive' => $inactive,
                'data' => $request->all(),
            ]);
        } else if ($request->report == 'question')
        {
            if (auth()->user()->institute_id != 1) {
                $que = Question::where('institute_id', auth()->user()->institute_id)->get();
            } else {
                $que = Question::get();
            }
            $publish = $que->where('is_published', 1)->count();
            $unpublish = $que->where('is_published', 0)->count();
            $approved = $que->where('is_approved', 1)->count();
            $rejected = $que->where('is_approved', 0)->count();

            $question = Question::groupBy('subject_grade_id')
                ->pluck('subject_grade_id')
                ->toArray();
            $subjectGradeName = [];
            $time_chart['series'] = [];
            foreach($question as $key => $q){
                $sub = Subject_grade::where('id',$q)->first();
                $subjectGradeName[] = $sub->subject_grade_name;
                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data[] = $que->where('subject_grade_id',$q)->count();
                $time_chart['series'][] = [
                    'name' => $sub->subject_grade_name,
                    'data' => $data
                ];
            }

            $question4 = Question::groupBy('created_by')
                ->pluck('created_by')
                ->toArray();
            $teacherName = [];
            $time_chart['series4'] = [];
            $j = 0;
            foreach($question4 as $q4){
                $user1 = User::where('id',$q4)->where('group_id',3)->first();
                if($user1 == null){
                    continue;
                } else {
                    $teacherName[] = $user1->name;
                    $data = [];
                    for ($i=0; $i < $j; $i++) {
                        $data[] = '';
                    }
                    $j++;
                    $data[] = Question::where('created_by',$user1->id)->count();
                    $time_chart['series4'][] = [
                        'name' => $user1->name,
                        'data' => $data
                    ];
                }
            }

            $question1 = Question::groupBy('difficulty_level_id')
                ->pluck('difficulty_level_id')
                ->toArray();
            $difficultyName = [];
            $time_chart['series1'] = [];
            foreach($question1 as $key => $q1){
                $difficulty = Difficulty_level::where('id',$q1)->first();
                $difficultyName[] = $difficulty->name;
                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data[] = $que->where('difficulty_level_id',$q1)->count();
                $time_chart['series1'][] = [
                    'name' => $difficulty->name,
                    'data' => $data
                ];
            }

            $question2 = Question::groupBy('question_type_id')
                ->pluck('question_type_id')
                ->toArray();
            $questionTypeName = [];
            $time_chart['series2'] = [];
            foreach($question2 as $key => $q2){
                $que_type = Question_type::where('id',$q2)->first();
                $questionTypeName[] = $que_type->name;
                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data[] = $que->where('question_type_id',$q2)->count();
                $time_chart['series2'][] = [
                    'name' => $que_type->name,
                    'data' => $data
                ];
            }

            $question3 = Question::groupBy('institute_id')
                ->pluck('institute_id')
                ->toArray();
            $instituteName = [];
            $time_chart['series3'] = [];
            foreach($question3 as $key => $q3){
                $institute = Institutes::where('id',$q3)->first();
                $instituteName[] = $institute->institute_name;
                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data[] = $que->where('institute_id',$q3)->count();
                $time_chart['series3'][] = [
                    'name' => $institute->institute_name,
                    'data' => $data
                ];
            }

            return response()->json([
                'name' => 'Question',
                'active' => $publish,
                'inactive' => $unpublish,
                'approved' => $approved,
                'rejected' => $rejected,
                'subjectGradeNames' => $subjectGradeName,
                'difficultyNames' => $difficultyName,
                'questionTypeNames' => $questionTypeName,
                'instituteNames' => $instituteName,
                'teacherNames' => $teacherName,
                'series' => $time_chart['series'],
                'series1' => $time_chart['series1'],
                'series2' => $time_chart['series2'],
                'series3' => $time_chart['series3'],
                'series4' => $time_chart['series4'],
                'data' => $request->all(),
            ]);
        } else if ($request->report == 'package')
        {
            $st_package = Student_package::get();
            $active = $st_package->where('expiry_date', '>=' ,Carbon::now()->toDateString())->count();
            $inactive = $st_package->where('expiry_date', '<' ,Carbon::now()->toDateString() )->count();

            $pac_price = Package_price::get();
            $comprehensive = $pac_price->where('package_module_id',1)->count();
            $study = $pac_price->where('package_module_id',2)->count();
            $test = $pac_price->where('package_module_id',3)->count();

            $comprehensive_sale = $st_package->where('package_module_id',1)->count();
            $study_sale = $st_package->where('package_module_id',2)->count();
            $test_sale = $st_package->where('package_module_id',3)->count();

            $comprehensive_amount = $st_package->where('package_module_id',1)->sum('package_price');
            $study_amount = $st_package->where('package_module_id',2)->sum('package_price');
            $test_amount = $st_package->where('package_module_id',3)->sum('package_price');

            $package_prices = Package_price::groupBy('package_id')
                ->pluck('package_id')
                ->toArray();
            $packageName = [];
            $time_chart['series'] = [];
            $time_chart['series1'] = [];
            foreach($package_prices as $key => $package){

                $package_name = Package::where('id',$package)->first();

                $packageName[] = $package_name->package_name;

                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data2 = $data;
                $data[] = $st_package->where('package_id',$package)->count();
                $time_chart['series'][] = [
                    'name' => $package_name->package_name,
                    'data' => $data
                ];
                $data2[] = $st_package->where('package_id',$package)->sum('package_price');
                $time_chart['series1'][] = [
                    'name' => $package_name->package_name,
                    'data' => $data2
                ];
            }

            $institutes = Institutes::get();
            $instituteName = [];
            $time_chart['series2'] = [];
            $time_chart['series3'] = [];
            foreach($institutes as $key => $institute){
                $instituteName[] = $institute->institute_name;

                $packageId = Package::where('institute_id', $institute->id)
                        ->pluck('id')
                        ->toArray();

                $data = [];
                for ($i=0; $i < $key; $i++) {
                    $data[] = '';
                }
                $data2 = $data;
                $data[] = $st_package->whereIn('package_id', $packageId)->count();
                $time_chart['series2'][] = [
                    'name' => $institute->institute_name,
                    'data' => $data
                ];
                $data2[] = $st_package->whereIn('package_id', $packageId)->sum('package_price');
                $time_chart['series3'][] = [
                    'name' => $institute->institute_name,
                    'data' => $data2
                ];
            }
            return response()->json([
                'name' => 'Package',
                'active' => $active,
                'inactive' => $inactive,
                'comprehensive' => $comprehensive,
                'study' => $study,
                'test' => $test,
                'comprehensiveSale' => $comprehensive_sale,
                'studySale' => $study_sale,
                'testSale' => $test_sale,
                'comprehensiveAmount' => $comprehensive_amount,
                'studyAmount' => $study_amount,
                'testAmount' => $test_amount,
                'packageNames' => $packageName,
                'instituteNames' => $instituteName,
                'time_chart' => $time_chart,
                'data' => $request->all(),
            ]);
        } else if ($request->report == 'test_overview')
        {
            return response()->json([
                'name' => 'Tests',
                'series' => [
                    [
                        'name' => 'Mock Tests',
                        'y' => Mock_test::count(),
                    ], [
                        'name' => 'Benchmark Tests',
                        'y' => Benchmark_test::count(),
                    ], [
                        'name' => 'General Tests',
                        'y' => General_test::count(),
                    ], [
                        'name' => 'Advanced Tests',
                        'y' => Advanced_test::count(),
                    ], [
                        'name' => 'Evaluation Tests',
                        'y' => Evaluation_test::count(),
                    ], [
                        'name' => 'App Tests',
                        'y' => App_test::count(),
                    ], [
                        'name' => 'Concept Evaluation Tests',
                        'y' => Concept_evaluation_test::count(),
                    ]
                ]
            ]);
        }
    }

    public function questionReportList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Question::join('subject_grades', 'questions.subject_grade_id', '=', 'subject_grades.id');
        $created_user = User::get();

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
            $created_user = $created_user->where('institute_id', auth()->user()->institute_id);
        }

        $is_published = [];
        $is_approved = [];
        $created_by = [];
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "from") {
                    $values = $values->whereDate('questions.created_at', '>=', $q[$key]);
                } else if ($opt == "to") {
                    $values = $values->whereDate('questions.created_at', '<=', $q[$key]);
                } else if ($opt == "subject_grade_name") {
                    $values = $values->where('subject_grades.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "is_published") {
                    $is_published[] = $q[$key];
                } else if ($opt == "is_approved") {
                    $is_approved[] = $q[$key];
                } else if ($opt == "created_by") {
                    $user = $created_user->where('group_id', $q[$key])->pluck('id')->toArray();
                    $created_by = array_merge($created_by, $user);
                } else if ($opt == 'is_rejected') {
                    $values = $values->where('questions.is_rejected', $q[$key]);
                }
            }
        }

        if (count($is_published) > 0) {
            $values = $values->whereIn('questions.is_published', $is_published);
        }
        if (count($is_approved) > 0) {
            $values = $values->whereIn('questions.is_approved', $is_approved);
        }
        if (count($created_by) > 0) {
            $values = $values->whereIn('questions.created_by', $created_by);
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
                    'subject_grades.subject_grade_name',
                ])->map(function ($recode) {

                $user = User::where('id', $recode->created_by)->first();
                if (!$user) {
                    dd($recode);
                }
                $name = $user->name;
                $recode->created = $name . '/' . $recode->created_at->format('d-m-Y');
                $recode->edit = route('question.edit', [$recode->id]);

                return $recode;
            }),
        ]);
    }

    public function packageReportList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Package_price::join('packages', 'package_prices.package_id', '=', 'packages.id')
            ->join('package_modules', 'package_prices.package_module_id', '=', 'package_modules.id');

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }
        $package_module_id = [];
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_module_name") {
                    $values = $values->where('package_modules.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "from") {
                    $values = $values->whereDate('package_prices.created_at', '>=', $q[$key]);
                } else if ($opt == "to") {
                    $values = $values->whereDate('package_prices.created_at', '<=', $q[$key]);
                } else if ($opt == "package_module_id") {
                    $package_module_id[] = $q[$key];
                }
            }
        }

        if (count($package_module_id) > 0) {
            $values = $values->whereIn('package_prices.package_module_id', $package_module_id);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)
                ->get()->map(function ($recode) {

                $student_package = Student_package::where('package_id', $recode->package_id)
                    ->where('package_module_id', $recode->package_module_id)
                    ->get();

                $recode->sale = $student_package->count();
                $recode->amount = $student_package->sum('package_price');

                return $recode;
            }),
        ]);
    }

    public function questionReport()
    {
        return view('student_report.question_report');
    }

    public function questionTeacherList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = User::join('institutes', 'users.institute_id', '=', 'institutes.id')->where('group_id', 3);

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['users.*','institutes.institute_name'])->map(function ($recode) {

                $question = Question::get();

                $total_que = $question->where('created_by',$recode->id)->count();
                $recode->total_que = $total_que;

                $published = $question->where('published_by',$recode->id)->where('is_published',1)->count();
                $recode->published = $published;

                $unpublished = $question->where('published_by',$recode->id)->where('is_published',0)->count();
                $recode->unpublished = $unpublished;

                $approved = $question->where('approved_by',$recode->id)->where('is_approved',1)->count();
                $recode->approved = $approved;

                $pending = $question->where('approved_by',$recode->id)->where('is_approved',0)->count();
                $recode->pending = $pending;

                $rejected = $question->where('rejected_by',$recode->id)->where('is_rejected',1)->count();
                $recode->rejected = $rejected;

                $single = $question->where('created_by',$recode->id)->where('question_type_id',1)->count();
                $recode->single = $single;

                $multi = $question->where('created_by',$recode->id)->where('question_type_id',2)->count();
                $recode->multi = $multi;

                $true = $question->where('created_by',$recode->id)->where('question_type_id',3)->count();
                $recode->true = $true;

                $subjective = $question->where('created_by',$recode->id)->where('question_type_id',4)->count();
                $recode->subjective = $subjective;

                $number = $question->where('created_by',$recode->id)->where('question_type_id',5)->count();
                $recode->number = $number;

                $paragraph = $question->where('created_by',$recode->id)->where('question_type_id',6)->count();
                $recode->paragraph = $paragraph;

                return $recode;
            }),
        ]);
    }

    public function questionSubjectgradeList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = new Subject_grade;

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $question = Question::get();

                $total_que = $question->where('subject_grade_id',$recode->id)->count();
                $recode->total_que = $total_que;

                $published = $question->where('subject_grade_id',$recode->id)->where('is_published',1)->count();
                $recode->published = $published;

                $unpublished = $question->where('subject_grade_id',$recode->id)->where('is_published',0)->count();
                $recode->unpublished = $unpublished;

                $approved = $question->where('subject_grade_id',$recode->id)->where('is_approved',1)->count();
                $recode->approved = $approved;

                $pending = $question->where('subject_grade_id',$recode->id)->where('is_approved',0)->count();
                $recode->pending = $pending;

                $rejected = $question->where('subject_grade_id',$recode->id)->where('is_rejected',1)->count();
                $recode->rejected = $rejected;

                $single = $question->where('subject_grade_id',$recode->id)->where('question_type_id',1)->count();
                $recode->single = $single;

                $multi = $question->where('subject_grade_id',$recode->id)->where('question_type_id',2)->count();
                $recode->multi = $multi;

                $true = $question->where('subject_grade_id',$recode->id)->where('question_type_id',3)->count();
                $recode->true = $true;

                $subjective = $question->where('subject_grade_id',$recode->id)->where('question_type_id',4)->count();
                $recode->subjective = $subjective;

                $number = $question->where('subject_grade_id',$recode->id)->where('question_type_id',5)->count();
                $recode->number = $number;

                $paragraph = $question->where('subject_grade_id',$recode->id)->where('question_type_id',6)->count();
                $recode->paragraph = $paragraph;

                return $recode;
            }),
        ]);
    }

    public function questionChapterList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');

        $values = new Chapter;

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {

                $question = Question::get();

                $total_que = $question->where('chapter_id',$recode->id)->count();
                $recode->total_que = $total_que;

                $published = $question->where('chapter_id',$recode->id)->where('is_published',1)->count();
                $recode->published = $published;

                $unpublished = $question->where('chapter_id',$recode->id)->where('is_published',0)->count();
                $recode->unpublished = $unpublished;

                $approved = $question->where('chapter_id',$recode->id)->where('is_approved',1)->count();
                $recode->approved = $approved;

                $pending = $question->where('chapter_id',$recode->id)->where('is_approved',0)->count();
                $recode->pending = $pending;

                $rejected = $question->where('chapter_id',$recode->id)->where('is_rejected',1)->count();
                $recode->rejected = $rejected;

                $single = $question->where('chapter_id',$recode->id)->where('question_type_id',1)->count();
                $recode->single = $single;

                $multi = $question->where('chapter_id',$recode->id)->where('question_type_id',2)->count();
                $recode->multi = $multi;

                $true = $question->where('chapter_id',$recode->id)->where('question_type_id',3)->count();
                $recode->true = $true;

                $subjective = $question->where('chapter_id',$recode->id)->where('question_type_id',4)->count();
                $recode->subjective = $subjective;

                $number = $question->where('chapter_id',$recode->id)->where('question_type_id',5)->count();
                $recode->number = $number;

                $paragraph = $question->where('chapter_id',$recode->id)->where('question_type_id',6)->count();
                $recode->paragraph = $paragraph;

                return $recode;
            }),
        ]);
    }
}
