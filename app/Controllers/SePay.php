<?php

namespace App\Controllers;

use App\Models\OauthModel;
use App\Services\SePayService;
use Exception;

class SePay extends BaseController
{
    protected SePayService $sePayService;

    protected $endpoint = 'https://my.sepay.vn/';

    public function __construct(){
        $this->sePayService = new SePayService();
    }

    public function getBankDetails($bank_id)
    {
        try {
            if($this->request->isAJAX() == false || $this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
                throw new Exception('This method only supports AJAX requests');
            }
            $setting = $this->sePayService->getSetting();
            $client = \Config\Services::curlrequest();
            $response = $client->request('get', $this->endpoint . 'api/v1/bank-accounts/' . $bank_id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . sepay_get_access_token($setting),
                ]
            ]);
            $body = $response->getBody();
            log_message('info',__CLASS__ . '@' . __FUNCTION__.'response: ' . $body);
            $data = json_decode($body, true);
            $data = $this->sePayService->getBankDetail($bank_id);
            return $this->response->setJSON([
                'error' => false,
                'message' => 'Bank details retrieved successfully',
                'data' => $data['data'] ?? null,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Throwable $th) {
            log_message('error',__CLASS__ . '@' . __FUNCTION__, ['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $th->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }
}
