<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateCollaborations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'collaboration_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'creator_id' => [ // Creador de la colaboración
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'deadline' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [ // Ej: 'open', 'in_progress', 'completed', 'cancelled'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'open',
                'null'       => false,
            ],
            'visibility' => [ // Ej: 'public', 'private', 'members_only'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'private',
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('collaboration_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('creator_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('collaborations');
    }

    public function down()
    {
        $this->forge->dropTable('collaborations');
    }
}