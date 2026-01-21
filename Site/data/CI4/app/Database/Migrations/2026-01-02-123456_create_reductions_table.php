<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReductionsTable extends Migration
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
            'pourcentage_reduction' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 0,
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
        
        // Ajout de l'index pour accélérer les calculs de prix promo
        $this->forge->addKey('produit_id');

        // Définition de la clé étrangère
        $this->forge->addForeignKey('produit_id', 'produits', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('reductions');
    }

    public function down()
    {
        $this->forge->dropTable('reductions');
    }
}