<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreateUserDocuments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'document_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'document_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'upload_date' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'document_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'shared_with_anel' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
            ],
        ]);

        $this->forge->addPrimaryKey('document_id');
        // La tabla 'users' debe existir ANTES
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable('user_documents');
    }

    public function down()
    {
        $this->forge->dropTable('user_documents');
    }
}