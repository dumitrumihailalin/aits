<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\InvoiceModel;
use App\Models\ProductModel;

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
}
