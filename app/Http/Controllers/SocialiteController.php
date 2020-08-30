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
        $redirectUrl = url()->previous();
        if (Str::startsWith($redirectUrl, route('admin.login'))) {
            $redirectUrl = route('admin.home');
        }

        redirect()->setIntendedUrl($redirectUrl);

        return Socialite::driver('google')->redirect();
    }

    public function loginWithGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->email)
            ->orWhere('google_id', $googleUser->id)
            ->first();

        if ($user == null) {
            $user = new User;
            $user->password = Hash::make(Str::random());
        }

        $user->username = $user->username ?? $googleUser->email;
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
