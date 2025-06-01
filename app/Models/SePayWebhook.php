<?php
namespace App\Models;

use CodeIgniter\Model;

class SePayWebhook extends Model {
    protected $table = 'app_sepay_webhooks';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'bank_account_id',
        'name',
        'event_type',
        'authen_type',
        'webhook_url',
        'is_verify_payment',
        'skip_if_no_code',
        'active',
        'only_va',
        'request_content_type',
        'addition_data'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = false;

    // protected $deletedField = 'deleted_at';
}