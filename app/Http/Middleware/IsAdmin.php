<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Gate;

class IsAdmin
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
        if (!Gate::allows('admin', \Auth::user())) {/*
            \Auth::logout();
            return redirect('login');*/
            return false;
        }
        return $next($request);
    }
}
