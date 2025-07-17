<?php

namespace App\Models;

use CodeIgniter\Model;

class AppDomainHookModel extends Model
{
    protected $table            = 'app_domain_hooks';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'domain_id',
        'data',
        'headers',
        'response_body',
        'status_code',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Optional: Validation rules
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
