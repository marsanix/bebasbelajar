<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'auto_increment' => true],
            'user_id'          => ['type' => 'INT'],
            'course_material_id' => ['type' => 'INT'],
            'comment'          => ['type' => 'TEXT'],
            'parent_id'        => ['type' => 'INT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->addForeignKey('course_material_id', 'course_materials', 'id');
        $this->forge->createTable('comments');
    }

    public function down()
    {
        //
    }
}
