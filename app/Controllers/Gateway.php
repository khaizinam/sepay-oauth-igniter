<?php

namespace App\Controllers;

use App\Models\AppDomainHookModel;
use App\Models\DomainModel;
use App\Services\GatewayService;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Gateway extends BaseController
{
    /**
     * @var DomainModel $domainModel;
     */
    protected $domainModel;

    /**
     * @var AppDomainHookModel $hook_model;
     */
    protected AppDomainHookModel $hook_model;

    public function __construct()
    {
        $this->domainModel = new DomainModel();
        $this->hook_model = new AppDomainHookModel();
    }

    public function index()
    {
        $domainList = $this->domainModel->findAll();
        $keys = array_column($domainList, 'key');

        return $this->response->setJSON([
            'message' => 'Gateway service is running.',
            'keys'    => $keys
        ]);
    }

    /**
     * GET /gateway/:domainKey
     * Trả về thông tin đầy đủ của domain theo key
     */
    public function info($domainKey = null)
    {
        if (!$domainKey) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Thiếu domain key trong URL.']);
        }

        $gatewayService = new \App\Services\GatewayService();
        $domain = $gatewayService->getDomainByKey($domainKey);

        if (!$domain) {
            $msg = "Không tìm thấy domain key: $domainKey";
            log_message('error', '❌ ' . $msg);
            return $this->response->setStatusCode(404)
                                  ->setJSON(['error' => $msg]);
        }

        // Lấy thống kê webhook gần đây
        $hookModel = new \App\Models\AppDomainHookModel();
        $recentHooks = $hookModel
            ->where('domain_id', $domain['id'])
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();

        // Đếm tổng số webhooks
        $totalHooks = $hookModel->where('domain_id', $domain['id'])->countAllResults();

        // Đếm webhooks theo status code
        $statusStats = [
            'success' => $hookModel->where('domain_id', $domain['id'])->where('status_code >=', 200)->where('status_code <', 300)->countAllResults(),
            'client_error' => $hookModel->where('domain_id', $domain['id'])->where('status_code >=', 400)->where('status_code <', 500)->countAllResults(),
            'server_error' => $hookModel->where('domain_id', $domain['id'])->where('status_code >=', 500)->countAllResults(),
        ];

        log_message('info', "ℹ️ Domain info requested: $domainKey");

        return $this->response->setStatusCode(200)
                              ->setJSON([
                                  'domain' => [
                                      'id' => $domain['id'],
                                      'key' => $domain['key'],
                                      'url' => $domain['url'],
                                      'description' => $domain['description'] ?? null,
                                      'method' => $domain['method'] ?? 'POST',
                                      'status' => $domain['status'] ?? 'active',
                                      'created_at' => $domain['created_at'],
                                      'updated_at' => $domain['updated_at'],
                                      'webhook_url' => base_url('gateway/' . $domain['key'])
                                  ],
                                  'statistics' => [
                                      'total_hooks' => $totalHooks,
                                      'status_breakdown' => $statusStats,
                                      'last_5_hooks' => array_map(function($hook) {
                                          return [
                                              'id' => $hook['id'],
                                              'status_code' => $hook['status_code'],
                                              'created_at' => $hook['created_at'],
                                              'data_preview' => strlen($hook['data']) > 100 ? substr($hook['data'], 0, 100) . '...' : $hook['data']
                                          ];
                                      }, $recentHooks)
                                  ],
                                  'status' => [
                                      'is_active' => $gatewayService->isDomainActive($domain['id']),
                                      'service_available' => ($domain['status'] ?? 'active') === 'active'
                                  ]
                              ]);
    }

    /**
     * All HTTP Methods /gateway/:domainKey
     * Chuyển tiếp dữ liệu đến domain tương ứng theo key
     */
    public function tunnel($domainKey)
    {
        $request = service('request');
        $gatewayService = new \App\Services\GatewayService();
        $method = $request->getMethod();

        // Lấy domain thông qua service method mới
        $domain = $gatewayService->getDomainByKey($domainKey);
        
        if (!$domain) {
            log_message('error', "❌ Domain key not found: $domainKey");
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['error' => "Không tìm thấy domain key: $domainKey"]);
        }

        // Kiểm tra trạng thái domain
        if (!$gatewayService->isDomainActive($domain['id'])) {
            log_message('warning', "⚠️ Domain inactive: $domainKey");
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_SERVICE_UNAVAILABLE)
                ->setJSON(['error' => 'Tunnel hiện tại không hoạt động']);
        }

        $targetUrl = $domain['url'];

        // Xử lý GET request - redirect với query parameters
        if (strtoupper($method) === 'GET') {
            $queryParams = $request->getUri()->getQuery();
            $redirectUrl = $targetUrl;
            
            if ($queryParams) {
                $separator = strpos($targetUrl, '?') !== false ? '&' : '?';
                $redirectUrl .= $separator . $queryParams;
            }

            log_message('info', "🔄 GET redirect: $domainKey -> $redirectUrl");
            
            return redirect()->to($redirectUrl);
        }

        // Xử lý các method khác (POST, PUT, DELETE, PATCH)
        $rawInput = $request->getBody();
        $domainMethod = $domain['method'] ?? 'POST';

        // Lấy headers gốc (trừ Host)
        $forwardedHeaders = [];
        foreach ($request->headers() as $key => $value) {
            if (strtolower($key) !== 'host') {
                $forwardedHeaders[$key] = $value->getValue();
            }
        }
        $forwardedHeaders = $gatewayService->formatHeaders($forwardedHeaders);
        
        // Gửi request với method từ domain config
        $result = $gatewayService->sendRequest($targetUrl, $rawInput, $forwardedHeaders, $domainMethod);

        // Ghi log vào CSDL
        $gatewayService->saveHistory([
            'domain_id'     => $domain['id'],
            'data'          => $rawInput,
            'headers'       => $forwardedHeaders,
            'response_body' => $result['response'],
            'status_code'   => $result['http_code'] ?? 0,
        ]);

        log_message('info', "✅ Webhook processed: $domainKey -> $method -> $domainMethod $targetUrl");
        log_message('debug', "Request data: $rawInput");
        log_message('debug', "Response: " . $result['response']);

        if (!$result['success']) {
            log_message('error', '‼️ Gateway error: ' . $result['error']);
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                ->setJSON(['error' => 'Gateway Request Failed']);
        }

        return $this->response
            ->setStatusCode($result['http_code'])
            ->setHeader('Content-Type', 'application/json')
            ->setBody($result['response']);
    }
}
