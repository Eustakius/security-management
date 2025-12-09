<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function loginView()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        // Check for 'registered' query param from JS redirect
        if ($this->request->getGet('registered')) {
             session()->setFlashdata('message', 'Registration successful! Please login.');
        }
        return view('auth/login');
    }

    public function registerView()
    {
        if (session()->get('user_id')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/register');
    }

    public function register()
    {
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'password' => 'required|min_length[8]',
            'public_key' => 'required',
            'encrypted_private_key' => 'required', // Ensure this is present
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $userModel = new \App\Models\UserModel(); // We need to create this

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'public_key' => $this->request->getPost('public_key'),
            'encrypted_private_key' => $this->request->getPost('encrypted_private_key'),
        ];

        try {
            $userModel->insert($data);
        } catch (\Exception $e) {
             // Handle duplicate user or DB error cleanly
             return redirect()->back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }

        // No Auto-Login! Force user to login to verify password.
        return redirect()->to(base_url('login'))->with('message', 'Account created! Please login.');
    }

    public function login()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session()->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'public_key' => $user['public_key'],
                    'encrypted_private_key' => $user['encrypted_private_key'], // Sync to session
                    'is_logged_in' => true
                ]);
                return redirect()->to(base_url('dashboard'));
            }
        }

        return redirect()->back()->withInput()->with('error', 'Invalid username or password');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/'));
    }
}
