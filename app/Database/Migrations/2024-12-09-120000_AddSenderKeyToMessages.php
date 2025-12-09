<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSenderKeyToMessages extends Migration
{
    public function up()
    {
        $fields = [
            'encrypted_aes_key_sender' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'encrypted_aes_key',
            ],
        ];
        $this->forge->addColumn('messages', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('messages', 'encrypted_aes_key_sender');
    }
}
