<?php

namespace App\Http\Controllers;

use App\Models\Support_ticket;
use App\Models\Support_ticket_department;
use App\Models\User;
use App\Models\User_groups;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Str;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function index(Request $request)
    {
        $status = $request->status;
        return view('support_ticket.list',compact('status'));
    }

    public function supportTicketList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = Support_ticket::join('support_ticket_departments', 'support_tickets.support_ticket_department_id', '=', 'support_ticket_departments.id')
                                ->join('user_groups', 'support_tickets.group_id', '=', 'user_groups.id');
        $user = auth()->user();
        if ($user->group_id != 1) {
            $values = $values->whereIn('support_tickets.support_ticket_department_id', explode(',', $user->support_ticket_department_id));
        }
        if ($q && $opts) {
            foreach ($opts as $key => $opt) {
                if ($opt == "support_ticket_department_name") {
                    $values = $values->where('support_ticket_departments.' . $opt, 'LIKE', "%$q[$key]%");
                } else if ($opt == "group_name") {
                    $values = $values->where('user_groups.' . $opt, 'LIKE', "%$q[$key]%");
                } else {
                    $values = $values->where('support_tickets.' . $opt, 'LIKE', "%$q[$key]%");
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
            "Records" => $values->offset($start)->limit($limit)
                ->get([
                    'support_tickets.*',
                    'support_ticket_departments.support_ticket_department_name',
                    'user_groups.group_name'
                    ])->map(function ($recode) {
                $recode->show = route('support_ticket.show', [$recode->id]);
                $recode->edit = route('dashboard.support.ticket.reply', [$recode->id]);
                $recode->delete = route('support_ticket.delete', [$recode->id]);

                $fivedate = Carbon::now()->sub('5 days')->format('Y-m-d');
                $threedate = Carbon::now()->sub('3 days')->format('Y-m-d');
                if($recode->created_at <=  $fivedate){
                    $recode->flag = 'https://targetentrance.com/img/flag-aqua-30.png';
                } else if($recode->created_at <=  $threedate){
                    $recode->flag = 'https://targetentrance.com/img/flag-red-30.png';
                } else {
                    $recode->flag = 'https://targetentrance.com/img/flag-green-30.png';
                }
                return $recode;
            }),
        ]);
    }

    /**
     * View the specified record.
     */
    public function show(Support_ticket $support_ticket)
    {
        $support_ticket_department = Support_ticket_department::where('id', $support_ticket->support_ticket_department_id)->first();
        $user_groups = User_groups::where('id', $support_ticket->group_id)->first();

        return view('support_ticket.show', compact('support_ticket', 'support_ticket_department', 'user_groups'));
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Support_ticket $support_ticket)
    {
        $departments = Support_ticket_department::where('status', 1)->get();
        $users = User::where('status', 1)->whereNotIn('group_id', [4, 5])->get();
        return view('support_ticket.edit', compact('support_ticket', 'departments', 'users'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Support_ticket $support_ticket)
    {
        $data = $request->validate([
            'group_id' => 'nullable',
            'support_ticket_department_id' => 'nullable',
            'name' => 'nullable',
            'email' => 'nullable|email',
            'mobile' => 'nullable|numeric',
            'priority' => 'nullable',
            'subject' => 'nullable',
            'move' => 'nullable',
            'message' => 'nullable',
            'file' => 'nullable',
            'status' => 'nullable',
        ]);

        if($data['move'] != null)
        {
            $user = User::where('id',$data['move'])->first();

            $support_ticket = Support_ticket::where('email',$data['email'])->first();

            $support_ticket->group_id = $user->group_id;
            $support_ticket->name = $user->name;
            $support_ticket->email = $user->email;
            $support_ticket->mobile = $user->mobile;
            $support_ticket->move = null;
            $support_ticket->save();

            if($request->input('move') != null)
            {
                $user = User::where('id',$request->input('move'))->first();
                $user->supportTicketMessage($request->input('email'));
            }

        } else {
            $support_ticket->update($data);
            if($request->input('status') == 0)
            {
                $student = User::where('id',$support_ticket->created_by)->first();
                $ticketData = [
                    'institute_name' => $student->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'student_name' => $student->name,
                    'student_email' => $student->email,
                    'ticket_title' => $support_ticket->subject,
                    'ticket_id' => $support_ticket->id,
                    'created_date' => $support_ticket->created_at,
                    'last_update_date' => $support_ticket->updated_at,
                    'support_dept' => $support_ticket->support_ticket_department->support_ticket_department_name,
                    'priority' => $support_ticket->priority,
                ];
                $student->closedTicket($ticketData);
            }
        }

        $files = [];
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $fil) {
                $name = Str::uuid() . '.' . $fil->getClientOriginalExtension();
                $fil->storeAs('public/file', $name);
                $files[] = asset('storage/file/' . $name);
            }
        }

        if($data['message'] != NULL){

            $support_ticket->conversation()->create([
                'user_id' => auth()->id(),
                'message' => $data['message'],
                'file' => implode(',', $files),
            ]);

            $user = User::where('id',$support_ticket->created_by)->first();

            $helpData = [
                'institute_name' => $user->institutes->institute_name,
                'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                'student_name' => $user->name,
                'student_email' => $user->email,
                'ticket_title' => $support_ticket->subject,
                'ticket_id' => $support_ticket->id,
                'created_date' => $support_ticket->created_at,
                'updated_date' => $support_ticket->updated_at,
                'support_dept' => $support_ticket->support_ticket_department->support_ticket_department_name,
                'priority' => $support_ticket->priority,
                'response' => $data['message'],
            ];
            $user->helpDeskTicketReply($helpData);
        }

        return redirect()->route('support_ticket.index')->with('success', 'Support Ticket updated successfully');
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Support_ticket $support_ticket)
    {
        $support_ticket->delete();

        return redirect()->route('support_ticket.index')->with('success', 'Support Ticket deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function supportTicketMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Support_ticket::where('id', $id)->delete();
        }

        return redirect()->route('support_ticket.index')->with('success', 'Support Ticket deleted successfully');
    }
}
