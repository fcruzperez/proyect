<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();
            logger()->error($user->role);
            if($user->role === 'admin') return redirect('/admin/dashboard');
            if($user->role === 'client') return redirect('/client/home');
            if($user->role === 'designer') return redirect('/designer/home');
//            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
