<?php

namespace App\Http\Controllers;

use App\Models\Email_template;
use App\Models\Institutes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SendEmailController extends Controller
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
        $emailTemplates = Email_template::where('status', 1)->get();

        return view('send_emails.list', compact('institutes', 'emailTemplates'));
    }

    public function getEmailTemplate(Request $request)
    {
        if($request->email_template_id == 0)
        {
            return [
                'id' => null, 
                'subject' => '', 
                'email_body' => '', 
                'email_parameter' => 'For Institute 
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
            $emailTemplateId = Email_template::where('id',$request->email_template_id)->first();

            return [
                'id' => $emailTemplateId->id, 
                'subject' => $emailTemplateId->subject, 
                'email_body' => $emailTemplateId->email_body, 
                'email_parameter' => $emailTemplateId->email_parameter,
            ];
        }
    }

    public function sendEmail(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required',
            'sendEmail' => 'required',
            'email_body' => 'required',
            'email_parameter' => 'required',
        ], [
            'sendEmail.required' => 'Please select any one',
        ]);

        $template = Email_template::where('id',$request->emailTemplateId)->first();
        
        foreach (explode(',', $request->input('sendEmail')) as $userId) {
            $user = User::where('id', $userId)->first();

            if($template == null)
            {
                if ($user->group_id == 1) {
                    $emailData = [
                        'institute_name' => $user->name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'email_body' => $data['email_body'],
                        'admin_name' => $user->name,
                        'admin_email' => $user->email,
                        'admin_mobile' => $user->mobile,
                    ];
                } 
                else if ($user->group_id == 2) {
                    $emailData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'email_body' => $data['email_body'],
                        'admin_name' => $user->name,
                        'admin_email' => $user->email,
                        'admin_mobile' => $user->mobile,
                    ];
                } 
                else if ($user->group_id == 3) {
                    $emailData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'email_body' => $data['email_body'],
                        'teacher_name' => $user->name,
                        'teacher_email' => $user->email,
                        'teacher_mobile' => $user->mobile,
                    ];
                } 
                else if ($user->group_id == 4) {
                    $emailData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'email_body' => $data['email_body'],
                        'student_name' => $user->name,
                        'student_email' => $user->email,
                        'student_mobile' => $user->mobile,
                    ];
                } 
                else {
                    $emailData = [
                        'institute_name' => $user->institutes->institute_name,
                        'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                        'subject' => $data['subject'],
                        'email_body' => $data['email_body'],
                        'parent_name' => $user->name,
                        'parent_email' => $user->email,
                        'parent_mobile' => $user->mobile,
                    ];
                }
                $user->sendEmails($emailData);
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
                $chapterData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendEmailUnlock($chapterData);
            }
            else if ($template->id == 10)
            {
                $stestData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendTestEmailParents($stestData);
            }
            else if ($template->id == 11)
            {
                $testData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'parent_name' => $user->name,
                    'parent_email' => $user->name,
                ];
                $user->sendTestEmailParents($testData);
            }
            else if ($template->id == 12)
            {
                $bmtData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendBMTResult($bmtData);
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
            else if ($template->id == 20)
            {
                dd($template->id);
            }
            else if ($template->id == 21)
            {
                dd($template->id);
            }
            else if ($template->id == 22)
            {
                dd($template->id);
            }
            else if ($template->id == 23)
            {
                dd($template->id);
            }
            else if ($template->id == 24)
            {
                dd($template->id);
            }
            else if ($template->id == 25)
            {
                dd($template->id);
            }
            else if ($template->id == 26)
            {
                dd($template->id);
            }
            else if ($template->id == 27)
            {
                $sEventData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->user->name,
                    'student_email' => $user->user->email,
                ];
                $user->eventEmailStudent($sEventData);
            }
            else if ($template->id == 28)
            {
                $puser = User::where('id',$user->parents()->user_id)->first();
                $pEventData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->user->name,
                    'student_email' => $user->user->email,
                    'parent_name' => $puser->name,
                    'parent_email' => $puser->email,
                ];
                $puser->eventEmailParent($pEventData);
            }
            else if ($template->id == 29)
            {
                $sExamData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->examEmailStudent($sExamData);
            }
            else if ($template->id == 30)
            {
                $puser = User::where('id',$user->parents()->user_id)->first();
                $pExamData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->user->name,
                    'student_email' => $user->user->email,
                    'parent_name' => $puser->name,
                    'parent_email' => $puser->email,
                ];
                $puser->examEmailParent($pExamData);
            }
            else if ($template->id == 31)
            {
                $sNewsData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->newsEmailStudent($sNewsData);
            }
            else if ($template->id == 32)
            {
                $pNewsData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->student->name,
                    'student_email' => $user->student->email,
                    'parent_name' => $user->name,
                    'parent_email' => $user->email,
                ];
                $user->newsEmailParent($pNewsData);
            }
            else if ($template->id == 33)
            {
                dd($template->id);
            }
            else if ($template->id == 34)
            {
                dd($template->id);
            }
            else if ($template->id == 35)
            {
                dd($template->id);
            }
            else if ($template->id == 36)
            {
                dd($template->id);
            }
            else if ($template->id == 37)
            {
                dd($template->id);
            }
            else if ($template->id == 38)
            {
                $queData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->questionResponse($queData);
            }
            else if ($template->id == 39)
            {
                dd($template->id);
            }
            else if ($template->id == 40)
            {
                dd($template->id);
            }
            else if ($template->id == 41)
            {
                $helpData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->helpDeskTicketReply($helpData);
            }
            else if ($template->id == 42)
            {
                $tOverDueData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'teacher_name' => $user->name,
                    'teacher_email' => $user->email,
                ];
                $user->overdueTicketTeacher($tOverDueData);
            }
            else if ($template->id == 43)
            {
                $overDueData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                ];
                $user->overdueTickeAdmin($overDueData);
            }
            else if ($template->id == 44)
            {
                $ticketData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->autoClosedIn5days($ticketData);
            }
            else if ($template->id == 45)
            {
                $ticketData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->closedTicket($ticketData);
            }
            else if ($template->id == 46)
            {
                $monthlyData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                    'report_name' => 'Monthly Report',
                    'month' => Carbon::now()->format('M'),
                    'year' => Carbon::now()->format('Y'),
                ];
                $user->monthlyreport($monthlyData);
            }
            else if ($template->id == 47)
            {
                dd($template->id);
            }
            else if ($template->id == 48)
            {
                $advancedData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendAdvancedResult($advancedData);
            }
            else if ($template->id == 49)
            {
                $generalData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendGeneralResult($generalData);
            }
            else if ($template->id == 54)
            {
                $couponData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                ];
                $user->newCouponAdd($couponData);
            }
            else if ($template->id == 55)
            {
                $couponData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                ];
                $user->couponUpdate($couponData);
            }
            else if ($template->id == 56)
            {
                dd($template->id);
            }
            else if ($template->id == 57)
            {
                $invoiceData = [
                    'time' => Carbon::now()->format('d-m-y H:i:s'),
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                ];
                $user->sendInvoiceEmailAdmin($invoiceData);
            }
            else if ($template->id == 58)
            {
                $invoiceData = [
                    'time' => Carbon::now()->format('d-m-y H:i:s'),
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                ];
                $user->sendInvoiceEmailStudent($invoiceData);
            }
            else if ($template->id == 59)
            {
                $teacherData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'teacher_name' => $user->name,
                    'teacher_email' => $user->email,
                ];
                $user->sendTeacherRegisterEmail($teacherData);
            }
            else if ($template->id == 60)
            {
                $instituteData = [
                    'institute_name' => $user->name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'super_admin_email' => $user->email,
                ];
                $user->sendInstituteRegisterEmail($instituteData);

            }
            else if ($template->id == 61)
            {
                $adminData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                ];
                $user->sendAdminRegisterEmail($adminData);
            }
            else if ($template->id == 62)
            {
                $promoData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                ];
                $user->sendPromotionEmail($promoData);
            }
        }
        return redirect()->route('send.emails')->with('success', 'Email Send successfully');
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
