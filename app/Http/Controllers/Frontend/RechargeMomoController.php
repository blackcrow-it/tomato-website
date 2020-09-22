<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RechargeRequest;
use App\Repositories\UserRepo;
use App\Services\Momo;
use Auth;
use Illuminate\Http\Request;
use Str;

class RechargeMomoController extends Controller
{
    private $momo;
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->momo = new Momo();
        $this->userRepo = $userRepo;
    }

    public function makeRequest(RechargeRequest $request)
    {
        $requestId = 'TOMATO_' . date('YmdHis') . '_' . Str::random(8);
        $result = $this->momo->captureMoMoWallet([
            'requestId' => $requestId,
            'amount'    => $request->input('money'),
            'orderId'   => $requestId,
            'orderInfo' => 'Nạp tiền vào tài khoản Tomato Online.',
            'returnUrl' => route('recharge.momo.callback'),
            'notifyUrl' => route('recharge.momo.notify'),
        ]);

        if ($result === false) {
            return redirect()->route('user.recharge')->withErrors('Yêu cầu nạp tiền không chính xác. Vui lòng thử lại.');
        }

        return [
            'pay_url' => $result['payUrl']
        ];
    }

    public function processCallback(Request $request)
    {
        $response = $request->input();
        $isValidResponse = $this->momo->checkResponse($response);
        if (!$isValidResponse) {
            $response = $this->momo->transactionStatus($response['requestId']);
        }

        if ($response['errorCode'] != 0) {
            return redirect()->route('user.recharge')->withErrors($response['localMessage']);
        }

        $this->userRepo->addMoney(Auth::user()->id, $response['amount']);

        return redirect()->route('user.recharge')->with('success', 'Nạp tiền thành công.');
    }

    public function processNotify()
    {
    }
}
