<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePayment extends Migration
{
    public function up()
    {
        $this->forge->addColumn('app_oauths', [
            'rawdata' => [
                'type' => 'JSON',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('app_oauths', 'rawdata');
    }
}
