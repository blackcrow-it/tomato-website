<?php

namespace App\Services;

class Momo
{
    private $partnerCode;
    private $accessKey;
    private $secretKey;
    private $apiEndpoint;

    public function __construct()
    {
        $this->partnerCode = config('momo.partner_code');
        $this->accessKey = config('momo.access_key');
        $this->secretKey = config('momo.secret_key');
        $this->apiEndpoint = config('momo.endpoint');
    }

    public function captureMoMoWallet($params)
    {
        $postParams = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => '',
            'amount' => '',
            'orderId' => '',
            'orderInfo' => '',
            'returnUrl' => '',
            'notifyUrl' => '',
            'requestType' => 'captureMoMoWallet',
            'extraData' => ''
        ];
        $postParams = array_merge($postParams, $params);
        $postParams = array_map(function ($item) {
            return strval($item);
        }, $postParams);
        $signature = "partnerCode=$postParams[partnerCode]&accessKey=$postParams[accessKey]&requestId=$postParams[requestId]&amount=$postParams[amount]&orderId=$postParams[orderId]&orderInfo=$postParams[orderInfo]&returnUrl=$postParams[returnUrl]&notifyUrl=$postParams[notifyUrl]&extraData=$postParams[extraData]";
        $signature = hash_hmac('sha256', $signature, $this->secretKey);
        $postParams['signature'] = $signature;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status != 200) return false;
        $result = json_decode($result, true);
        if ($result['requestType'] != 'captureMoMoWallet') return false;
        $signature = "requestId=$postParams[requestId]&orderId=$postParams[orderId]&message=$result[message]&localMessage=$result[localMessage]&payUrl=$result[payUrl]&errorCode=$result[errorCode]&requestType=$result[requestType]";
        $signature = hash_hmac('sha256', $signature, $this->secretKey);
        if ($result['signature'] != $signature) return false;
        return $result;
    }

    public function checkResponse($response)
    {
        $signature = "partnerCode=$response[partnerCode]&accessKey=$response[accessKey]&requestId=$response[requestId]&amount=$response[amount]&orderId=$response[orderId]&orderInfo=$response[orderInfo]&orderType=$response[orderType]&transId=$response[transId]&message=$response[message]&localMessage=$response[localMessage]&responseTime=$response[responseTime]&errorCode=$response[errorCode]&payType=$response[payType]&extraData=$response[extraData]";
        $signature = hash_hmac('sha256', $signature, $this->secretKey);
        if ($response['signature'] != $signature) return false;
        return true;
    }

    public function callbackResponse($response)
    {
        header('Content-Type: application/json;charset=UTF-8');

        $responseData = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $response['requestId'],
            'orderId' => $response['orderId'],
            'errorCode' => $response['errorCode'],
            'message' => $response['message'],
            'responseTime' => date('YYYY-MM-DD HH:mm:ss'),
            'extraData' => isset($response['extraData']) ? $response['extraData'] : '',
        ];
        $signature = "partnerCode=$response[partnerCode]&accessKey=$response[accessKey]&requestId=$response[requestId]&orderId=$response[orderId]&errorCode=$response[errorCode]&message=$response[message]&responseTime=$response[responseTime]&extraData=$response[extraData]";
        $responseData['signature'] = hash_hmac('sha256', $signature, $this->secretKey);
        echo json_encode($responseData);
    }

    public function transactionStatus($requestId)
    {
        $postParams = [
            'partnerCode' => $this->partnerCode,
            'accessKey' => $this->accessKey,
            'requestId' => $requestId,
            'orderId' => $requestId,
            'requestType' => 'transactionStatus',
        ];
        $signature = "partnerCode=$postParams[partnerCode]&accessKey=$postParams[accessKey]&requestId=$postParams[requestId]&orderId=$postParams[orderId]&requestType=$postParams[requestType]";
        $postParams['signature'] = hash_hmac('sha256', $signature, $this->secretKey);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiEndpoint);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParams));
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($status != 200) return false;
        $result = json_decode($result, true);
        if ($result['requestType'] != 'transactionStatus') return false;
        $signature = "partnerCode=$result[partnerCode]&accessKey=$result[accessKey]&requestId=$result[requestId]&orderId=$result[orderId]&errorCode=$result[errorCode]&transId=$result[transId]&amount=$result[amount]&message=$result[message]&localMessage=$result[localMessage]&requestType=$result[requestType]&payType=$result[payType]&extraData=$result[extraData]";
        $signature = hash_hmac('sha256', $signature, $this->secretKey);
        if ($result['signature'] != $signature) return false;
        return $result;
    }
}
