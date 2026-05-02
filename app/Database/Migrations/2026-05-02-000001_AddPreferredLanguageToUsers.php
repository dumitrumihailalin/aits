<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPreferredLanguageToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'preferred_language' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => false,
                'default'    => 'en',
                'after'      => 'city',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'preferred_language');
    }
}
