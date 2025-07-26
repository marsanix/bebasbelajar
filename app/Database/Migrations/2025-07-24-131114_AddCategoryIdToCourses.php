<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryIdToCourses extends Migration
{
    public function up()
    {
        $this->forge->addColumn('courses', [
            'category_id' => [
                'type'       => 'INT',
                'null'       => true,
                'after'      => 'id', // Letakkan setelah ID atau sesuaikan
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'category_id');
    }
}
