<?php

namespace App\Http\Controllers;

use App\PasswordSecurity;
use Auth;
use Debugbar;
use Hash;
use Illuminate\Http\Request;
use Google2FA;

class PasswordSecurityController extends Controller
{
    public function show2faForm(Request $request){
        $user = Auth::user();

        $google2fa_url = "";
        if($user->passwordSecurity){
            $google2fa_img = Google2FA::getQRCodeInline(
                'Tomato Education Online',
                $user->email,
                $user->passwordSecurity->google2fa_secret
            );
            $google2fa_url = Google2FA::getQRCodeUrl(
                'Tomato Education Online',
                $user->email,
                $user->passwordSecurity->google2fa_secret
            );
            $secret_key = Google2FA::getSecret($user->passwordSecurity->google2fa_secret);
        }
        $data = [
            'user' => $user,
            'google2fa_img' => $google2fa_img,
            'google2fa_url' => $google2fa_url,
            'secret_key' => $secret_key
        ];

        return view('frontend.auth.2fa')->with('data', $data);
    }

    public function generate2faSecret(Request $request){
        $user = Auth::user();
        // Initialise the 2FA class

        // Add the secret key to the registration data
        PasswordSecurity::create([
            'user_id' => $user->id,
            'google2fa_enable' => 0,
            'google2fa_secret' => Google2FA::generateSecretKey(),
        ]);

        return redirect()
            ->route('user.2fa')
            ->with('success',"Mã đã được tạo. Vui lòng xác minh mã để kích hoạt 2FA");
    }

    public function enable2fa(Request $request){
        $user = Auth::user();
        $secret = strval($request->input('first'));
        $secret .= strval($request->input('second'));
        $secret .= strval($request->input('third'));
        $secret .= strval($request->input('fourth'));
        $secret .= strval($request->input('fifth'));
        $secret .= strval($request->input('sixth'));
        $valid = Google2FA::verifyKey($user->passwordSecurity->google2fa_secret, $secret);
        $otp = Google2FA::getCurrentOtp($user->passwordSecurity->google2fa_secret);
        Debugbar::info('SECRET_KEY: '.$user->passwordSecurity->google2fa_secret);
        Debugbar::info('CODE: '.$secret);
        Debugbar::info('OTP: '.$otp);
        if ($valid) {
            $user->passwordSecurity->google2fa_enable = 1;
            $user->passwordSecurity->save();
            return redirect()
            ->route('user.2fa')
            ->with('success', 'Bật tính năng xác minh 2 bước thành công.');
        } else {
            return redirect()
            ->route('user.2fa')
            ->withErrors('Mã OTP nhập sai. Vui lòng nhập lại.');
        }
    }

    public function disable2fa(Request $request){
        // if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
        //     // The passwords matches
        //     return redirect()->back()->with("error","Your  password does not matches with your account password. Please try again.");
        // }
        $user = Auth::user();
        $secret = strval($request->input('first'));
        $secret .= strval($request->input('second'));
        $secret .= strval($request->input('third'));
        $secret .= strval($request->input('fourth'));
        $secret .= strval($request->input('fifth'));
        $secret .= strval($request->input('sixth'));
        $valid = Google2FA::verifyKey($user->passwordSecurity->google2fa_secret, $secret);
        if ($valid) {
            $user->passwordSecurity->google2fa_enable = 0;
            $user->passwordSecurity->save();
            return redirect()
            ->route('user.info')
            ->with('success', 'Tắt tính năng xác minh 2 bước thành công.');
        } else {
            return redirect()
            ->route('user.2fa')
            ->withErrors('Mã OTP nhập sai. Vui lòng nhập lại.');
        }
    }
}
