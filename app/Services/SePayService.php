<?php
namespace App\Services;
use App\Models\OauthModel;
use CodeIgniter\HTTP\CURLRequest;
class SePayService
{
    protected $model;

    public function __construct()
    {
        $this->model = new OauthModel();
    }

    public function getBanks()
    {
        try {
            $setting = $this->model->where('key', 'se-pay')->first();
            $client = \Config\Services::curlrequest();
            $response = $client->request('get', 'https://my.sepay.vn/api/v1/bank-accounts', [
                'headers' => [
                    'Authorization' => 'Bearer ' . sepay_get_access_token($setting),
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getMessage(), (array)$th->getMessage());
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getTraceAsString());
            return null;
        }
    }
}