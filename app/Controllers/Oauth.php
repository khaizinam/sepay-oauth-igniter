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

            $client = \Config\Services::curlrequest();
            $response = $client->request('post', 'https://my.sepay.vn/oauth/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type'    => 'authorization_code',
                    'code'          => $code,
                    'redirect_uri'  => $setting['redirect_uri'],
                    'client_id'     => $setting['client_id'],
                    'client_secret' => $setting['client_secret'],
                ]
            ]);
            $body = $response->getBody();
            $data = json_decode($body, true);
            log_message('error', json_encode($data));
            $model->update($setting['id'], [
                'code' => $code,
                'state' => $state,
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'expires_in' => app_create_expires_in($data['expires_in']),
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
