<?php

namespace App\Controllers;

use App\Models\SePayTransaction;
use App\Models\SePayWebhook;
use App\Services\SePayService;
use Exception;

class SePay extends BaseController
{
    protected SePayService $sePayService;

    protected $endpoint = 'https://my.sepay.vn/';

    public function __construct(){
        $this->sePayService = new SePayService();
    }

    public function getBankDetails($bank_id)
    {
        try {
            if($this->request->isAJAX() == false || $this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
                throw new Exception('This method only supports AJAX requests');
            }
            $data = $this->sePayService->getBankDetail($bank_id);
            if(blank($data['data'] ?? null)) {
                throw new Exception('No bank details found for the provided bank ID');
            }
            $html = view('partials/bank-account', [
                'bank' => $data['data'],
            ]);
            return $this->response->setJSON([
                'error' => false,
                'message' => 'Bank details retrieved successfully',
                'data' => $data['data'] ?? null,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $th->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    /**
     * Get sub-account details for a specific bank account.
     *
     * @param int $bank_id The ID of the bank account.
     * @return \CodeIgniter\HTTP\Response
     */
    public function getSubAccount($bank_id)
    {
        try {
            if($this->request->isAJAX() == false || $this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
                throw new Exception('This method only supports AJAX requests');
            }
            $data = $this->sePayService->getSubAccount($bank_id);
            if(blank($data ?? null)) {
                throw new Exception('No bank details found for the provided bank ID');
            }
            $html = view('partials/bank-account', [
                'va_accounts' => $data
            ]);
            return $this->response->setJSON([
                'error' => false,
                'message' => 'Sub-account details retrieved successfully',
                'data' => $html,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $th->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function updateBankAcount()
    {
        try {
            $data = $this->request->getPost();
            if(empty($data['bank_account_id']) || empty($data['va_account_id'])) {
                throw new Exception('Bank account ID and VA account ID are required');
            }
            $result = $this->sePayService->updateBankAccount($data);
            return redirect()->back()->with('success', 'Bank account updated successfully');
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return redirect()->back()->with('success', 'Failed to update bank account.');
        }
    }

    public function getTransactions()
    {
        try {
            // Set the script execution time limit to unlimited
            set_time_limit(0);
            if($this->request->isAJAX() == false || $this->request->getHeaderLine('X-Requested-With') !== 'XMLHttpRequest') {
                throw new Exception('This method only supports AJAX requests');
            }
            $payload = $this->request->getPost();
            $data = $this->sePayService->getTransactions($payload);
            
            return $this->response->setJSON([
                'error' => false,
                'message' => 'Transaction retrieved successfully',
                'data' => $data,
                'csrf_hash' => csrf_hash()
            ]);
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $th->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function leadgen(){
        try {
            $leadgen = $this->request->getJSON(true);
            
            log_message('error', json_encode($leadgen, JSON_PRETTY_PRINT));
            
            $model = new SePayTransaction();

            $data = [
                'transaction_id' => app_get_data($leadgen, 'id'),
                'account_number' => app_get_data($leadgen, 'accountNumber'),
                'bank_brand_name' => app_get_data($leadgen, 'gateway'),
                'transaction_date' => app_get_data($leadgen, 'transactionDate'),
                'amount_out' => 0,
                'amount_in' => 0,
                'accumulated' => app_get_data($leadgen, 'accumulated'),
                'transaction_content' => app_get_data($leadgen, 'content'),
                'reference_number' => app_get_data($leadgen, 'referenceCode'),
                'code' => app_get_data($leadgen, 'code'),
                'sub_account' => app_get_data($leadgen, 'subAccount'),
            ];

            if(app_get_data($leadgen, 'transferType') == 'in'){
                $data['amount_in'] = app_get_data($leadgen, 'transferAmount');
            } else {
                $data['amount_out'] = app_get_data($leadgen, 'transferAmount');
            }

            $model->insert($data);
            return $this->response->setJSON([
                'error' => false,
                'message' => 'success',
            ]);
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return $this->response->setStatusCode(500)->setJSON([
                'error' => true,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function createNewWebhook(){
        try {
            $payload = $this->request->getPost();
            log_message('error', json_encode($payload, JSON_PRETTY_PRINT));
            $prepareData = [
                "bank_account_id" => app_get_data($payload, 'bank_account_id'),
                "name" => app_get_data($payload, 'name'),
                "event_type" => app_get_data($payload, 'event_type'),
                "authen_type" => 'No_Authen',
                "webhook_url" => app_get_data($payload, 'webhook_url'),
                "is_verify_payment" => app_get_data($payload, 'is_verify_payment', 0),
                "skip_if_no_code" => app_get_data($payload, 'skip_if_no_code', 1),
                "active" => app_get_data($payload, 'active', 1),
                "only_va" => 0,
                "request_content_type" => "Json",
            ];
            $res = $this->sePayService->createNewWebhook($prepareData);
            $model = new SePayWebhook();
            $model->insert([
                ...$prepareData,
                'webhook_id' => app_get_data($res, 'id')
            ]);
            return redirect()->route('webhooks')->with('success', 'Create webhooks success.');
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return redirect()->route('webhooks')->with('error', 'Create webhooks fail.');
        }
    }

    public function updateWebhook($id){
         try {
            $model = new SePayWebhook();
            $webhook = $model->where('id', $id)->first();
            $payload = $this->request->getPost();
            log_message('error', json_encode($payload, JSON_PRETTY_PRINT));
            $prepareData = [
                "bank_account_id" => app_get_data($payload, 'bank_account_id'),
                "name" => app_get_data($payload, 'name'),
                "event_type" => app_get_data($payload, 'event_type'),
                "authen_type" => 'No_Authen',
                "webhook_url" => app_get_data($payload, 'webhook_url'),
                "is_verify_payment" => app_get_data($payload, 'is_verify_payment', 0),
                "skip_if_no_code" => app_get_data($payload, 'skip_if_no_code', 1),
                "active" => app_get_data($payload, 'active', 1),
                "only_va" => 0,
                "request_content_type" => "Json",
            ];
            $this->sePayService->updateWebhook(app_get_data($webhook, 'webhook_id'), $prepareData);
            $model->update($id, $prepareData);
            return redirect()->route('webhooks')->with('success', 'Update webhooks success.');
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return redirect()->route('webhooks')->with('error', 'Update webhooks fail.');
        }
    }

    public function deleteWebhook($id) {
        try {
            $model = new SePayWebhook();
            $webhook = $model->where('id', $id)->first();
            $this->sePayService->deleteWebhook(app_get_data($webhook, 'webhook_id'));
            $model->delete($id);
            return redirect()->route('webhooks')->with('success', 'Delete webhooks fail.');
        } catch (\Throwable $th) {
            app_log_error(__CLASS__, __FUNCTION__, $th);
            return redirect()->route('webhooks')->with('error', 'Delete webhooks fail.');
        }
    }
}
