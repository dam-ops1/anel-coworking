<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',  // Formato internacional: +34 XXX XXX XXX
                'null'       => true,
            ],
            'profile_image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
            'email_verified' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
            'activation_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => true,
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
                'null'       => true,
            ],
            'reset_token_expires' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Clave primaria
        $this->forge->addPrimaryKey('user_id');
        
        // Claves foráneas
        $this->forge->addForeignKey('role_id', 'roles', 'role_id', 'CASCADE', 'CASCADE');
        
        // Índices
        $this->forge->addKey('email', false, true);
        $this->forge->addKey('username', false, true);
        $this->forge->addKey('role_id', false);
        $this->forge->addKey(['deleted_at', 'is_active'], false);

        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}