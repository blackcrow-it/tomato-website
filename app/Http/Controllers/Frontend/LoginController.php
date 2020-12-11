<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Session;
use Str;

class LoginController extends Controller
{
    public function index() {
        redirect()->setIntendedUrl(url()->previous());

        return view('frontend.auth.login');
    }

    public function login(Request $request) {
        $username = mb_strtolower(trim($request->input('username')));
        $password = $request->input('password');

        $attemptLogin = Auth::attempt([
            'username' => $username,
            'password' => $password,
        ], $request->input('remember'));

        if (!$attemptLogin) {
            return redirect()->route('login')->withErrors('Tài khoản hoặc mật khẩu không chính xác.');
        }

        $this->setLoginToken();

        return redirect()->intended(route('home'));
    }

    public function setLoginToken()
    {
        $loginToken = Str::random();
        Auth::user()->update([
            'login_token' =>  $loginToken
        ]);
        Session::put('login_token', $loginToken);
    }
}
