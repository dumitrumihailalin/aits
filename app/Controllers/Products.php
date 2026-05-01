<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\InvoiceModel;
use App\Models\ProductModel;
use App\Models\ProductFeatureModel;

class Products extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        return view('products/index', [
            'title'     => 'Our IT Services & Plans — Alin IT Services',
            'metaDesc'  => 'Explore AITS cloud hosting, CRM, ERP and IT support plans. Transparent pricing, 24/7 support, 30-day money-back guarantee.',
            'activeNav' => 'products',
            'products'  => $productModel->getActive(),
        ]);
    }

    public function show(string $slugWithId)
    {
        $productModel = new ProductModel();
        $product      = $productModel->findBySlug($slugWithId);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Canonical redirect: if URL slug doesn't end in -{id}, redirect to the canonical form.
        $canonical = ProductModel::urlSlug($product);
        if ($slugWithId !== $canonical) {
            return redirect()->to(base_url('products/' . $canonical), 301);
        }

        $inCart           = false;
        $alreadyPurchased = false;
        if (session()->get('isLoggedIn')) {
            $userId           = session()->get('user_id');
            $inCart           = (new CartModel())->inCart($userId, (string) $product['id']);
            $alreadyPurchased = (new InvoiceModel())->userHasProduct($userId, (string) $product['id']);
        }

        return view('products/show', [
            'title'            => esc($product['name']) . ' — AITS',
            'product'          => $product,
            'inCart'           => $inCart,
            'alreadyPurchased' => $alreadyPurchased,
        ]);
    }

    public function feature(string $productSlug, string $featureId)
    {
        $productModel = new ProductModel();
        $product      = $productModel->findBySlug($productSlug);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $featureModel = new ProductFeatureModel();
        $feature      = $featureModel->where('id', $featureId)
                                     ->where('product_id', $product['id'])
                                     ->where('is_active', 1)
                                     ->first();

        if (! $feature) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('products/feature', [
            'title'   => esc($feature['name']) . ' — ' . esc($product['name']) . ' — AITS',
            'product' => $product,
            'feature' => $feature,
        ]);
    }
}
