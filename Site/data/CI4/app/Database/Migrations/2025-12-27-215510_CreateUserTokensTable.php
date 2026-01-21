<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTokensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INTEGER',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INTEGER',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'token_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('token_hash');
        
        // Ajout de l'index sur user_id pour la performance
        $this->forge->addKey('user_id');

        // Ajout de la clé étrangère
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('user_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('user_tokens');
    }
}