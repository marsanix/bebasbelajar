<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CourseModel;
use App\Models\CourseMaterialModel;

class Material extends BaseController
{
    public function upload($courseId)
    {
        helper(['form']);

        if (in_array($this->request->getMethod(), ['post', 'POST'])) {
            try {
                $materialModel = new CourseMaterialModel();

                $file = $this->request->getFile('file');
                $filename = $file && $file->isValid() ? $file->getRandomName() : null;
                $type = $this->request->getPost('type');
                $youtube_link = null;

                // Validasi dinamis
                $rules = [
                    'title' => 'required',
                    'type'  => 'required|in_list[article,youtube,video,audio,image,ebook]',
                    'content' => 'required',
                ];

                if ($type === 'youtube') {
                    $youtube_link = $this->request->getPost('youtube_link');
                    $rules['youtube_link'] = 'valid_url';
                }

                if (in_array($type, ['video', 'audio', 'image', 'ebook'])) {
                    $rules['file'] = 'uploaded[file]';

                    $mimeMap = [
                        'video' => 'video/mp4,video/mpeg,video/quicktime',
                        'audio' => 'audio/mpeg,audio/wav',
                        'image' => 'image/jpg,image/jpeg,image/png',
                        'ebook' => 'application/pdf',
                    ];

                    if (isset($mimeMap[$type])) {
                        $rules['file'] .= '|mime_in[file,' . $mimeMap[$type] . ']';
                    }
                }

                if (! $this->validate($rules)) {
                    return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
                }

                // Handle path dan simpan file
                $folderMap = [
                    'video' => 'videos/',
                    'audio' => 'audio/',
                    'image' => 'images/',
                    'ebook' => 'ebooks/',
                ];

                $path = $folderMap[$type] ?? '';
                if ($path && $file && $file->isValid()) {
                    $file->move(WRITEPATH . 'uploads/' . $path, $filename);
                }

                $materialModel->save([
                    'course_id'     => $courseId,
                    'title'         => $this->request->getPost('title'),
                    'type'          => $type,
                    'content'       => $this->request->getPost('content'),
                    'youtube_link'  => $youtube_link,
                    'file_path'     => $filename ? ($path . $filename) : null,
                    'created_by'    => session('user_id'),
                ]);

                $message = 'Materi berhasil diunggah (menunggu approval admin)';
                if (session()->get('role') === 'admin') {
                    $message = 'Materi berhasil ditambahkan';
                }

                return redirect()->to('/course/' . $courseId)->with('message', $message);
            } catch (\Throwable $e) {
                log_message('error', '[UPLOAD ERROR] ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat upload materi.');
            }
        }

        return view('material/upload', ['courseId' => $courseId]);
    }

    public function view($id)
    {
        $model = new \App\Models\CourseMaterialModel();
        $material = $model
            ->select('course_materials.*, users.name AS creator_name')
            ->join('users', 'users.id = course_materials.created_by', 'left')
            ->find($id);

        
        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Materi tidak ditemukan.');
        }

        $commentModel = new \App\Models\CommentModel();
        $comments = $commentModel
            ->select('comments.*, users.name AS user_name')
            ->join('users', 'users.id = comments.user_id')
            ->where('course_material_id', $material['id'])
            ->orderBy('created_at', 'asc')
            ->findAll();

        $data['comments'] = buildCommentTree($comments);
        $data['material'] = $material;

        return view('material/view', $data);
    }


    public function edit($id)
    {
        helper(['form']);
        $model = new \App\Models\CourseMaterialModel();
        $material = $model->find($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Materi tidak ditemukan.');
        }

        // Cek izin akses: hanya admin/instruktur
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
        }

        if (in_array($this->request->getMethod(), ['post', 'POST'])) {
            try {
                $type = $this->request->getPost('type');
                $file = $this->request->getFile('file');
                $youtube_link = $this->request->getPost('youtube_link');
                $content = $this->request->getPost('content');

                // Validasi dinamis
                $rules = [
                    'title' => 'required',
                    'type'  => 'required|in_list[article,youtube,video,audio,image,ebook]',
                ];

                if ($type === 'article') {
                    $rules['content'] = 'required';
                }

                if ($type === 'youtube') {
                    $rules['youtube_link'] = 'required|valid_url';
                }

                if (in_array($type, ['video', 'audio', 'image', 'ebook'])) {
                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $rules['file'] = 'uploaded[file]';

                        $mimeMap = [
                            'video' => 'video/mp4,video/mpeg,video/quicktime',
                            'audio' => 'audio/mpeg,audio/wav',
                            'image' => 'image/jpg,image/jpeg,image/png',
                            'ebook' => 'application/pdf',
                        ];

                        if (isset($mimeMap[$type])) {
                            $rules['file'] .= '|mime_in[file,' . $mimeMap[$type] . ']';
                        }
                    }
                }

                if (! $this->validate($rules)) {
                    return redirect()->back()->withInput()->with('error', $this->validator->getErrors());
                }

                // Siapkan data update
                $data = [
                    'id'        => $id,
                    'title'     => $this->request->getPost('title'),
                    'type'      => $type,
                    'content'   => $content,
                    'updated_at'=> date('Y-m-d H:i:s'),
                ];

                // Simpan YouTube link jika tipe youtube
                if ($type === 'youtube') {
                    $data['youtube_link'] = $youtube_link;
                } else {
                    $data['youtube_link'] = null;
                }

                // Simpan file jika ada dan valid
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $folderMap = [
                        'video' => 'videos/',
                        'audio' => 'audio/',
                        'image' => 'images/',
                        'ebook' => 'ebooks/',
                    ];
                    $path = $folderMap[$type] ?? '';
                    $filename = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/' . $path, $filename);
                    $data['file_path'] = $path . $filename;
                }

                $model->save($data);

                return redirect()->to('/course/' . $material['course_id'])
                                ->with('message', 'Materi berhasil diperbarui.');

            } catch (\Throwable $e) {
                log_message('error', 'Edit materi error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        }

        return view('material/edit', ['material' => $material]);
    }


    public function editx($id)
    {
        $model = new \App\Models\CourseMaterialModel();
        $material = $model->find($id);

        if (!$material) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Optional: Batasi hanya admin/instruktur yang boleh edit
        if (session('role') !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin.');
        }

        return view('material/edit', ['material' => $material]);
    }

    public function delete($id)
    {
        $model = new \App\Models\CourseMaterialModel();
        $material = $model->find($id);

        if (!$material) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        }

        // Optional: hanya admin/instruktur
        if (session('role') !== 'admin') {
            return redirect()->back()->with('error', 'Tidak diizinkan.');
        }

        $model->delete($id);

        return redirect()->back()->with('message', 'Materi berhasil dihapus.');
    }


    public function comment($materialId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => ['login' => 'Silakan login untuk mengomentari.']])->setStatusCode(403);
        }

        $rules = ['comment' => 'required|min_length[2]'];
        if (! $this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()])->setStatusCode(422);
        }

        $model = new \App\Models\CommentModel();
        $model->save([
            'user_id' => session('user_id'),
            'course_material_id' => $materialId,
            'comment' => $this->request->getPost('comment'),
            'parent_id' => $this->request->getPost('parent_id') ?: null
        ]);

        return $this->response->setJSON(['message' => 'Komentar berhasil ditambahkan.']);
    }

    public function reply($commentId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => ['login' => 'Silakan login untuk membalas komentar.']])->setStatusCode(403);
        }

        $rules = ['reply' => 'required|min_length[2]'];
        if (! $this->validate($rules)) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()])->setStatusCode(422);
        }

        $model = new \App\Models\CommentModel();
        $model->save([
            'user_id' => session('user_id'),
            'course_material_id' => $this->request->getPost('material_id'),
            'comment' => $this->request->getPost('reply'),
            'parent_id' => $commentId
        ]);

        return $this->response->setJSON(['message' => 'Balasan berhasil ditambahkan.']);
    }

    public function deleteComment($commentId)
    {
        if (! session()->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => ['login' => 'Silakan login untuk menghapus komentar.']])->setStatusCode(403);
        }

        $model = new \App\Models\CommentModel();
        $comment = $model->find($commentId);

        if (!$comment || $comment['user_id'] !== session('user_id')) {
            return $this->response->setJSON(['error' => ['not_found' => 'Komentar tidak ditemukan atau tidak diizinkan.']])->setStatusCode(404);
        }

        $model->delete($commentId);

        return $this->response->setJSON(['message' => 'Komentar berhasil dihapus.']);
    }



}
