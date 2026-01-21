<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSizeToCartItems extends Migration
{
    public function up()
    {
        // On ajoute la colonne 'size' à la table 'cart_items'
        $fields = [
            'size' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true, // On le met à true pour ne pas faire planter les anciens items
                'after'      => 'product_id', // Optionnel : place la colonne après product_id
            ],
        ];
        $this->forge->addColumn('cart_items', $fields);
    }

    public function down()
    {
        // Permet de revenir en arrière si besoin
        $this->forge->dropColumn('cart_items', 'size');
    }
}