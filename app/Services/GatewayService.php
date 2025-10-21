<?php

namespace App\Services;

use App\Models\AppDomainHookModel;
use App\Models\DomainModel;

class GatewayService
{
    protected AppDomainHookModel $hook_model;
    protected DomainModel $domain_model;

    public function __construct()
    {
        $this->hook_model = new AppDomainHookModel();
        $this->domain_model = new DomainModel();
    }

    /**
     * Gửi lại webhook theo ID bản ghi
     */
    public function sendWebhookAgain(int $hookId): array
    {
        $hook = $this->hook_model->find($hookId);
        if (!$hook) {
            return ['success' => false, 'error' => 'Bản ghi không tồn tại.'];
        }

        $domain = $this->domain_model->find($hook['domain_id']);
        if (!$domain) {
            return ['success' => false, 'error' => 'Domain không tồn tại.'];
        }

        $headers = $this->formatHeaders(json_decode($hook['headers'], true));
        $method = $domain['method'] ?? 'POST';
        
        $result = $this->sendRequest($domain['url'], $hook['data'], $headers, $method);

        $this->saveHistory([
            'domain_id'     => $domain['id'],
            'data'          => $hook['data'],
            'headers'       => $headers,
            'response_body' => $result['response'],
            'status_code'   => $result['http_code'] ?? 0,
        ]);

        return $result;
    }

    /**
     * Gửi request đến URL với method, headers và body
     */
    public function sendRequest(string $url, string $body, array $headers = [], string $method = 'POST'): array
    {
        $ch = curl_init();
        
        // Base cURL options
        $curlOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_ENCODING       => '',
            CURLOPT_USERAGENT      => 'GatewayService/1.0',
        ];

        // Chỉ thêm POSTFIELDS cho POST, PUT, PATCH
        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($ch, $curlOptions);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return [
                'success' => false,
                'error' => $error,
                'http_code' => null,
                'response' => null,
            ];
        }
        
        curl_close($ch);
        return [
            'success'   => true,
            'http_code' => $httpCode,
            'response'  => $response,
            'error'     => null,
        ];
    }

    /**
     * Gửi POST đến URL với headers và body (backward compatibility)
     */
    public function sendPostRequest(string $url, string $body, array $headers = []): array
    {
        return $this->sendRequest($url, $body, $headers, 'POST');
    }

    /**
     * Format lại headers từ mảng key => value thành array header string
     */
    public function formatHeaders(array $headers): array
    {
        $result = [];
        foreach ($headers as $key => $value) {
            if ($key && $value) {
                $result[] = "$key: $value";
            }
        }
        return $result;
    }

    /**
     * Lưu lịch sử webhook vào database
     */
    public function saveHistory(array $data): ?int
    {
        return $this->hook_model->insert([
            'domain_id'     => $data['domain_id'],
            'data'          => $data['data'],
            'headers'       => json_encode($data['headers'], JSON_UNESCAPED_UNICODE),
            'response_body' => $data['response_body'],
            'status_code'   => $data['status_code'] ?? 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => null,
        ]);
    }

    /**
     * Kiểm tra trạng thái domain có hoạt động không
     */
    public function isDomainActive(int $domainId): bool
    {
        $domain = $this->domain_model->find($domainId);
        return $domain && ($domain['status'] ?? 'active') === 'active';
    }

    /**
     * Lấy thông tin domain theo key
     */
    public function getDomainByKey(string $key): ?array
    {
        return $this->domain_model->where('key', $key)->first();
    }
}
