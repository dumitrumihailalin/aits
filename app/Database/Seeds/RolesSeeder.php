<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id'         => Uuid::uuid4()->toString(),
                'name'       => 'Admin',
                'slug'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'         => Uuid::uuid4()->toString(),
                'name'       => 'Customer',
                'slug'       => 'customer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($roles as $role) {
            $exists = $this->db->table('roles')
                               ->where('slug', $role['slug'])
                               ->get()->getRow();

            if (! $exists) {
                $this->db->table('roles')->insert($role);
                echo "Role inserted: {$role['slug']}\n";
            } else {
                echo "Role already exists: {$role['slug']}, skipping.\n";
            }
        }
    }
}