<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateSupportTickets extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'ticket_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [ // Creador del ticket
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'assigned_to' => [ // Usuario asignado (puede ser un administrador)
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'subject' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'open',
                'null'       => false,
            ],
            'priority' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'medium',
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'last_updated' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'on update' => new RawSql('CURRENT_TIMESTAMP'), // Define la actualización automática
            ],
        ]);

        $this->forge->addPrimaryKey('ticket_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('assigned_to', 'users', 'user_id', 'SET NULL', 'NO ACTION'); // Si el asignado se elimina, el ticket queda sin asignar

        $this->forge->createTable('support_tickets');
    }

    public function down()
    {
        $this->forge->dropTable('support_tickets');
    }
}