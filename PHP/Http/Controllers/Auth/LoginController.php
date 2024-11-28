<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){

        $this->middleware('guest')->except('logout');
        
    }
    
    protected function validateLogin(Request $request)
    {
        $usernameType = $this->username();
        $usernameValue = $request->$usernameType;
        $user = \App\User::where($usernameType,$usernameValue)->get()->first();
        $managerRequired = '';
        if(!$user->hasRole('restaurant_manager')){
            $managerRequired = 'required';
        }
        $this->validate($request, [
            $this->username() => 'required', 
            'password' => 'required',
            'manager' => $managerRequired
        ]);
    }
}
