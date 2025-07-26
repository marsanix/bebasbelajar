<?php

namespace App\Controllers;
use App\Models\CourseModel;

class Home extends BaseController
{

    public function index()
    {
        $model = new CourseModel();

        $query = $this->request->getGet('q');

        $courses = $model
            ->select('courses.*, categories.name as category_name, users.name AS creator_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->join('users', 'users.id = courses.created_by', 'left')
            ->where('is_published', 1)
            ->where('is_approved', 1);

        if ($query) {
            $courses->like('courses.title', $query);
        }

        $data['courses'] = $courses->orderBy('courses.created_at', 'DESC')->findAll();
        $data['search'] = $query;

        return view('home/index', $data);
    }

}
