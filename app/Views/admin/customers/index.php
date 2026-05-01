<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'customers'; ?>

<?= $this->section('content') ?>

<style>
  .avatar { width:36px;height:36px;border-radius:50%;background:#1877f2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;flex-shrink:0; }
  .badge-verified   { background:#d1fae5;color:#065f46; }
  .badge-unverified { background:#fee2e2;color:#991b1b; }
  .status-tabs { display:flex;gap:4px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:6px;margin-bottom:16px; }
  .status-tab { padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:all .15s; }
  .status-tab:hover { background:rgba(255,255,255,.06);color:var(--text-primary); }
  .status-tab.active { background:var(--brand-accent);color:#fff; }
  .status-tab .count { background:rgba(0,0,0,.15);border-radius:20px;padding:1px 8px;font-size:11px; }
  .status-tab.active .count { background:rgba(255,255,255,.25); }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Customers</div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
       style="background:#1877f2;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
    <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
    <span><?= session()->getFlashdata('success') ?></span>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
  </div>
<?php endif; ?>

<div class="status-tabs">
  <a href="<?= base_url('admin/customers') ?>" class="status-tab <?= $verified === 'all'      ? 'active' : '' ?>">All <span class="count"><?= $counts['all'] ?></span></a>
  <a href="<?= base_url('admin/customers?verified=verified') ?>" class="status-tab <?= $verified === 'verified' ? 'active' : '' ?>">✓ Verified <span class="count"><?= $counts['verified'] ?></span></a>
  <a href="<?= base_url('admin/customers?verified=pending') ?>"  class="status-tab <?= $verified === 'pending'  ? 'active' : '' ?>">⏳ Pending <span class="count"><?= $counts['pending'] ?></span></a>
</div>

<div class="card border-0 shadow-sm rounded-3 mb-4">
  <div class="card-body p-3">
    <form method="GET" action="<?= base_url('admin/customers') ?>">
      <?php if ($verified !== 'all'): ?>
        <input type="hidden" name="verified" value="<?= esc($verified) ?>">
      <?php endif; ?>
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Search by name, email or company..."
               value="<?= esc($search) ?>">
        <?php if ($search || $verified !== 'all'): ?>
          <a href="<?= base_url('admin/customers') ?>" class="btn btn-outline-secondary">Clear</a>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-0">
    <?php if (empty($customers)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-people" style="font-size:40px;"></i>
        <p class="mt-3">No customers found<?= $search ? ' for "' . esc($search) . '"' : '' ?>.</p>
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
        <tr>
          <th class="ps-4 py-3">Customer</th>
          <th>Company</th>
          <th>Phone</th>
          <th>Verified</th>
          <th>Joined</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="font-size:14px;">
        <?php foreach ($customers as $customer): ?>
        <tr>
          <td class="ps-4">
            <div class="d-flex align-items-center gap-3">
              <div class="avatar"><?= strtoupper(substr($customer['name'], 0, 1)) ?></div>
              <div>
                <div style="font-weight:600;"><?= esc($customer['name']) ?></div>
                <div style="font-size:12px;color:var(--text-muted);"><?= esc($customer['email']) ?></div>
              </div>
            </div>
          </td>
          <td style="color:var(--text-muted);"><?= esc($customer['company_name'] ?? '—') ?></td>
          <td style="color:var(--text-muted);"><?= esc($customer['phone'] ?? '—') ?></td>
          <td>
            <?php if ($customer['email_verified_at']): ?>
              <span class="badge badge-verified rounded-pill px-3">✓ Verified</span>
            <?php else: ?>
              <span class="badge badge-unverified rounded-pill px-3">Pending</span>
            <?php endif; ?>
          </td>
          <td style="font-size:13px;color:var(--text-muted);"><?= date('M d, Y', strtotime($customer['created_at'])) ?></td>
          <td>
            <a href="<?= base_url('admin/customers/' . $customer['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
