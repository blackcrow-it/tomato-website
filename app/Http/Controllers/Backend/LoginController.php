<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {
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

        return redirect()->route('admin.home');
    }
}
