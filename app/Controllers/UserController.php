<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class UserController extends BaseController
{
    use ResponseTrait;

    public function search()
    {
        $username = $this->request->getGet('username');
        if (!$username) {
            return $this->fail('Username is required');
        }

        $userModel = new UserModel();
        // Find users matching username, excluding current user
        $users = $userModel->like('username', $username)
                           // ->where('id !=', session()->get('user_id')) // Allow self-search for "Note to Self"
                           ->select('id, username, public_key')
                           ->findAll(10); // Limit 10

        return $this->respond($users);
    }
}
