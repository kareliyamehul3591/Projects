<?php

namespace App\Http\Controllers;

use App\Models\Institute_package;
use App\Models\Package_request;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PackageRequestController extends Controller
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
        return view('package_request.list');
    }

    public function packageRequestList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Package_request::join('packages', 'package_requests.package_id', '=', 'packages.id')
            ->join('institutes', 'package_requests.institute_id', '=', 'institutes.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('package_requests.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'package_requests.*',
                    'packages.package_name',
                    'institutes.institute_name',
                    ])->map(function ($recode) {
                $recode->show = route('package_request.show', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Package_request $package_request)
    {
        return view('package_request.show', compact('package_request'));
    }

    public function packageApproved(Request $request ,Package_request $package_request)
    {
        Institute_package::create([
            'institute_id' => $package_request->institute_id,
            'invoice_number' => '123',
            'package_id' => $package_request->package_id,
            'package_module_id' => $package_request->package_module_id,
            'duration' => $package_request->expiry_days,
            'expiry_date' => Carbon::now()->addDay($package_request->expire_day),
            'price' => $package_request->price,
            'package_count' => $package_request->student_count,
            'sub_total' => ($package_request->price)*($package_request->student_count),
            'payment_status' => 'Completed'
        ]);

        $package_request->status = 1;
        $package_request->save();

        return redirect()->route('package_request.index')->with('success','Package Approved Successfully !');
    }
}
