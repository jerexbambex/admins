<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard)
    {
        $authorized = false;
        foreach (explode('|', $guard) as $role) {
            if ($request->user()->hasRole($role)) {
                $authorized = true;
            }
        }
        if ($authorized) return $next($request);
        return abort(401, 'Unauthorized page');
    }
}
