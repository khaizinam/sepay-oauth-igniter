<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_admin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'password'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_admin');
    }
}
