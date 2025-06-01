<?php

namespace App\Controllers;

use App\Models\OauthModel;
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
            log_message('error',__CLASS__ . '@' . __FUNCTION__, ['error' => $th->getMessage(), 'trace' => $th->getTraceAsString()]);
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
}
