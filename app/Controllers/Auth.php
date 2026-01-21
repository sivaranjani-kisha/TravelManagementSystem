<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function saveRegister()
    {
        $userModel = new UserModel();

        $mobile = $this->request->getPost('mobile');

        // Check duplicate mobile
        if ($userModel->where('mobile', $mobile)->first()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Mobile number already exists'
            ]);
        }


        

        $data = [
            'name'     => $this->request->getPost('name'),
            'mobile'   => $mobile,
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            )
        ];

        $userModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Registered successfully'
        ]);
    }

    public function login()
    {
        return view('auth/login');
    }

    public function checkLogin()
    {
        $userModel = new UserModel();

        $mobile   = $this->request->getPost('mobile');
        $password = $this->request->getPost('password');

        $user = $userModel->where('mobile', $mobile)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid mobile or password'
            ]);
        }

        session()->set([
            'user_id' => $user['id'],
            'name'    => $user['name'],
            'logged_in' => true
        ]);

        return $this->response->setJSON([
            'status' => 'success'
        ]);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
