<?php

namespace App\Controllers;

use App\Models\AppDomainHookModel;
use App\Models\DomainModel;
use App\Services\GatewayService;

class Tunel extends BaseController
{
    protected DomainModel $tunelModel;

    protected GatewayService $gatewayService;

    public function __construct()
    {
        $this->tunelModel = new DomainModel();
        $this->gatewayService = new GatewayService();
    }

    public function index()
    {
        $perPage = 20;

        $domains = $this->tunelModel->paginate($perPage);
        $pager = \Config\Services::pager();

        $hookModel = new AppDomainHookModel();
        $latestHooks = $hookModel
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->find();

        return view('templates/header') .
            view('pages/tunel/index', [
                'domains' => $domains,
                'pager' => $pager,
                'latestHooks' => $latestHooks,
            ]) .
            view('templates/footer');
    }

    public function create()
    {
        return view('templates/header') .
               view('pages/tunel/create') .
               view('templates/footer');
    }

    public function store()
    {
        $data = [
            'key' => $this->request->getPost('key'),
            'url' => $this->request->getPost('url'),
            'description' => $this->request->getPost('description'),
            'method' => $this->request->getPost('method') ?: 'POST',
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        if ($this->tunelModel->where('key', $data['key'])->countAllResults() > 0) {
            return redirect()->back()->withInput()->with('error', 'Key đã tồn tại.');
        }

        if (!$this->tunelModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Không thể lưu tunnel mới.');
        }

        return redirect()->to('/tunel')->with('success', 'Đã thêm tunnel mới.');
    }

    public function edit($id)
    {
        $domain = $this->tunelModel->find($id);
        if (!$domain) {
            return redirect()->to('/tunel')->with('error', 'Tunnel không tồn tại.');
        }

        $hookModel = new \App\Models\AppDomainHookModel();

        $perPage = 20;

        $hooks = $hookModel
            ->where('domain_id', $id)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'hooks');

        $pager = $hookModel->pager;

        return view('templates/header')
            . view('pages/tunel/edit', [
                'domain'      => $domain,
                'hooks'       => $hooks,
                'pager'       => $pager,
            ])
            . view('templates/footer');
    }

    public function update($id)
    {
        $data = [
            'key' => $this->request->getPost('key'),
            'url' => $this->request->getPost('url'),
            'description' => $this->request->getPost('description'),
            'method' => $this->request->getPost('method'),
            'status' => $this->request->getPost('status'),
        ];

        if (!$this->tunelModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Không thể cập nhật tunnel.');
        }

        return redirect()->to('/tunel')->with('success', 'Cập nhật thành công.');
    }

    public function delete($id)
    {
        if (!$this->tunelModel->delete($id)) {
            return redirect()->to('/tunel')->with('error', 'Xoá không thành công.');
        }

        return redirect()->to('/tunel')->with('success', 'Đã xoá tunnel.');
    }

    public function hookReload($id)
    {
        $result = $this->gatewayService->sendWebhookAgain($id);
       
        if (!$result['success']) {
            return redirect()->back()->with('error', 'Gửi lại thất bại: ' . $result['error']);
        }

        return redirect()->back()->with('success', 'Đã gửi lại webhook. HTTP: ' . $result['http_code']);
    }

    public function hookDelete($id)
    {
        $model = new \App\Models\AppDomainHookModel();
        $model->delete($id);
        return redirect()->back()->with('success', 'Đã xoá bản ghi.');
    }
}
