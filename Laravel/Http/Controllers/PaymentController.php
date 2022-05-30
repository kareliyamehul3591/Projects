<?php

namespace App\Http\Controllers;

use App\Models\Institutes;
use App\Models\Package;
use App\Models\Student_package;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
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
        return view('payment.list');
    }

    public function paymentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Student_package::join('packages', 'student_packages.package_id', '=', 'packages.id')
            ->join('users', 'student_packages.user_id', '=', 'users.id')
            ->join('package_modules', 'student_packages.package_module_id', '=', 'package_modules.id')
            ->join('institutes', 'users.institute_id', '=', 'institutes.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_module_name") {
                    $values = $values->where('package_modules.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "expiry") {
                    if($q[$key] == 0){
                        $values = $values->whereDate('student_packages.expiry_date', '<', Carbon::now()->format('Y-m-d'));
                    }else{
                        $values = $values->whereDate('student_packages.expiry_date', '>=', Carbon::now()->format('Y-m-d'));
                    }

                } else {
                    $values = $values->where('student_packages.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'student_packages.*',
                    'packages.package_name',
                    'package_modules.package_module_name',
                    'institutes.institute_name'
                    ])->map(function ($recode) {
                $recode->show = route('payment.show', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                $recode->expiry = (Carbon::parse($recode->expiry_date)->gte(Carbon::now()->format('d-m-Y'))) ? 1 : 0;
                $recode->discount = $recode->package_price;
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Student_package $student_package)
    {
        $institutes = Institutes::where('status',1)->first();
        $package = Package::where('status',1)->first();
        return view('payment.show', compact('student_package','institutes','package'));
    }
}
