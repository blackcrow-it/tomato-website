<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

class LoginSingleDevice
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
        if (Auth::check()) {
            $loginToken = Session::get('login_token');
            if ($loginToken != Auth::user()->login_token) {
                Auth::logout();
                return redirect()->route('home');
            }
        }
        return $next($request);
    }
}
