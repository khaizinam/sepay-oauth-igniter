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
            $response = $this->client->request('post', $this->endpoint . 'oauth/token', [
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
            $body = $response->getBody();
            log_message('info', __CLASS__ . '@' . __FUNCTION__ . ' response: ' . $body);
            return json_decode($body, true);
        } catch (\Throwable $th) {
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getMessage());
            log_message('error',__CLASS__. '@' . __FUNCTION__, (array)$th->getTraceAsString());
            return null;
        }
    }

    public function getAccessToken($setting)
    {
        try {
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
        return [
            'Authorization' => 'Bearer ' . $accessToken,
        ];
    }

    public function getBanks()
    {
        try {
            $setting = $this->model->where('key', 'se-pay')->first();
            $response = $this->client->request('get', $this->endpoint . 'api/v1/bank-accounts', [
                'headers' => $this->getAuthorizationHeader($setting)
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
            $this->model->insert($settingData);
            $setting = $this->model->where('key', 'se-pay')->first();
        }
        return $setting;
    }

    public function getBankDetail($bank_id)
    {
        try {
            $setting = $this->getSetting();
            if (blank($setting)) {
                return null;
            }
            $client = \Config\Services::curlrequest();
            $response = $client->request('get',  $this->endpoint . 'api/v1/bank-accounts/' . $bank_id, [
                'headers' => $this->getAuthorizationHeader($setting)
            ]);

            log_message('error', __CLASS__ . '@' . __FUNCTION__ . ' response: ' . $response->getBody());
            $data = json_decode($response->getBody(), true);
            return $data;
        } catch (\Throwable $th) {
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getMessage());
            log_message('error', __CLASS__ . '@' . __FUNCTION__ . $th->getTraceAsString());
            return null;
        }
    }
}