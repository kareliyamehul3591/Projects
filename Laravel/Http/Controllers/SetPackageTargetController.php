<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Package_target;
use Illuminate\Http\Request;

class SetPackageTargetController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::where('status',1)->get();
        if ($request->isMethod('post')) 
        {
            $data = $request->validate([
                'package_id' => 'required',
                'package_module_id' => 'required',
                'target_question' => 'required|numeric',
                'target_cet' => 'required|numeric',
                'target_mock_test' => 'required|numeric',
                'target_benchmark_test' => 'required|numeric',
                'total_time' => 'required',
            ]);
            
            $data['created_by'] = auth()->user()->id;
            $data['updated_by'] = auth()->user()->id;

            Package_target::create($data);
        }
        return view('package.target',compact('packages'))->with('success','Package target Set Successfullay !');
    }
}
