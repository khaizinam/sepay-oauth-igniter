<?php

namespace App\Controllers;

use App\Models\DomainModel;

class Tunel extends BaseController
{
    protected $tunelModel;

    public function __construct()
    {
        $this->tunelModel = new DomainModel();
    }

    public function index()
    {
        $data['domains'] = $this->tunelModel->findAll();

        return view('templates/header') .
               view('pages/tunel/index', $data) .
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

        return view('templates/header') .
            view('pages/tunel/edit', ['domain' => $domain]) .
            view('templates/footer');
    }

    public function update($id)
    {
        $data = [
            'key' => $this->request->getPost('key'),
            'url' => $this->request->getPost('url'),
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
}
