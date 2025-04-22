<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateSharedFiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'file_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'file_path' => [ // Ruta del archivo en el servidor/almacenamiento
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'uploader_id' => [ // Usuario que subió el archivo
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'collaboration_id' => [ // Colaboración a la que está asociado el archivo (opcional)
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],
            'upload_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'file_type' => [ // Ej: 'pdf', 'docx', 'jpg'
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'file_size' => [ // Tamaño en bytes
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('file_id');
        // Las tablas 'users' y 'collaborations' deben existir ANTES
        $this->forge->addForeignKey('uploader_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION'); // Si el usuario se elimina, el archivo permanece pero sin uploader (o SET NULL)
        $this->forge->addForeignKey('collaboration_id', 'collaborations', 'collaboration_id', 'SET NULL', 'NO ACTION'); // Si la colaboración se elimina, el archivo puede quedar sin colaboración asignada

        $this->forge->createTable('shared_files');
    }

    public function down()
    {
        $this->forge->dropTable('shared_files');
    }
}