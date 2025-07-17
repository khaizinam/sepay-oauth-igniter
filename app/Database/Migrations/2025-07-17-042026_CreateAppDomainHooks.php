<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppDomainHooks extends Migration
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
            'domain_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'data' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'response_body' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status_code' => [
                'type'       => 'SMALLINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
            ],
            'headers' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('domain_id', 'app_domains', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('app_domain_hooks', true);
    }

    public function down()
    {
        $this->forge->dropTable('app_domain_hooks', true);
    }
}
