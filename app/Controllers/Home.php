<?php

namespace App\Controllers;

use App\Models\OauthModel;
use App\Services\SePayService;

class Home extends BaseController
{
    protected SePayService $sePayService;

    public function __construct()
    {
        $this->sePayService = new SePayService();
    }

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
            $setting = $this->sePayService->getSetting();
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
                'state' => $postData['state'],
                'client_secret' => $postData['client_secret'],
            ];
            $model->update($setting['id'], $data);
            return redirect()->back()->with('success', 'Setting updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function bankAccountPage()
    {
        try {
            $sePayService = new SePayService();
            $banks = $sePayService->getBanks();
            $setting = $sePayService->getSetting();
            return view('templates/header') .
                view('pages/bank-account', ['banks'=> $banks, 'setting' => $setting]) .
                view('templates/footer');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
