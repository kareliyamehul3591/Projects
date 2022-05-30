<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $url = substr($request->getPathInfo(), 1);
            $menu = Menu::where('url',$url)->first();
            // dd($url,$menu);
            if($menu){
                $menuId = [];
                $user = Auth::guard('web')->user();
                $menuId = explode(',',$user->privileges);
                if (in_array($menu->id,$menuId)){
                    return $next($request);
                } else {
                    return redirect(RouteServiceProvider::HOME);
                }
            } else{
                return $next($request);
            }

        }
        return redirect('/');
    }
}
