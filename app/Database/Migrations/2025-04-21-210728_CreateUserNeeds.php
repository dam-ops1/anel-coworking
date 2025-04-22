<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateUserNeeds extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'need_id' => [
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
            'need_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'submission_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'submitted',
                'null'       => false,
            ],
            'assigned_to' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true, // Opcional, si la necesidad no está asignada aún
            ],
        ]);

        $this->forge->addPrimaryKey('need_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('assigned_to', 'users', 'user_id', 'NO ACTION', 'NO ACTION'); // Puede ser NULL

        $this->forge->createTable('user_needs');
    }

    public function down()
    {
        $this->forge->dropTable('user_needs');
    }
}