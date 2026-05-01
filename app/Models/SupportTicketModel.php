<?php
namespace App\Models;

use CodeIgniter\Model;

class SupportTicketModel extends Model
{
    protected $table         = 'support_tickets';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id', 'subject', 'description', 'image_path',
        'status', 'priority', 'is_read', 'product_feature_id',
    ];

    public function getByUser(string $userId): array
    {
        return $this->where('user_id', $userId)
                    ->where('deleted_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getTicketForUser(string $ticketId, string $userId): ?array
    {
        return $this->where('id', $ticketId)
                    ->where('user_id', $userId)
                    ->where('deleted_at', null)
                    ->first();
    }
}