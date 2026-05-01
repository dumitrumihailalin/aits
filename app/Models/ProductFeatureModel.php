<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductFeatureModel extends Model
{
    protected $table         = 'product_features';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'product_id', 'name', 'description', 'price',
        'subscription_type', 'module_type', 'video', 'limit', 'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getByProduct(string $productId): array
    {
        return $this->where('product_id', $productId)
                    ->where('is_active', 1)
                    ->orderBy('limit', 'ASC')
                    ->findAll();
    }
}
