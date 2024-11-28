<?php

namespace App\Http\Controllers;

use App\Models\Banned_ip;
use Illuminate\Http\Request;

class BannedIpController extends Controller
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
        return view('banned_ip.list');
    }

    public function bannedIpList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Banned_ip;

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
                $recode->show = route('banned_ip.show', [$recode->id]);
                $recode->edit = route('banned_ip.edit', [$recode->id]);
                $recode->delete = route('banned_ip.delete', [$recode->id]);
                $recode->created_date = $recode->created_at->format('d-m-Y');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('banned_ip.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request, Banned_ip $banned_ip)
    {
        $data = $request->validate([
            'ip_address' => "required|unique:banned_ips,ip_address,$banned_ip->id,id",
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Banned_ip::create($data);

        return redirect()->route('banned_ip.index')->with('success', 'Banned_ip created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Banned_ip $banned_ip)
    {
        return view('banned_ip.show', compact('banned_ip'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Banned_ip $banned_ip)
    {
        return view('banned_ip.edit', compact('banned_ip'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Banned_ip $banned_ip)
    {
        $data = $request->validate([
            'ip_address' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $banned_ip->update($data);

        return redirect()->route('banned_ip.index')->with('success', 'Banned_ip updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Banned_ip $banned_ip)
    {
        $banned_ip->delete();

        return redirect()->route('banned_ip.index')->with('success', 'Banned_ip deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function bannedIpMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Banned_ip::where('id', $id)->delete();
        }

        return redirect()->route('banned_ip.index')->with('success', 'Banned_ip deleted successfully');
    }

}
