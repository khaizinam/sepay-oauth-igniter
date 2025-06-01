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
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return null;
        }
    }

    /**
     * Get acctoken from db or refresh token
     */
    public function getAccessToken($setting)
    {
        try {
            // check time expires time
            if (strtotime($setting['expires_in']) < time()) {
                // call action refresh token
                $newToken = $this->refreshToken($setting);
                if (blank($newToken) || !blank($newToken['error'] ?? null)) {
                    return null;
                }
                // update access token to db
                $this->model->update($setting['id'], [
                    'access_token' => $newToken['access_token'],
                    'refresh_token' => $newToken['refresh_token'],
                    'expires_in' => app_create_expires_in($newToken['expires_in']),
                ]);
                return $newToken['access_token'];
            }
            // return default token from db.
            return $setting['access_token'];
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
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
            app_log_error(__CLASS__, __FUNCTION__, $th);
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
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }

    /**
     * https://docs.sepay.vn/oauth2/api-webhooks.html
     * Lấy danh sách webhooks
     * Scope: webhook:read
     */
    public function getWebooks(){
        $setting = $this->getSetting();
        $response = $this->client->request('GET',  $this->endpoint . 'api/v1/webhooks', [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting)
            ],
            'query' => [
                'active' => 1,
                'page' => 1,
                'limit' => 20
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }

    /**
     * https://docs.sepay.vn/oauth2/api-webhooks.html
     * Lấy chi tiết webhook
     * Scope: webhook:read
     */
    public function getWebookDetail($webhok_id){
        $setting = $this->getSetting();
        $response = $this->client->request('GET',  $this->endpoint . 'api/v1/webhooks/' . $webhok_id, [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting)
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }

    /**
     * https://docs.sepay.vn/oauth2/api-webhooks.html
     * Tạo webhook mới
     * Scope: webhook:write
     */
    public function createNewWebhook($payload){
        $setting = $this->getSetting();
        $response = $this->client->request('POST',  $this->endpoint . '/api/v1/webhooks', [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                "bank_account_id" => app_get_data($payload, 'bank_account_id'), // Bắt buộc, ID của tài khoản ngân hàng
                "name" => app_get_data($payload, 'name'), // Bắt buộc, Tên của webhook
                "event_type" => app_get_data($payload, 'event_type'), // Bắt buộc, Loại sự kiện (All, In_only, Out_only)
                "authen_type" => 'No_Authen', // Bắt buộc, Kiểu xác thực (No_Authen, OAuth2.0, Api_Key)
                "webhook_url" => app_get_data($payload, 'webhook_url'), // Bắt buộc, URL nhận webhook
                "is_verify_payment" => app_get_data($payload, 'is_verify_payment', 0), // Bắt buộc, Có xác thực thanh toán không (0: không, 1: có)
                "skip_if_no_code" => app_get_data($payload, 'skip_if_no_code', 1), // Bắt buộc, Bỏ qua nếu không có mã thanh toán (0: không, 1: có)
                "active" => app_get_data($payload, 'active', 1), // Bắt buộc, Trạng thái hoạt động (0: không hoạt động, 1: đang hoạt động)
                "only_va" => 0,
                // "bank_sub_account_ids" => [25, 26],
                // "retry_conditions" => [
                //     "non_2xx_status_code" => 1
                // ],
                // "api_key" => "a7c3b4e5f6a7b8c9d0e1f2a3b4c5d6e7",
                "request_content_type" => "Json" // Bắt buộc, Kiểu nội dung yêu cầu (Json, multipart_form-data)
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }

    /**
     * https://docs.sepay.vn/oauth2/api-webhooks.html
     * Xóa webhook
     * Scope: webhook:write
     */
    public function updateWebhook($wh_id, $payload){
        $setting = $this->getSetting();
        $response = $this->client->request("PATCH",  $this->endpoint . '/api/v1/webhooks/' . $wh_id, [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                "bank_account_id" => app_get_data($payload, 'bank_account_id'), // Bắt buộc, ID của tài khoản ngân hàng
                "name" => app_get_data($payload, 'name'), // Bắt buộc, Tên của webhook
                "event_type" => app_get_data($payload, 'event_type'), // Bắt buộc, Loại sự kiện (All, In_only, Out_only)
                "authen_type" => 'No_Authen', // Bắt buộc, Kiểu xác thực (No_Authen, OAuth2.0, Api_Key)
                "webhook_url" => app_get_data($payload, 'webhook_url'), // Bắt buộc, URL nhận webhook
                "is_verify_payment" => app_get_data($payload, 'is_verify_payment', 0), // Bắt buộc, Có xác thực thanh toán không (0: không, 1: có)
                "skip_if_no_code" => app_get_data($payload, 'skip_if_no_code', 1), // Bắt buộc, Bỏ qua nếu không có mã thanh toán (0: không, 1: có)
                "active" => app_get_data($payload, 'active', 1), // Bắt buộc, Trạng thái hoạt động (0: không hoạt động, 1: đang hoạt động)
                "only_va" => 0,
                // "bank_sub_account_ids" => [25, 26],
                // "retry_conditions" => [
                //     "non_2xx_status_code" => 1
                // ],
                // "api_key" => "a7c3b4e5f6a7b8c9d0e1f2a3b4c5d6e7",
                "request_content_type" => "Json" // Bắt buộc, Kiểu nội dung yêu cầu (Json, multipart_form-data)
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }


    /**
     * https://docs.sepay.vn/oauth2/api-webhooks.html
     * Cập nhật webhook 
     * Scope:  webhook:delete
     */
    public function deleteWebhook($wh_id){
        $setting = $this->getSetting();
        $response = $this->client->request('DELETE',  $this->endpoint . '/api/v1/webhooks/' . $wh_id, [
            'headers' => [
                'Authorization' => $this->getAuthorizationHeader($setting),
            ]
        ]);
        $data = json_decode($response->getBody(), true);
        log_message('error', __CLASS__ . '@' .__FUNCTION__ . ' response: ' . json_encode($data, JSON_PRETTY_PRINT));
        return app_get_data($data, 'data');
    }
}