<?php
namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class InvoiceModel extends Model
{
    protected $table              = 'invoices';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = false;
    protected $returnType         = 'array';
    protected $useTimestamps      = true;
    protected $allowedFields = [
        'id', 'user_id', 'invoice_number', 'amount', 'total_amount',
        'status', 'is_read', 'issue_date', 'due_date',
        'paid_date', 'notes', 'description', 'deleted_at',
    ];

    protected $beforeInsert = ['injectUuid'];

    protected function injectUuid(array $data): array
    {
        if (empty($data['data']['id'])) {
            $data['data']['id'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function getByUser(string $userId): array
    {
        return $this->where('user_id', $userId)
                    ->where('deleted_at', null)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getInvoiceForUser(string $invoiceId, string $userId): ?array
    {
        return $this->where('id', $invoiceId)
                    ->where('user_id', $userId)
                    ->where('deleted_at', null)
                    ->first();
    }

    public function userHasProduct(string $userId, string $productId): bool
    {
        $count = $this->db->table('order_items oi')
            ->join('orders o', 'o.id = oi.order_id')
            ->join('invoices inv', 'inv.invoice_number = o.order_number')
            ->where('inv.user_id', $userId)
            ->where('inv.deleted_at', null)
            ->where('oi.product_id', $productId)
            ->countAllResults();

        return $count > 0;
    }

    public function getAll(): array
    {
        return $this->db->table('invoices')
                    ->select('invoices.*, users.name as user_name, users.email as user_email, users.company_name')
                    ->join('users', 'users.id = invoices.user_id')
                    ->where('invoices.deleted_at', null)
                    ->orderBy('invoices.created_at', 'DESC')
                    ->get()
                    ->getResultArray();
    }
}