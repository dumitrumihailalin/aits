<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'invoices'; ?>
<?php $breadcrumb = 'Create Invoice'; ?>

<?= $this->section('content') ?>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/invoices') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title">Create Invoice</div>
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

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h5 style="font-weight:700;margin-bottom:24px;">Invoice Details</h5>

        <form action="<?= base_url('admin/invoices/store') ?>" method="POST">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Customer</label>
            <select name="user_id" class="form-select" required>
              <option value="">— Select customer —</option>
              <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['id'] ?>" <?= old('user_id') == $customer['id'] ? 'selected' : '' ?>>
                  <?= esc($customer['name']) ?>
                  <?php if ($customer['company_name']): ?> — <?= esc($customer['company_name']) ?><?php endif; ?>
                  (<?= esc($customer['email']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:14px;">Description</label>
            <textarea name="description" class="form-control" rows="3"
                      placeholder="What is this invoice for?" required><?= old('description') ?></textarea>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Amount</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="amount" class="form-control" step="0.01" min="0"
                       value="<?= old('amount') ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:14px;">Due Date</label>
              <input type="date" name="due_date" class="form-control"
                     value="<?= old('due_date', date('Y-m-d', strtotime('+30 days'))) ?>" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold" style="font-size:14px;">Notes <span class="text-muted fw-normal">(optional)</span></label>
            <textarea name="notes" class="form-control" rows="2"
                      placeholder="Any additional notes..."><?= old('notes') ?></textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4"><i class="bi bi-receipt me-1"></i> Create Invoice</button>
            <a href="<?= base_url('admin/invoices') ?>" class="btn btn-outline-secondary px-4">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
