<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateEventRegistrations extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'registration_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [ // Evento
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'user_id' => [ // Usuario registrado
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'registration_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'attendance_status' => [ // Ej: 'registered', 'attended', 'cancelled'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'registered',
                'null'       => false,
            ],
            'special_requirements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('registration_id');
         // Asegura que un usuario solo pueda registrarse a un evento una vez
        $this->forge->addUniqueKey(['event_id', 'user_id']);
        // Las tablas 'events' y 'users' deben existir ANTES
        $this->forge->addForeignKey('event_id', 'events', 'event_id', 'CASCADE', 'NO ACTION'); // Si se elimina el evento, se eliminan los registros
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'NO ACTION'); // Si se elimina el usuario, se eliminan sus registros a eventos

        $this->forge->createTable('event_registrations');
    }

    public function down()
    {
        $this->forge->dropTable('event_registrations');
    }
}