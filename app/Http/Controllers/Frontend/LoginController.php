<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Str;

class LoginController extends Controller
{
    public function index() {
        redirect()->setIntendedUrl(url()->previous());

        return view('frontend.auth.login');
    }

    public function login(Request $request) {
        $attemptLogin = Auth::attempt([
            'username' => mb_strtolower(trim($request->input('username'))),
            'password' => $request->input('password'),
        ], $request->input('remember'));

        if (!$attemptLogin) {
            return redirect()->route('login')->withErrors('Tài khoản hoặc mật khẩu không chính xác.');
        }

        return redirect()->intended(route('home'));
    }
}
