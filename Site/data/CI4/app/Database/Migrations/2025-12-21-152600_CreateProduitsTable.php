<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'prix' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'images' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'quantite' => [
                'type' => 'INTEGER',
                'default' => 0
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('produits', true);
    }

    public function down()
    {
        $this->forge->dropTable('produits', true);
    }
}
