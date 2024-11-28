<?php

namespace App\Http\Controllers;

use App\Models\Email_template;
use App\Models\Package_module;
use App\Models\User_access_area;
use Illuminate\Http\Request;

class PackageModuleController extends Controller
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
        return view('package_module.list');
    }

    /**
     * get all records
     *
     * @param Request $request
     * @return json
     */
    public function packageModuleList(Request $request)
    {
        $q = $request->input('q');
        $opts = $request->input('opt');
        $start = (($request->input('jtStartIndex')) ? $request->input('jtStartIndex') : 0);
        $limit = $request->input('jtPageSize');
        $orders = $request->input('jtSorting');

        $values = new Package_module;
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
                $recode->show = route('package_module.show', [$recode->id]);
                $recode->edit = route('package_module.edit', [$recode->id]);
                $recode->delete = route('package_module.delete', [$recode->id]);
                return $recode;
            }),
        ]);
    }

    /**
     * Show the form for creating a new record.
     */
    public function create()
    {
        $userAccessAreas = User_access_area::get();
        $emailTemplates = Email_template::get();
        return view('package_module.create',compact('userAccessAreas','emailTemplates'));
    }

    /**
     * Store a newly created record.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'package_module_name' => 'required',
            'user_access_area_id' => 'nullable',
            'email_template_id' => 'nullable',
        ]);

        if(isset($data['user_access_area_id'])){
            $data['user_access_area_id'] = implode(',',$data['user_access_area_id']);
        }
        if(isset($data['email_template_id'])){
            $data['email_template_id'] = implode(',',$data['email_template_id']);
        }
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        Package_module::create($data);

        return redirect()->route('package_module.index')->with('success', 'Package Module created successfully.');
    }

    /**
     * Show the form for editing the specified record.
     */
    public function edit(Package_module $package_module)
    {
        $userAccessAreas = User_access_area::get();
        $emailTemplates = Email_template::get();
        return view('package_module.edit', compact('package_module','userAccessAreas','emailTemplates'));
    }

    /**
     * Update the specified record.
     */
    public function update(Request $request, Package_module $package_module)
    {
        $data = $request->validate([
            'package_module_name' => 'required',
            'user_access_area_id' => 'nullable',
            'email_template_id' => 'nullable',
            'status' => 'required',
        ]);

        if(isset($data['user_access_area_id'])){
            $data['user_access_area_id'] = implode(',',$data['user_access_area_id']);
        }
        if(isset($data['email_template_id'])){
            $data['email_template_id'] = implode(',',$data['email_template_id']);
        }
        $data['updated_by'] = auth()->user()->id;

        $package_module->update($data);

        return redirect()->route('package_module.index')->with('success', 'Package Module updated successfully');
    }

    /**
     * View the specified record.
     */
    public function show(Package_module $package_module)
    {
        return view('package_module.show', compact('package_module'));
    }

    /**
     * Remove the specified record from storage.
     */
    public function destroy(Package_module $package_module)
    {
        $package_module->delete();

        return redirect()->route('package_module.index')->with('success', 'Package Module deleted successfully');
    }

    /**
     * Remove the multiple records from storage.
     */
    public function packageModuleMultipleDelete(Request $request)
    {
        $ids = $request->input('ids');

        foreach ($ids as $id) {
            Package_module::where('id', $id)->delete();
        }

        return redirect()->route('package_module.index')->with('success', 'Package Module deleted successfully');
    }

}
