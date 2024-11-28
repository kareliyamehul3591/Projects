<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Advanced_study;
use App\Models\Advanced_test;
use App\Models\App_test;
use App\Models\Benchmark_test;
use App\Models\Concept;
use App\Models\Concept_evaluation_test;
use App\Models\Contact_us;
use App\Models\Daily_news;
use App\Models\Daily_question;
use App\Models\Detailed_study;
use App\Models\Evaluation_test;
use App\Models\Event;
use App\Models\General_test;
use App\Models\Image;
use App\Models\Institutes;
use App\Models\Institute_package;
use App\Models\Issue;
use App\Models\Mentor_question;
use App\Models\Mock_test;
use App\Models\News;
use App\Models\Package;
use App\Models\Package_module;
use App\Models\Package_price;
use App\Models\Package_request;
use App\Models\Question;
use App\Models\Reported_question;
use App\Models\Student_advanced_practice;
use App\Models\Student_advanced_test;
use App\Models\Student_advanced_test_question;
use App\Models\Student_app_test;
use App\Models\Student_app_test_question;
use App\Models\Student_benchmark_test;
use App\Models\Student_benchmark_test_question;
use App\Models\Student_concept_evaluation_test;
use App\Models\Student_concept_evaluation_test_question;
use App\Models\Student_daily_test;
use App\Models\Student_daily_test_question;
use App\Models\Student_detailed_practice;
use App\Models\Student_evaluation_test;
use App\Models\Student_evaluation_test_question;
use App\Models\Student_general_test;
use App\Models\Student_general_test_question;
use App\Models\Student_mock_test;
use App\Models\Student_mock_test_question;
use App\Models\Student_package;
use App\Models\Subject;
use App\Models\Subject_grade;
use App\Models\Support_ticket;
use App\Models\Support_ticket_conversation;
use App\Models\Support_ticket_department;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /*
     * Show the application dashboard.
     */
    public function index()
    {
        $SMTQ = Student_mock_test_question::get();
        $SBTQ = Student_benchmark_test_question::get();
        $SGTQ = Student_general_test_question::get();
        $SATQ = Student_advanced_test_question::get();
        $SETQ = Student_evaluation_test_question::get();
        $SAPPTQ = Student_app_test_question::get();
        $SCETQ = Student_concept_evaluation_test_question::get();
        $SDTQ = Student_daily_test_question::get();
        $SDP = Student_detailed_practice::get();
        $SAP = Student_advanced_practice::get();

        $SMT = Student_mock_test::get();
        $SBT = Student_benchmark_test::get();
        $SGT = Student_general_test::get();
        $SAT = Student_advanced_test::get();
        $SET = Student_evaluation_test::get();
        $SAPPT = Student_app_test::get();
        $SCET = Student_concept_evaluation_test::get();
        $SDT = Student_daily_test::get();
        $SDPT = Student_detailed_practice::groupBy('chapter_id')->pluck('chapter_id')->toArray();
        $SAPT = Student_advanced_practice::groupBy('chapter_id')->pluck('chapter_id')->toArray();

        //SOLVED QUESTIONS
        $question = Question::get();
        
        //MOCK TESTS
        $mt = Mock_test::get();

        //CONCEPTS
        $concepts = Concept::where('status', 1)->get();

        //SUBJECTS
        $subjects = Subject::get();
        $subject_grades = Subject_grade::get();

        //ADVISORS
        $admins = User::where('group_id', 2)->get();
        $institutes = Institutes::get();

        //TEACHERS
        $teachers = User::where('group_id', 3)->get();

        //STUDENTS -- Students Till date
        $students = User::where('group_id', 4)->get();

        //PARENTS
        $parents = User::where('group_id', 5)->get();

        //Questions Studied Till date
        $SMTQCount = $SMTQ->where('status', 1)->count();
        $SBTQCount = $SBTQ->where('status', 1)->count();
        $SGTQCount = $SGTQ->where('status', 1)->count();
        $SATQCount = $SATQ->where('status', 1)->count();
        $SETQCount = $SETQ->where('status', 1)->count();
        $SAPPTQCount = $SAPPTQ->where('status', 1)->count();
        $SCETQCount = $SCETQ->where('status', 1)->count();
        $SDTQCount = $SDTQ->where('status', 1)->count();
        $SDPCount = $SDP->where('status', 1)->count();
        $SAPCount = $SAP->where('status', 1)->count();
        $questionsStudiedTillDate = $SMTQCount + $SBTQCount + $SGTQCount + $SATQCount + $SETQCount + $SAPPTQCount + $SCETQCount + $SDTQCount + $SDPCount + $SAPCount;

        //Tests Taken Till date
        $testsTakenTillDate = count($SMT) + count($SBT) + count($SGT) + count($SAT) + count($SET) + count($SAPPT) + count($SCET) + count($SDT) + count($SDPT) + count($SAPT);

        //Happy Learning Hours Till date

        //Question studied in last 30 days
        //Test done in last 30 days
        $user_id = User::where('institute_id', auth()->user()->institute_id)->pluck('id')->toArray();
        $total_question = [];
        $total_test = [];
        $categories = [];
        $my_total_question = [];
        $my_total_test = [];
        $j = 1;
        for ($i = 30; $i > 0; $i--) {
            $fromDate = Carbon::today()->subDays($i - 1)->format('Y-m-d');
            $toDate = Carbon::today()->subDays($i)->format('Y-m-d');

            $SMTQue = $SMTQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SBTQue = $SBTQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SGTQue = $SGTQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SATQue = $SATQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SETQue = $SETQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SAPPTQue = $SAPPTQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SCETQue = $SCETQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SDTQue = $SDTQ->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SDPQue = $SDP->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $SAPQue = $SAP->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);

            $total_question[] = $SMTQue->count() + $SBTQue->count() + $SGTQue->count() + $SATQue->count() + $SETQue->count() + $SAPPTQue->count() + $SCETQue->count() + $SDTQue->count() + $SDPQue->count() + $SAPQue->count();

            $SMTMyQue = $SMTQue->whereIn('user_id', $user_id)->count();
            $SBTMyQue = $SBTQue->whereIn('user_id', $user_id)->count();
            $SGTMyQue = $SGTQue->whereIn('user_id', $user_id)->count();
            $SATMyQue = $SATQue->whereIn('user_id', $user_id)->count();
            $SETMyQue = $SETQue->whereIn('user_id', $user_id)->count();
            $SAPPTMyQue = $SAPPTQue->whereIn('user_id', $user_id)->count();
            $SCETMyQue = $SCETQue->whereIn('user_id', $user_id)->count();
            $SDTMyQue = $SDTQue->whereIn('user_id', $user_id)->count();
            $SDPMyQue = $SDPQue->whereIn('user_id', $user_id)->count();
            $SAPMyQue = $SAPQue->whereIn('user_id', $user_id)->count();

            $my_total_question[] = $SMTMyQue + $SBTMyQue + $SGTMyQue + $SATMyQue + $SETMyQue + $SAPPTMyQue + $SCETMyQue + $SDTMyQue + $SDPMyQue + $SAPMyQue;

            $STMT = $SMT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STBT = $SBT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STGT = $SGT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STAT = $SAT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STET = $SET->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STAPPT = $SAPPT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STCET = $SCET->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            $STDT = $SDT->where('created_at', '<=', $fromDate)->where('created_at', '>=', $toDate);
            // $STDPT = $SDPT->where('created_at', '<=',  $fromDate)->where('created_at', '>=',  $toDate);
            // $STAPT = $SAPT->where('created_at', '<=',  $fromDate)->where('created_at', '>=',  $toDate);

            $total_test[] = $STMT->count() + $STBT->count() + $STGT->count() + $STAT->count() + $STET->count() + $STAPPT->count() + $STCET->count() + $STDT->count();

            $STMTMy = $STMT->whereIn('user_id', $user_id)->count();
            $STBTMy = $STBT->whereIn('user_id', $user_id)->count();
            $STGTMy = $STGT->whereIn('user_id', $user_id)->count();
            $STATMy = $STAT->whereIn('user_id', $user_id)->count();
            $STETMy = $STET->whereIn('user_id', $user_id)->count();
            $STAPPTMy = $STAPPT->whereIn('user_id', $user_id)->count();
            $STCETMy = $STCET->whereIn('user_id', $user_id)->count();
            $STDTMy = $STDT->whereIn('user_id', $user_id)->count();
            // $STDPTMy = $SDPT->whereIn('user_id', $user_id)->count();
            // $STAPTMy = $SAPT->whereIn('user_id', $user_id)->count();

            $my_total_test[] = $STMTMy + $STBTMy + $STGTMy + $STATMy + $STETMy + $STAPPTMy + $STCETMy + $STDTMy;

            $categories[] = $j;
            $j++;
        }

        //NOTES
        $issue = Issue::get();

        //ASK A MENTOR
        $mentor_question = Mentor_question::take('5')->get();

        //DOWNLOADS
        $images = Image::count();
        $videos = Video::count();
        $upload = $images + $videos;

        //STRATEGIES
        //ENGAGE
        //TICKETS -- Open Support Tickets
        $support_ticket = Support_ticket::get();
        $support_tickets = Support_ticket::where('status',1)->take('5')->get();
        $support_ticket_conversation = Support_ticket_conversation::
            whereNotIn('support_ticket_id', $support_ticket->pluck('id')->toArray())
            ->get();

        //NEWS
        $news = News::get();

        //NEWSLETTERS
        $events = Event::get();

        //MAGAZINES
        $daily_news = Daily_news::get();

        //App Messages
        $contact_us = Contact_us::take('5')->get();

        //Reported Questions
        $reported_que = Reported_question::take('5')->get();

        //Online Students -- Online Teachers -- App Users -- Guest Users
        $online = User::get();

        //Interactive Area Chart
        $onlineStudent[] = $online->where('group_id', 4)->where('is_login', 1)->count();
        $onlineTeacher[] = $online->where('group_id', 3)->where('is_login', 1)->count();
        $appUser[] = $online->where('device', '!=', null)->count();
        $guestUser[] = 0;

        //Purchased Packages -- Periodwise Packages -- Package Modewise
        $package_prices = Package_price::groupBy('package_id')
            ->pluck('package_id')
            ->toArray();
        $st_package = Student_package::get();
        $packageName = [];
        $periodWise = [];
        $time_chart = [
            'series1' => [],
            'series2' => [],
            'series3' => [],
        ];
        foreach ($package_prices as $key => $package) {
            $package_name = Package::where('id', $package)->first();
            $time_chart['series1'][] = [
                'name' => $package_name->package_name,
                'y' => $st_package->where('package_id', $package)->count(),
            ];
        }

        $package_epire_day = Package_price::groupBy('expire_day')
            ->pluck('expire_day')
            ->toArray();
        foreach ($package_epire_day as $key => $expire) {
            $time_chart['series2'][] = [
                'name' => $expire,
                'y' => Package_price::where('expire_day', $expire)->count(),
            ];
        }

        $package_mode = Package_price::groupBy('package_module_id')
            ->pluck('package_module_id')
            ->toArray();
        foreach ($package_mode as $key => $module) {
            $module_name = Package_module::where('id', $module)->first();
            $time_chart['series3'][] = [
                'name' => $module_name->package_module_name,
                'y' => Package_price::where('package_module_id', $module)->count(),
            ];
        }

        //MY TESTS
        $my_ds = Detailed_study::where('created_by', auth()->user()->id)->groupBy('chapter_id')->pluck('chapter_id')->toArray();
        $my_asq = Advanced_study::where('created_by', auth()->user()->id)->groupBy('chapter_id')->pluck('chapter_id')->toArray();
        $my_dt = Daily_question::where('created_by', auth()->user()->id)->groupBy('day')->pluck('day')->toArray();
        $my_test = Mock_test::where('created_by', auth()->user()->id)->count() +
        Benchmark_test::where('created_by', auth()->user()->id)->count() +
        General_test::where('created_by', auth()->user()->id)->count() +
        Advanced_test::where('created_by', auth()->user()->id)->count() +
        Evaluation_test::where('created_by', auth()->user()->id)->count() +
        App_test::where('created_by', auth()->user()->id)->count() +
        Concept_evaluation_test::where('created_by', auth()->user()->id)->count() +
        count($my_dt) + count($my_ds) + count($my_asq);

        //Packages
        $package_count = Package::where('status', 1)->get();
        $institute_packages = Institute_package::where('institutes_id', config('app.institute_id'))->get();

        //MY DOWNLOADS
        $image = Image::where('created_by', auth()->user()->id)->count();
        $video = Video::where('created_by', auth()->user()->id)->count();
        $my_upload = $image + $video;

        if (auth()->user()->institute_id == 1) {
            return view('dashboard.westkutt', compact(
                'question', 'concepts', 'subject_grades', 'subjects', 'admins', 'institutes', 'teachers', 'students',
                'parents', 'questionsStudiedTillDate', 'testsTakenTillDate', 'categories', 'total_question', 'total_test', 'issue',
                'mentor_question', 'upload', 'news', 'daily_news', 'support_ticket', 'contact_us', 'reported_que',
                'online', 'time_chart', 'mt', 'support_ticket_conversation', 'events', 'onlineStudent',
                'onlineTeacher', 'appUser', 'guestUser','support_tickets'
            ));
        } else {
            return view('dashboard.institute', compact(
                'question', 'concepts', 'user_id', 'subject_grades', 'subjects', 'admins', 'teachers', 'students',
                'parents', 'questionsStudiedTillDate', 'testsTakenTillDate', 'issue', 'mentor_question', 'my_upload', 'news', 'my_test',
                'online', 'categories', 'my_total_question', 'my_total_test', 'mt', 'events', 'daily_news',
                'package_count', 'institute_packages', 'st_package'
            ));
        }
    }

    public function packageRequest(Package $package)
    {
        $package_mode = Package_price::where('package_id', $package->id)->get();
        return view('dashboard.package_request', compact('package', 'package_mode'));
    }

    public function packageRequestStore(Request $request, Package $package)
    {
        $data = $request->validate([
            'module_id.*' => 'required|distinct',
            'package_count.*' => 'required',
            'discount' => 'nullable',
            'grand_total' => 'nullable',
            'invoice' => 'nullable',
        ], [
            'module_id.*.required' => 'The Package module field is required.',
            'module_id.*.distinct' => 'The Package module field has a duplicate value.',
            'package_count.*.required' => 'The Package count field is required.',
        ]);

        foreach ($data['module_id'] as $key => $moduleId) {

            $package_price = Package_price::where('id', $moduleId)->first();

            $package_request = Package_request::create([
                'package_id' => $package_price->package_id,
                'package_module_id' => $package_price->package_module_id,
                'institute_id' => config('app.institute_id'),
                'expiry_days' => $package_price->expire_day,
                'price' => $package_price->price,
                'student_count' => $data['package_count'][$key],
            ]);
        }

        if ($request->input('invoice') == 1) {
            $invoiceData = [
                'time' => Carbon::now()->format('d-m-y H:i:s'),
                'admin_name' => $package_request->institutes->user->name,
                'admin_email' => $package_request->institutes->user->email,
            ];
            $package_request->institutes->user->sendInvoiceEmailAdmin($invoiceData);
        }

        return redirect()->route('dashboard')->with('success', 'Package Request Successfully !');
    }

    public function ticketReply(Support_ticket $support_ticket)
    {
        $departments = Support_ticket_department::where('status', 1)->get();
        $users = User::where('status', 1)->whereNotIn('group_id', [4, 5])->get();
        return view('dashboard.reply', compact('support_ticket', 'departments', 'users'));
    }

    /**
     * Update the specified record.
     */
    public function ticketReplyUpdate(Request $request, Support_ticket $support_ticket)
    {
        $data = $request->validate([
            'message' => 'nullable',
            'file' => 'nullable',
        ]);

        $files = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $fil) {
                $name = Str::uuid() . '.' . $fil->getClientOriginalExtension();
                $fil->storeAs('public/file', $name);
                $files[] = asset('storage/file/' . $name);
            }
        }

        if ($data['message'] != null) {
            $support_ticket->conversation()->create([
                'user_id' => auth()->user()->id,
                'message' => $data['message'],
                'file' => ($request->hasFile('file')) ? implode(',', $files) : null,
            ]);
        }

        return redirect()->route('dashboard.support.ticket.reply', $support_ticket->id)->with('success', 'Support Ticket updated successfully');
    }
}
