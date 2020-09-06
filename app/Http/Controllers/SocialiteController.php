<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Socialite;
use Str;

class SocialiteController extends Controller
{
    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = Auth::user() ?? User::where('email', $googleUser->email)->first();

        if ($user == null) {
            $user = new User;
            $user->username = $googleUser->email;
            $user->password = Hash::make(Str::random());
        }

        $user->google_id = $googleUser->id;
        $user->email = $googleUser->email;
        $user->avatar = $user->avatar ?? $googleUser->avatar;
        $user->name = $user->name ?? $googleUser->name;
        $user->email_verified_at = Carbon::now();

        $user->save();

        Auth::login($user);

        return redirect()->intended(route('home'));
    }
}
