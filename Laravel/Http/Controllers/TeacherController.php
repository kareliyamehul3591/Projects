<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Institutes;
use App\Models\Occupation;
use App\Models\Qualification;
use App\Models\State;
use App\Models\Student;
use App\Models\Subject_grade;
use App\Models\Support_ticket_department;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
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
        return view('teacher.list',compact('status'));
    }

    public function teacherList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('teachers', 'users.id', '=', 'teachers.user_id')->where('group_id', 3);

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                $values = $values->where($opt, 'LIKE', "%$q[$key]%");
            }
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['users.*', 'teachers.id as teacher_id'])->map(function ($recode) {
                $recode->show = route('teacher.show', [$recode->teacher_id]);
                $recode->edit = route('teacher.edit', [$recode->teacher_id]);
                $recode->delete = route('teacher.delete', [$recode->teacher_id]);
                $recode->permit = route('teacher.permit', [$recode->teacher_id]);
                
                $recode->lastLoginAt = $recode->last_login_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();
        $occupations = Occupation::where('status', 1)->get();
        $qualifications = Qualification::where('status', 1)->get();
        $subject_grades = Subject_grade::where('status', 1)->get();
        $departments = Support_ticket_department::where('status', 1)->get();
        $admins = User::where('group_id',2)->where('institute_id',auth()->user()->institute_id)->where('status', 1)->get();
        return view('teacher.create', compact('countries', 'occupations', 'qualifications', 'subject_grades','departments','admins'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|required_with:password|same:password',
            'address' => 'nullable',
            'pincode' => 'nullable|numeric|min:5',
            'country_id' => 'required',
            'state_id' => 'required',
            'occupation_id' => 'nullable',
            'qualification_id' => 'nullable',
            'subject_grade_id' => 'required',
            'support_ticket_department_id' => 'nullable',
            'admin_id' => 'nullable'
        ]);

        $teacher_count = User::where('group_id',3)->where('institute_id',config('app.institute_id'))->count();

        $user = User::create([
            'group_id' => 3,
            'privileges' => "4,11,133,134,135,136,479,14,141,142,143,21,22,159,160,161,162,23,10,335,336,337,338,116,224,28,240,32,249,33,252,34,254,39,167,42,169,41,39,40,172,173,174,45,346,347,110,190,191,192,193,72,258,259",
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
            'institute_id' => config('app.institute_id'),
            'support_ticket_department_id' => ($request->input('support_ticket_department_id') == null) ? NULL : implode(",", $request->input('support_ticket_department_id')),
            'admin_id' => $request->input('admin_id'),
            'status' => 1,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);

        $user->teacher()->create([
            'occupation_id' => $request->input('occupation_id'),
            'qualification_id' => ($request->input('qualification_id') == null) ? NULL : implode(",", $request->input('qualification_id')),
            'subject_grade_id' => implode(",", $request->input('subject_grade_id')),
            'address' => $request->input('address'),
            'pincode' => $request->input('pincode'),
            'country_id' => $request->input('country_id'),
            'state_id' => $request->input('state_id'),
            'is_default_teacher' => ($teacher_count == 0) ? 1 : 0,
        ]);

        if($teacher_count == 0) {
            $institutes = Institutes::where('id',config('app.institute_id'))->first();
            if($institutes){
                $institutes->default_teacher_id = $user->id;
                $institutes->save();
            }
        }

        $teacherData = [
            'institute_name' => auth()->user()->institutes->institute_name,
            'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
            'teacher_name' => $user->name,
            'teacher_email' => $user->email,
        ];

        $user->sendTeacherRegisterEmail($teacherData);

        return redirect()->route('teacher.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Teacher $teacher)
    {
        $user = User::where('id', $teacher->user_id)->first();
        $countries = Country::where('status', 1)->get();
        $states = State::where(['country_id' => $teacher->country_id, 'status' => 1])->get();
        $occupations = Occupation::where('status', 1)->get();
        $qualifications = Qualification::where('status', 1)->get();
        $subject_grades = Subject_grade::where('status', 1)->get();
        $departments = Support_ticket_department::where('status', 1)->get();
        $admins = User::where('group_id',2)->where('institute_id',auth()->user()->institute_id)->where('status', 1)->get();
        return view('teacher.edit', compact('admins','teacher', 'user', 'countries', 'states', 'subject_grades', 'occupations', 'qualifications','departments'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$teacher->user_id,id",
            'mobile' => "required|unique:users,mobile,$teacher->user_id,id",
            'confirm_password' => 'required_with:password|same:password',
            'address' => 'nullable',
            'pincode' => 'nullable|numeric|min:5',
            'country_id' => 'required',
            'state_id' => 'required',
            'occupation_id' => 'nullable',
            'qualification_id' => 'nullable',
            'subject_grade_id' => 'required',
            'support_ticket_department_id' => 'nullable',
            'admin_id' => 'nullable',
            'status' => 'required'
        ]);

        $teacher->update([
            'occupation_id' => $request->input('occupation_id'),
            'qualification_id' => ($request->input('qualification_id') == null) ? NULL : implode(",", $request->input('qualification_id')),
            'subject_grade_id' => implode(",", $request->input('subject_grade_id')),
            'address' => $request->input('address'),
            'pincode' => $request->input('pincode'),
            'country_id' => $request->input('country_id'),
            'state_id' => $request->input('state_id'),
        ]);
        $userdata = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'institute_id' => auth()->user()->institute_id,
            'support_ticket_department_id' => ($request->input('support_ticket_department_id') == null) ? NULL : implode(",", $request->input('support_ticket_department_id')),
            'admin_id' => $request->input('admin_id'),
            'status' => $request->input('status'),
            'updated_by' => auth()->user()->id,
        ];
        if ($request->input('password')) {
            $userdata['password'] = Hash::make($request->input('password'));
        }

        $teacher->user()->update($userdata);
        return redirect()->route('teacher.index')->with('success', 'Teacher updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Teacher $teacher)
    {
        $user = User::where('id', $teacher->user_id)->first();
        $country = Country::where('id', $teacher->country_id)->first();
        $state = State::where('id', $teacher->state_id)->first();
        $occupation = Occupation::where('id', $teacher->occupation_id)->first();
        $qualifications = Qualification::whereIn('id', explode(",", $teacher->qualification_id))->get();
        $subject_grades = Subject_grade::whereIn('id', explode(",", $teacher->subject_grade_id))->get();

        $qualification_name = array();
        foreach ($qualifications as $qualification) {
            $qualification_name[] = $qualification->qualification_name;
        }

        $qualifications = implode(",", $qualification_name);

        $subject_grade_name = array();
        foreach ($subject_grades as $subject_grade) {
            $subject_grade_name[] = $subject_grade->subject_grade_name;
        }

        $subject_grades = implode(",", $subject_grade_name);
        return view('teacher.show', compact('subject_grades', 'teacher', 'user', 'country', 'state', 'occupation', 'qualifications'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $students = Student::whereRaw("find_in_set('" . $teacher->id . "',teacher_id)")->count();
        
        if($students > 0)
        {
            return redirect()->route('teacher.index')->with('success', 'Teacher can not be delete');
        } else {
            $teacher->delete();
            return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully');
        }
    }

    /**
     * Remove the multiple records from storage.
     */
    public function teacherMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');
        $teachers = Teacher::whereIn('user_id',$ids)->pluck('id')->toArray();
        $students = Student::whereIn('teacher_id',$teachers)->count();

        if($students > 0)
        {
            return redirect()->route('teacher.index')->with('success', 'Teacher can not be delete');
        } else {
            foreach ($ids as $id) {
                User::where('id', $id)->delete();
            }
            return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully');
        }
    }

    public function teacherPermit($id)
    {
        $teacher = Teacher::where('id',$id)->first();
        $user = User::where('id', $teacher->user_id)->first();

        if(!$user){
            return redirect()->route('teacher.index')->with('success', 'User not exist');
        }

        $group_id = $user->group_id;
        $institute_id = $user->institute_id;
        $privileges = explode(',', $user->privileges);
        return view('teacher.privileges', compact('user','group_id', 'privileges','institute_id'));
    }

    public function teacherPermitStore($id, Request $request)
    {
        $user = User::where('id', $id)->first();

        if(!$user){
            return redirect()->route('teacher.index')->with('success', 'User not exist');
        }
        $user->privileges = implode(',',$request->input('privileges'));
        $user->save();

        return redirect()->route('teacher.index')->with('success', 'Teacher Previlegs Add successfully');
    }

}
