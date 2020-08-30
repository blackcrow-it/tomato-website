<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RegisterRequest;
use App\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Str;

class RegisterController extends Controller
{
    public function index() {
        $redirectUrl = url()->previous();
        if (!Str::startsWith($redirectUrl, route('login'))) {
            redirect()->setIntendedUrl($redirectUrl);
        }

        return view('frontend.auth.register');
    }

    public function register(RegisterRequest $request) {
        $user = new User();
        $user->fill($request->input());

        $user->username = mb_strtolower(trim($user->username));
        $user->password = Hash::make($user->password);

        $user->save();

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}
