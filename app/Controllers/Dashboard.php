<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CourseUserModel;

class Dashboard extends BaseController
{

    public function index()
    {
        $role = session()->get('role');

        if ($role === 'admin') {
            return redirect()->to('/dashboard/admin');
        }

        return redirect()->to('/dashboard/member');
    }

    public function admin()
    {
        $model = new CourseModel();
        $data['title'] = 'Dashboard - BebasBelajar';

        $data['courses'] = $model->select('courses.*, categories.name as category_name, users.name as creator_name')
                         ->join('categories', 'categories.id = courses.category_id', 'left')
                         ->join('users', 'users.id = courses.created_by', 'left')
                         ->orderBy('is_approved', 'ASC')
                         ->orderBy('is_published', 'ASC')
                         ->findAll();

        return view('dashboard/index_admin', $data);
    }

    public function member()
    {
        $userId = session()->get('user_id');
        $courseUserModel = new CourseUserModel();
        $courseModel = new CourseModel();

        // Course yang di-enroll oleh member
        $enrolledCourses = $courseUserModel
            ->select('courses.*, categories.name AS category_name, users.name AS creator_name')
            ->join('courses', 'courses.id = course_users.course_id')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->join('users', 'users.id = courses.created_by', 'left')
            ->where('course_users.user_id', $userId)
            ->findAll();

        // Course yang dibuat oleh member sendiri (tidak peduli status publish/approve)
        $myOwnCourses = $courseModel
            ->select('courses.*, categories.name AS category_name, users.name AS creator_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->join('users', 'users.id = courses.created_by', 'left')
            ->where('courses.created_by', $userId)
            ->findAll();

        // Gabungkan & hilangkan duplikat (jika ada)
        $data['courses'] = array_unique(array_merge($enrolledCourses, $myOwnCourses), SORT_REGULAR);

        return view('dashboard/index_member', $data);
    }

}
