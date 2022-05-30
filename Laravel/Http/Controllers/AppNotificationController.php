<?php

namespace App\Http\Controllers;

use App\Models\App_notification;
use App\Models\Institutes;
use App\Models\User;
use Illuminate\Http\Request;

class AppNotificationController extends Controller
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
        $institutes = Institutes::where('status', 1)->get();
        return view('app_notification.list',compact('institutes'));
    }

    public function sendAppNotification(Request $request)
    {
        $data = $request->validate([
            'sendNotification' => 'required',
            'title' => 'required',
            'description' => 'required',
        ],[
            'sendNotification.required' => 'Please select atleast one'
        ]);
        
        foreach(explode(',',$request->input('sendNotification')) as $userId)
        {
            App_notification::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
            ]);
            $user = User::where('id',$userId)->first();

            if($user->group_id == 1)
            {
                $smsData = [
                    'institute_name' => $user->name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                    'admin_mobile' => $user->mobile,
                ];
            }
            else if($user->group_id == 2)
            {
                $smsData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'admin_name' => $user->name,
                    'admin_email' => $user->email,
                    'admin_mobile' => $user->mobile,
                ];
            }
            else if($user->group_id == 3)
            {
                $smsData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'teacher_name' => $user->name,
                    'teacher_email' => $user->email,
                    'teacher_mobile' => $user->mobile,
                ];
            }
            else if($user->group_id == 4)
            {
                $smsData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'student_name' => $user->name,
                    'student_email' => $user->email,
                    'student_mobile' => $user->mobile,
                ];
            }
            else
            {
                $smsData = [
                    'institute_name' => $user->institutes->institute_name,
                    'institute_logo' => 'http://localhost/westkutt.targetentrance/theme/frontend/images/logo.webp',
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'parent_name' => $user->name,
                    'parent_email' => $user->email,
                    'parent_mobile' => $user->mobile,
                ];
            }
            
            dump($userId);
        }dd();
        return redirect()->route('notification')->with('success','App notification send successfully');
    }
}
