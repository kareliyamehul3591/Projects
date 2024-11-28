<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\System_setting;
use Str;

class SystemSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $system_setting =  System_setting::get();
        return view('system_setting.edit',compact('system_setting'));
    }

    public function update(Request $request)
    {
        $rules = [];
        $system_setting =  System_setting::get();
        foreach($system_setting as $setting){
            if($setting->type == 'text'){
                $rules[$setting->name] = 'required';
            } else if($setting->type == 'image') {
                $rules[$setting->name] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
            } else if($setting->type == 'checkbox'){
                $rules[$setting->name] = 'nullable';
            }
        }
        $data = $request->validate($rules);
        foreach($system_setting as $setting){
            if($setting->type == 'checkbox'){
                $setting->value = 0;
                $setting->save();
            }
        }
        foreach($data as $key => $value){
            $setting =  System_setting::where('name',$key)->first();
            if(!$setting){
                continue;
            }
            if($setting->type == 'text'){
                $setting->value = $value;
            } else if($setting->type == 'image') {
                if ($request->hasFile($setting->name)) {
                    $image = Str::uuid() . '.' . $request->file($setting->name)->getClientOriginalExtension();
                    $request->file($setting->name)->storeAs('public/setting/', $image);
                    $setting->value = $image;
                }
            } else if($setting->type == 'checkbox'){
                $setting->updated_by = auth()->user()->id;
                $setting->value = 1;
            }
            $setting->save();
        }
        return redirect()->route('system_setting')->with('success', 'System setting updated successfully.');
    }
}
