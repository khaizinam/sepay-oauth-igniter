<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableConnection extends Migration
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
            'webhook_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'bank_account_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'event_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'authen_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'webhook_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'is_verify_payment' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => false,
            ],
            'skip_if_no_code' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => false,
            ],
            'active' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => false,
            ],
            'only_va' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => false,
            ],
            'request_content_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'addition_data' => [
                'type' => 'JSON',
                'null' => true,
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
        $this->forge->createTable('app_sepay_webhooks', true);
    }

    public function down()
    {
        $this->forge->dropTable('app_sepay_webhooks', true);
    }
}
