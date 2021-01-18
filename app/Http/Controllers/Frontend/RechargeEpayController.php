<?php

namespace App\Http\Controllers\Frontend;

use App\Constants\RechargePartner;
use App\Constants\RechargeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\RechargeRequest;
use App\Mail\EpayRechargeMail;
use App\Recharge;
use App\Repositories\UserRepo;
use App\Services\Epay;
use Auth;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Mail;
use Str;

class RechargeEpayController extends Controller
{
    private $epay;
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->epay = new Epay();
        $this->userRepo = $userRepo;
    }

    public function makeRequest(RechargeRequest $request)
    {
        $requestId = 'TOMATO' . date('YmdHis') . rand(1000, 9999);

        $recharge = new Recharge();
        $recharge->user_id = Auth::user()->id;
        $recharge->amount = $request->input('money');
        $recharge->type = RechargePartner::EPAY;
        $recharge->status = RechargeStatus::PENDING;
        $recharge->trans_id = $requestId;
        $recharge->save();

        return $this->epay->generateMegapayFormData($recharge->trans_id, $recharge->amount);
    }

    public function processCallback(Request $request)
    {
        $response = $request->input();

        try {
            DB::beginTransaction();

            $requestId = $response['merTrxId'] ?? null;
            if ($requestId == null) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Mã giao dịch không chính xác.');
            }

            $recharge = Recharge::lockForUpdate()
                ->where([
                    'trans_id' => $requestId,
                    'type' => RechargePartner::EPAY,
                    // 'status' => RechargeStatus::PENDING,
                ])
                ->first();

            if ($recharge == null) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Yêu cầu nạp tiền không tồn tại.');
            }

            if ($recharge->status == RechargeStatus::SUCCESS) {
                DB::commit();
                return redirect()->route('user.recharge')->with('success', 'Nạp tiền thành công.');
            }

            if ($recharge->status == RechargeStatus::CANCEL) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Có lỗi xảy ra. Vui lòng thử lại.');
            }

            $isValidResponse = $this->epay->verifyMerchantToken($response);
            if (!$isValidResponse) {
                $response = $this->epay->transactionStatus($requestId);
            }

            if ($response === false) {
                DB::commit();
                return redirect()->route('user.recharge')->withErrors('Giao dịch thất bại.');
            }

            $recharge->callback_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['resultCd'] != '00_000') {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                DB::commit();
                return redirect()->route('user.recharge')->withErrors($response['resultMsg']);
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();

            DB::commit();

            $this->userRepo->addMoney($recharge->user->id, $response['amount']);

            if (config('settings.email_notification')) {
                Mail::to(config('settings.email_notification'))
                    ->send(
                        new EpayRechargeMail([
                            'user' => $recharge->user,
                            'amount' => $response['amount'],
                            'request_id' => $requestId
                        ])
                    );
            }

            return redirect()->route('user.recharge')->with('success', 'Nạp tiền thành công.');
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex);
            return redirect()->route('user.recharge')->withErrors('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function processNotify(Request $request)
    {
        $response = $request->getContent();

        DB::transaction(function () use ($response) {
            $requestId = $response['merTrxId'] ?? null;
            if ($requestId == null) return;

            $recharge = Recharge::lockForUpdate()
                ->where([
                    'trans_id' => $requestId,
                    'type' => RechargePartner::EPAY,
                    'status' => RechargeStatus::PENDING,
                ])
                ->first();
            if ($recharge == null) return;

            $isValidResponse = $this->epay->verifyMerchantToken($response);
            if (!$isValidResponse) {
                $response = $this->epay->transactionStatus($requestId);
            }

            if ($response === false) return;

            $recharge->notify_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['resultCd'] != '00_000') {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                return;
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();

            $this->userRepo->addMoney($recharge->user->id, $response['amount']);

            if (config('settings.email_notification')) {
                Mail::to(config('settings.email_notification'))
                    ->send(
                        new EpayRechargeMail([
                            'user' => $recharge->user,
                            'amount' => $response['amount'],
                            'request_id' => $requestId
                        ])
                    );
            }
        });
    }
}
