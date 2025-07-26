<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddYoutubeLinkToCourseMaterials extends Migration
{
    public function up()
    {
        $fields = [
            'youtube_link' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'content' // Letakkan setelah kolom content
            ]
        ];

        $this->forge->addColumn('course_materials', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('course_materials', 'youtube_link');
    }
}
