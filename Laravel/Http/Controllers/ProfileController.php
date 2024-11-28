<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Str;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all records.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit');
    }

    /**
     * Store a newly created record.
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,$user->id,id",
            'mobile' => "required|unique:users,mobile,$user->id,id",
            'confirm_password' => 'required_with:password|same:password',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        unset($data['confirm_password']);
        $data['updated_by'] = auth()->user()->id;

        if ($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        if ($request->hasFile('image')) {
            $image_name = Str::uuid() . '.' . Str::lower($request->image->getClientOriginalExtension());
            $request->image->storeAs('public/user/' . $user->id, $image_name);
            $data['image'] = $image_name;
        }

        $user->update($data);
        return redirect()->route('profile')->with('success', 'Profile created successfully.');
    }

}
