<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Route;
use Str;

class LoginController extends Controller
{
    public function index() {
        $redirectUrl = url()->previous(route('admin.home'));

        $previousRoute = Route::getRoutes()->match(Request::create($redirectUrl));
        $previousRouteName = $previousRoute ? $previousRoute->getName() : null;
        if (Str::startsWith($previousRouteName, 'auth.login') || !Str::startsWith($previousRouteName, 'auth.')) {
            $redirectUrl = route('admin.home');
        }

        redirect()->setIntendedUrl($redirectUrl);

        return view('backend.login.index');
    }

    public function login(Request $request) {
        $attemptLogin = Auth::attempt([
            'username' => mb_strtolower(trim($request->input('username'))),
            'password' => $request->input('password'),
        ], $request->input('remember'));

        if (!$attemptLogin) {
            return redirect()->route('admin.login')->withErrors('Tài khoản hoặc mật khẩu không chính xác.');
        }

        app(\App\Http\Controllers\Frontend\LoginController::class)->setLoginToken();

        return redirect()->intended(route('admin.home'));
    }
}
