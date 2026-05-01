<?php
namespace App\Models;

use CodeIgniter\Model;

class TicketReplyModel extends Model
{
    protected $table         = 'ticket_replies';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'support_ticket_id', 'user_id', 'message',
        'attachment_path', 'is_admin_reply',
    ];

    public function getByTicket(string $ticketId): array
    {
        return $this->db
            ->table('ticket_replies')
            ->select('
                ticket_replies.id,
                ticket_replies.support_ticket_id,
                ticket_replies.user_id,
                ticket_replies.message,
                ticket_replies.attachment_path,
                ticket_replies.is_admin_reply,
                ticket_replies.created_at,
                ticket_replies.updated_at
            ')
            ->where('ticket_replies.support_ticket_id', $ticketId)
            ->orderBy('ticket_replies.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }
}