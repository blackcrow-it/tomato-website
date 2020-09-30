<?php

namespace App\Http\Controllers\Backend;

use App\Constants\RechargePartner;
use App\Constants\RechargeStatus;
use App\Http\Controllers\Controller;
use App\Recharge;
use App\Services\Epay;
use App\Services\Momo;
use DB;
use Illuminate\Http\Request;

class RechargeController extends Controller
{
    private $momo;
    private $epay;

    public function __construct()
    {
        $this->momo = new Momo();
        $this->epay = new Epay();
    }

    public function list()
    {
        return view('backend.recharge.list');
    }

    public function getData()
    {
        $data = Recharge::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate();

        $data->getCollection()->transform(function ($item) {
            $item->setHidden([]);
            return $item;
        });

        return $data;
    }

    public function recheck(Request $request)
    {
        $recharge = Recharge::with('user')->findOrFail($request->input('id'));
        switch ($recharge->type) {
            case RechargePartner::MOMO:
                $this->recheckMomo($recharge);
                break;

            case RechargePartner::EPAY:
                $this->recheckEpay($recharge);
                break;
        }
        $recharge->refresh();
        $recharge->setHidden([]);
        return $recharge;
    }

    private function recheckMomo(Recharge $recharge)
    {
        DB::transaction(function () use ($recharge) {
            Recharge::lockForUpdate()->find($recharge->id);

            $response = $this->momo->transactionStatus($recharge->trans_id);

            $recharge->callback_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['errorCode'] < 0) return;

            if ($response['errorCode'] != 0) {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                return;
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();
        });
    }

    private function recheckEpay(Recharge $recharge)
    {
        DB::transaction(function () use ($recharge) {
            Recharge::lockForUpdate()->find($recharge->id);

            $response = $this->epay->transactionStatus($recharge->trans_id);

            if ($response === false) return;

            $recharge->callback_data = json_encode($response);
            $recharge->amount = $response['amount'];

            if ($response['resultCd'] != '00_000') {
                $recharge->status = RechargeStatus::CANCEL;
                $recharge->save();

                return;
            }

            $recharge->status = RechargeStatus::SUCCESS;
            $recharge->save();
        });
    }
}
