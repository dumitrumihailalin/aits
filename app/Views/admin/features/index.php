<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'features'; ?>

<?= $this->section('content') ?>

<style>
  .badge-active   { background:#d1fae5;color:#065f46; }
  .badge-inactive { background:#fee2e2;color:#991b1b; }
  .status-tabs { display:flex;gap:4px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:6px;margin-bottom:16px; }
  .status-tab { padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:all .15s; }
  .status-tab:hover { background:rgba(255,255,255,.06);color:var(--text-primary); }
  .status-tab.active { background:var(--brand-accent);color:#fff; }
  .status-tab .count { background:rgba(0,0,0,.15);border-radius:20px;padding:1px 8px;font-size:11px; }
  .status-tab.active .count { background:rgba(255,255,255,.25); }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Features</div>
  </div>
  <a href="<?= base_url('admin/features/create') ?>" class="btn-aits btn-aits-primary">
    <i class="bi bi-plus-circle"></i> Add Feature
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

<?php
  $qs = ($search    ? '&search='     . urlencode($search)    : '')
      . ($productId ? '&product_id=' . urlencode($productId) : '');
?>
<div class="status-tabs">
  <a href="<?= base_url('admin/features?' . ltrim($qs, '&')) ?>"            class="status-tab <?= $status === 'all'      ? 'active' : '' ?>">All</a>
  <a href="<?= base_url('admin/features?status=active' . $qs) ?>"           class="status-tab <?= $status === 'active'   ? 'active' : '' ?>">Active</a>
  <a href="<?= base_url('admin/features?status=inactive' . $qs) ?>"         class="status-tab <?= $status === 'inactive' ? 'active' : '' ?>">Inactive</a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-3">
    <form method="GET" action="<?= base_url('admin/features') ?>">
      <?php if ($status !== 'all'): ?>
        <input type="hidden" name="status" value="<?= esc($status) ?>">
      <?php endif; ?>
      <div class="row g-2">
        <div class="col">
          <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
              <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" class="form-control border-start-0"
                   placeholder="Search by name, description or module..."
                   value="<?= esc($search) ?>">
          </div>
        </div>
        <div class="col-auto" style="min-width:200px;">
          <select name="product_id" class="form-select">
            <option value="">All products</option>
            <?php foreach ($products as $product): ?>
              <option value="<?= esc($product['id']) ?>"
                <?= $productId === $product['id'] ? 'selected' : '' ?>>
                <?= esc($product['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-auto d-flex gap-2">
          <button type="submit" class="btn btn-primary">Filter</button>
          <?php if ($search || $productId || $status !== 'all'): ?>
            <a href="<?= base_url('admin/features') ?>" class="btn btn-outline-secondary">Clear</a>
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-0">
    <?php if (empty($features)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-list-stars" style="font-size:40px;"></i>
        <p class="mt-3">No features yet. <a href="<?= base_url('admin/features/create') ?>">Add your first feature</a>.</p>
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
        <tr>
          <th class="ps-4 py-3">Product</th>
          <th>Feature</th>
          <th>Price</th>
          <th>Subscription</th>
          <th>Module</th>
          <th>Limit</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="font-size:14px;">
        <?php foreach ($features as $f): ?>
        <tr>
          <td class="ps-4">
            <span style="font-weight:600;color:#1877f2;"><?= esc($f['product_name'] ?? '—') ?></span>
          </td>
          <td>
            <div style="font-weight:600;"><?= esc($f['name']) ?></div>
            <?php if ($f['description']): ?>
              <div style="font-size:12px;color:var(--text-muted);"><?= esc($f['description']) ?></div>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($f['price'] !== null && $f['price'] !== ''): ?>
              <span style="font-weight:700;color:#1877f2;">$<?= number_format((float)$f['price'], 2) ?></span>
            <?php else: ?>
              <span style="color:#9ca3af;">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($f['subscription_type']): ?>
              <span class="badge rounded-pill" style="background:#ede9fe;color:#5b21b6;font-size:11px;">
                <?= esc($f['subscription_type']) ?>
              </span>
            <?php else: ?>
              <span style="color:#9ca3af;">—</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ($f['module_type']): ?>
              <span class="badge rounded-pill" style="background:#e0f2fe;color:#0369a1;font-size:11px;">
                <?= esc($f['module_type']) ?>
              </span>
            <?php else: ?>
              <span style="color:#9ca3af;">—</span>
            <?php endif; ?>
          </td>
          <td style="color:var(--text-muted);">
            <?= $f['limit'] !== null ? esc($f['limit']) : '—' ?>
          </td>
          <td>
            <span class="badge badge-<?= $f['is_active'] ? 'active' : 'inactive' ?> rounded-pill px-3">
              <?= $f['is_active'] ? 'Active' : 'Inactive' ?>
            </span>
          </td>
          <td>
            <div class="d-flex gap-2">
              <a href="<?= base_url('admin/features/edit/' . $f['id']) ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                      data-action="<?= base_url('admin/features/delete/' . $f['id']) ?>"
                      data-label="<?= esc($f['name']) ?>">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?= $this->include('admin/partials/delete_modal') ?>

<script>
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('deleteForm').action = btn.dataset.action;
      document.getElementById('deleteLabel').textContent = btn.dataset.label;
      new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
  });
</script>

<?= $this->endSection() ?>
