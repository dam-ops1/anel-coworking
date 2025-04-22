<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateCollaborationMembers extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'membership_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'collaboration_id' => [ // Colaboración
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'user_id' => [ // Miembro
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'role' => [ // Rol dentro de la colaboración (ej: 'member', 'lead')
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'member',
                'null'       => false,
            ],
            'joined_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('membership_id');
        // Asegura que un usuario solo pueda ser miembro de una colaboración una vez
        $this->forge->addUniqueKey(['collaboration_id', 'user_id']);
        // Las tablas 'collaborations' y 'users' deben existir ANTES
        $this->forge->addForeignKey('collaboration_id', 'collaborations', 'collaboration_id', 'CASCADE', 'NO ACTION'); // Si se elimina la colaboración, se eliminan los miembros
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'NO ACTION'); // Si se elimina el usuario, se elimina de las colaboraciones

        $this->forge->createTable('collaboration_members');
    }

    public function down()
    {
        $this->forge->dropTable('collaboration_members');
    }
}