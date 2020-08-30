<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Str;

class LoginController extends Controller
{
    public function index() {
        $redirectUrl = url()->previous();
        if (Str::startsWith($redirectUrl, route('admin.login'))) {
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

        return redirect()->intended(route('home'));
    }
}
