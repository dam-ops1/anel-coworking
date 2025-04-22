<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Forge;
use CodeIgniter\Database\RawSql;

class CreatePostComments extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'comment_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'post_id' => [ // Post al que pertenece el comentario
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],
            'user_id' => [ // Usuario que hizo el comentario
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
        ]);

        $this->forge->addPrimaryKey('comment_id');
        // Las tablas 'community_posts' y 'users' deben existir ANTES
        $this->forge->addForeignKey('post_id', 'community_posts', 'post_id', 'CASCADE', 'NO ACTION'); // Si se elimina un post, sus comentarios se eliminan (CASCADE)
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'NO ACTION', 'NO ACTION'); // Si el usuario se elimina, el comentario permanece (o SET NULL)

        $this->forge->createTable('post_comments');
    }

    public function down()
    {
        $this->forge->dropTable('post_comments');
    }
}