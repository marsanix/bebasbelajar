<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\CategoryModel;
use CodeIgniter\Controller;

class UserCourse extends BaseController
{
    public function create()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->findAll();

        return view('user/course/create', $data);
    }

    public function store()
    {
        $rules = [
            'title'        => 'required|min_length[3]',
            'description'  => 'required',
            'category_id'  => 'required|is_natural_no_zero',
            'thumbnail'    => 'uploaded[thumbnail]|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $model = new CourseModel();
        $file = $this->request->getFile('thumbnail');
        // $filename = $file->getRandomName();
        // $file->move(FCPATH . 'uploads/images/', $filename);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/images/', $filename);
        } else {
            $filename = null;
        }

        $model->save([
            'title'        => $this->request->getPost('title'),
            'description'  => $this->request->getPost('description'),
            'category_id'  => $this->request->getPost('category_id'),
            'thumbnail'    => $filename,
            'user_id'      => session('user_id'),
            'created_by'   => session()->get('user_id'),
            'is_approved'  => 0,
            'is_published' => 0,
        ]);

        return redirect()->to('/dashboard')->with('message', 'Course berhasil diajukan. Menunggu persetujuan admin.');
    }

    public function myCourses()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $model = new \App\Models\CourseModel();
        $courses = $model
            ->select('courses.*, categories.name as category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('created_by', session()->get('user_id'))
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/course/my_courses', ['courses' => $courses]);
    }

    public function edit($id)
    {
        $model = new CourseModel();
        $userId = session()->get('user_id');

        $course = $model->where('id', $id)
                        ->where('created_by', $userId)
                        ->first();

        if (!$course) {
            return redirect()->to('/my-course')->with('error', 'Course tidak ditemukan atau bukan milik Anda.');
        }

        $data['course'] = $course;
        $data['categories'] = (new CategoryModel())->findAll();
        $data['title'] = 'Edit Course Saya';

        return view('user/course/edit', $data);
    }

    public function update($id)
    {
        $model = new CourseModel();
        $userId = session()->get('user_id');

        $course = $model->where('id', $id)->where('created_by', $userId)->first();
        if (! $course) {
            return redirect()->to('/my-course')->with('error', 'Course tidak ditemukan.');
        }

        $rules = [
            'title'       => 'required|min_length[3]',
            'description' => 'required',
            'category_id' => 'required|integer',
            'thumbnail'   => 'permit_empty|is_image[thumbnail]|mime_in[thumbnail,image/jpg,image/jpeg,image/png]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
        }

        $file = $this->request->getFile('thumbnail');
        $filename = $course['thumbnail'];

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $filename = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/images/', $filename);
        }

        $model->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'category_id' => $this->request->getPost('category_id'),
            'thumbnail'   => $filename,
            'is_approved' => 0, // reset approval
            'is_published' => 0, // reset publish
        ]);

        return redirect()->to('/my-course')->with('message', 'Course berhasil diperbarui. Menunggu approval admin.');
    }


}
