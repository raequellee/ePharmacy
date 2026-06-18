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
        // Dummy auth — ganti dengan call API kalau ada endpoint login
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // TODO: ganti kondisi ini dengan verifikasi ke API
        if ($username === 'admin' && $password === 'admin123') {
            session()->set([
                'logged_in' => true,
                'username'  => $username,
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
}