<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'features'; ?>
<?php $breadcrumb = 'Edit Feature'; ?>

<?= $this->section('content') ?>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/features') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title">Edit — <?= esc($feature['name']) ?></div>
  </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
  <div class="alert alert-danger mb-4">
    <ul class="mb-0">
      <?php foreach (session()->getFlashdata('errors') as $e): ?>
        <li><?= esc($e) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form action="<?= base_url('admin/features/update/' . $feature['id']) ?>" method="POST">
  <?= csrf_field() ?>

  <div class="row g-4">

    <div class="col-lg-8">

      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Feature Information</h5>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Product</label>
            <select name="product_id" class="form-select" required>
              <option value="">— Select product —</option>
              <?php foreach ($products as $product): ?>
                <option value="<?= esc($product['id']) ?>"
                  <?= old('product_id', $feature['product_id']) === $product['id'] ? 'selected' : '' ?>>
                  <?= esc($product['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Feature Name</label>
            <input type="text" name="name" class="form-control"
                   value="<?= old('name', $feature['name']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= old('description', $feature['description'] ?? '') ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Video URL <span class="text-muted fw-normal">(optional)</span></label>
            <input type="url" name="video" class="form-control"
                   placeholder="https://..." value="<?= old('video', $feature['video'] ?? '') ?>">
          </div>

        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Pricing</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Price <span class="text-muted fw-normal">(optional)</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="price" class="form-control" step="0.01" min="0"
                       value="<?= old('price', $feature['price'] ?? '') ?>">
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Subscription Type</label>
              <input type="text" name="subscription_type" class="form-control"
                     value="<?= old('subscription_type', $feature['subscription_type'] ?? '') ?>"
                     placeholder="e.g. monthly, yearly">
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="col-lg-4">

      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Settings</h5>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Module Type</label>
            <input type="text" name="module_type" class="form-control"
                   value="<?= old('module_type', $feature['module_type'] ?? '') ?>"
                   placeholder="e.g. core, addon">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Limit</label>
            <input type="number" name="limit" min="0" class="form-control"
                   value="<?= old('limit', $feature['limit'] ?? '') ?>"
                   placeholder="Leave blank for unlimited">
          </div>

          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                   <?= old('is_active', $feature['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isActive" style="font-size:14px;">Active</label>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Feature</button>
        <a href="<?= base_url('admin/features') ?>" class="btn btn-outline-secondary">Cancel</a>
        <hr class="my-1">
        <button type="button" class="btn btn-outline-danger btn-delete"
                data-action="<?= base_url('admin/features/delete/' . $feature['id']) ?>"
                data-label="<?= esc($feature['name']) ?>">
          <i class="bi bi-trash me-1"></i> Delete Feature
        </button>
      </div>

    </div>
  </div>
</form>

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
