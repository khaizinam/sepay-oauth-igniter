<?php

namespace App\Controllers;

use App\Models\DomainModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Gateway extends BaseController
{
    protected $domainModel;

    public function __construct()
    {
        $this->domainModel = new DomainModel();
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

        $domain = $this->domainModel->where('key', $domainKey)->first();

        if (!$domain) {
            return $this->response
                        ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                        ->setJSON(['error' => "Không tìm thấy domain key: $domainKey"]);
        }

        $targetUrl = $domain['url'];

        try {
            $rawInput = $request->getBody();
            $headers = [];

            foreach ($request->headers() as $key => $value) {
                if (strtolower($key) !== 'host') {
                    $headers[] = $key . ': ' . $value->getValue();
                }
            }

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL            => $targetUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => "POST",
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_POSTFIELDS     => $rawInput,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_TIMEOUT        => 30,
            ]);

            $responseBody = curl_exec($curl);
            $httpCode     = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                throw new Exception(curl_error($curl));
            }

            curl_close($curl);

            return $this->response
                        ->setStatusCode($httpCode)
                        ->setHeader('Content-Type', 'application/json')
                        ->setBody($responseBody);

        } catch (Exception $e) {
            log_message('error', '‼️ Gateway error: ' . $e->getMessage());
            return $this->response
                        ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)
                        ->setJSON(['error' => 'Internal Gateway Error']);
        }
    }
}
