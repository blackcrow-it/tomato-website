<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RequestResetPassword;
use App\Repositories\UserRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

class ForgotPasswordController extends Controller
{

    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        return view('frontend.auth.forgot');
    }

    public function sendCodeResetPassword(Request $request)
    {
        try {
            $email = $request->get('email');
            $checkExistUser = $this->userRepo->getFirstByEmail($email);

            if (!$checkExistUser) {
                return redirect()->back()->with('danger', 'Email không tồn tại !');
            }

            $code = \Hash::make(time() . $email);

            $checkExistUser->code = $code;
            $checkExistUser->time_code = Carbon::now();
            $checkExistUser->save();

            $url = route('resetPassword', ['code' => $checkExistUser->code, 'email' => $email]);
            $data = [
                'route' => $url
            ];

            Mail::send('mail.password_reset', $data, function ($message) use ($email) {
                $message->to($email, 'reset password')->subject('Mail lấy lại mật khẩu người dùng từ Tomato');
            });

            return redirect()->back()->with('success', 'Link lấy lại mật khẩu đã được gửi đến email của bạn !');
        }catch (\Exception $ex){
            report($ex);
            return abort(500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $email = $request->get('email');
            $code = $request->get('code');
//            dd($code, $email);
            $user = $this->userRepo->getFirstByEmailAndCode($email, $code);

            $now = Carbon::now();
            dd($user);
            $timeExpires = Carbon::parse($user->time_code)->addMinutes(15);

            if ($timeExpires < $now) {
                return redirect('/')->with('danger', 'Xin lỗi! thời gian đã hết hạn, vui lòng thử lại');
            }

            if (!$user) {
                return redirect('/')->with('danger', 'Xin lỗi! đường dẫn không đúng, vui lòng thử lại');
            }

            return view('frontend.auth.resetPassword');
        }catch (\Exception $ex){
            dd($ex);
            report($ex);
            return abort(500);
        }
    }

    public function saveResetPassword(RequestResetPassword $requestResetPassword)
    {
        try {
            $email = $requestResetPassword->get('email');
            $code = $requestResetPassword->get('code');

            $user = $this->userRepo->getFirstByEmailAndCode($email, $code);

            if (!$user) {
                return redirect('/')->with('danger', 'Xin lỗi! đường dẫn không đúng, vui lòng thử lại');
            }

            $user->password = \Hash::make($requestResetPassword->get('password'));
            $user->save();

            return redirect('/dang-nhap')->with('success','Mật khẩu của bạn đã được thay đổi thành công !');
        }catch (\Exception $ex){
            report($ex);
            return abort(500);
        }
    }
}
