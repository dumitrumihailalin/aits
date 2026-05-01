<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;
use App\Models\ProductFeatureModel;

class ProductModel extends Model
{
    protected $table              = 'products';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = false;
    protected $returnType         = 'array';
    protected $useTimestamps      = true;

    protected $allowedFields = [
        'id', 'name', 'slug', 'description', 'short_description',
        'base_price', 'price', 'price_label',
        'icon', 'color', 'youtube_url',
        'is_featured', 'is_active', 'sort_order',
        'quantity_available', 'sku', 'user_id',
    ];
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['injectUuid'];

    protected function injectUuid(array $data): array
    {
        if (empty($data['data']['id'])) {
            $data['data']['id'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function getActive(): array
    {
        $products = $this->where('is_active', 1)
                         ->orderBy('sort_order', 'ASC')
                         ->findAll();

        return $this->attachFeatures($products);
    }

    public function getFeatured(): array
    {
        $products = $this->where('is_active', 1)
                         ->where('is_featured', 1)
                         ->orderBy('sort_order', 'ASC')
                         ->findAll();

        return $this->attachFeatures($products);
    }

    public function findBySlug(string $slug): ?array
    {
        $product = $this->where('slug', $slug)
                        ->where('is_active', 1)
                        ->first();

        if ($product) {
            $featureModel        = new ProductFeatureModel();
            $product['features'] = $featureModel->getByProduct($product['id']);
        }

        return $product;
    }

    public static function urlSlug(array $product): string
    {
        return $product['slug'];
    }

    private function attachFeatures(array $products): array
    {
        if (empty($products)) {
            return $products;
        }

        $featureModel = new ProductFeatureModel();
        $ids          = array_column($products, 'id');

        $features = $featureModel->whereIn('product_id', $ids)
                                 ->where('is_active', 1)
                                 ->orderBy('limit', 'ASC')
                                 ->findAll();

        $grouped = [];
        foreach ($features as $feature) {
            $grouped[$feature['product_id']][] = $feature;
        }

        foreach ($products as &$product) {
            $product['features'] = $grouped[$product['id']] ?? [];
        }

        return $products;
    }
}