<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        // ── 1. Get customer role id ────────────────────────
        $customerRole = $this->db->table('roles')
                              ->where('slug', 'customer')
                              ->get()->getRow();

        if (! $customerRole) {
            echo "Roles not found. Run RolesSeeder first.\n";
            return;
        }

        // ── 2. Insert customer user ────────────────────────
        $email = 'customer@alinitservices.com';

        $existingUser = $this->db->table('users')
                                 ->where('email', $email)
                                 ->get()->getRow();

        if (! $existingUser) {
            $this->db->table('users')->insert([
                'name'       => 'Alin Customer',
                'email'      => $email,
                'password'   => password_hash('customer123', PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            $userId = $this->db->insertID();
            echo "Customer user inserted: {$email}\n";
        } else {
            $userId = $existingUser->id;
            echo "Customer user already exists: {$email}, skipping insert.\n";
        }

        // ── 3. Assign customer role in role_user ───────────
        $existingRole = $this->db->table('role_user')
                                 ->where('user_id', $userId)
                                 ->where('role_id', $customerRole->id)
                                 ->get()->getRow();

        if (! $existingRole) {
            $this->db->table('role_user')->insert([
                'user_id'    => $userId,
                'role_id'    => $customerRole->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Customer role assigned to user.\n";
        } else {
            echo "Role already assigned, skipping.\n";
        }
    }
}