<?php
namespace App\Models;

use CodeIgniter\Model;

class OauthModel extends Model {
    protected $table = 'app_oauths';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'description',
        'code',
        'client_id',
        'redirect_uri',
        'state',
        'key'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    protected $useSoftDeletes = false;

    // protected $deletedField = 'deleted_at';
}