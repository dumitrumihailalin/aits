<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use Ramsey\Uuid\Uuid; // still used for role_user.id

class AdminSeeder extends Seeder
{
    public function run()
    {
        // ── 1. Get admin role id ────────────────────────
        $adminRole = $this->db->table('roles')
                              ->where('slug', 'admin')
                              ->get()->getRow();

        if (! $adminRole) {
            echo "Roles not found. Run RolesSeeder first.\n";
            return;
        }

        // ── 2. Insert admin user ────────────────────────
        $email     = 'admin@aits.com';
        $userModel = new UserModel();

        $existingUser = $userModel->where('email', $email)->first();

        if (! $existingUser) {
            $userId = $userModel->insert([
                'name'     => 'Alin Admin',
                'email'    => $email,
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
            ]);

            if (! $userId) {
                echo "Failed to insert admin user.\n";
                return;
            }

            echo "Admin user inserted: {$email}\n";
        } else {
            $userId = $existingUser['id'];
            echo "Admin user already exists: {$email}, skipping insert.\n";
        }

        // ── 3. Assign admin role in role_user ───────────
        $existingRole = $this->db->table('role_user')
                                 ->where('user_id', $userId)
                                 ->where('role_id', $adminRole->id)
                                 ->get()->getRow();

        if (! $existingRole) {
            $this->db->table('role_user')->insert([
                'id'         => Uuid::uuid4()->toString(),
                'user_id'    => $userId,
                'role_id'    => $adminRole->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Admin role assigned to user.\n";
        } else {
            echo "Role already assigned, skipping.\n";
        }
    }
}