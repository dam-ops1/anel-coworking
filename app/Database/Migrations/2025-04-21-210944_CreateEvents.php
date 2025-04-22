<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateEvents extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'event_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'start_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'end_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'space_id' => [ // Espacio donde se realiza el evento (opcional)
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'organizer_id' => [ // Usuario que organiza el evento
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'event_type' => [ // Ej: 'workshop', 'talk', 'networking'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'registration_required' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
             'status' => [ // Ej: 'scheduled', 'active', 'completed', 'cancelled'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'scheduled',
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('event_id');
        // Las tablas 'spaces' y 'users' deben existir ANTES
        $this->forge->addForeignKey('space_id', 'spaces', 'space_id', 'SET NULL', 'NO ACTION'); // Si el espacio se elimina, el evento puede quedar sin espacio asignado
        $this->forge->addForeignKey('organizer_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('events');
    }

    public function down()
    {
        $this->forge->dropTable('events');
    }
}