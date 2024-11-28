<?php

namespace App\Http\Controllers;

use App\Models\Reward_point;
use Illuminate\Http\Request;

class RewardPointController extends Controller
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
        return view('reward_point.list');
    }

    public function rewardPointList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Reward_point;
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
                $recode->show = route('reward_point.show', [$recode->id]);
                $recode->edit = route('reward_point.edit', [$recode->id]);
                $recode->delete = route('reward_point.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('reward_point.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'reward_title' => 'required',
            'points' => 'required|numeric',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Reward_point::create($data);

        return redirect()->route('reward_point.index')->with('success', 'Reward point created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Reward_point $reward_point)
    {
        return view('reward_point.show', compact('reward_point'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Reward_point $reward_point)
    {
        return view('reward_point.edit', compact('reward_point'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Reward_point $reward_point)
    {
        $data = $request->validate([
            'reward_title' => 'nullable',
            'points' => 'required|numeric',
            'status' => 'required'
        ]);

        $data['updated_by'] = auth()->user()->id;

        $reward_point->update($data);

        return redirect()->route('reward_point.index')->with('success', 'Reward Point updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Reward_point $reward_point)
    {
        $reward_point->delete();

        return redirect()->route('reward_point.index')->with('success', 'Reward Point deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function reward_pointMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Reward_point::where('id', $id)->delete();
        }

        return redirect()->route('reward_point.index')->with('success', 'Reward Point deleted successfully');
    }
}
