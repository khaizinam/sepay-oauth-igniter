<?php

namespace App\Controllers;

class Oauth extends BaseController
{
    public function callback(){
        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');
        var_dump($code, $state);
        // Here you would typically exchange the code for an access token
        return redirect()->to(base_url('oauth/callback-success'));
    }

    public function success()
    {
        return view('templates/header') .
            view('pages/oauth/call-back-success') .
            view('templates/footer');
    }
}
