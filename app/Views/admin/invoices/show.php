<?= $this->extend('layouts/admin') ?>

<?php $activeNav  = 'invoices'; ?>
<?php $breadcrumb = esc($invoice['invoice_number']); ?>

<?= $this->section('content') ?>

<style>
  .invoice-box { background:#fff;border:1px solid #e5eaf5;border-radius:16px;padding:40px;color:#111827; }
  .badge-paid   { background:#d1fae5;color:#065f46; }
  .badge-unpaid { background:#fee2e2;color:#991b1b; }
</style>

<div class="page-header">
  <div style="display:flex;align-items:center;gap:10px;">
    <a href="<?= base_url('admin/invoices') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <div class="page-title"><?= esc($invoice['invoice_number']) ?></div>
    <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3"><?= ucfirst($invoice['status']) ?></span>
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

<div class="row g-4">

  <div class="col-lg-8">
    <div class="invoice-box shadow-sm">

      <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
          <h2 style="font-size:24px;font-weight:800;color:#1877f2;">💻 AITS</h2>
          <p style="font-size:13px;color:#6b7280;margin:0;">Alin IT Services<br>alinitservices.com</p>
        </div>
        <div class="text-end">
          <div style="font-size:20px;font-weight:700;color:#111827;"><?= esc($invoice['invoice_number']) ?></div>
          <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3 mt-1"><?= ucfirst($invoice['status']) ?></span>
        </div>
      </div>

      <hr style="border-color:#e5eaf5;">

      <div class="mb-4">
        <div style="font-size:12px;color:#6b7280;margin-bottom:6px;text-transform:uppercase;letter-spacing:.5px;">Bill To</div>
        <div style="font-size:15px;font-weight:600;color:#111827;"><?= esc($invoice['user_name']) ?></div>
        <?php if ($invoice['company_name']): ?>
          <div style="font-size:13px;color:#6b7280;"><?= esc($invoice['company_name']) ?></div>
        <?php endif; ?>
        <div style="font-size:13px;color:#6b7280;"><?= esc($invoice['user_email']) ?></div>
        <?php if ($invoice['address']): ?>
          <div style="font-size:13px;color:#6b7280;"><?= esc($invoice['address']) ?>, <?= esc($invoice['city']) ?>, <?= esc($invoice['country']) ?></div>
        <?php endif; ?>
      </div>

      <hr style="border-color:#e5eaf5;">

      <div class="row mb-4">
        <div class="col-4">
          <div style="font-size:12px;color:#6b7280;">Issue Date</div>
          <div style="font-size:14px;font-weight:600;color:#111827;"><?= $invoice['issue_date'] ? date('M d, Y', strtotime($invoice['issue_date'])) : date('M d, Y', strtotime($invoice['created_at'])) ?></div>
        </div>
        <div class="col-4">
          <div style="font-size:12px;color:#6b7280;">Due Date</div>
          <div style="font-size:14px;font-weight:600;color:#111827;"><?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?></div>
        </div>
        <div class="col-4">
          <div style="font-size:12px;color:#6b7280;">Paid Date</div>
          <div style="font-size:14px;font-weight:600;color:#111827;"><?= $invoice['paid_date'] ? date('M d, Y', strtotime($invoice['paid_date'])) : '—' ?></div>
        </div>
      </div>

      <div style="background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:16px 20px;margin-bottom:24px;">
        <table class="table table-sm mb-0">
          <thead style="font-size:12px;color:#6b7280;">
            <tr><th>Product / Service</th><th class="text-center">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Total</th></tr>
          </thead>
          <tbody>
            <?php if (!empty($items)): ?>
              <?php foreach ($items as $item): ?>
              <tr>
                <td style="font-size:14px;color:#111827;font-weight:500;"><?= esc($item['product_name']) ?>
                  <?php if ($item['description']): ?>
                    <div style="font-size:12px;color:#6b7280;font-weight:400;"><?= esc($item['description']) ?></div>
                  <?php endif; ?>
                </td>
                <td class="text-center" style="font-size:14px;color:#6b7280;"><?= (int) $item['quantity'] ?></td>
                <td class="text-end" style="font-size:14px;color:#6b7280;">$<?= number_format($item['unit_price'], 2) ?></td>
                <td class="text-end" style="font-size:14px;font-weight:600;color:#111827;">$<?= number_format($item['total_price'], 2) ?></td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="4" style="font-size:14px;color:#111827;"><?= esc($invoice['description'] ?? 'Service') ?></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($invoice['notes']): ?>
        <div style="font-size:13px;color:#6b7280;margin-bottom:24px;"><strong>Notes:</strong> <?= esc($invoice['notes']) ?></div>
      <?php endif; ?>

      <div style="background:#1877f2;border-radius:10px;padding:16px 24px;">
        <div class="d-flex justify-content-between align-items-center">
          <span style="color:rgba(255,255,255,.8);font-size:14px;">Total Due</span>
          <span style="color:#fff;font-size:24px;font-weight:800;">$<?= number_format($invoice['amount'], 2) ?></span>
        </div>
      </div>

    </div>
  </div>

  <div class="col-lg-4">

    <!-- Actions -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Actions</h6>
        <?php if ($invoice['status'] === 'unpaid'): ?>
          <form action="<?= base_url('admin/invoices/paid/' . $invoice['id']) ?>" method="POST" class="mb-2">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle me-1"></i> Mark as Paid</button>
          </form>
        <?php else: ?>
          <form action="<?= base_url('admin/invoices/unpaid/' . $invoice['id']) ?>" method="POST" class="mb-2">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline-warning w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Mark as Unpaid</button>
          </form>
        <?php endif; ?>
        <form action="<?= base_url('admin/invoices/delete/' . $invoice['id']) ?>" method="POST"
              onsubmit="return confirm('Delete this invoice?')">
          <?= csrf_field() ?>
          <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Delete Invoice</button>
        </form>
      </div>
    </div>

    <!-- Invoice summary -->
    <div class="card border-0 shadow-sm rounded-3 mb-3">
      <div class="card-body p-4">
        <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Invoice Info</h6>
        <table style="font-size:13px;width:100%;">
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Number</td><td class="text-end" style="font-weight:600;"><?= esc($invoice['invoice_number']) ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Amount</td><td class="text-end" style="font-weight:700;color:#1877f2;">$<?= number_format($invoice['total_amount'] ?? $invoice['amount'], 2) ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Status</td><td class="text-end"><span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3"><?= ucfirst($invoice['status']) ?></span></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Issue Date</td><td class="text-end"><?= $invoice['issue_date'] ? date('M d, Y', strtotime($invoice['issue_date'])) : '—' ?></td></tr>
          <tr><td style="color:var(--text-muted);padding-bottom:10px;">Due Date</td><td class="text-end"><?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?></td></tr>
          <?php if ($invoice['paid_date']): ?>
          <tr><td style="color:var(--text-muted);">Paid On</td><td class="text-end" style="color:#065f46;font-weight:600;"><?= date('M d, Y', strtotime($invoice['paid_date'])) ?></td></tr>
          <?php endif; ?>
        </table>
      </div>
    </div>

    <!-- Customer details — highlighted in green when paid -->
    <div class="card border-0 shadow-sm rounded-3 <?= $invoice['status'] === 'paid' ? 'border-success' : '' ?>"
         style="<?= $invoice['status'] === 'paid' ? 'border:2px solid #16a34a !important;' : '' ?>">
      <div class="card-body p-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <h6 style="font-size:13px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin:0;">Customer</h6>
          <?php if ($invoice['status'] === 'paid'): ?>
            <span class="badge rounded-pill px-2" style="background:#dcfce7;color:#16a34a;font-size:10px;">Paid</span>
          <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-3 mb-3">
          <div style="width:40px;height:40px;border-radius:50%;background:#1877f2;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;flex-shrink:0;">
            <?= strtoupper(mb_substr($invoice['user_name'], 0, 1)) ?>
          </div>
          <div>
            <div style="font-size:14px;font-weight:600;color:#111827;"><?= esc($invoice['user_name']) ?></div>
            <?php if ($invoice['company_name']): ?>
              <div style="font-size:12px;color:#6b7280;"><?= esc($invoice['company_name']) ?></div>
            <?php endif; ?>
          </div>
        </div>

        <table style="font-size:13px;width:100%;">
          <tr>
            <td style="color:var(--text-muted);padding-bottom:8px;vertical-align:top;">Email</td>
            <td class="text-end" style="padding-bottom:8px;">
              <a href="mailto:<?= esc($invoice['user_email']) ?>" style="color:#1877f2;font-weight:500;"><?= esc($invoice['user_email']) ?></a>
            </td>
          </tr>
          <?php if (!empty($invoice['phone'])): ?>
          <tr>
            <td style="color:var(--text-muted);padding-bottom:8px;">Phone</td>
            <td class="text-end" style="padding-bottom:8px;"><?= esc($invoice['phone']) ?></td>
          </tr>
          <?php endif; ?>
          <?php if (!empty($invoice['address'])): ?>
          <tr>
            <td style="color:var(--text-muted);padding-bottom:8px;vertical-align:top;">Address</td>
            <td class="text-end" style="padding-bottom:8px;">
              <?= esc($invoice['address']) ?><?= $invoice['city'] ? ', ' . esc($invoice['city']) : '' ?><?= $invoice['country'] ? ', ' . esc($invoice['country']) : '' ?>
            </td>
          </tr>
          <?php endif; ?>
          <?php if (!empty($invoice['customer_since'])): ?>
          <tr>
            <td style="color:var(--text-muted);">Customer since</td>
            <td class="text-end"><?= date('M d, Y', strtotime($invoice['customer_since'])) ?></td>
          </tr>
          <?php endif; ?>
        </table>

        <?php if ($invoice['status'] === 'paid'): ?>
        <div class="mt-3 p-2 rounded-2 text-center" style="background:#dcfce7;color:#16a34a;font-size:12px;font-weight:600;">
          <i class="bi bi-check-circle-fill me-1"></i> Payment received on <?= date('M d, Y', strtotime($invoice['paid_date'])) ?>
        </div>
        <?php endif; ?>

      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
