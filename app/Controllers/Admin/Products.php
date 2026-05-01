<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Products extends BaseController
{
    protected ProductModel $productModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
    }

    // ── List ─────────────────────────────────────────────
    public function index()
    {
        return view('admin/products/index', [
            'title'    => 'Products',
            'products' => $this->productModel->orderBy('sort_order', 'ASC')->findAll(),
        ]);
    }

    // ── Create form ──────────────────────────────────────
    public function create()
    {
        return view('admin/products/create', [
            'title' => 'Add Product',
        ]);
    }

    // ── Store ────────────────────────────────────────────
    public function store()
    {
        if (! $this->validate([
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|decimal',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $slug = url_title($this->request->getPost('name'), '-', true);

        $this->productModel->insert([
            'name'              => $this->request->getPost('name'),
            'slug'              => $slug,
            'description'       => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price'             => $this->request->getPost('price'),
            'base_price'        => $this->request->getPost('price'),
            'price_label'       => $this->request->getPost('price_label') ?? 'month',
            'icon'              => $this->request->getPost('icon') ?? 'bi-box-seam',
            'color'             => $this->request->getPost('color') ?? '#1877f2',
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'        => $this->request->getPost('sort_order') ?? 0,
            'user_id'           => session()->get('user_id'),
        ]);

        return redirect()->to(base_url('admin/products'))->with('success', 'Product created successfully.');
    }

    // ── Edit form ────────────────────────────────────────
    public function edit(string $id)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()->to(base_url('admin/products'))->with('error', 'Product not found.');
        }

        return view('admin/products/edit', [
            'title'   => 'Edit Product',
            'product' => $product,
        ]);
    }

    // ── Update ───────────────────────────────────────────
    public function update(string $id)
    {
        if (! $this->validate([
            'name'  => 'required|min_length[2]|max_length[255]',
            'price' => 'required|decimal',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->to(base_url('admin/products'))->with('error', 'Product not found.');
        }

        $this->productModel->update($id, [
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description'),
            'short_description' => $this->request->getPost('short_description'),
            'price'             => $this->request->getPost('price'),
            'base_price'        => $this->request->getPost('price'),
            'price_label'       => $this->request->getPost('price_label') ?? 'month',
            'icon'              => $this->request->getPost('icon') ?? 'bi-box-seam',
            'color'             => $this->request->getPost('color') ?? '#1877f2',
            'is_featured'       => $this->request->getPost('is_featured') ? 1 : 0,
            'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
            'sort_order'        => $this->request->getPost('sort_order') ?? 0,
        ]);

        return redirect()->to(base_url('admin/products'))->with('success', 'Product updated successfully.');
    }

    // ── Delete ───────────────────────────────────────────
    public function delete(string $id)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()->to(base_url('admin/products'))->with('error', 'Product not found.');
        }

        $this->productModel->delete($id);

        return redirect()->to(base_url('admin/products'))->with('success', 'Product deleted.');
    }
}