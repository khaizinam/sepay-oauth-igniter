<?php

namespace App\Controllers;

use App\Models\OauthModel;

class Home extends BaseController
{
    public function index()
    {
        return view('templates/header') .
            view('pages/index') .
            view('templates/footer');
    }

    public function success()
    {
        return view('templates/header') .
            view('pages/oauth/call-back-success') .
            view('templates/footer');
    }

    public function settingPage(){
        try {
            $model = new OauthModel();
            $setting = $model->where('key', 'se-pay')->first();
            if (blank($setting)) {
                $settingData = [
                    'key'   => 'se-pay',
                    'name' => 'Tích hợp SePay',
                    'description' => 'Tích hợp SePay',
                    'client_id' => '',
                    'state' => 'RANDOM_STATE_VALUE',
                    'redirect_uri' => base_url('oauth/callback')
                ];
                $model->insert($settingData);
                $setting = $model->where('key', 'se-pay')->first();
            }
            return view('templates/header') .
                view('pages/setting', ['setting' => $setting]) .
                view('templates/footer');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function updateSetting($key)
    {
        try {
            $postData = $this->request->getPost();
            $model = new OauthModel();
            $setting = $model->where('key', $key)->first();
            if (blank($setting)) {
                return redirect()->back()->with('error', 'Setting not found');
            }
            $data = [
                'name' => $postData['name'],
                'description' => $postData['description'],
                'client_id' => $postData['client_id'],
                'redirect_uri' => $postData['redirect_uri'],
                'state' => $postData['state']
            ];
            $model->update($setting['id'], $data);
            return redirect()->back()->with('success', 'Setting updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
