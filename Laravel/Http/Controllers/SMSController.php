<?php

namespace App\Http\Controllers;

use App\Models\Sms_template;
use Illuminate\Http\Request;

class SMSController extends Controller
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
        return view('sms_template.list');
    }

    public function smsTemplateList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Sms_template;

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
                $recode->edit = route('sms_template.edit', [$recode->id]);
                $recode->delete = route('sms_template.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('sms_template.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sender_id' => 'required',
            'sms_template_name' => 'required',
            'subject' => 'required',
            'sms_body' => 'required',
            'sms_parameter' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Sms_template::create($data);

        return redirect()->route('sms_template.index')->with('success', 'Email Template created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Sms_template $sms_template)
    {
        return view('sms_template.edit', compact('sms_template'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Sms_template $sms_template)
    {
        $data = $request->validate([
            'sender_id' => 'required',
            'sms_template_name' => 'required',
            'subject' => 'required',
            'sms_body' => 'required',
            'sms_parameter' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $sms_template->update($data);

        return redirect()->route('sms_template.index')->with('success', 'Email Template updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Sms_template $sms_template)
    {
        $sms_template->delete();

        return redirect()->route('sms_template.index')->with('success', 'Email Template deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function smsTemplateMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Sms_template::where('id', $id)->delete();
        }

        return redirect()->route('sms_template.index')->with('success', 'Email Template deleted successfully');
    }
}
