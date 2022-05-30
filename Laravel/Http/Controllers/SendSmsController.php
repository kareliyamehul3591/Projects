<?php

namespace App\Http\Controllers;

use App\Models\Institutes;
use App\Models\Sms_template;
use App\Models\User;
use Illuminate\Http\Request;

class SendSmsController extends Controller
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
        $institutes = Institutes::where('status', 1)->get();
        $smsTemplates = Sms_template::where('status', 1)->get();
        return view('send_sms.list', compact('institutes','smsTemplates'));
    }

    public function getSmsTemplate(Request $request)
    {
        if($request->sms_template_id == 0)
        {
            return [
                'id' => null, 
                'subject' => '', 
                'sms_body' => '', 
                'sms_parameter' => 'For Institute 
                Institute Name : {institute_name}
                Institute Logo : {institute_logo}
        For Admin
                Admin Name : {admin_name}
                Admin Email : {admin_email}
        For Teacher
                Teacher Name : {teacher_name}
                Teacher Email : {teacher_email}
        For Student
                Student  Name : {student_name}
                Student Email : {student_email}
                Student Mobile : {student_mobile}
        For Parent
                Parent Name : {parent_name}
                Parent Email : {parent_email}
                Parent Mobile : {parent_mobile}
        Other
                Package Name : {package_name}
                Subject Grades : {subject_grades}
                Package Cost : {package_cost}
                Scholarship : {scholarship}
                Final Cost : {final_cost}
                Transaction ID : {transaction_id}
                Mode of Payment : {mode_of_payment}
                Valid from : {valid_from}
                Valid to : {valid_to}
                Package : {package}
                No. of Mock Tests Attempted Till date : {no_of_mock_tests}
                Last Benchmark Test Percentile : {last_benchmark_test_percentile}
                CETs attempted : {cet_attempted}
                Questions studied : {questions_studied}',
            ];
        } 
        else
        {
            $emailTemplateId = Sms_template::where('id',$request->sms_template_id)->first();

            return [
                'id' => $emailTemplateId->id, 
                'subject' => $emailTemplateId->subject, 
                'sms_body' => $emailTemplateId->sms_body, 
                'sms_parameter' => $emailTemplateId->sms_parameter,
            ];
        }
    }

    public function sendSms(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required',
            'sendSms' => 'required',
            'sms_body' => 'required',
            'sms_parameter' => 'required',
        ],[
            'sendSms.required' => 'Please select any one'
        ]);
        
        $template = Sms_template::where('id',$request->smsTemplateId)->first();

        foreach(explode(',',$request->input('sendSms')) as $userId)
        {
            $user = User::where('id',$userId)->first();

            if($template == null)
            {
                if($user->group_id == 1)
                {
                    $smsData = [
                        'institute_name' => $user->name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'sms_body' => $data['sms_body'],
                        'admin_name' => $user->name,
                        'admin_email' => $user->email,
                        'admin_mobile' => $user->mobile,
                    ];
                }
                else if($user->group_id == 2)
                {
                    $smsData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'sms_body' => $data['sms_body'],
                        'admin_name' => $user->name,
                        'admin_email' => $user->email,
                        'admin_mobile' => $user->mobile,
                    ];
                }
                else if($user->group_id == 3)
                {
                    $smsData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'sms_body' => $data['sms_body'],
                        'teacher_name' => $user->name,
                        'teacher_email' => $user->email,
                        'teacher_mobile' => $user->mobile,
                    ];
                }
                else if($user->group_id == 4)
                {
                    $smsData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'sms_body' => $data['sms_body'],
                        'student_name' => $user->name,
                        'student_email' => $user->email,
                        'student_mobile' => $user->mobile,
                    ];
                }
                else
                {
                    $smsData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'sms_body' => $data['sms_body'],
                        'parent_name' => $user->name,
                        'parent_email' => $user->email,
                        'parent_mobile' => $user->mobile,
                    ];
                }
                dump($userId);
            }
            else if ($template->id == 1)
            {
                dd($template->id);
            }
            else if ($template->id == 2)
            {
                dd($template->id);
            }
            else if ($template->id == 3)
            {
                dd($template->id);
            }
            else if ($template->id == 4)
            {
                dd($template->id);
            }
            else if ($template->id == 5)
            {
                dd($template->id);
            }
            else if ($template->id == 6)
            {
                dd($template->id);
            }
            else if ($template->id == 7)
            {
                dd($template->id);
            }
            else if ($template->id == 8)
            {
                dd($template->id);
            }
            else if ($template->id == 9)
            {
                dd($template->id);
            }
            else if ($template->id == 10)
            {
                dd($template->id);
            }
            else if ($template->id == 11)
            {
                dd($template->id);
            }
            else if ($template->id == 12)
            {
                dd($template->id);
            }
            else if ($template->id == 13)
            {
                dd($template->id);
            }
            else if ($template->id == 14)
            {
                dd($template->id);
            }
            else if ($template->id == 15)
            {
                dd($template->id);
            }
            else if ($template->id == 16)
            {
                dd($template->id);
            }
            else if ($template->id == 17)
            {
                dd($template->id);
            }
            else if ($template->id == 18)
            {
                dd($template->id);
            }
            else if ($template->id == 19)
            {
                dd($template->id);
            }
            
        }dd();
        return redirect()->route('send.sms')->with('success','SMS Send successfully');
    }

    public function instituteList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('institutes', 'users.institute_id', '=', 'institutes.id')
            ->where('users.group_id', 1)
            ->where('users.id', '!=', '1');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "is_westkutt_institute") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "domain") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "total_admins") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "total_examiners") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "total_students") {
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
                    'institutes.is_westkutt_institute',
                    'institutes.domain',
                    'institutes.total_admins',
                    'institutes.total_examiners',
                    'institutes.total_students',
                ])->map(function ($recode) {
                return $recode;
            }),
        ]);
    }

    public function adminList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('users.group_id', 2);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->whereIn('users.' . $opt, explode(',', $q[$key]));
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->lastLoginAt = $recode->last_login_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    public function teacherList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::where('users.group_id', 3);

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'institute_id') {
                    $values = $values->whereIn('users.' . $opt, explode(',', $q[$key]));
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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->lastLoginAt = $recode->last_login_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
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

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'teacher_id') {
                    $values = $values->whereIn('students.' . $opt, explode(',', $q[$key]));
                } else if ($opt == "institute_name") {
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
                ->map(function ($recode) {
                    $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                    return $recode;
                }),
        ]);
    }

    public function parentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('parents', 'users.id', '=', 'parents.user_id')->where('users.group_id', 5);

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == 'student_id') {
                    $values = $values->whereIn('parents.' . $opt, explode(',', $q[$key]));
                } else {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if (auth()->user()->institute_id != 1) {
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['users.*', 'parents.id as parents_id'])->map(function ($recode) {
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }
}
