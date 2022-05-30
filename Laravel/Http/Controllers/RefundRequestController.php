<?php

namespace App\Http\Controllers;

use App\Models\Refund;
use App\Models\Student_package;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
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
        return view('refund_request.list');
    }

    public function refundRequestList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Refund::join('users', 'refunds.user_id', '=', 'users.id')
            ->join('packages', 'refunds.package_id', '=', 'packages.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('refunds.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'refunds.*',
                    'users.name',
                    'packages.package_name'
                ])->map(function ($recode) {
                $recode->show = route('refund_request.show', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y');

                $student_package = Student_package::where('user_id',$recode->user_id)->where('package_id',$recode->package_id)->first();
                $recode->refund_amount = $student_package->amount;
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Refund $refund)
    {
        return view('refund_request.show', compact('refund'));
    }
}
