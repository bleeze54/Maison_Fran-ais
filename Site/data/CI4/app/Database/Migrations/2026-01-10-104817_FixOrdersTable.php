<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixOrdersTable extends Migration
{
    public function up()
    {
        $fields = [
            'address' => [
                'type' => 'TEXT',
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
        ];

        $this->forge->addColumn('orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('orders', 'created_at');
        $this->forge->dropColumn('orders', 'updated_at');
        $this->forge->dropColumn('orders', 'address');
    }
}
