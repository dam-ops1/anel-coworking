<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;

class CreateSpaces extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'space_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'space_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'space_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'has_projector' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
            'has_whiteboard' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'floor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
            ],
        ]);

        $this->forge->addPrimaryKey('space_id');
        $this->forge->createTable('spaces');
    }

    public function down()
    {
        $this->forge->dropTable('spaces');
    }
}