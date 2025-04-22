<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;

class CreateUserDirectory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'directory_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'unique'         => true, // Un usuario solo tiene una entrada en el directorio
                'null'           => false,
            ],
            'expertise' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'interests' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'collaboration_areas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'visible_in_directory' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
            ],
            'contact_preferences' => [
                'type' => 'JSON',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('directory_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('user_directory');
    }

    public function down()
    {
        $this->forge->dropTable('user_directory');
    }
}