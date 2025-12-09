<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table            = 'messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sender_id', 'receiver_id', 
        'encrypted_content', 'encrypted_aes_key', 
        'encrypted_aes_key_sender', // NEW
        'iv', 'signature'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
