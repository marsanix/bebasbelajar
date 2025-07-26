<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {

         if (in_array($this->request->getMethod(), ['post', 'POST'])) {

            $userModel = new UserModel();
            
            $data = [
                'name'     => $this->request->getPost('name'),
                'email'    => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role'     => 'member',
            ];

            if (! $userModel->insert($data)) {
                return redirect()->back()->with('auth_error', 'Gagal menyimpan data')->withInput();
            }

            return redirect()->to('/login')->with('auth_message', 'Registrasi berhasil. Silakan login.');

            //$userModel->save($data);
            //return redirect()->to('/login')->with('message', 'Registrasi berhasil. Silakan login.');
        }

        return view('auth/register');
    }

    public function login()
    {
        if (in_array($this->request->getMethod(), ['post', 'POST'])) {
            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getPost('email'))->first();

            if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                session()->set([
                    'user_id'   => $user['id'],
                    'name'      => $user['name'],
                    'email'     => $user['email'],
                    'role'      => $user['role'],
                    'isLoggedIn'=> true,
                ]);
                return redirect()->to('/dashboard');
            }

            return redirect()->back()->with('auth_error', 'Email atau Password salah');
        }

        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
