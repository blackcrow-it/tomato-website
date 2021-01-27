<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CanAccessAdminDashboard
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
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (!Auth::user()->is_super_admin && !Auth::user()->roles()->exists()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
