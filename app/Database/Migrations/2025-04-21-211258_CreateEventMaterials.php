<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateEventMaterials extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'material_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [ // Evento al que pertenece el material
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'file_path' => [ // Ruta del archivo material
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'upload_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('material_id');
        // La tabla 'events' debe existir ANTES
        $this->forge->addForeignKey('event_id', 'events', 'event_id', 'CASCADE', 'NO ACTION'); // Si se elimina un evento, se eliminan sus materiales

        $this->forge->createTable('event_materials');
    }

    public function down()
    {
        $this->forge->dropTable('event_materials');
    }
}