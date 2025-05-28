<?php

namespace App\Controllers;

use App\Models\OauthModel;
use Exception;

class Oauth extends BaseController
{
    public function callback(){
        try {
            $code = $this->request->getGet('code');
            $state = $this->request->getGet('state');
            $model = new OauthModel();
            $setting = $model->where('key', 'se-pay')->first();
            if (blank($setting)) {
                throw new Exception('Setting not found');
            }
            $model->update($setting['id'], [
                'code' => $code,
                'state' => $state
            ]);
            return redirect()->to(base_url('oauth/callback-success'));
        } catch (\Throwable $th) {
            return redirect()->to(base_url('oauth/callback-success'));
        }
    }

    public function success()
    {
        return view('templates/header') .
            view('pages/oauth/call-back-success') .
            view('templates/footer');
    }
}
