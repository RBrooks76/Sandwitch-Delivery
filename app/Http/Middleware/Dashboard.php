<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Dashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if((Auth::user()->CheckRole(1) && Auth::user()->active == 1) || (Auth::user()->CheckRole(5) && Auth::user()->active == 1)){
                return $next($request);
            }
        }
        return redirect('/');
    }
}
