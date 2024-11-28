<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthAdminOrManager{
    
    public function handle($request, Closure $next, $guard = null){
        if(Auth::guard($guard)->guest()){
            if($request->ajax() || $request->wantsJson()) return response('Unauthorized.', 401);
            else return redirect()->guest('login');
        }elseif(!Auth::user()->hasAnyRole(['admin','restaurant_manager'])) return abort(401);
        return $next($request);
    }

}