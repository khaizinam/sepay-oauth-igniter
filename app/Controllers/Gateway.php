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
     * Trả về thông tin domain theo key
     */
    public function info($domainKey = null)
    {
        if (!$domainKey) {
            return $this->response->setStatusCode(400)
                                  ->setJSON(['error' => 'Thiếu domain key trong URL.']);
        }

        $domain = $this->domainModel->where('key', $domainKey)->first();

        if (!$domain) {
            $msg = "Không tìm thấy domain key: $domainKey";
            log_message('error', '‼️ ' . $msg);
            return $this->response->setStatusCode(404)
                                  ->setJSON(['error' => $msg]);
        }

        log_message('info', "ℹ️ Domain info requested: $domainKey");

        return $this->response->setStatusCode(200)
                              ->setJSON([
                                  'id'  => $domain['id'],
                                  'key' => $domain['key'],
                                  'url' => $domain['url']
                              ]);
    }

    /**
     * POST /gateway/:domainKey
     * Chuyển tiếp dữ liệu đến domain tương ứng theo key
     */
    public function tunel($domainKey)
    {
        $request = service('request');
        $gatewayService = new \App\Services\GatewayService();

        $domain = $this->domainModel->where('key', $domainKey)->first();
        log_message('error', "domain: " . json_encode($domain, true));

        if (!$domain) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['error' => "Không tìm thấy domain key: $domainKey"]);
        }

        $targetUrl = $domain['url'];
        $rawInput = $request->getBody();

        // Lấy headers gốc (trừ Host)
        $forwardedHeaders = [];
        foreach ($request->headers() as $key => $value) {
            if (strtolower($key) !== 'host') {
                $forwardedHeaders[$key] = $value->getValue();
            }
        }
        $forwardedHeaders = $gatewayService->formatHeaders($forwardedHeaders);
        // Gửi request thông qua service
        $result = $gatewayService->sendPostRequest($targetUrl, $rawInput, $forwardedHeaders);

        // Ghi log vào CSDL
        $gatewayService->saveHistory([
            'domain_id'     => $domain['id'],
            'data'          => $rawInput,
            'headers'       => $forwardedHeaders,
            'response_body' => $result['response'],
            'status_code'   => $result['http_code'] ?? 0,
        ]);

        log_message('error', "rawInput: $rawInput");
        log_message('error', "responseBody: " . $result['response']);

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
