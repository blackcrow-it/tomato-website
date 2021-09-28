<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Frontend\LoginController;
use App\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Socialite;
use Str;
use Throwable;

class SocialiteController extends Controller
{
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $th) {
            return redirect()->intended(route('home'));
        }

        $user = Auth::user() ?? User::where('google_id', $googleUser->id)->first() ?? User::where('email', $googleUser->email)->first();

        if ($user == null) {
            $user = new User;
            $user->username = $googleUser->email;
            $user->password = Hash::make(Str::random());
            $user->email = $googleUser->email;
            $user->avatar = $user->avatar ?? $googleUser->avatar;
            $user->name = $user->name ?? $googleUser->name;
            $user->email_verified_at = Carbon::now();
        }

        $user->google_id = $googleUser->id;
        $user->save();

        Auth::login($user);
        app(LoginController::class)->setLoginToken();

        return redirect()->intended(route('2faVerify'));
    }

    public function loginWithFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function loginWithFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (Throwable $th) {
            return redirect()->intended(route('home'));
        }

        $user = Auth::user() ?? User::where('facebook_id', $facebookUser->id)->first() ?? User::where('email', $facebookUser->email)->first();

        if ($user == null) {
            $user = new User;
            $user->username = 'fb' . $facebookUser->id;
            $user->password = Hash::make(Str::random());
            $user->email = $user->email ?? $facebookUser->email;
            $user->avatar = $user->avatar ?? $facebookUser->avatar;
            $user->name = $user->name ?? $facebookUser->name;
        }

        $user->facebook_id = $facebookUser->id;
        $user->save();

        Auth::login($user);
        app(LoginController::class)->setLoginToken();

        return redirect()->intended(route('2faVerify'));
    }
}
