<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\RechargePartner;
use App\Constants\RechargeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RechargeRequest;
use App\Recharge;
use App\Repositories\UserRepo;
use App\Services\Momo;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
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
        $requestId = 'TOMATO' . date('YmdHis') . rand(1000, 9999);
        $response = $this->momo->captureMoMoWallet([
            'requestId' => $requestId,
            'amount'    => $request->input('money'),
            'orderId'   => $requestId,
            'orderInfo' => 'Nạp tiền vào tài khoản Tomato Online',
            'returnUrl' => route('recharge.momo.callback'),
            'notifyUrl' => route('recharge.momo.notify'),
        ]);

        if ($response === false) {
            return redirect()->route('user.recharge')->withErrors('Yêu cầu nạp tiền không chính xác. Vui lòng thử lại.');
        }

        $recharge = new Recharge();
        $recharge->user_id = Auth::user()->id;
        $recharge->amount = $request->input('money');
        $recharge->type = RechargePartner::MOMO;
        $recharge->status = RechargeStatus::PENDING;
        $recharge->trans_id = $requestId;
        $recharge->request_data = json_encode($response);
        $recharge->save();

        return [
            'pay_url' => $response['payUrl']
        ];
    }

    public function processCallback(Request $request)
    {
        $response = $request->input();

        try {
            DB::beginTransaction();

            $requestId = $response['requestId'] ?? null;
            if ($requestId == null) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Dữ liệu nhận được không chính xác.');
            }

            $recharge = Recharge::lockForUpdate()
                ->where([
                    'trans_id' => $requestId,
                    'type' => RechargePartner::MOMO,
                    'status' => RechargeStatus::PENDING,
                ])
                ->first();
            if ($recharge == null) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Yêu cầu nạp tiền không tồn tại.');
            }

            $isValidResponse = $this->momo->checkResponse($response);
            if (!$isValidResponse) {
                $response = $this->momo->transactionStatus($requestId);
            }

            $recharge->callback_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['errorCode'] != 0) {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                DB::commit();
                return redirect()->route('user.recharge')->withErrors($response['localMessage']);
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();

            DB::commit();

            $this->userRepo->addMoney(Auth::user()->id, $response['amount']);

            return redirect()->route('user.recharge')->with('success', 'Nạp tiền thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return redirect()->route('user.recharge')->withErrors('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function processNotify(Request $request)
    {
        $response = $request->input();

        DB::transaction(function () use ($response) {
            $requestId = $response['requestId'] ?? null;
            if ($requestId == null) return;

            $recharge = Recharge::lockForUpdate()
                ->where([
                    'trans_id' => $requestId,
                    'type' => RechargePartner::MOMO,
                    'status' => RechargeStatus::PENDING,
                ])
                ->first();
            if ($recharge == null) return;

            $isValidResponse = $this->momo->checkResponse($response);
            if (!$isValidResponse) {
                $response = $this->momo->transactionStatus($requestId);
            }

            $recharge->notify_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['errorCode'] != 0) {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                return;
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();

            $this->userRepo->addMoney(Auth::user()->id, $response['amount']);
        });
    }
}
