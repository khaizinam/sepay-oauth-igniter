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
}
