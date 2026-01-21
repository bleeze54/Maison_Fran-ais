<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'auto_increment' => true,
                'null'           => false,
            ],
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'items_json' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'priority' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'standard',
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'en_attente',
            ],
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
            // Note: address, created_at et updated_at sont supprimés 
            // pour correspondre à ton nouveau Model.
        ]);

        $this->forge->addKey('id', true); 
        
        // Assure-toi que la table 'users' existe avant de lancer ça !
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE'); 
        
        $this->forge->createTable('orders');
    }

    public function down(): void
    {
        $this->forge->dropTable('orders');
    }
}