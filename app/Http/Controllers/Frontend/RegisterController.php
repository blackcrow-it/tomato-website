<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RegisterRequest;
use App\Setting;
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
        $background = Setting::where('key', 'register_background')->first();
        return view('frontend.auth.register', [
            'background' => $background->value
        ]);
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
