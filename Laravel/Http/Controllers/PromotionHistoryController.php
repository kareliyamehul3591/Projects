<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Promotion_history;
use App\Models\Student_package;
use Illuminate\Http\Request;

class PromotionHistoryController extends Controller
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
        return view('promotion_history.list');
    }

    public function promotionHistoryList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Promotion_history::join('student_packages', 'promotion_histories.student_package_id', '=', 'student_packages.id')
            ->join('users', 'student_packages.user_id', '=', 'users.id')
            ->join('packages', 'student_packages.package_id', '=', 'packages.id')
            ->join('promotions', 'promotion_histories.promotion_id', '=', 'promotions.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "promo_code") {
                    $values = $values->where('promotions.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_name") {
                    $values = $values->where('packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "discount") {
                    $values = $values->where('promotions.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "package_price") {
                    $values = $values->where('student_packages.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('promotion_histories.' . $opt, 'LIKE', "%$q[$key]%");
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
                    'promotion_histories.*',
                    'users.name',
                    'packages.package_name',
                    'promotions.promo_code',
                    'promotions.discount',
                    'student_packages.package_price',
                ])->map(function ($recode) {
                $recode->show = route('promotion_history.show', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y');

                $discount = '₹ 0';
                if ($recode->promotion->type == 1) {
                    $discount = '₹ ' . $recode->promotion->discount;
                } else {
                    $discount = $recode->promotion->discount . ' %';
                }
                $recode->discount = $discount;

                $package_price = '₹ ' . (0);
                if ($recode->promotion->type == 1) {
                    $package_price = '₹ ' . $recode->promotion->discount;
                } else {
                    $package_price = '₹ ' . (($recode->student_package->package_price * $recode->promotion->discount) / 100);
                }
                $recode->package_price = $package_price;

                $recode->total = $package_price-$discount;

                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Promotion_history $promotion_history)
    {
        $promotion = Promotion::where('id', $promotion_history->promotion_id)->first();
        $student_package = Student_package::where('id', $promotion_history->student_package_id)->first();

        return view('promotion_history.show', compact('promotion_history', 'promotion', 'student_package'));
    }
}
