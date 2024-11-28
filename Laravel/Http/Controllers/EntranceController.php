<?php

namespace App\Http\Controllers;

use App\Models\Entrance;
use Illuminate\Http\Request;

class EntranceController extends Controller
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
        return view('entrance.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function entranceList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Entrance();

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
                $recode->show = route('entrance.show', [$recode->id]);
                $recode->edit = route('entrance.edit', [$recode->id]);
                $recode->delete = route('entrance.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('entrance.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'entrance_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Entrance::create($data);

        return redirect()->route('entrance.index')->with('success', 'Entrance created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Entrance $entrance)
    {
        return view('entrance.edit', compact('entrance'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Entrance $entrance)
    {
        $data = $request->validate([
            'entrance_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $entrance->update($data);

        return redirect()->route('entrance.index')->with('success', 'Entrance updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Entrance $entrance)
    {
        return view('entrance.show', compact('entrance'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Entrance $entrance)
    {
        $entrance->delete();

        return redirect()->route('entrance.index')->with('success', 'Entrance deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function entranceMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Entrance::where('id', $id)->delete();
        }

        return redirect()->route('entrance.index')->with('success', 'Entrance deleted successfully');
    }

}
