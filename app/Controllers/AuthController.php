<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/obat');
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $model = new \App\Models\UserModel();
        
        $user = $model->where('username', $username)->first();

        if ($user && $user['password'] === $password) {
            
            session()->set([
                'logged_in' => true,
                'username'  => $user['username'],
                'role'      => $user['role'], 
            ]);
            
            return redirect()->to(base_url('obat'));
        }

        return redirect()->to('/login')->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function processRegister()
    {
        $model = new \App\Models\UserModel();
        
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role'     => 'user' 
        ];

        $model->insert($data);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}