<?php

namespace App\Http\Middleware;

use App\Constants\PermissionString;
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

        if (Auth::user()->can(PermissionString::ACCESS_ADMIN_DASHBOARD) == false) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
