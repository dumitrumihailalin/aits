<?php
namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class CartModel extends Model
{
    protected $table            = 'product_user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'id', 'user_id', 'product_id', 'status', 'is_active',
        'subscribed_at', 'expires_at', 'purchased_at',
    ];

    protected $beforeInsert = ['injectUuid'];

    protected function injectUuid(array $data): array
    {
        if (empty($data['data']['id'])) {
            $data['data']['id'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function getCartItems(string $userId): array
    {
        return $this->db->table('product_user pu')
            ->select('pu.*, p.name as product_name, p.price, p.icon, p.color, p.short_description, p.slug')
            ->join('products p', 'p.id = pu.product_id')
            ->where('pu.user_id', $userId)
            ->where('pu.status', 'cart')
            ->get()
            ->getResultArray();
    }

    public function getActiveProducts(string $userId): array
    {
        return $this->db->table('product_user pu')
            ->select('pu.*, p.name as product_name, p.price, p.icon, p.color, p.short_description, p.slug')
            ->join('products p', 'p.id = pu.product_id')
            ->where('pu.user_id', $userId)
            ->whereIn('pu.status', ['active', 'cart'])
            ->orderBy('pu.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCount(string $userId): int
    {
        return $this->where('user_id', $userId)
                    ->where('status', 'cart')
                    ->countAllResults();
    }

    public function inCart(string $userId, string $productId): bool
    {
        return $this->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->where('status', 'cart')
                    ->first() !== null;
    }

    public function addToCart(string $userId, string $productId): bool
    {
        if ($this->inCart($userId, $productId)) {
            return false;
        }

        $this->insert([
            'user_id'    => $userId,
            'product_id' => $productId,
            'status'     => 'cart',
            'is_active'  => 0,
        ]);

        return true;
    }

    public function removeFromCart(string $userId, string $productId): void
    {
        $this->where('user_id', $userId)
             ->where('product_id', $productId)
             ->where('status', 'cart')
             ->delete();
    }

    /**
     * Sync the cart: add any newly-selected products, remove any unchecked ones.
     * Only touches rows with status='cart' (never removes active/paid rows).
     *
     * @param string   $userId
     * @param int[]    $selectedIds  Product IDs the user checked
     */
    public function syncCart(string $userId, array $selectedIds): void
    {
        // Current cart product IDs
        $current = $this->where('user_id', $userId)
                        ->where('status', 'cart')
                        ->findAll();
        $currentIds = array_map(fn($r) => $r['product_id'], $current);

        // Add newly checked
        foreach ($selectedIds as $id) {
            if (! in_array($id, $currentIds, true)) {
                $this->insert([
                    'user_id'    => $userId,
                    'product_id' => $id,
                    'status'     => 'cart',
                    'is_active'  => 0,
                ]);
            }
        }

        // Remove unchecked (only cart-status rows, not active)
        foreach ($currentIds as $id) {
            if (! in_array($id, $selectedIds, true)) {
                $this->where('user_id', $userId)
                     ->where('product_id', (string) $id)
                     ->where('status', 'cart')
                     ->delete();
            }
        }
    }

    public function markAsOrdered(string $userId): void
    {
        $this->db->table('product_user')
            ->where('user_id', $userId)
            ->where('status', 'cart')
            ->delete();
    }
}
