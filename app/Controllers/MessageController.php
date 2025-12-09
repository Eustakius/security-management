<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MessageModel;

class MessageController extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        return $this->getInbox();
    }

    public function getInbox()
    {
        try {
            $userId = session()->get('user_id');
            $model = new MessageModel();

            // Get messages received by user
            $messages = $model->select('messages.*, users.username as sender_name, users.public_key as sender_public_key')
                              ->join('users', 'users.id = messages.sender_id')
                              ->where('receiver_id', $userId)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();

            return $this->respond($messages);
        } catch (\Exception $e) {
            return $this->failServerError('Failed to fetch inbox: ' . $e->getMessage());
        }
    }

    public function getSent()
    {
        try {
            $userId = session()->get('user_id');
            $model = new MessageModel();

            $messages = $model->select('messages.*, users.username as receiver_name, encrypted_aes_key_sender as my_encrypted_aes_key')
                              ->join('users', 'users.id = messages.receiver_id')
                              ->where('sender_id', $userId)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();

            return $this->respond($messages);
        } catch (\Exception $e) {
            return $this->failServerError('Failed to fetch sent items: ' . $e->getMessage());
        }
    }

    public function send()
    {
        if (!session()->get('is_logged_in')) {
            return $this->failUnauthorized();
        }

        $rules = [
            'receiver_id' => 'required|numeric',
            'encrypted_content' => 'required',
            'encrypted_aes_key' => 'required',
            'encrypted_aes_key_sender' => 'required', // NEW RULE
            'iv' => 'required',
            'signature' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $data = [
            'sender_id' => session()->get('user_id'),
            'receiver_id' => $this->request->getPost('receiver_id'),
            'encrypted_content' => $this->request->getPost('encrypted_content'),
            'encrypted_aes_key' => $this->request->getPost('encrypted_aes_key'),
            'encrypted_aes_key_sender' => $this->request->getPost('encrypted_aes_key_sender'), // NEW FIELD
            'iv' => $this->request->getPost('iv'),
            'signature' => $this->request->getPost('signature'),
        ];

        try {
            $model = new MessageModel();
            if ($model->insert($data)) {
                return $this->respondCreated(['id' => $model->getInsertID(), 'message' => 'Message sent securely']);
            } else {
                return $this->failServerError('Failed to send message: DB Error');
            }
        } catch (\Exception $e) {
            return $this->failServerError('Exception sending message: ' . $e->getMessage());
        }
    }
}
