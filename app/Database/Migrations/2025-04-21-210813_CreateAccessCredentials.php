<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateAccessCredentials extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'credential_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'credential_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'access_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
                'null'       => false,
            ],
            'valid_from' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'valid_until' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
            ],
        ]);

        $this->forge->addPrimaryKey('credential_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('access_credentials');
    }

    public function down()
    {
        $this->forge->dropTable('access_credentials');
    }
}