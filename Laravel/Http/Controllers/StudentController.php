<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Imports\StudentImport;
use App\Models\Country;
use App\Models\Grade;
use App\Models\Institute_package;
use App\Models\Package;
use App\Models\Package_price;
use App\Models\State;
use App\Models\Student;
use App\Models\Student_advanced_test;
use App\Models\Student_benchmark_test;
use App\Models\Student_concept_evaluation_test;
use App\Models\Student_detailed_practice;
use App\Models\Student_evaluation_test;
use App\Models\Student_general_test;
use App\Models\Student_mock_test;
use App\Models\Student_package;
use App\Models\Subject_grade;
use App\Models\User;
use App\Models\User_logs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Excel;
use Str;

class StudentController extends Controller
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
        return view('student.list', compact('status'));
    }

    public function studentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('students', 'users.id', '=', 'students.user_id')
                        ->leftJoin('institutes', 'users.institute_id', '=', 'institutes.id')
                        ->where('users.group_id', 4);

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'users.*',
                    'students.id as student_id',
                    'institutes.institute_name'])
                ->map(function ($recode)
                {
                $recode->show = route('student.show', [$recode->student_id]);
                $recode->edit = route('student.edit', [$recode->student_id]);
                $recode->cart = route('student.cart', [$recode->student_id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    public function questionCount($student_id, $start = null, $end = null)
    {
        $student_advanced_test = Student_advanced_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_advanced_test = $student_advanced_test->whereBetween('started_at',[$start, $end]);
        }
        $at_que = 0;
        foreach($student_advanced_test as $advanced_test){
            $at_que = $at_que +  $advanced_test->student_advanced_test_question()
                ->where('status',1)
                ->count();
        }

        $student_benchmark_test = Student_benchmark_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_benchmark_test = $student_benchmark_test->whereBetween('started_at',[$start, $end]);
        }
        $bt_que = 0;
        foreach($student_benchmark_test as $benchmark_test){
            $bt_que = $bt_que +  $benchmark_test->student_benchmark_test_question()
                ->where('status',1)
                ->count();
        }

        $student_evaluation_test = Student_evaluation_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_evaluation_test = $student_evaluation_test->whereBetween('started_at',[$start, $end]);
        }
        $et_que = 0;
        foreach($student_evaluation_test as $evaluation_test){
            $et_que = $et_que +  $evaluation_test->student_evaluation_test_question()
                ->where('status',1)
                ->count();
        }

        $student_general_test = Student_general_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_general_test = $student_general_test->whereBetween('started_at',[$start, $end]);
        }
        $gt_que = 0;
        foreach($student_general_test as $general_test){
            $gt_que = $gt_que +  $general_test->student_general_test_question()
                ->where('status',1)
                ->count();
        }

        $student_mock_test = Student_mock_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_mock_test = $student_mock_test->whereBetween('started_at',[$start, $end]);
        }
        $mt_que = 0;
        foreach($student_mock_test as $mock_test){
            $mt_que = $mt_que +  $mock_test->student_mock_test_question()
                ->where('status',1)
                ->count();
        }

        $student_concept_evaluation_test = Student_concept_evaluation_test::where('user_id',$student_id)->get();
        if($start && $end){
            $student_concept_evaluation_test = $student_concept_evaluation_test->whereBetween('created_at',[$start, $end]);
        }
        $cet_que = 0;
        foreach($student_concept_evaluation_test as $concept_evaluation_test){
            $cet_que = $cet_que +  $concept_evaluation_test->student_concept_evaluation_test_question()
                ->where('status',1)
                ->count();
        }

        $student_detailed_practice = Student_detailed_practice::where('user_id',$student_id)
            ->whereMonth('created_at', [$start, $end])
            ->where('status',1)
            ->count();

        return [
            'at_que' => $at_que,
            'bt_que' => $bt_que,
            'et_que' => $et_que,
            'gt_que' => $gt_que,
            'mt_que' => $mt_que,
            'cet_que' => $cet_que,
            'student_detailed_practice' => $student_detailed_practice,

            'all_que' => $at_que + $bt_que + $et_que + $gt_que + $mt_que + $cet_que + $student_detailed_practice,

            'mt_test' => $student_mock_test->count(),
            'bt_test' => $student_benchmark_test->count(),
            'et_test' => $student_evaluation_test->count(),
            'cet_test' => $student_concept_evaluation_test->count(),
        ];
    }

    /**
     * View the specified record.
     */
    public function show(Student $student)
    {
        $user = User::where('id', $student->user_id)->first();
        $country = Country::where('id', $student->country_id)->first();
        $state = State::where('id', $student->state_id)->first();
        $subject_grade = Subject_grade::where('id', $student->subject_grade_id)->first();

        $user_logs = User_logs::where('user_id',$student->user->id)
            ->whereMonth('date', Carbon::now()->month)
            ->get();

        $dateS = Carbon::now()->startOfMonth()->subMonth(1);
        $dateE = Carbon::now()->startOfMonth();
        $previous_user_logs = User_logs::where('user_id',$student->user->id)
            ->whereBetween('date',[$dateS,$dateE])
            ->get();

        $sum = 0;
        foreach($user_logs as $user_log)
        {
            $start_time = Carbon::parse($user_log->in_time);
            $end_time = Carbon::parse($user_log->out_time);
            $diff_in_seconds = $end_time->diffInSeconds($start_time);
            $diff_in_minutes = $diff_in_seconds/60;
            $sum = $sum + $diff_in_minutes;
        }
        $total_time = $sum;

        $sum = 0;
        foreach($previous_user_logs as $user_log)
        {
            $start_time = Carbon::parse($user_log->in_time);
            $end_time = Carbon::parse($user_log->out_time);
            $diff_in_seconds = $end_time->diffInSeconds($start_time);
            $diff_in_minutes = $diff_in_seconds/60;
            $sum = $sum + $diff_in_minutes;
        }
        $previous_total_time = $sum;

        $start = Carbon::now()->startOfMonth()->subMonth(1);
        $end = Carbon::now()->startOfMonth();
        $pre_all = $this->questionCount($student->user->id, $start, $end);
        $pre_all_que = $pre_all['all_que'];

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $this_all = $this->questionCount($student->user->id, $start, $end);

        $all = $this->questionCount($student->user->id);

        $time_chart = [
            'test' => [],
            'series' => []
        ];

        $testCount = $this_all['mt_test'] + $this_all['bt_test'] + $this_all['et_test'] + $this_all['cet_test'];
        for ($x = 1; $x <= $testCount; $x++) {
            $time_chart['test'][] = $x;
        }

        $student_mock_tests = Student_mock_test::where('user_id',$student->user->id)
            ->whereBetween('started_at',[$start, $end])
            ->get();
        $mt = [];
        foreach($student_mock_tests as $student_mock_test){
            $student_mock_test->types = 'Mock Test';
            $mt[] = $student_mock_test;
        }

        $student_benchmark_tests = Student_benchmark_test::where('user_id',$student->user->id)
            ->whereBetween('started_at',[$start, $end])
            ->get();
        $bt = [];
        foreach($student_benchmark_tests as $student_benchmark_test){
            $student_benchmark_test->types = 'Benchmark Test';
            $bt[] = $student_benchmark_test;
        }

        $student_evaluation_tests = Student_evaluation_test::where('user_id',$student->user->id)
            ->whereBetween('started_at',[$start, $end])
            ->get();
        $et = [];
        foreach($student_evaluation_tests as $student_evaluation_test){
            $student_evaluation_test->types = 'Evaluation Test';
            $et[] = $student_evaluation_test;
        }

        $student_concept_evaluation_tests = Student_concept_evaluation_test::where('user_id',$student->user->id)
            ->whereBetween('created_at',[$start, $end])
            ->get();
        $cet = [];
        foreach($student_concept_evaluation_tests as $student_concept_evaluation_test){
            $student_concept_evaluation_test->types = 'Concept Evaluation Test';
            $cet[] = $student_concept_evaluation_test;
        }

        $datas = collect(array_merge($mt, $bt, $et, $cet));
        $sorted = $datas->sortBy('created_at');
        $sorted->all();
        $i = 0;
        $time_chart['data'] = [];
        foreach($sorted as $sort){
            $time_chart['data'][$sort->types][] = [ $i , $sort->correct_question+$sort->incorrect_question ];
            $i++;
        }
        foreach ($time_chart['data'] as $name => $data) {
            $time_chart['series'][] = [
                'name' => $name,
                'data' => $data
            ];
        }

        return view('student.show', compact('time_chart','student', 'user','country','state','subject_grade','this_all','all'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Student $student)
    {
        $countries = Country::where('status', '1')->get();
        $grades = Grade::where('status', '1')->get();
        $states = State::where('status', '1')->get();
        $users = User::where('status', '1')->whereIn('group_id',[5])->get();
        return view('student.edit', compact('student','countries','grades','states','users'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'whatsapp_mobile' => 'required',
            'country_id' => 'required',
            'grade_id' => 'required',
            'school' => 'required',
            'state_id' => 'required',
            'pincode' => 'required',
            'address' => 'required',
            'parent_id' => 'required',
            'status' => 'required',
        ]);
        $data['updated_by'] = $student->user->id;

        $student->user()->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'whatsapp_mobile' => $data['whatsapp_mobile'],
            'status' => $data['status'],
        ]);

        $student->update($data);
        $student->parents()->update([
            'user_id' => $data['parent_id']
        ]);

        return redirect()->route('student.index')->with('success', 'Student updated successfully');
    }

    public function studentBulkUpload()
    {
        return view('student.student_bulk_upload');
    }

    public function studentBulkUploadExport()
    {
        return Excel::download(new StudentExport, 'Student.xlsx');
    }

    public function studentBulkUploadImport(Request $request)
    {
        $mimes = 'xlsx';
        $request->validate([
            'import' => 'required|mimes:' . $mimes . '|max:2048',
        ]);

        $import_name = 'test.xlsx';
        if ($request->hasFile('import')) {
            $import_name = Str::uuid() . '.' . $request->import->getClientOriginalExtension();
            $request->import->storeAs('public/import/student', $import_name);
        }
        Excel::import(new StudentImport(1), storage_path('app/public/import/student/' . $import_name));
        return redirect()->back()->with('success', 'Student Data has been added successfully!');
    }

    public function studentPackagePurchase(Student $student)
    {
        $institute_package = Institute_package::where('institutes_id',$student->user->institute_id)->get();
        $packages = Package::whereIn('id',$institute_package->pluck('package_id')->toArray())->where('status',1)->get();

        return view('student.cart', compact('student','packages'));
    }

    public function studentPackageStore(Request $request, Student $student)
    {
        $data = $request->validate([
            'package_id' => 'required',
            'module_id' => 'required',
            'discount' => 'nullable',
            'grand_total' => 'nullable',
            'invoice' => 'nullable',
        ]);

        $package_price = Package_price::where('id',$data['module_id'])->first();
        $institute_package = Institute_package::where('institutes_id',$student->user->institute_id)
            ->where('package_id',$package_price->package_id)
            ->where('package_module_id',$package_price->package_module_id)
            ->first();
        Student_package::create([
            'user_id' => $student->user->id,
            'package_price_id' => $data['module_id'],
            'package_id' => $package_price->package_id,
            'package_module_id' => $package_price->package_module_id,
            'package_price' => $package_price->price,
            'expire_day' => $package_price->expire_day,
            'expiry_date' => Carbon::now()->addDay($package_price->expire_day),
            'payment_method' => 'Cash',
            'transaction_id' => 'NULL',
            'amount' => $data['grand_total'],
            'currency' => 'INR',
            'method' => 'Cash',
            'status' => 'Completed',
        ]);

        if($institute_package->package_count == $institute_package->used_count)
        {
            return redirect()->route('student.index')->with('success','Sorry ! Package Limit Exist !');
        } else if(($institute_package->expiry_date < Carbon::now()->format('Y-m-d'))) {
            return redirect()->route('student.index')->with('success','Sorry ! Package No More Exist !');
        } else {
            $institute_package->used_count = $institute_package->used_count + 1;
            $institute_package->save();
        }

        if($request->input('invoice') == 1){
            $invoiceData = [
                'time' => Carbon::now()->format('d-m-y H:i:s'),
                'student_name' => $student->user->name,
                'student_email' => $student->user->email,
            ];
            $student->user->sendInvoiceEmailStudent($invoiceData);
        }

        return redirect()->route('student.index')->with('success','Package Purchsed Successfully !');
    }
}
