<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'products'; ?>
<?php $breadcrumb = 'Edit Product'; ?>

<?= $this->section('content') ?>

<style>
  .icon-preview { width:52px;height:52px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;color:#fff; }
</style>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/products') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title">Edit — <?= esc($product['name']) ?></div>
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

<form action="<?= base_url('admin/products/update/' . $product['id']) ?>" method="POST">
  <?= csrf_field() ?>

  <div class="row g-4">

    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Product Information</h5>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $product['name']) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Short Description</label>
            <input type="text" name="short_description" class="form-control"
                   value="<?= old('short_description', $product['short_description'] ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Full Description</label>
            <textarea name="description" class="form-control" rows="5"><?= old('description', $product['description'] ?? '') ?></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">YouTube URL <span class="text-muted fw-normal">(optional)</span></label>
            <input type="url" name="youtube_url" class="form-control"
                   value="<?= old('youtube_url', $product['youtube_url'] ?? '') ?>">
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Pricing</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Price</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="price" class="form-control" step="0.01" min="0"
                       value="<?= old('price', $product['price']) ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Price Label</label>
              <select name="price_label" class="form-select">
                <option value="month" <?= old('price_label', $product['price_label']) === 'month' ? 'selected' : '' ?>>/ month</option>
                <option value="year"  <?= old('price_label', $product['price_label']) === 'year'  ? 'selected' : '' ?>>/ year</option>
                <option value="once"  <?= old('price_label', $product['price_label']) === 'once'  ? 'selected' : '' ?>>one time</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">

      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Appearance</h5>
          <div class="d-flex align-items-center gap-3 mb-4">
            <div class="icon-preview" id="iconPreview" style="background:<?= esc($product['color'] ?? '#1877f2') ?>;">
              <i class="bi <?= esc($product['icon'] ?? 'bi-box-seam') ?>" id="iconEl"></i>
            </div>
            <div style="font-size:13px;color:var(--text-muted);">Live preview</div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Bootstrap Icon</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-grid"></i></span>
              <input type="text" name="icon" id="iconInput" class="form-control"
                     value="<?= old('icon', $product['icon'] ?? 'bi-box-seam') ?>">
            </div>
            <div class="form-text"><a href="https://icons.getbootstrap.com" target="_blank">Browse icons</a></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Color</label>
            <div class="input-group">
              <input type="color" name="color" id="colorInput" class="form-control form-control-color"
                     value="<?= old('color', $product['color'] ?? '#1877f2') ?>">
              <input type="text" id="colorText" class="form-control"
                     value="<?= old('color', $product['color'] ?? '#1877f2') ?>" readonly>
            </div>
          </div>
        </div>
      </div>

      <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
          <h5 style="font-weight:700;margin-bottom:20px;">Settings</h5>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Sort Order</label>
            <input type="number" name="sort_order" class="form-control"
                   value="<?= old('sort_order', $product['sort_order'] ?? 0) ?>" min="0">
          </div>
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                   <?= old('is_active', $product['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isActive" style="font-size:14px;">Active</label>
          </div>
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1"
                   <?= old('is_featured', $product['is_featured']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isFeatured" style="font-size:14px;">Featured</label>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Product</button>
        <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">Cancel</a>
      </div>

    </div>
  </div>
</form>

<script>
  const iconInput   = document.getElementById('iconInput');
  const colorInput  = document.getElementById('colorInput');
  const colorText   = document.getElementById('colorText');
  const iconEl      = document.getElementById('iconEl');
  const iconPreview = document.getElementById('iconPreview');

  iconInput.addEventListener('input', () => { iconEl.className = 'bi ' + iconInput.value; });
  colorInput.addEventListener('input', () => {
    iconPreview.style.background = colorInput.value;
    colorText.value = colorInput.value;
  });
</script>

<?= $this->endSection() ?>
