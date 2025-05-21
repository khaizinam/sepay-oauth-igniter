<?php

namespace App\Controllers;

use App\Models\UserModel;

class Home extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();

        return view('template/header')
            .view('pages/main', ['users' => $users])
            .view('template/footer');
    }
}
