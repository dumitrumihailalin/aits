<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanyFieldsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'company_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'default'    => null,
                'after'      => 'name',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => null,
                'after'      => 'company_name',
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'phone',
            ],
            'country' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'address',
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'country',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'company_name');
        $this->forge->dropColumn('users', 'phone');
        $this->forge->dropColumn('users', 'address');
        $this->forge->dropColumn('users', 'country');
        $this->forge->dropColumn('users', 'city');
    }
}