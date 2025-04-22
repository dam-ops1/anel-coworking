<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateServiceRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'request_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [ // Usuario que solicita el servicio
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'service_id' => [ // Servicio de ANEL solicitado
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'request_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'status' => [ // Ej: 'submitted', 'in_progress', 'completed', 'cancelled'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => 'submitted',
                'null'       => false,
            ],
            'additional_info' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'resolution_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('request_id');
        // Las tablas 'users' y 'anel_services' deben existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');
        $this->forge->addForeignKey('service_id', 'anel_services', 'service_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('service_requests');
    }

    public function down()
    {
        $this->forge->dropTable('service_requests');
    }
}