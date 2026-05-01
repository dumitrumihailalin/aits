<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'products'; ?>

<?= $this->section('content') ?>

<style>
  .badge-active   { background:#d1fae5;color:#065f46; }
  .badge-inactive { background:#fee2e2;color:#991b1b; }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Products</div>
  </div>
  <a href="<?= base_url('admin/products/create') ?>" class="btn-aits btn-aits-primary">
    <i class="bi bi-plus-circle"></i> Add Product
  </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
       style="background:#1877f2;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
    <span><?= session()->getFlashdata('success') ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
  </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
       style="background:#dc2626;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
    <i class="bi bi-exclamation-circle-fill" style="font-size:18px;"></i>
    <span><?= session()->getFlashdata('error') ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
  </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-0">
    <?php if (empty($products)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-box-seam" style="font-size:40px;"></i>
        <p class="mt-3">No products yet. <a href="<?= base_url('admin/products/create') ?>">Add your first product</a>.</p>
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
        <tr>
          <th class="ps-4 py-3">Product</th>
          <th>Price</th>
          <th>Featured</th>
          <th>Status</th>
          <th>Sort</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="font-size:14px;">
        <?php foreach ($products as $product): ?>
        <tr>
          <td class="ps-4">
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:38px;height:38px;background:<?= esc($product['color'] ?? '#1877f2') ?>;border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;">
                <i class="bi <?= esc($product['icon'] ?? 'bi-box-seam') ?>"></i>
              </div>
              <div>
                <div style="font-weight:600;"><?= esc($product['name']) ?></div>
                <div style="font-size:12px;color:var(--text-muted);"><?= esc($product['slug']) ?></div>
              </div>
            </div>
          </td>
          <td style="font-weight:700;color:#1877f2;">
            $<?= number_format($product['price'], 2) ?>
            <span style="font-size:12px;color:var(--text-muted);font-weight:400;">/ <?= esc($product['price_label'] ?? 'month') ?></span>
          </td>
          <td>
            <?php if ($product['is_featured']): ?>
              <span class="badge rounded-pill" style="background:#fef3c7;color:#92400e;">⭐ Featured</span>
            <?php else: ?>
              <span style="color:#9ca3af;font-size:13px;">—</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="badge badge-<?= $product['is_active'] ? 'active' : 'inactive' ?> rounded-pill px-3">
              <?= $product['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td style="color:var(--text-muted);"><?= $product['sort_order'] ?></td>
          <td>
            <div class="d-flex gap-2">
              <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="<?= base_url('admin/products/delete/' . $product['id']) ?>" method="POST"
                    onsubmit="return confirm('Delete this product?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
