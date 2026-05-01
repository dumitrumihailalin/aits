<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data = [
            'title'    => 'AITS — Alin IT Services',
            'products' => $productModel->getActive(),
            'featured' => $productModel->getFeatured(),
        ];

        return view('home', $data);
    }
}