<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'customers'; ?>
<?php $breadcrumb = esc($customer['name']); ?>

<?= $this->section('content') ?>

<style>
  .avatar-lg { width:64px;height:64px;border-radius:50%;background:#1877f2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700; }
  .badge-paid        { background:#d1fae5;color:#065f46; }
  .badge-unpaid      { background:#fee2e2;color:#991b1b; }
  .badge-open        { background:#dbeafe;color:#1d4ed8; }
  .badge-in_progress { background:#fef3c7;color:#92400e; }
  .badge-resolved    { background:#d1fae5;color:#065f46; }
  .badge-closed      { background:#f3f4f6;color:#374151; }
</style>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/customers') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title"><?= esc($customer['name']) ?></div>
  </div>
</div>

<div class="row g-4">

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-4">
          <div class="avatar-lg"><?= strtoupper(substr($customer['name'], 0, 1)) ?></div>
          <div>
            <div style="font-size:16px;font-weight:700;"><?= esc($customer['name']) ?></div>
            <div style="font-size:13px;color:var(--text-muted);"><?= esc($customer['email']) ?></div>
            <?php if ($customer['email_verified_at']): ?>
              <span class="badge rounded-pill mt-1" style="background:#d1fae5;color:#065f46;">✓ Verified</span>
            <?php else: ?>
              <span class="badge rounded-pill mt-1" style="background:#fee2e2;color:#991b1b;">Unverified</span>
            <?php endif; ?>
          </div>
        </div>
        <table style="font-size:13px;width:100%;">
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Company</td><td class="text-end" style="font-weight:600;"><?= esc($customer['company_name'] ?? '—') ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Phone</td><td class="text-end"><?= esc($customer['phone'] ?? '—') ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Address</td><td class="text-end"><?= esc($customer['address'] ?? '—') ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">City</td><td class="text-end"><?= esc($customer['city'] ?? '—') ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Country</td><td class="text-end"><?= esc($customer['country'] ?? '—') ?></td></tr>
          <tr><td style="color:var(--text-muted);">Member since</td><td class="text-end"><?= date('M d, Y', strtotime($customer['created_at'])) ?></td></tr>
        </table>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Quick Stats</h6>
        <div class="d-flex justify-content-between mb-3">
          <span style="font-size:14px;color:var(--text-muted);">Total Invoices</span>
          <span style="font-weight:700;"><?= count($invoices) ?></span>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <span style="font-size:14px;color:var(--text-muted);">Unpaid</span>
          <span style="font-weight:700;color:#dc2626;"><?= count(array_filter($invoices, fn($i) => $i['status'] === 'unpaid')) ?></span>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <span style="font-size:14px;color:var(--text-muted);">Total Tickets</span>
          <span style="font-weight:700;"><?= count($tickets) ?></span>
        </div>
        <div class="d-flex justify-content-between">
          <span style="font-size:14px;color:var(--text-muted);">Open Tickets</span>
          <span style="font-weight:700;color:#1877f2;"><?= count(array_filter($tickets, fn($t) => $t['status'] === 'open')) ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">

    <div class="card border-0 shadow-sm rounded-3 mb-4">
      <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 style="font-size:15px;font-weight:700;margin:0;">Invoices</h5>
          <a href="<?= base_url('admin/invoices/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i> New Invoice
          </a>
        </div>
        <?php if (empty($invoices)): ?>
          <p class="text-muted text-center py-3">No invoices yet.</p>
        <?php else: ?>
        <table class="table table-sm table-hover mb-0">
          <thead style="font-size:12px;color:var(--text-muted);">
            <tr><th>Invoice #</th><th>Amount</th><th>Due Date</th><th>Status</th><th></th></tr>
          </thead>
          <tbody style="font-size:13px;">
            <?php foreach ($invoices as $invoice): ?>
            <tr>
              <td style="font-weight:600;"><?= esc($invoice['invoice_number']) ?></td>
              <td style="font-weight:700;color:#1877f2;">$<?= number_format($invoice['amount'], 2) ?></td>
              <td style="color:var(--text-muted);"><?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?></td>
              <td><span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-2"><?= ucfirst($invoice['status']) ?></span></td>
              <td><a href="<?= base_url('admin/invoices/' . $invoice['id']) ?>" class="btn btn-sm btn-outline-primary py-0">View</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
      <div class="card-body p-4">
        <h5 style="font-size:15px;font-weight:700;margin-bottom:16px;">Support Tickets</h5>
        <?php if (empty($tickets)): ?>
          <p class="text-muted text-center py-3">No tickets yet.</p>
        <?php else: ?>
        <table class="table table-sm table-hover mb-0">
          <thead style="font-size:12px;color:var(--text-muted);">
            <tr><th>#</th><th>Subject</th><th>Priority</th><th>Status</th><th></th></tr>
          </thead>
          <tbody style="font-size:13px;">
            <?php foreach ($tickets as $ticket): ?>
            <tr>
              <td style="color:var(--text-muted);">#<?= $ticket['id'] ?></td>
              <td style="font-weight:500;"><?= esc($ticket['subject']) ?></td>
              <td><?= ucfirst($ticket['priority']) ?></td>
              <td><span class="badge badge-<?= $ticket['status'] ?> rounded-pill px-2"><?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?></span></td>
              <td><a href="<?= base_url('admin/support/' . $ticket['id']) ?>" class="btn btn-sm btn-outline-primary py-0">View</a></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
