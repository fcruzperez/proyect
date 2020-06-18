<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $user = auth()->user();

        if($guard !== $user->role) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
