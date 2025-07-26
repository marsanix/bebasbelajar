<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseMaterialsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'course_id'     => ['type' => 'INT'],
            'title'         => ['type' => 'TEXT'],
            'type'          => ['type' => 'ENUM', 'constraint' => ['article', 'video', 'audio', 'image', 'ebook']],
            'content'       => ['type' => 'LONGTEXT', 'null' => true],
            'file_path'     => ['type' => 'TEXT', 'null' => true],
            'created_by'    => ['type' => 'INT'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->createTable('course_materials');
    }

    public function down()
    {
        //
    }
}
