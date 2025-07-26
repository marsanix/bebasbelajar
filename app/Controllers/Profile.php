<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    // public function index()
    // {
    //     if (! session()->get('isLoggedIn')) {
    //         return redirect()->to('/login');
    //     }

    //     $model = new UserModel();
    //     $user = $model->find(session()->get('user_id'));

    //     return view('user/profile', ['user' => $user]);
    // }

    public function update()
    {
        $model = new \App\Models\UserModel();
        $userId = session()->get('user_id');

        $rules = [
            'name' => 'required|min_length[3]',
        ];

        // Validasi password jika diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }

        if (! $this->validate($rules)) {
            // Response format JSON untuk AJAX
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $model->update($userId, $data);

        // Update session name agar tampilan navbar ikut berubah
        session()->set('name', $data['name']);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'name'    => $data['name']
        ]);
    }

}
