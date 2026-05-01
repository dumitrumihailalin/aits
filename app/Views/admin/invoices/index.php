<?= $this->extend('layouts/admin') ?>

<?php $activeNav = 'invoices'; ?>

<?= $this->section('content') ?>

<style>
  .status-tabs { display:flex;gap:4px;background:var(--card-bg);border:1px solid var(--card-border);border-radius:12px;padding:6px;margin-bottom:24px; }
  .status-tab { padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:6px;transition:all .15s; }
  .status-tab:hover { background:rgba(255,255,255,.06);color:var(--text-primary); }
  .status-tab.active { background:var(--brand-accent);color:#fff; }
  .status-tab .count { background:rgba(0,0,0,.15);border-radius:20px;padding:1px 8px;font-size:11px; }
  .status-tab.active .count { background:rgba(255,255,255,.25); }
  .badge-paid   { background:#d1fae5;color:#065f46; }
  .badge-unpaid { background:#fee2e2;color:#991b1b; }
</style>

<div class="page-header">
  <div>
    <div class="page-title">Invoices</div>
  </div>
  <a href="<?= base_url('admin/invoices/create') ?>" class="btn-aits btn-aits-primary">
    <i class="bi bi-plus-circle"></i> New Invoice
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

<div class="status-tabs">
  <a href="<?= base_url('admin/invoices') ?>" class="status-tab <?= $status === 'all'    ? 'active' : '' ?>">All <span class="count"><?= $counts['all'] ?></span></a>
  <a href="<?= base_url('admin/invoices?status=unpaid') ?>" class="status-tab <?= $status === 'unpaid' ? 'active' : '' ?>">🔴 Unpaid <span class="count"><?= $counts['unpaid'] ?></span></a>
  <a href="<?= base_url('admin/invoices?status=paid') ?>"   class="status-tab <?= $status === 'paid'   ? 'active' : '' ?>">🟢 Paid <span class="count"><?= $counts['paid'] ?></span></a>
</div>

<div class="card border-0 shadow-sm rounded-3">
  <div class="card-body p-0">
    <?php if (empty($invoices)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-receipt" style="font-size:40px;"></i>
        <p class="mt-3">No invoices found.</p>
      </div>
    <?php else: ?>
    <table class="table table-hover mb-0">
      <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
        <tr>
          <th class="ps-4 py-3">Invoice #</th>
          <th>Customer</th>
          <th>Description</th>
          <th>Amount</th>
          <th>Due Date</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <tbody style="font-size:14px;">
        <?php foreach ($invoices as $invoice): ?>
        <tr>
          <td class="ps-4"><span style="font-weight:600;"><?= esc($invoice['invoice_number']) ?></span></td>
          <td>
            <div style="font-weight:600;"><?= esc($invoice['user_name']) ?></div>
            <div style="font-size:12px;color:var(--text-muted);"><?= esc($invoice['company_name'] ?? $invoice['user_email']) ?></div>
          </td>
          <td style="font-size:13px;color:var(--text-muted);max-width:200px;"><?= esc(substr($invoice['description'] ?? '', 0, 50)) ?>...</td>
          <td style="font-weight:700;">$<?= number_format($invoice['amount'], 2) ?></td>
          <td style="font-size:13px;color:var(--text-muted);"><?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?></td>
          <td><span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3"><?= ucfirst($invoice['status']) ?></span></td>
          <td><a href="<?= base_url('admin/invoices/' . $invoice['id']) ?>" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>
