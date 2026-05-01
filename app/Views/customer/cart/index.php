<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AITS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        #sidebar { width:250px;min-height:100vh;background:#1877f2;position:fixed;top:0;left:0;z-index:100;transition:left .3s; }
        #sidebar .brand { padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.15); }
        #sidebar .brand span { font-size:20px;font-weight:700;color:#fff; }
        #sidebar .brand small { font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;display:block; }
        #sidebar .nav-link { color:rgba(255,255,255,.75);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color:#fff;background:rgba(255,255,255,.15); }
        #main { margin-left:250px; }
        #topbar { background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99; }
        .content { padding:28px; }
        @media (max-width:768px) { #sidebar { left:-250px; } #sidebar.show { left:0; } #main { margin-left:0; } }
        .product-row { background:#fff;border:1px solid #e5eaf5;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:16px;margin-bottom:12px; }
        .product-icon-sm { width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;flex-shrink:0; }
    </style>
</head>
<body>
<?php $cartCount = count($items); ?>

<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<div id="sidebar">
    <div class="brand"><span>💻 AITS</span><small>Alin IT Services</small></div>
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= base_url('/') ?>#products" class="nav-link"><i class="bi bi-grid"></i> Products</a>
        <a href="<?= base_url('cart') ?>" class="nav-link active">
            <i class="bi bi-basket2"></i> Basket
            <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger rounded-pill ms-auto" style="font-size:11px;"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= base_url('invoices') ?>" class="nav-link"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="<?= base_url('support') ?>" class="nav-link"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('profile') ?>" class="nav-link"><i class="bi bi-person"></i> Profile</a>
        <hr style="border-color:rgba(255,255,255,.15);margin:12px 24px;">
        <a href="<?= base_url('logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>

<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;">My Basket</h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('cart') ?>" class="position-relative text-decoration-none" title="My Basket">
                <i class="bi bi-basket2" style="font-size:20px;color:#1877f2;"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <div style="font-size:14px;color:#6b7280;"><?= esc(session()->get('name')) ?></div>
        </div>
    </div>

    <div class="content">

        <?php foreach (['success','error','info'] as $f): ?>
            <?php if ($msg = session()->getFlashdata($f)): ?>
                <div class="alert alert-<?= $f === 'error' ? 'danger' : ($f === 'info' ? 'info' : 'success') ?> alert-dismissible fade show">
                    <?= esc($msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 style="font-size:18px;font-weight:700;color:#111827;margin:0;">Basket</h2>
                <p style="font-size:14px;color:#6b7280;margin:0;">Review your selected products before placing an order.</p>
            </div>
            <a href="<?= base_url('/') ?>#products" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add More
            </a>
        </div>

        <?php if (empty($items)): ?>
            <div class="text-center py-5" style="background:#fff;border:1px solid #e5eaf5;border-radius:16px;">
                <i class="bi bi-basket2" style="font-size:48px;color:#d1d5db;display:block;margin-bottom:16px;"></i>
                <p style="font-size:16px;font-weight:600;color:#374151;">Your basket is empty</p>
                <p style="font-size:14px;color:#6b7280;margin-bottom:24px;">Browse our products and add something you like.</p>
                <a href="<?= base_url('/') ?>#products" class="btn btn-primary">Browse Products</a>
            </div>
        <?php else: ?>

            <div class="row g-4">
                <div class="col-lg-8">

                    <?php foreach ($items as $item): ?>
                    <div class="product-row">
                        <div class="product-icon-sm" style="background:<?= esc($item['color'] ?: '#1877f2') ?>">
                            <i class="<?= esc($item['icon'] ?: 'bi-box-seam') ?>"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:15px;font-weight:600;color:#111827;"><?= esc($item['product_name']) ?></div>
                            <?php if ($item['short_description']): ?>
                                <div style="font-size:13px;color:#6b7280;"><?= esc($item['short_description']) ?></div>
                            <?php endif; ?>
                        </div>
                        <div style="font-size:18px;font-weight:700;color:#1877f2;white-space:nowrap;">
                            $<?= number_format($item['price'], 2) ?><span style="font-size:12px;color:#6b7280;font-weight:400;">/mo</span>
                        </div>
                        <form action="<?= base_url('cart/remove/' . $item['product_id']) ?>" method="POST" class="ms-3">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                    <?php endforeach; ?>

                </div>

                <div class="col-lg-4">
                    <div style="background:#fff;border:1px solid #e5eaf5;border-radius:16px;padding:24px;position:sticky;top:80px;">
                        <h3 style="font-size:16px;font-weight:700;color:#111827;margin-bottom:20px;">Order Summary</h3>

                        <?php $total = array_sum(array_column($items, 'price')); ?>

                        <div class="d-flex justify-content-between mb-2" style="font-size:14px;color:#6b7280;">
                            <span><?= count($items) ?> product<?= count($items) > 1 ? 's' : '' ?></span>
                            <span>$<?= number_format($total, 2) ?>/mo</span>
                        </div>
                        <hr style="border-color:#e5eaf5;">
                        <div class="d-flex justify-content-between mb-4" style="font-size:16px;font-weight:700;color:#111827;">
                            <span>Total</span>
                            <span style="color:#1877f2;">$<?= number_format($total, 2) ?></span>
                        </div>

                        <form action="<?= base_url('cart/checkout') ?>" method="POST">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary w-100 py-2" style="font-weight:700;">
                                <i class="bi bi-bag-check me-2"></i> Place Order
                            </button>
                        </form>
                        <p class="mt-2 text-center" style="font-size:12px;color:#9ca3af;">
                            You'll receive an invoice after placing the order.
                        </p>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('show');
    o.style.display = s.classList.contains('show') ? 'block' : 'none';
}
</script>
</body>
</html>
