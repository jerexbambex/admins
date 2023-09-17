<?php

namespace App\Http\Middleware;

use App\Services\AccessService;
use Closure;
use Illuminate\Http\Request;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!AccessService::loginStatus()) return abort(401, 'You\'re restricted from viewing this page!');
        if(!AccessService::grantAccess()) return redirect()->route('profile.show');
        if(!session()->has('app_session')){
            session()->put('app_session', 2020);
            session()->put('sch_session', '2020/2021');
        } 
        return $next($request);
    }
}
