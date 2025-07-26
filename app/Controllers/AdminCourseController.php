<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class AdminCourseController extends BaseController
{
    public function index()
    {

        $courseModel = new \App\Models\CourseModel();

        $data['courses'] = $courseModel
            ->select('courses.*, categories.name AS category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->findAll();
        $data['title'] = 'Manajemen Course';
        

        return view('admin/course/index', $data);
    }

    public function create()
    {
        $categoryModel = new \App\Models\CategoryModel();
        $data['categories'] = $categoryModel->findAll();
        $data['title'] = 'Tambah Course';

        return view('admin/course/create', $data);
    }

    public function store()
    {

        $rules = [
            'title'        => 'required|min_length[3]',
            'description'  => 'required',
            'thumbnail'    => 'uploaded[thumbnail]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
            'category_id'  => 'required|is_natural_no_zero',
        ];

        $is_approved = 0;
        $is_published = 0;
        if (session('role') !== 'admin') {
            $rules['is_approved'] = 'in_list[0,1]';
            $rules['is_published'] = 'in_list[0,1]';
        } else {
            $is_approved = 1;
            $is_published = 1;
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $model = new CourseModel();
        $file = $this->request->getFile('thumbnail');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/images/', $filename);
        } else {
            $filename = null;
        }

        $data = [
            'user_id'     => session()->get('user_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'thumbnail'   => $filename,
            'category_id'  => $this->request->getPost('category_id'),
            'is_approved' => $is_approved,
            'is_published'=> $is_published,
            'created_by'  => session()->get('user_id'),
        ];

        $model->insert($data);

        $courseId = $model->getInsertID();
        return redirect()->to('/admin/course/materials/' . $courseId)->with('message', 'Course berhasil ditambahkan. Silakan upload materi.');
    }

    public function edit($id)
    {
        $categoryModel = new \App\Models\CategoryModel();
        $model = new CourseModel();

        $data['course'] = $model->select('courses.*, categories.name AS category_name')
                                ->join('categories', 'categories.id = courses.category_id', 'left')
                                ->find($id);
        $data['title'] = 'Edit Course';
        $data['categories'] = $categoryModel->findAll();

        return view('admin/course/edit', $data);
    }

    public function update($id)
    {

        $rules = [
            'title'        => 'required|min_length[3]',
            'description'  => 'required',
            'thumbnail'    => 'permit_empty|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
            'category_id'  => 'required|is_natural_no_zero',
            'is_approved'  => 'in_list[0,1]',
            'is_published' => 'in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $model = new CourseModel();
        $course = $model->find($id);
        $file = $this->request->getFile('thumbnail');

        $filename = $course['thumbnail'];
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/images/', $filename);
        }

        $model->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'thumbnail'   => $filename,
            'category_id'  => $this->request->getPost('category_id'),
            'is_approved'  => $this->request->getPost('is_approved'),
            'is_published' => $this->request->getPost('is_published'),
        ]);

        return redirect()->to('/admin/course')->with('message', 'Course berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new CourseModel();
        $model->delete($id);

        return redirect()->back()->with('message', 'Course berhasil dihapus.');
    }

    public function toggleStatus()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $courseId = $this->request->getPost('course_id');
        $field    = $this->request->getPost('field');
        $value    = $this->request->getPost('value');

        if (! in_array($field, ['is_approved', 'is_published'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid field']);
        }

        $model = new \App\Models\CourseModel();
        $model->update($courseId, [$field => (int)$value]);

        return $this->response->setJSON(['status' => 'success']);
    }

}
