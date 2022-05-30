<?php

namespace App\Http\Controllers;

use App\Models\Institute_package;
use Illuminate\Http\Request;

class PurchasePackageController extends Controller
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
        return view('purchase_package.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function purchasePackageList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Institute_package::join('packages', 'institute_packages.package_id', '=', 'packages.id')
            ->join('institutes', 'institute_packages.institutes_id', '=', 'institutes.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "institute_name") {
                    $values = $values->where('institutes.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('institute_packages.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institutes_id', auth()->user()->institute_id);
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get([
                'institute_packages.*',
                'packages.package_name',
                'institutes.institute_name',
            ])->map(function ($recode) {
                $recode->show = route('purchase_package.show', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                $recode->expiryDate = $recode->expiry_date->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Institute_package $institute_package)
    {
        return view('purchase_package.show', compact('institute_package'));
    }
}
