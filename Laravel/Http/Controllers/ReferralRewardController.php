<?php

namespace App\Http\Controllers;

use App\Models\Referral_reward;
use App\Models\User;
use App\Models\User_reward;
use Illuminate\Http\Request;

class ReferralRewardController extends Controller
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
        return view('referral_reward.list');
    }

    public function referralRewardList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User_reward::join('users', 'user_rewards.user_id', '=', 'users.id');

        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "name") {
                    $values = $values->where('users.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('user_rewards.' . $opt, 'LIKE', "%$q[$key]%");
                }
            }
        }

        $values = $values->groupBy('user_rewards.user_id');
        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy('user_rewards.'.$orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'user_rewards.*',
                    'users.name',
                ])->map(function ($recode) {
                $recode->show = route('referral_reward.show', [$recode->id]);

                $recode->one = $recode->where('reward_id',1)->where('user_id',$recode->user_id)->sum('points');
                $recode->two = $recode->where('reward_id',2)->where('user_id',$recode->user_id)->sum('points');
                $recode->three = $recode->where('reward_id',3)->where('user_id',$recode->user_id)->sum('points');
                $recode->four = $recode->where('reward_id',4)->where('user_id',$recode->user_id)->sum('points');
                $recode->five = $recode->where('reward_id',5)->where('user_id',$recode->user_id)->sum('points');
                $recode->six = $recode->where('reward_id',6)->where('user_id',$recode->user_id)->sum('points');
                $recode->seven = $recode->where('reward_id',7)->where('user_id',$recode->user_id)->sum('points');
                $recode->eight = $recode->where('reward_id',8)->where('user_id',$recode->user_id)->sum('points');
                $recode->nine = $recode->where('reward_id',9)->where('user_id',$recode->user_id)->sum('points');
                $recode->ten = $recode->where('reward_id',10)->where('user_id',$recode->user_id)->sum('points');

                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(User_reward $user_reward)
    {
        return view('referral_reward.show', compact('user_reward'));
    }

    /**
     * View the specified record.
     */
    public function create()
    {
        $users = User::where('group_id',4)->where('status',1)->get();
        return view('referral_reward.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
        ]);

        foreach($data['user_id'] as $user)
        {
            User_reward::create([
                'institute_id' => auth()->user()->institute_id,
                'user_id' => $user,
                'reward_id' => '10',
                'points' => '1000',
            ]);
        }

        return redirect()->route('referral_reward.index')->with('success', 'MISCELLANEOUS Add Successfully');
    }
}
