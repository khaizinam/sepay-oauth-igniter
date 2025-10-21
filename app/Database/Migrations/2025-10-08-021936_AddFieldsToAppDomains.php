<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToAppDomains extends Migration
{
    public function up()
    {
        $this->forge->addColumn('app_domains', [
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mô tả ngắn về tunnel (tối đa 500 ký tự)'
            ],
            'method' => [
                'type' => 'ENUM',
                'constraint' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
                'default' => 'POST',
                'comment' => 'HTTP method cho tunnel'
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive', 'maintenance'],
                'default' => 'active',
                'comment' => 'Trạng thái hoạt động của tunnel'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('app_domains', ['description', 'method', 'status']);
    }
}
