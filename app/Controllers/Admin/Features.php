<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductFeatureModel;
use App\Models\ProductModel;

class Features extends BaseController
{
    protected ProductFeatureModel $featureModel;
    protected ProductModel        $productModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->featureModel = new ProductFeatureModel();
        $this->productModel = new ProductModel();
    }

    // ── List ─────────────────────────────────────────────
    public function index()
    {
        $search    = $this->request->getGet('search') ?? '';
        $productId = $this->request->getGet('product_id') ?? '';
        $status    = $this->request->getGet('status') ?? 'all';

        $builder = $this->db->table('product_features pf')
            ->select('pf.*, p.name AS product_name')
            ->join('products p', 'p.id = pf.product_id', 'left')
            ->orderBy('p.name', 'ASC')
            ->orderBy('pf.name', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('pf.name', $search)
                ->orLike('pf.description', $search)
                ->orLike('pf.module_type', $search)
                ->groupEnd();
        }

        if ($productId) {
            $builder->where('pf.product_id', $productId);
        }

        if ($status === 'active') {
            $builder->where('pf.is_active', 1);
        } elseif ($status === 'inactive') {
            $builder->where('pf.is_active', 0);
        }

        $features = $builder->get()->getResultArray();

        return view('admin/features/index', [
            'title'     => 'Features',
            'features'  => $features,
            'products'  => $this->productModel->orderBy('name', 'ASC')->findAll(),
            'search'    => $search,
            'productId' => $productId,
            'status'    => $status,
        ]);
    }

    // ── Create form ──────────────────────────────────────
    public function create()
    {
        return view('admin/features/create', [
            'title'    => 'Add Feature',
            'products' => $this->productModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    // ── Store ────────────────────────────────────────────
    public function store()
    {
        if (! $this->validate([
            'product_id' => 'required',
            'name'       => 'required|min_length[2]|max_length[255]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $this->featureModel->insert([
            'product_id'        => $this->request->getPost('product_id'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description') ?: null,
            'price'             => $this->request->getPost('price') ?: null,
            'subscription_type' => $this->request->getPost('subscription_type') ?: null,
            'module_type'       => $this->request->getPost('module_type') ?: null,
            'video'             => $this->request->getPost('video') ?: null,
            'limit'             => $this->request->getPost('limit') ?: null,
            'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/features'))->with('success', 'Feature added successfully.');
    }

    // ── Edit form ────────────────────────────────────────
    public function edit(string $id)
    {
        $feature = $this->featureModel->find($id);

        if (! $feature) {
            return redirect()->to(base_url('admin/features'))->with('error', 'Feature not found.');
        }

        return view('admin/features/edit', [
            'title'    => 'Edit Feature',
            'feature'  => $feature,
            'products' => $this->productModel->orderBy('name', 'ASC')->findAll(),
        ]);
    }

    // ── Update ───────────────────────────────────────────
    public function update(string $id)
    {
        if (! $this->validate([
            'product_id' => 'required',
            'name'       => 'required|min_length[2]|max_length[255]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $feature = $this->featureModel->find($id);
        if (! $feature) {
            return redirect()->to(base_url('admin/features'))->with('error', 'Feature not found.');
        }

        $this->featureModel->update($id, [
            'product_id'        => $this->request->getPost('product_id'),
            'name'              => $this->request->getPost('name'),
            'description'       => $this->request->getPost('description') ?: null,
            'price'             => $this->request->getPost('price') ?: null,
            'subscription_type' => $this->request->getPost('subscription_type') ?: null,
            'module_type'       => $this->request->getPost('module_type') ?: null,
            'video'             => $this->request->getPost('video') ?: null,
            'limit'             => $this->request->getPost('limit') ?: null,
            'is_active'         => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        return redirect()->to(base_url('admin/features'))->with('success', 'Feature updated successfully.');
    }

    // ── Delete ───────────────────────────────────────────
    public function delete(string $id)
    {
        $feature = $this->featureModel->find($id);

        if (! $feature) {
            return redirect()->to(base_url('admin/features'))->with('error', 'Feature not found.');
        }

        $this->featureModel->delete($id);

        return redirect()->to(base_url('admin/features'))->with('success', 'Feature deleted.');
    }
}
