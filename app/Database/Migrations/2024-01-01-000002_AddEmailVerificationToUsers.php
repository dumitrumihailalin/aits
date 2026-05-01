<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'verification_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'remember_token',
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'customer'],
                'default'    => 'customer',
                'after'      => 'verification_token',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'verification_token');
        $this->forge->dropColumn('users', 'role');
    }
}