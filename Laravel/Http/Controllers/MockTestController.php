<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Mock_test;
use App\Models\Package;
use App\Models\Sms_template;
use App\Models\Student;
use App\Models\Student_mock_test;
use App\Models\Student_package;
use App\Models\Subject_grade;
use App\Models\User;
use Illuminate\Http\Request;

class MockTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('mock_test.list');
    }

    public function mockTestList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Mock_test::join('packages', 'mock_tests.package_id', '=', 'packages.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_list") {
                    $values = $values->where('packages.package_name', 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('mock_tests.' . $opt, 'LIKE', "%$q[$key]%");
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
                'mock_tests.*'
            ])->map(function ($recode) {
                $recode->show = route('mock_test.show', [$recode->id]);
                $recode->edit = route('mock_test.edit', [$recode->id]);
                $recode->delete = route('mock_test.delete', [$recode->id]);

                $package = Package::whereIn('id',explode(',',$recode->package_id))->pluck('package_name')->toArray();
                $recode->package_list = implode(', ',$package);
                $recode->marking_scheme = '+'.$recode->correct_mark.' , '.'-'.$recode->negative_mark;

                $recode->total_student = Student_mock_test::where('mock_test_id',$recode->id)->count();

                return $recode;
            }),
        ]);
    }

    public function create()
    {
        $packages = Package::where('status',1)->get();
        $subject_grades = Subject_grade::where('status',1)->get();
        return view('mock_test.create',compact('packages','subject_grades'));
    }

    public function store(Request $request, Mock_test $mock_test)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'duration' => 'required',
            'correct_mark' => 'required',
            'negative_mark' => 'required',
            'total_question' => 'required',
            'chapter' => 'required',
            'package_id' => 'required',
            'is_published' => 'nullable',
            'subject_analysis' => 'nullable',
            'exam_analysis' => 'nullable',
            'language' => 'required',
        ]);
        $total = 0;
        foreach($data['chapter'] as $id => $count){
            $chapter = Chapter::find($id);
            if(!$chapter || $count == ''){
                continue;
            }
            $total += $count;
        }
        if($data['total_question'] != $total){
            $request->validate([
                'total_question' => 'required|between:1000,1001',
            ],[
                'total_question.between' => 'Total questions and selected question must be same'
            ]);
        }

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }

        $data['package_id'] = implode(',', $data['package_id']);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $mock_test = Mock_test::create($data);

        foreach($data['chapter'] as $id => $count){
            $chapter = Chapter::find($id);
            if(!$chapter || $count == ''){
                continue;
            }
            $mock_test->subgrade()->create([
                'subject_grade_id' => $chapter->subject_grade_id,
                'chapter_id' => $chapter->id,
                'question_count' => $count
            ]);
        }
        
        $templateData = [
            'name' => $mock_test->name,
            'test_name' => $mock_test->name
        ];
        
        $student_packages = Student_package::whereIn('package_id',explode(',',$mock_test->package_id))->groupBy('user_id')->pluck('user_id')->toArray();
        $users = User::whereIn('id', $student_packages)->get();
        foreach($users as $user)
        {
            $stestData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'test_name' => $mock_test->name,
            ];
            $user->sendTestEmailStudent($stestData);

            $template = Sms_template::find(8);
            $template->sendMessage($templateData);

            $student = Student::where('user_id',$user->id)->first();
            $puser = User::where('id',$student->parents()->user_id)->first();
            $testData = [
                'institute_name' => auth()->user()->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'test_name' => $mock_test->name,
                'parent_name' => $puser->name,
                'parent_email' => $puser->name,
            ];
            $puser->sendTestEmailParents($testData);

            $template = Sms_template::find(9);
            $template->sendMessage($templateData);
        }

        return redirect()->route('mock_test.index')->with('success', 'Mock Test created successfully.');
    }

    public function addNew(Request $request)
    {
        $id = $request->id;
        $mock_test = Mock_test::find($request->MTid);
        $subject_grades = Subject_grade::find($id);
        return view('mock_test.addnew',compact('subject_grades','mock_test'));
    }

    public function edit(Mock_test $mock_test)
    {
        $packages = Package::where('status',1)->get();
        $subject_grades = Subject_grade::where('status',1)->get();
        return view('mock_test.edit',compact('mock_test','packages','subject_grades'));
    }

    /**
     * Update a created record.
     */
    public function update(Request $request, Mock_test $mock_test)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'duration' => 'required',
            'correct_mark' => 'required',
            'negative_mark' => 'required',
            'total_question' => 'required',
            'chapter' => 'required',
            'package_id' => 'required',
            'is_published' => 'nullable',
            'subject_analysis' => 'nullable',
            'exam_analysis' => 'nullable',
            'language' => 'required',
        ]);
        $total = 0;
        foreach($data['chapter'] as $id => $count){
            $chapter = Chapter::find($id);
            if(!$chapter || $count == ''){
                continue;
            }
            $total += $count;
        }
        if($data['total_question'] != $total){
            $request->validate([
                'total_question' => 'required|between:1000,1001',
            ],[
                'total_question.between' => 'Total questions and selected question must be same'
            ]);
        }

        if (isset($request->is_published)) {
            $data['published_by'] = auth()->user()->id;
            $data['published_at'] = date('Y-m-d H:i:s');
        } else {
            $data['is_published'] = 0;
        }

        $data['package_id'] = implode(',', $data['package_id']);
        $data['updated_by'] = auth()->user()->id;

        $mock_test->update($data);

        $subgrade_id = [];
        foreach($data['chapter'] as $id => $count){
            $chapter = Chapter::find($id);
            if(!$chapter || $count == ''){
                continue;
            }
            $subgrade = $mock_test->subgrade()->create([
                'subject_grade_id' => $chapter->subject_grade_id,
                'chapter_id' => $chapter->id,
                'question_count' => $count
            ]);
            $subgrade_id[] = $subgrade->id;
        }
        $mock_test->subgrade()->whereNotIn('id', $subgrade_id)->delete();

        return redirect()->route('mock_test.index')->with('success', 'Mock Test updated successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Mock_test $mock_test)
    {
        return view('mock_test.show', compact('mock_test'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Mock_test $mock_test)
    {
        $mock_test->delete();

        return redirect()->route('mock_test.index')->with('success', 'Mock Test deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function mockTestMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Mock_test::where('id', $id)->delete();
        }

        return redirect()->route('mock_test.index')->with('success', 'Mock Test deleted successfully');
    }
}
