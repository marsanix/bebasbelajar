<?php
namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'name', 'created_at', 'updated_at'
    ];

    protected $useTimestamps    = true; // agar otomatis set created_at & updated_at

    protected $validationRules  = [
        'name' => 'required|min_length[3]|max_length[100]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'Nama kategori wajib diisi.',
            'min_length'  => 'Nama kategori minimal 3 karakter.',
            'max_length'  => 'Nama kategori maksimal 100 karakter.'
        ]
    ];
}
