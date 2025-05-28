<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTableSetting extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
            'description' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
            'client_id' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
            'redirect_uri' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
             'key' => [
                'type' => 'VARCHAR',
                'constraint' => 196,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('app_oauths');
    }

    public function down()
    {
        $this->forge->dropTable('app_oauths');
    }
}
