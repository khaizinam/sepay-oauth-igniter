<?php
namespace App\Models;

use CodeIgniter\Model;

class SePayTransaction extends Model {
    protected $table = 'app_sepay_transactions';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'transaction_id',
        'bank_account_id',
        'bank_brand_name',
        'account_number',
        'transaction_date',
        'amount_out',
        'amount_in',
        'accumulated',
        'transaction_content',
        'reference_number',
        'code',
        'sub_account',
        'additional_data'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = false;

    // protected $deletedField = 'deleted_at';
}