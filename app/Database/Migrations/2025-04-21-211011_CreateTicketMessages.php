<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateTicketMessages extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'message_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [ // Ticket al que pertenece el mensaje
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'user_id' => [ // Usuario que envió el mensaje
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'content' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'timestamp' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'attachment' => [ // Ruta o nombre del archivo adjunto
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);

        $this->forge->addPrimaryKey('message_id');
        // Las tablas 'support_tickets' y 'users' deben existir ANTES
        $this->forge->addForeignKey('ticket_id', 'support_tickets', 'ticket_id', 'CASCADE', 'NO ACTION'); // Si se elimina un ticket, sus mensajes se eliminan (CASCADE)
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION'); // Si el usuario se elimina, el mensaje permanece (o puedes usar SET NULL si user_id es NULLable)

        $this->forge->createTable('ticket_messages');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_messages');
    }
}