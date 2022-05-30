<?php

namespace App\Http\Controllers;

use App\Models\Trouble_shooter_level_1;
use Illuminate\Http\Request;

class TroubleShooterLevel1 extends Controller
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
        return view('trouble_shooter_level_1.list');
    }

    public function troubleShooterLevel1List(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Trouble_shooter_level_1;
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
                $recode->show = route('trouble_shooter_level_1.show', [$recode->id]);
                $recode->edit = route('trouble_shooter_level_1.edit', [$recode->id]);
                $recode->delete = route('trouble_shooter_level_1.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('trouble_shooter_level_1.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'trouble_shooter_name1' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Trouble_shooter_level_1::create($data);

        return redirect()->route('trouble_shooter_level_1.index')->with('success', 'Trouble shooter level 1 created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Trouble_shooter_level_1 $trouble_shooter_level_1)
    {
        return view('trouble_shooter_level_1.show', compact('trouble_shooter_level_1'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Trouble_shooter_level_1 $trouble_shooter_level_1)
    {
        return view('trouble_shooter_level_1.edit', compact('trouble_shooter_level_1'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Trouble_shooter_level_1 $trouble_shooter_level_1)
    {
        $data = $request->validate([
            'trouble_shooter_name1' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $trouble_shooter_level_1->update($data);

        return redirect()->route('trouble_shooter_level_1.index')->with('success', 'Trouble shooter level 1 updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Trouble_shooter_level_1 $trouble_shooter_level_1)
    {
        $trouble_shooter_level_1->delete();

        return redirect()->route('trouble_shooter_level_1.index')->with('success', 'Trouble shooter level 1 deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function troubleShooterLevel1MultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Trouble_shooter_level_1::where('id', $id)->delete();
        }

        return redirect()->route('trouble_shooter_level_1.index')->with('success', 'Trouble shooter level 1 deleted successfully');
    }

}
