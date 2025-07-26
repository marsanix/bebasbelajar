<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseMaterialModel;

class Admin extends BaseController
{
    public function pendingMaterials()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        $model = new CourseMaterialModel();
        $data['title'] = 'Materi Menunggu Persetujuan';
        $data['materials'] = $model
            ->where('deleted_at', null)
            ->join('courses', 'courses.id = course_materials.course_id')
            ->select('course_materials.*, courses.title as course_title')
            ->where('courses.is_approved', 0)
            ->findAll();

        return view('admin/pending', $data);
    }

    public function approve($id)
    {
        $courseModel = new \App\Models\CourseModel();
        $courseModel->update($id, ['is_approved' => 1]);

        return redirect()->back()->with('message', 'Course berhasil disetujui.');
    }
}
