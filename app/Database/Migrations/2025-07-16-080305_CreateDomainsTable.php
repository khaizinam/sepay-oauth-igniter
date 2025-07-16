<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDomainsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('app_domains', true);
    }

    public function down()
    {
        $this->forge->dropTable('app_domains', true);
    }
}
