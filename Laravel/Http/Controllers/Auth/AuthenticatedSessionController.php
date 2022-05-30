<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User_logs;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();
        $user->is_login = 1;
        $user->last_login_at = now();
        $user->ip_address = $request->ip();
        $user->save();

        User_logs::create([
            'user_id' => auth()->user()->id,
            'in_time' => Carbon::now(),
            'date' => Carbon::now()
        ]);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->is_login = 0;
        $user->save();

        $user_logs = User_logs::where('user_id',$user->id)->orderBy('id','DESC')->first();
        $user_logs->out_time = Carbon::now();

        $startTime = $user_logs->in_time;
        $endTime = $user_logs->out_time;

        $totalDuration = $endTime->diff($startTime);
        $h = $totalDuration->h;
        $i = $totalDuration->i;
        $s = $totalDuration->s;

        $user_logs->total_time_spent = $h .':'. $i .':'. $s;
        $user_logs->is_log_out = 1;
        $user_logs->save();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
