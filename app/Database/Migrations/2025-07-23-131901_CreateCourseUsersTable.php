<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCourseUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'course_id'  => ['type' => 'INT'],
            'user_id'    => ['type' => 'INT'],
            'is_enrolled'=> ['type' => 'TINYINT', 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('course_id', 'courses', 'id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('course_users');
    }

    public function down()
    {
        //
    }
}
