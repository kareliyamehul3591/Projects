<?php

namespace App\Http\Controllers;

use App\Models\Trouble_shooter_level_1;
use App\Models\Trouble_shooter_level_3;
use Illuminate\Http\Request;

class TroubleShooterLevel3 extends Controller
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
        return view('trouble_shooter_level_3.list');
    }

    public function troubleShooterLevel3List(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Trouble_shooter_level_3::join('trouble_shooter_level_1s','trouble_shooter_level_3s.trouble_shooter_level_1_id','=','trouble_shooter_level_1s.id')
            ->join('trouble_shooter_level_2s','trouble_shooter_level_3s.trouble_shooter_level_2_id','=','trouble_shooter_level_2s.id');
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "trouble_shooter_name1") {
                    $values = $values->where('trouble_shooter_level_1s' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "trouble_shooter_name2") {
                    $values = $values->where('trouble_shooter_level_2s' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('trouble_shooter_level_3s.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)->get([
                'trouble_shooter_level_3s.*',
                'trouble_shooter_level_1s.trouble_shooter_name1',
                'trouble_shooter_level_2s.trouble_shooter_name2'
            ])->map(function ($recode) {
                $recode->show = route('trouble_shooter_level_3.show', [$recode->id]);
                $recode->edit = route('trouble_shooter_level_3.edit', [$recode->id]);
                $recode->delete = route('trouble_shooter_level_3.delete', [$recode->id]);
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
        return view('trouble_shooter_level_3.create',compact('level1'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'trouble_shooter_level_1_id' => "required",
            'trouble_shooter_level_2_id' => "required",
            'trouble_shooter_name3' => "required",
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Trouble_shooter_level_3::create($data);

        return redirect()->route('trouble_shooter_level_3.index')->with('success', 'Trouble shooter level 2 created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Trouble_shooter_level_3 $trouble_shooter_level_3)
    {
        return view('trouble_shooter_level_3.show', compact('trouble_shooter_level_3'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Trouble_shooter_level_3 $trouble_shooter_level_3)
    {
        $level1 = Trouble_shooter_level_1::where('status',1)->get();
        return view('trouble_shooter_level_3.edit', compact('trouble_shooter_level_3','level1'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Trouble_shooter_level_3 $trouble_shooter_level_3)
    {
        $data = $request->validate([
            'trouble_shooter_level_1_id' => "required",
            'trouble_shooter_level_2_id' => "required",
            'trouble_shooter_name3' => "required",
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $trouble_shooter_level_3->update($data);

        return redirect()->route('trouble_shooter_level_3.index')->with('success', 'Trouble shooter level 2 updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Trouble_shooter_level_3 $trouble_shooter_level_3)
    {
        $trouble_shooter_level_3->delete();

        return redirect()->route('trouble_shooter_level_3.index')->with('success', 'Trouble shooter level 2 deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function troubleShooterLevel3MultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Trouble_shooter_level_3::where('id', $id)->delete();
        }

        return redirect()->route('trouble_shooter_level_3.index')->with('success', 'Trouble shooter level 2 deleted successfully');
    }

}
