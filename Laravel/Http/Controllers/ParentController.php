<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
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
        return view('parents.list');
    }

    public function parentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('parents', 'users.id', '=', 'parents.user_id')->where('group_id', 5);
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                $values = $values->where($opt, 'LIKE', "%$q[$key]%");
            }
        }

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

        if ($orders) {
            $orders = explode(" ", $orders);
            $values = $values->orderBy($orders[0], $orders[1]);
        }

        return response()->json([
            "Result" => "OK",
            "TotalRecordCount" => $values->count(),
            "Records" => $values->offset($start)->limit($limit)->get(['users.*', 'parents.id as parents_id'])->map(function ($recode) {
                $recode->show = route('parents.show', [$recode->parents_id]);
                $recode->edit = route('parents.edit', [$recode->parents_id]);
                $recode->created_date = $recode->created_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Parents $parent)
    {
        $user = User::where('id', $parent->user_id)->first();


        return view('parents.show', compact('parent', 'user'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Parents $parent)
    {
        $users = User::where('status', '1')->whereIn('group_id',[4])->get();
        return view('parents.edit', compact('parent','users'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Parents $parent)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $parent->user()->update($data);

        return redirect()->route('parents.index')->with('success', 'Parent updated successfully');
    }

}
