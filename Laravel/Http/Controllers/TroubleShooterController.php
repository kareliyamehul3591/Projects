<?php

namespace App\Http\Controllers;

use App\Models\Trouble_shooter;
use App\Models\Trouble_shooter_level_1;
use Illuminate\Http\Request;

class TroubleShooterController extends Controller
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
        return view('trouble_shooter.list');
    }

    public function troubleShooterList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Trouble_shooter;
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
                $recode->edit = route('trouble_shooter.edit', [$recode->id]);
                $recode->delete = route('trouble_shooter.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $level1 = Trouble_shooter_level_1::where('status',1)->get();
        return view('trouble_shooter.create',compact('level1'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'trouble_shooter' => 'required',
            'trouble_shooter_level_1_id' => 'required',
            'trouble_shooter_level_2_id' => 'nullable',
            'trouble_shooter_level_3_id' => 'nullable',
            'trouble_shooter_level_4_id' => 'nullable',
            'trouble_shooter_level_5_id' => 'nullable',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Trouble_shooter::create($data);

        return redirect()->route('trouble_shooter.index')->with('success', 'Trouble Shooter created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Trouble_shooter $trouble_shooter)
    {
        $level1 = Trouble_shooter_level_1::where('status',1)->get();
        return view('trouble_shooter.edit', compact('trouble_shooter','level1'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Trouble_shooter $trouble_shooter)
    {
        $data = $request->validate([
            'trouble_shooter' => 'required',
            'trouble_shooter_level_1_id' => 'required',
            'trouble_shooter_level_2_id' => 'nullable',
            'trouble_shooter_level_3_id' => 'nullable',
            'trouble_shooter_level_4_id' => 'nullable',
            'trouble_shooter_level_5_id' => 'nullable',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $trouble_shooter->update($data);

        return redirect()->route('trouble_shooter.index')->with('success', 'Trouble Shooter updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Trouble_shooter $trouble_shooter)
    {
        $trouble_shooter->delete();

        return redirect()->route('trouble_shooter.index')->with('success', 'Trouble Shooter deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function troubleShooterMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Trouble_shooter::where('id', $id)->delete();
        }

        return redirect()->route('trouble_shooter.index')->with('success', 'Trouble Shooter deleted successfully');
    }
}
