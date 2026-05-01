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
  <button class="btn-aits btn-aits-primary">
    <i class="bi bi-plus-lg"></i> New Product
  </button>
</div>

<!-- Stat cards -->
<div class="stat-grid">
  <div class="stat-card">
    <div class="stat-icon blue"><i class="bi bi-box-seam-fill"></i></div>
    <div>
      <div class="stat-value"><?= $totalProducts ?? 0 ?></div>
      <div class="stat-label">Total Products</div>
      <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> 3 this month</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
    <div>
      <div class="stat-value"><?= $totalCustomers ?? 0 ?></div>
      <div class="stat-label">Customers</div>
      <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> 12 this month</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon amber"><i class="bi bi-receipt-cutoff"></i></div>
    <div>
      <div class="stat-value">$<?= number_format($totalRevenue ?? 0, 2) ?></div>
      <div class="stat-label">Total Revenue</div>
      <div class="stat-delta up"><i class="bi bi-arrow-up-short"></i> 8% vs last month</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon purple"><i class="bi bi-headset"></i></div>
    <div>
      <div class="stat-value"><?= $openTickets ?? 0 ?></div>
      <div class="stat-label">Open Tickets</div>
      <div class="stat-delta down"><i class="bi bi-arrow-down-short"></i> needs attention</div>
    </div>
  </div>
</div>

<!-- Recent invoices -->
<div class="aits-card">
  <div class="aits-card-header">
    <span class="aits-card-title">Recent Invoices</span>
    <a href="<?= base_url('admin/invoices') ?>" class="btn-aits btn-aits-ghost" style="padding:5px 12px;font-size:12px;">
      View all <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <table class="aits-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Customer</th>
        <th>Product</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Date</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($recentInvoices)): ?>
        <?php foreach ($recentInvoices as $inv): ?>
        <tr>
          <td style="color:var(--text-muted);font-family:var(--font-mono)">#<?= $inv['id'] ?></td>
          <td><?= esc($inv['customer_name']) ?></td>
          <td><?= esc($inv['product_name']) ?></td>
          <td style="font-weight:500">$<?= number_format($inv['amount'], 2) ?></td>
          <td><span class="badge-status <?= $inv['status'] ?>"><?= ucfirst($inv['status']) ?></span></td>
          <td style="color:var(--text-muted)"><?= date('M d, Y', strtotime($inv['created_at'])) ?></td>
          <td>
            <a href="<?= base_url('admin/invoices/' . $inv['id']) ?>" class="btn-aits btn-aits-ghost" style="padding:4px 10px;font-size:12px;">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px">
            No invoices yet
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>