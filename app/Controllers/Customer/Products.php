<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        $userId       = session()->get('user_id');
        $productModel = new ProductModel();
        $cartModel    = new CartModel();

        $allProducts = $productModel->getActive();

        // Get IDs already in cart or active for this user
        $userRows   = $cartModel->getActiveProducts($userId);
        $userIds    = array_map(fn($r) => $r['product_id'], $userRows);
        $activeIds  = array_map(
            fn($r) => $r['product_id'],
            array_filter($userRows, fn($r) => $r['status'] === 'active')
        );

        return view('customer/products/index', [
            'title'      => 'Select Products',
            'products'   => $allProducts,
            'userIds'    => $userIds,
            'activeIds'  => $activeIds,
        ]);
    }
}
