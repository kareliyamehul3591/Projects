<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Institutes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class CouponController extends Controller
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
        return view('coupon.list');
    }

    public function couponList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Coupon;

        // if(auth()->user()->institute_id != 1){
        //     $values = $values->where('institute_id', auth()->user()->institute_id);
        // }

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
            "Records" => $values->offset($start)->limit($limit)->get()->map(function ($recode) {
                $recode->show = route('coupon.show', [$recode->id]);
                $recode->edit = route('coupon.edit', [$recode->id]);
                $recode->delete = route('coupon.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $institutes = Institutes::where('status',1)->get();
        return view('coupon.create',compact('institutes'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'coupon_code' => 'required',
            'coupon_name' => 'required',
            'reward_points' => 'required|numeric',
            'coupon_amount' => 'required',
            'coupon_image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:2048|dimensions:max_width=225,max_height=225',
        ]);

        if ($request->hasFile('coupon_image')) {
            $coupon_image = Str::uuid() . '.' . $request->coupon_image->getClientOriginalExtension();
            $request->coupon_image->storeAs('public/coupon_images', $coupon_image);
            $data['coupon_image'] = asset('storage/coupon_images/'. $coupon_image);
        }

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $coupon = Coupon::create($data);

        if(($request->input('sendEmail')))
        {
            foreach(explode(',',$request->input('sendEmail')) as $userId)
            {
                $couponData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_code' => $coupon->coupon_code,
                    'coupon_amount' => $coupon->coupon_amount,
                    'reward_points' => $coupon->reward_points,
                ];
                $user = User::where('id',$userId)->first();
                $user->newCouponAdd($couponData);
            }
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Coupon $coupon)
    {
        return view('coupon.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Coupon $coupon)
    {
        $institutes = Institutes::where('status',1)->get();
        return view('coupon.edit', compact('coupon','institutes'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'coupon_code' => 'required',
            'coupon_name' => 'required',
            'reward_points' => 'required|numeric',
            'coupon_amount' => 'required',
            'coupon_image' => 'nullable|dimensions:max_width=225,max_height=225',
            'status' => 'required'
        ]);

        $data['institute_id'] = implode(',', ((isset($data['institute_id']))?$data['institute_id']:[]));

        if ($request->hasFile('coupon_image')) {
            $coupon_image = Str::uuid() . '.' . $request->coupon_image->getClientOriginalExtension();
            $request->coupon_image->storeAs('public/coupon_images', $coupon_image);
            $data['coupon_image'] = asset('storage/coupon_images/'. $coupon_image);
        }

        $data['updated_by'] = auth()->user()->id;

        $coupon->update($data);

        if(($request->input('sendEmail')))
        {
            foreach(explode(',',$request->input('sendEmail')) as $userId)
            {
                $couponData = [
                    'institute_name' => auth()->user()->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_code' => $coupon->coupon_code,
                    'coupon_amount' => $coupon->coupon_amount,
                    'reward_points' => $coupon->reward_points,
                ];
                $user = User::where('id',$userId)->first();
                $user->couponUpdate($couponData);
            }
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function couponMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Coupon::where('id', $id)->delete();
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully');
    }
}
