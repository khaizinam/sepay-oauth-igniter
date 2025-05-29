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
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' response: ' . $response->getBody());
            $data = json_decode($response->getBody(), true);
            if($data['status'] !== 'success') {
                return null;
            }
            return $data['data'] ?? null;
        } catch (\Throwable $th) {
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getMessage());
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getTraceAsString());
            return null;
        }
    }

    public function getSetting()
    {
        $setting = $this->model->where('key', 'se-pay')->first();
        if (blank($setting)) {
            $settingData = [
                'key'   => 'se-pay',
                'name' => 'Tích hợp SePay',
                'description' => 'Tích hợp SePay',
                'client_id' => '',
                'state' => 'RANDOM_STATE_VALUE',
                'redirect_uri' => base_url('oauth/callback')
            ];
            $model->insert($settingData);
            $setting = $model->where('key', 'se-pay')->first();
        }
        return $setting;
    }
}