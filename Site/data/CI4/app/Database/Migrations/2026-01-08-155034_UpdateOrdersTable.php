<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateOrdersTable extends Migration
{
    public function up()
    {
        // On supprime les colonnes qui ne sont plus dans le allowedFields
        //$this->forge->dropColumn('orders', ['address', 'created_at', 'updated_at']);
    }

    public function down()
    {
        // En cas de retour en arrière, on recrée les colonnes
        $fields = [
            'address'    => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ];
        $this->forge->addColumn('orders', $fields);
    } 
}