<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AITS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f0f2f5; }
        #sidebar { width:250px;min-height:100vh;background:#111827;position:fixed;top:0;left:0;z-index:100; }
        #sidebar .brand { padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.1); }
        #sidebar .brand span { font-size:20px;font-weight:700;color:#fff; }
        #sidebar .brand small { font-size:10px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;display:block; }
        #sidebar .nav-link { color:rgba(255,255,255,.6);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color:#fff;background:rgba(255,255,255,.08); }
        #main { margin-left:250px; }
        #topbar { background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99; }
        .content { padding:28px; }
        .product-icon { width:38px;height:38px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;font-size:17px;color:#fff; }
    </style>
</head>
<body>
<div id="sidebar">
    <div class="brand"><span>💻 AITS</span><small>Admin Panel</small></div>
    <nav class="mt-3">
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= base_url('admin/customers') ?>" class="nav-link"><i class="bi bi-people"></i> Customers</a>
        <a href="<?= base_url('admin/products') ?>" class="nav-link"><i class="bi bi-box-seam"></i> Products</a>
        <a href="<?= base_url('admin/invoices') ?>" class="nav-link"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="<?= base_url('admin/cart') ?>" class="nav-link active"><i class="bi bi-cart3"></i> Cart</a>
        <a href="<?= base_url('admin/support') ?>" class="nav-link"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('admin/settings') ?>" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
        <hr style="border-color:rgba(255,255,255,.1);margin:12px 24px;">
        <a href="<?= base_url('admin/profile') ?>" class="nav-link"><i class="bi bi-person"></i> Profile</a>
        <a href="<?= base_url('admin/logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>

<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-2">
            <a href="<?= base_url('admin/cart') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;">Checkout</h1>
        </div>
        <span style="font-size:14px;color:#6b7280;"><?= esc(session()->get('name')) ?></span>
    </div>

    <div class="content">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row g-4 justify-content-center">

            <!-- Order form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 style="font-weight:700;color:#111827;margin-bottom:24px;">Order Details</h5>

                        <form action="<?= base_url('admin/cart/place-order') ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="font-size:14px;">Customer <span class="text-danger">*</span></label>
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
                                <div class="form-text">The invoice and email will be sent to this customer.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="font-size:14px;">Due Date <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control"
                                       value="<?= old('due_date', date('Y-m-d', strtotime('+30 days'))) ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="font-size:14px;">Notes <span class="text-muted fw-normal">(optional)</span></label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions or notes for the customer…"><?= old('notes') ?></textarea>
                            </div>

                            <!-- Order summary inside form -->
                            <div class="rounded-3 mb-4" style="background:#f8faff;border:1px solid #e5eaf5;padding:20px;">
                                <h6 style="font-size:13px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">Order Summary</h6>
                                <?php foreach ($cart as $item): ?>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="product-icon" style="background:<?= esc($item['color'] ?? '#1877f2') ?>">
                                        <i class="bi <?= esc($item['icon'] ?? 'bi-box-seam') ?>"></i>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-size:13px;font-weight:600;color:#111827;"><?= esc($item['product_name']) ?></div>
                                        <div style="font-size:12px;color:#6b7280;">Qty <?= (int) $item['quantity'] ?> &times; $<?= number_format($item['unit_price'], 2) ?></div>
                                    </div>
                                    <div style="font-size:14px;font-weight:700;color:#1877f2;">$<?= number_format($item['total_price'], 2) ?></div>
                                </div>
                                <?php endforeach; ?>
                                <div class="d-flex justify-content-between pt-3" style="border-top:1px solid #e5eaf5;">
                                    <span style="font-size:14px;font-weight:600;">Total</span>
                                    <span style="font-size:18px;font-weight:800;color:#1877f2;">$<?= number_format($total, 2) ?></span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 p-3 rounded-3 mb-4" style="background:#fef9c3;border:1px solid #fde047;">
                                <i class="bi bi-envelope-fill" style="font-size:20px;color:#92400e;flex-shrink:0;"></i>
                                <div style="font-size:13px;color:#92400e;">
                                    <strong>Email notification:</strong> An order confirmation email will be sent automatically to the selected customer.
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" style="padding:12px;font-size:15px;font-weight:700;">
                                <i class="bi bi-check-circle me-2"></i> Place Order &amp; Send Invoice
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
