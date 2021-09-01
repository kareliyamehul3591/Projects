<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class AuthManager{

    public function handle($request, Closure $next, $guard = null){
        if(Auth::guard($guard)->guest()){
            if($request->ajax() || $request->wantsJson()) return response('Unauthorized.', 401);
            else return redirect()->guest('login');
        }elseif(!(Auth::user()->hasRole('restaurant_manager') || Auth::user()->hasRole('admin'))) return abort(401);

        return $next($request);

    }

}
