<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class InvoiceItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useTimestamps    = false;
    protected $allowedFields    = [
        'id', 'order_id', 'product_id', 'quantity', 'unit_price', 'total_price',
        'created_at',
    ];

    public function getByInvoice(string $invoiceId): array
    {
        return $this->db->table('order_items oi')
            ->select('oi.*, p.name AS product_name, p.short_description AS description')
            ->join('products p', 'p.id = oi.product_id', 'left')
            ->join('orders o', 'o.id = oi.order_id', 'inner')
            ->join('invoices inv', 'inv.invoice_number = o.order_number', 'inner')
            ->where('inv.id', $invoiceId)
            ->get()
            ->getResultArray();
    }

    public function insertItems(string $invoiceId, array $items): void
    {
        $rows = [];
        foreach ($items as $item) {
            $rows[] = [
                'id'         => Uuid::uuid4()->toString(),
                'order_id'   => $invoiceId,
                'product_id' => $item['product_id'] ?? null,
                'quantity'   => (int) $item['quantity'],
                'unit_price' => (float) $item['unit_price'],
                'total_price'=> (int) $item['quantity'] * (float) $item['unit_price'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }
        $this->insertBatch($rows);
    }
}
