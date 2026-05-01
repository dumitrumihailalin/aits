<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductFeatureModel extends Model
{
    protected $table              = 'product_features';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = false;
    protected $returnType         = 'array';
    protected $allowedFields      = [
        'id', 'product_id', 'name', 'description', 'price',
        'subscription_type', 'module_type', 'video', 'limit', 'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $beforeInsert  = ['injectUuid'];

    protected function injectUuid(array $data): array
    {
        if (empty($data['data']['id'])) {
            $data['data']['id'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function getByProduct(string $productId): array
    {
        return $this->where('product_id', $productId)
                    ->where('is_active', 1)
                    ->orderBy('limit', 'ASC')
                    ->findAll();
    }
}
