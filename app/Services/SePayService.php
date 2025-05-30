<?php
namespace App\Services;
use App\Models\OauthModel;
use CodeIgniter\HTTP\CURLRequest;
class SePayService
{
    protected $model;

    protected $endpoint = 'https://my.sepay.vn/';

    protected $client;

    public function __construct()
    {
        $this->model = new OauthModel();
        $this->client = \Config\Services::curlrequest();
    }

    public function refreshToken($setting){
        try {
            $response = $this->client->request('POST', $this->endpoint . 'oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type'        => 'refresh_token',
                    'refresh_token'     => $setting['refresh_token'],
                    'client_id'         => $setting['client_id'],
                    'client_secret'     => $setting['client_secret'],
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            log_message('error',__CLASS__. '@' . __FUNCTION__ . ' : '. $th->getMessage());
            log_message('error',__CLASS__. '@' . __FUNCTION__ . ' : ' . $th->getTraceAsString());
            return null;
        }
    }

    public function getAccessToken($setting)
    {
        try {
            log_message('error',__CLASS__. '@' . __FUNCTION__ . ' : START');
            if (strtotime($setting['expires_in']) < time()) {
                $newToken = $this->refreshToken($setting);
                if (blank($newToken) || !blank($newToken['error'] ?? null)) {
                    return null;
                }
                $this->model->update($setting['id'], [
                    'access_token' => $newToken['access_token'],
                    'refresh_token' => $newToken['refresh_token'],
                    'expires_in' => app_create_expires_in($newToken['expires_in']),
                ]);
                return $newToken['access_token'];
            }
            return $setting['access_token'];
        } catch (\Throwable $th) {
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getMessage());
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getTraceAsString());
            return null;
        }
    }

    private function getAuthorizationHeader($setting)
    {
        $accessToken = $this->getAccessToken($setting);
        return 'Bearer ' . $accessToken;
    }

    public function getBanks()
    {
        try {
            $setting = $this->model->where('key', 'se-pay')->first();
            $response = $this->client->request('GET', $this->endpoint . 'api/v1/bank-accounts', [
                'headers' => [
                    'Authorization' => $this->getAuthorizationHeader($setting)
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['data'] ?? null;
        } catch (\Throwable $th) {
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' : ' . $th->getMessage());
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' : ' . $th->getTraceAsString());
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
            $this->model->insert($settingData);
            $setting = $this->model->where('key', 'se-pay')->first();
        }
        $setting['rawdata'] = json_decode(app_get_data($setting, 'rawdata', '{}'), true);
        $setting['va_account_id'] = app_get_data($setting, 'rawdata.bank_setting.va_account_id');
        $setting['bank_account_id'] = app_get_data($setting, 'rawdata.bank_setting.bank_account_id');
        return $setting;
    }

    public function getBankDetail($bank_id)
    {
        $setting = $this->getSetting();
        $response = $this->client->request('GET',  $this->endpoint . 'api/v1/bank-accounts/' . $bank_id, [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting)
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    public function getSubAccount($bank_id)
    {
        $setting = $this->getSetting();
        $response = $this->client->request('GET',  $this->endpoint . 'api/v1/bank-accounts/' . $bank_id . '/sub-accounts', [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting)
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        return app_get_data($data, 'data', []);
    }

    public function updateBankAccount($data)
    {
        log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' START');
        $setting = $this->getSetting();
        $rawdata = $setting['rawdata'] ?? [];
        log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' rawdata : ' . json_encode($data, true));
        $rawdata['bank_setting']= [
            'bank_account_id' => app_get_data($data, 'bank_account_id', ''),
            'va_account_id' => app_get_data($data, 'va_account_id', ''),
        ];
        $result = $this->model->update($setting['id'], [
            'rawdata' => json_encode($rawdata, true),
        ]);
        return $result;
    }

    public function getTransactions($payload)
    {
        $setting = $this->getSetting();
        $response = $this->client->request('GET',  $this->endpoint . 'api/v1/transactions', [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting)
            ],
            'query' => $payload
        ]);
        $data = json_decode($response->getBody(), true);
        return app_get_data($data, 'data');
    }
}