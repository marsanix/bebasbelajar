<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CourseModel;
use App\Models\CourseMaterialModel;
use App\Models\CourseUserModel;

class Course extends BaseController
{

    public function detail($id)
    {
        $courseModel = new CourseModel();
        $materialModel = new CourseMaterialModel();

        $course = $courseModel
            ->select('courses.*, categories.name AS category_name')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->find($id);

        if (!$course) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Course tidak ditemukan');
        }

        $data['title'] = $course['title'];
        $data['course'] = $course;
        $data['materials'] = [];

        $isLoggedIn = session()->get('isLoggedIn') ?? false;
        $data['isLoggedIn'] = $isLoggedIn;
        $data['isEnrolled'] = false;

        if ($isLoggedIn) {
            $userId = session()->get('user_id');
            
            // Cek apakah user adalah pemilik course
            $isOwner = $isLoggedIn && $course['created_by'] == $userId;
            $data['isOwner'] = $isOwner;
            
            // Cek apakah user sudah terdaftar di course
            $enrolled = db_connect()
                ->table('course_users')
                ->where('course_id', $id)
                ->where('user_id', $userId)
                ->get()
                ->getRow();

            if ($enrolled) {
                $data['isEnrolled'] = true;
                $data['materials'] = $materialModel
                    ->where('course_id', $id)
                    ->where('deleted_at', null)
                    ->findAll();
            }
        }

        return view('course/detail', $data);
    }


    public function detailx($id)
    {
        $courseModel = new CourseModel();
        $materialModel = new CourseMaterialModel();


        $data['course'] = $courseModel->select('courses.*, categories.name AS category_name')
                                ->join('categories', 'categories.id = courses.category_id', 'left')
                                ->find($id);

        //$data['course'] = $courseModel->find($id);
        $data['materials'] = $materialModel->where('course_id', $id)->where('deleted_at', null)->findAll();
        $data['title'] = $data['course']['title'];

        $isLoggedIn = session()->get('isLoggedIn') ?? false;
        $data['isLoggedIn'] = $isLoggedIn;

        return view('course/detail', $data);
    }

    public function access($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        if (!$course || !$course['is_published']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Course tidak ditemukan atau belum dipublish.');
        }

        $userId = session()->get('user_id');
        $courseUserModel = new CourseUserModel();

        $isEnrolled = $courseUserModel
            ->where('user_id', $userId)
            ->where('course_id', $id)
            ->countAllResults();

        if (! $isEnrolled) {
            return redirect()->to('/course/enroll/' . $id)
                ->with('message', 'Silakan daftar terlebih dahulu untuk mengakses course ini.');
        }

        // Sudah enroll
        return redirect()->to('/course/' . $id);
    }

    public function enroll($id)
    {
        $courseModel = new CourseModel();
        $course = $courseModel->find($id);

        if (!$course) {
            return redirect()->to('/')->with('error', 'Course tidak ditemukan.');
        }

        if (in_array($this->request->getMethod(), ['post', 'POST'])) {
            $userId = session()->get('user_id');
            $courseUserModel = new \App\Models\CourseUserModel();

            // Cek jika sudah enroll
            $exists = $courseUserModel
                ->where('user_id', $userId)
                ->where('course_id', $id)
                ->first();

            if (! $exists) {
                $courseUserModel->insert([
                    'user_id' => $userId,
                    'course_id' => $id,
                ]);
            }

            return redirect()->to('/course/' . $id)
                            ->with('message', 'Berhasil bergabung ke course.');
        }

        return view('course/enroll', ['course' => $course]);
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

    public function togglePublish()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $courseId = $this->request->getPost('course_id');
        $isPublished    = $this->request->getPost('is_published');
        // $value  = $this->request->getPost('value');

        if (!isset($isPublished)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid field']);
        }

        $model = new \App\Models\CourseModel();
        $model->update($courseId, ['is_published' => (int)$isPublished]);

        return $this->response->setJSON(['status' => 'success', 'message' => 'Publish status already changes']);
    }


}
