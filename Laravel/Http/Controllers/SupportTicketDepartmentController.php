<?php

namespace App\Http\Controllers;

use App\Models\Support_ticket_department;
use Illuminate\Http\Request;

class SupportTicketDepartmentController extends Controller
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
        return view('support_ticket_department.list');
    }

    public function supportTicketDepartmentList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Support_ticket_department;
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
                $recode->show = route('support_ticket_department.show', [$recode->id]);
                $recode->edit = route('support_ticket_department.edit', [$recode->id]);
                $recode->delete = route('support_ticket_department.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        return view('support_ticket_department.create');
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'support_ticket_department_name' => 'required',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Support_ticket_department::create($data);

        return redirect()->route('support_ticket_department.index')->with('success', 'Support Ticket Department created successfully.');
    }

    /**
     * View the specified record.
     */
    public function show(Support_ticket_department $support_ticket_department)
    {
        return view('support_ticket_department.show', compact('support_ticket_department'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Support_ticket_department $support_ticket_department)
    {
        return view('support_ticket_department.edit', compact('support_ticket_department'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Support_ticket_department $support_ticket_department)
    {
        $data = $request->validate([
            'support_ticket_department_name' => 'required',
            'status' => 'required',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $support_ticket_department->update($data);

        return redirect()->route('support_ticket_department.index')->with('success', 'Support Ticket Department updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Support_ticket_department $support_ticket_department)
    {
        $support_ticket_department->delete();

        return redirect()->route('support_ticket_department.index')->with('success', 'Support Ticket Department deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function supportTicketDepartmentMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Support_ticket_department::where('id', $id)->delete();
        }

        return redirect()->route('support_ticket_department.index')->with('success', 'Support Ticket Department deleted successfully');
    }
}
