<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RequestResetPassword;
use App\Repositories\UserRepo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
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

            $checkExistUser->code_forgot_password = $code;
            $checkExistUser->time_code_forgot_password_at = Carbon::now();
            $checkExistUser->save();

            $url = route('resetPassword', ['code' => $checkExistUser->code_forgot_password, 'email' => $email]);
            $data = [
                'route' => $url
            ];

            Mail::send('mail.password_reset', $data, function ($message) use ($email) {
                $message->to($email, 'reset password')->subject('Lấy Lại mật khẩu người dùng(Tomato Website)');
            });

            return redirect()->back()->with('success', 'Link lấy lại mật khẩu đã được gửi đến email của bạn !');
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()
                ->route('forgot')
                ->with('danger', 'việc thay đổi password bị thất bại, vui lòng thử lại !');
        }
    }

    public function resetPassword(Request $request)
    {
        // try {
            $email = $request->get('email');
            $code = $request->get('code');

            $user = $this->userRepo->getFirstByEmailAndCode($email, $code);

            $now = Carbon::now();
            $timeExpires = Carbon::parse($user->time_code_forgot_password_at)->addMinutes(15);

            if ($timeExpires < $now) {
                return redirect()->route('login')->with('danger', 'Xin lỗi! thời gian đã hết hạn, vui lòng thử lại');
            }

            if (!$user) {
                return redirect()->route('login')->with('danger', 'Xin lỗi! đường dẫn không đúng, vui lòng thử lại');
            }

            return view('frontend.auth.resetPassword')->with(['email' => $email, 'code' => $code]);
        // } catch (\Exception $ex) {
        //     Log::error($ex);
        //     return redirect()
        //         ->route('login')
        //         ->with('danger', 'Việc thay đổi password bị thất bại, vui lòng thử lại !');
        // }
    }

    public function saveResetPassword(RequestResetPassword $request)
    {
        try {
            $email = $request->get('email');
            $code = $request->get('code');

            $user = $this->userRepo->getFirstByEmailAndCode($email, $code);

            if (!$user) {
                return redirect()->route('login')->with('danger', 'Xin lỗi! đường dẫn không đúng, vui lòng thử lại');
            }

            $user->password = \Hash::make($request->get('password'));
            $user->save();

            return redirect()->route('login')->with('success', 'Mật khẩu của bạn đã được thay đổi thành công, Vui lòng đăng nhập lại !');
        } catch (\Exception $ex) {
            Log::error($ex);
            return redirect()
                ->route('login')
                ->with('danger', 'Việc thay đổi password bị thất bại, vui lòng thử lại !');
        }
    }
}
