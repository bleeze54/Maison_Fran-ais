<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVedettesTable extends Migration
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
            'produit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'date_debut' => [
                'type' => 'DATETIME',
            ],
            'date_fin' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        // ajout de la clé étrangère
        $this->forge->addForeignKey('produit_id', 'produits', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('vedettes');
    }

    public function down()
    {
        $this->forge->dropTable('vedettes');
    }
}