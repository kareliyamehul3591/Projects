<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Support_ticket_department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
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
        return view('admin.list');
    }

    public function adminList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = User::join('admins', 'users.id', '=', 'admins.user_id')->where('group_id', 2);

        if(auth()->user()->institute_id != 1){
            $values = $values->where('institute_id', auth()->user()->institute_id);
        }

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
            "Records" => $values->offset($start)->limit($limit)->get(['users.*', 'admins.id as admin_id'])->map(function ($recode) {
                $recode->show = route('admin.show', [$recode->admin_id]);
                $recode->edit = route('admin.edit', [$recode->admin_id]);
                $recode->delete = route('admin.delete', [$recode->admin_id]);
                $recode->permit = route('admin.permit', [$recode->admin_id]);
                $recode->lastLoginAt = $recode->last_login_at->format('d-m-Y H:i:s');
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $departments = Support_ticket_department::where('status', 1)->get();
        return view('admin.create',compact('departments'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|required_with:password|same:password',
            'support_ticket_department_id' => 'nullable'
        ]);

        $user = User::create([
            'group_id' => 2,
            'privileges' => 4,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
            'password' => Hash::make($request->input('password')),
            'institute_id' => config('app.institute_id'),
            'support_ticket_department_id' => ($request->input('support_ticket_department_id') == null) ? NULL : implode(",", $request->input('support_ticket_department_id')),
            'status' => 1,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        $user->admin()->create();
        
        $adminData = [
            'institute_name' => $user->institutes->institute_name,
            'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
            'admin_name' => $user->name,
            'admin_email' => $user->email,
        ];

        $user->sendAdminRegisterEmail($adminData);

        return redirect()->route('admin.index')->with('success', 'Administrator created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Admin $admin)
    {
        $user = User::where('id', $admin->user_id)->first();

        $departments = Support_ticket_department::where('status', 1)->get();
        return view('admin.edit', compact('admin', 'user','departments'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Admin $admin)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$admin->user_id,id",
            'mobile' => "required|unique:users,mobile,$admin->user_id,id",
            'confirm_password' => 'required_with:password|same:password',
            'status' => "required",
            'support_ticket_department_id' => 'nullable'
        ]);
        unset($data['confirm_password']);
        $data['updated_by'] = auth()->user()->id;
        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        $data['support_ticket_department_id'] =  ($request->input('support_ticket_department_id') == null) ? NULL : implode(",", $request->input('support_ticket_department_id'));
        $admin->user()->update($data);

        return redirect()->route('admin.index')->with('success', 'Administrator updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Admin $admin)
    {
        $user = User::where('id', $admin->user_id)->first();

        return view('admin.show', compact('admin', 'user'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Admin $admin)
    {
        $teachers = User::where('group_id',3)->where('admin_id',$admin->id)->count();
        
        if($teachers > 0)
        {
            return redirect()->route('admin.index')->with('success', 'Administrator can not be delete');
        } else {
            $admin->delete();
            return redirect()->route('admin.index')->with('success', 'Administrator deleted successfully');
        }
    }

    /**
     * Remove the multiple records from storage.
     */
    public function adminMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');
        $teachers = User::where('group_id',3)->whereIn('id',$ids)->count();

        if($teachers > 0)
        {
            return redirect()->route('admin.index')->with('success', 'Administrator can not be deleted');
        } else {
            foreach ($ids as $id) {
                User::where('id', $id)->delete();
            }
            return redirect()->route('admin.index')->with('success', 'Administrator deleted successfully');
        }
    }

    public function adminPermit($id)
    {
        $admin = Admin::where('id', $id)->first();
        $user = User::where('id', $admin->user_id)->first();

        if(!$user){
            return redirect()->route('admin.index')->with('success', 'User not exist');
        }

        $group_id = $user->group_id;
        $institute_id = $user->institute_id;
        $privileges = explode(',', $user->privileges);
        return view('admin.privileges', compact('user','group_id', 'privileges','institute_id'));
    }

    public function adminPermitStore($id, Request $request)
    {
        $user = User::where('id', $id)->first();

        if(!$user){
            return redirect()->route('admin.index')->with('success', 'User not exist');
        }
        $user->privileges = implode(',',array_unique($request->input('privileges')));
        $user->save();

        return redirect()->route('admin.index')->with('success', 'Administrator Previlegs Add successfully');
    }

}
