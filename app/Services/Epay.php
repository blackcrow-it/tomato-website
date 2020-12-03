<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Log;

class Epay
{
    private $domain;
    private $merId;
    private $encodeKey;

    public function __construct()
    {
        $this->domain = config('epay.domain');
        $this->merId = config('epay.mer_id');
        $this->encodeKey = config('epay.encode_key');
    }

    public function generateMegapayFormData($transId, $amount)
    {
        $timestamp = date('YmdHis');
        return [
            'merId'         => $this->merId,
            'currency'      => 'VND',
            'amount'        => $amount,
            'invoiceNo'     => $transId,
            'goodsNm'       => 'Nạp tiền vào tài khoản Tomato Online',
            'payType'       => 'NO',
            'callBackUrl'   => route('recharge.epay.callback'),
            'notiUrl'       => route('recharge.epay.notify'),
            'reqDomain'     => 'http://tomatoonline.edu.vn', // url()->to('/')
            'vat'           => 0,
            'fee'           => 0,
            'notax'         => 0,
            'description'   => 'Nap tien Tomato',
            'merchantToken' => hash('sha256', $timestamp . $transId . $this->merId . $amount . $this->encodeKey),
            'userLanguage'  => 'VN',
            'timeStamp'     => $timestamp,
            'merTrxId'      => $transId,
            'userFee'       => 0,
            'goodsAmount'   => $amount,
            'windowColor'   => '#ef5459',
            'windowType'    => null,
        ];
    }

    public function verifyMerchantToken($response)
    {
        $expectedHash = hash('sha256', $response['resultCd'] . $response['timeStamp'] . $response['merTrxId'] . $response['trxId'] . $this->merId . $response['amount'] . $this->encodeKey);
        return $response['merchantToken'] == $expectedHash;
    }

    public function transactionStatus($transId)
    {
        try {
            $timestamp = date('YmdHis');

            $client = new Client();
            $response = $client->request('POST', $this->domain . '/pg_was/order/trxStatus.do', [
                RequestOptions::FORM_PARAMS => [
                    'merId' => $this->merId,
                    'merTrxId' => $transId,
                    'timeStamp' => $timestamp,
                    'merchantToken' => hash('sha256', $timestamp . $transId . $this->merId . $this->encodeKey),
                ]
            ]);

            $responseJson = $response->getBody()->__toString();
            $responseData = json_decode($responseJson, true);

            if ($responseData['resultCd'] != '00_000') {
                return false;
            }

            if (!$this->verifyMerchantToken($responseData['data'])) {
                return false;
            }

            return $responseData['data'];
        } catch (Exception $ex) {
            Log::error($ex);
            return false;
        }
    }
}
