<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        try {
            return view('welcome_message');

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
