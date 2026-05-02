<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id', 'name', 'email', 'password',
        'company_name', 'phone', 'address', 'country', 'city',
        'email_verified_at', 'verification_token',
        'reset_token', 'reset_expires_at', 'remember_token',
        'notify_ticket_updates', 'preferred_language',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Find user by email
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }

    // Find by verification token
    public function findByVerificationToken(string $token): ?array
    {
        return $this->where('verification_token', $token)->first();
    }

    // Find by reset token
    public function findByResetToken(string $token): ?array
    {
        return $this->where('reset_token', $token)
                    ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
                    ->first();
    }

    public function insert($data = null, bool $returnID = true)
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        while ($this->where('id', $uuid)->first()) {
            $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        }

        if (is_array($data)) {
            $data['id'] = $uuid;
        }

        $result = parent::insert($data, $returnID);

        // Return the UUID string instead of 0 (which insertID() gives for non-int PKs)
        return $result ? $uuid : false;
    }
    // Get role slug via role_user pivot
    public function getRole(string $userId): ?string
    {
        $row = $this->db->table('role_user')
                        ->select('roles.slug')
                        ->join('roles', 'roles.id = role_user.role_id')
                        ->where('role_user.user_id', $userId)
                        ->get()
                        ->getRow();

        return $row ? $row->slug : null;
    }

    // Find user with role joined
public function findWithRole(string $id): ?array
{
    return $this->db
        ->table('users')
        ->select('users.*, roles.slug as role, roles.name as role_name')
        ->join('role_user', 'role_user.user_id = users.id', 'left')
        ->join('roles', 'roles.id = role_user.role_id', 'left')
        ->where('users.id', $id)
        ->get()
        ->getRowArray() ?: null;
}

    // Find user by email with role joined
    public function findByEmailWithRole(string $email): ?array
    {
        return $this->db
            ->table('users')
            ->select('users.*, roles.slug as role, roles.name as role_name')
            ->join('role_user', 'role_user.user_id = users.id', 'left')
            ->join('roles', 'roles.id = role_user.role_id', 'left')
            ->where('users.email', $email)
            ->get()
            ->getRowArray() ?: null;
    }

    // Mark email as verified
    public function verifyEmail(string $userId): void
    {
        $this->update($userId, [
            'email_verified_at'  => date('Y-m-d H:i:s'),
            'verification_token' => null,
        ]);
    }

    // Set password reset token
    public function setResetToken(string $userId, string $token, string $expires): void
    {
        $this->update($userId, [
            'reset_token'      => $token,
            'reset_expires_at' => $expires,
        ]);
    }


    // Clear password reset token
    public function clearResetToken(string $userId): void
    {
        $this->update($userId, [
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);
    }
}