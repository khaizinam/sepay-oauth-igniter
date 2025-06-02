<?php

namespace App\Controllers;

use App\Models\OauthModel;
use App\Models\SePayTransaction;
use App\Models\SePayWebhook;
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
        try {
              $model = new SePayTransaction();
            $transactions = $model->paginate(20);
            return view('templates/header') .
                view('pages/index', [
                    'transactions' => $transactions,
                ]) .
                view('templates/footer');
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return $th->getMessage();
        }
    }

    public function test(){
        $db      = \Config\Database::connect();
        $query = 'SELECT ao.name, ao.description FROM app_oauths as ao WHERE ao.name LIKE '.$db->escape("%Tích hợp%") . ';';
        $result = $db->query($query);
        echo $query;
        echo '<br>';
        foreach ($result->getResult() as $row) {
            echo $row->name;
            echo '<br>';
        }
        return 123;
    }

    public function success()
    {
        return view('templates/header') .
            view('pages/oauth/call-back-success') .
            view('templates/footer');
    }

    public function settingPage(){
        $setting = $this->sePayService->getSetting();
        return view('templates/header') .
            view('pages/setting', ['setting' => $setting]) .
            view('templates/footer');
    }

    public function webhookPage(){
        try {
            $webhookModel = new SePayWebhook();
            $webhooks = $webhookModel->paginate(20); // 20 items per page
            $pager = $webhookModel->pager;
            return view('templates/header').
            view('pages/webhook/index',[
                'webhooks'=> $webhooks,
                'pager' => $pager
            ]).
            view('templates/footer');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function createWebhookPage(){
        try {
            $banks = $this->sePayService->getBanks();
            $se_pay_setting = $this->sePayService->getSetting();
            return view('templates/header') .
            view('pages/webhook/create', [
                'banks'=> $banks,
                'se_pay_setting' => $se_pay_setting
            ]) .
            view('templates/footer');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function editWebhookPage($id){
        try {
            $model = new SePayWebhook();
            $webhook = $model->where('id', $id)->first();
            $banks = $this->sePayService->getBanks();
            return view('templates/header') .
            view('pages/webhook/edit', [
                'webhook'=> $webhook,
                'banks' => $banks
            ]) .
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
            $banks = $this->sePayService->getBanks();
            $se_pay_setting = $this->sePayService->getSetting();
            $va_accounts = [];
            if(
                !blank(app_get_data($se_pay_setting, 'bank_account_id', null))
            ) {
                $va_accounts = $this->sePayService->getSubAccount(app_get_data($se_pay_setting, 'rawdata.bank_setting.bank_account_id'));
            }

            return view('templates/header')
                .view('pages/bank-account', [
                    'banks'=> $banks,
                    'va_accounts' => $va_accounts,
                    'se_pay_setting' => $se_pay_setting
                ]).view('templates/footer');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function transactionsPage(){
        $se_pay_setting = $this->sePayService->getSetting();

        return view('templates/header') .
            view('pages/transactions', ['se_pay_setting' => $se_pay_setting]) .
            view('templates/footer');
    }
}
