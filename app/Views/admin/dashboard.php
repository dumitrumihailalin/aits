<?= $this->extend('layouts/admin') ?>

<?php $title     = 'Dashboard'; ?>
<?php $activeNav = 'dashboard'; ?>
<?php $breadcrumb = 'Dashboard'; ?>

<?= $this->section('content') ?>

<div class="page-header">
  <div>
    <div class="page-title">Dashboard</div>
    <div class="page-subtitle">Welcome back, <?= esc(session()->get('admin_name') ?? 'Admin') ?></div>
  </div>
  <a href="<?= base_url('admin/products/create') ?>" class="btn-aits btn-aits-primary">
    <i class="bi bi-plus-lg"></i> New Product
  </a>
</div>

<!-- Stat cards -->
<div class="stat-grid">

  <div class="stat-card">
    <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
    <div>
      <div class="stat-value"><?= $totalProducts ?></div>
      <div class="stat-label">Total Products</div>
      <?php if ($productsThisMonth > 0): ?>
        <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> <?= $productsThisMonth ?> this month</div>
      <?php else: ?>
        <div class="stat-delta" style="color:var(--text-muted);">No new this month</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
    <div>
      <div class="stat-value"><?= $totalCustomers ?></div>
      <div class="stat-label">Customers</div>
      <?php if ($customersThisMonth > 0): ?>
        <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> <?= $customersThisMonth ?> this month</div>
      <?php else: ?>
        <div class="stat-delta" style="color:var(--text-muted);">No new this month</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon amber"><i class="bi bi-receipt-cutoff"></i></div>
    <div>
      <div class="stat-value">$<?= number_format($totalRevenue, 2) ?></div>
      <div class="stat-label">Total Revenue</div>
      <?php if ($revenueChange !== null): ?>
        <div class="stat-delta <?= $revenueChange >= 0 ? 'up' : 'down' ?>">
          <i class="bi bi-arrow-<?= $revenueChange >= 0 ? 'up' : 'down' ?>-short"></i>
          <?= abs($revenueChange) ?>% vs last month
        </div>
      <?php elseif ($revenueThisMonth > 0): ?>
        <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> $<?= number_format($revenueThisMonth, 2) ?> this month</div>
      <?php else: ?>
        <div class="stat-delta" style="color:var(--text-muted);">No revenue this month</div>
      <?php endif; ?>
    </div>
  </div>

  <div class="stat-card">
    <div class="stat-icon purple"><i class="bi bi-headset"></i></div>
    <div>
      <div class="stat-value"><?= $openTickets ?></div>
      <div class="stat-label">Open Tickets</div>
      <?php if ($openTickets > 0): ?>
        <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> needs attention</div>
      <?php else: ?>
        <div class="stat-delta up"><i class="bi bi-check-circle"></i> all clear</div>
      <?php endif; ?>
    </div>
  </div>

</div>

<!-- Unpaid invoices -->
<div class="aits-card">
  <div class="aits-card-header">
    <span class="aits-card-title">
      Unpaid Invoices
      <?php if (!empty($recentInvoices)): ?>
        <span style="background:#fee2e2;color:#991b1b;border-radius:20px;padding:2px 10px;font-size:12px;font-weight:600;margin-left:8px;">
          <?= count($recentInvoices) ?>
        </span>
      <?php endif; ?>
    </span>
    <a href="<?= base_url('admin/invoices?status=unpaid') ?>" class="btn-aits btn-aits-ghost" style="padding:5px 12px;font-size:12px;">
      View all <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <table class="aits-table">
    <thead>
      <tr>
        <th>Invoice #</th>
        <th>Customer</th>
        <th>Amount</th>
        <th>Due Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($recentInvoices)): ?>
        <?php foreach ($recentInvoices as $inv): ?>
        <?php $overdue = $inv['due_date'] && strtotime($inv['due_date']) < time(); ?>
        <tr>
          <td style="font-family:var(--font-mono);font-weight:600;"><?= esc($inv['invoice_number']) ?></td>
          <td>
            <div style="font-weight:500;"><?= esc($inv['customer_name']) ?></div>
            <?php if ($inv['company_name']): ?>
              <div style="font-size:12px;color:var(--text-muted);"><?= esc($inv['company_name']) ?></div>
            <?php endif; ?>
          </td>
          <td style="font-weight:700;color:#1877f2;">$<?= number_format($inv['amount'], 2) ?></td>
          <td>
            <?php if ($inv['due_date']): ?>
              <span style="<?= $overdue ? 'color:#dc2626;font-weight:600;' : 'color:var(--text-muted);' ?>">
                <?= $overdue ? '⚠ ' : '' ?><?= date('M d, Y', strtotime($inv['due_date'])) ?>
              </span>
            <?php else: ?>
              <span style="color:var(--text-muted);">—</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= base_url('admin/invoices/' . $inv['id']) ?>" class="btn-aits btn-aits-ghost" style="padding:4px 10px;font-size:12px;">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px">
            <i class="bi bi-check-circle" style="font-size:24px;display:block;margin-bottom:8px;color:#10b981;"></i>
            All invoices are paid
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
