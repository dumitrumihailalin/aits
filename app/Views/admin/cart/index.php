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
        .product-icon { width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;flex-shrink:0; }
        .cart-badge { background:#dc2626;color:#fff;border-radius:50%;width:20px;height:20px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;position:absolute;top:-6px;right:-6px; }
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
        <a href="<?= base_url('admin/cart') ?>" class="nav-link active"><i class="bi bi-cart3"></i> Cart <?php if (!empty($cart)): ?><span style="background:#dc2626;color:#fff;border-radius:10px;padding:1px 7px;font-size:11px;margin-left:4px;"><?= count($cart) ?></span><?php endif; ?></a>
        <a href="<?= base_url('admin/support') ?>" class="nav-link"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('admin/settings') ?>" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
        <hr style="border-color:rgba(255,255,255,.1);margin:12px 24px;">
        <a href="<?= base_url('admin/profile') ?>" class="nav-link"><i class="bi bi-person"></i> Profile</a>
        <a href="<?= base_url('admin/logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>

<div id="main">
    <div id="topbar">
        <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;">Shopping Cart</h1>
        <div class="d-flex align-items-center gap-3">
            <span style="font-size:14px;color:#6b7280;"><?= esc(session()->get('name')) ?></span>
            <?php if (!empty($cart)): ?>
            <a href="<?= base_url('admin/cart/checkout') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-arrow-right-circle me-1"></i> Checkout (<?= count($cart) ?> item<?= count($cart) > 1 ? 's' : '' ?>)
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="content">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
                 style="background:#1877f2;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
                <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
                <span><?= session()->getFlashdata('success') ?></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4"><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Products list -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 style="font-size:15px;font-weight:700;color:#111827;margin:0;">Available Products</h5>
                        <p style="font-size:13px;color:#6b7280;margin:6px 0 0;">Click <strong>Add to Cart</strong> to add a product to the order.</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($products)): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-box-seam" style="font-size:40px;"></i>
                                <p class="mt-3">No active products available.</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($products as $product): ?>
                                <div class="col-12">
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8faff;border:1px solid #e5eaf5;">
                                        <div class="product-icon" style="background:<?= esc($product['color'] ?? '#1877f2') ?>">
                                            <i class="bi <?= esc($product['icon'] ?? 'bi-box-seam') ?>"></i>
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:14px;font-weight:600;color:#111827;"><?= esc($product['name']) ?></div>
                                            <?php if ($product['short_description']): ?>
                                                <div style="font-size:12px;color:#6b7280;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($product['short_description']) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div style="text-align:right;white-space:nowrap;">
                                            <div style="font-size:15px;font-weight:700;color:#1877f2;">$<?= number_format($product['base_price'] ?? $product['price'], 2) ?></div>
                                            <div style="font-size:11px;color:#6b7280;">/ <?= esc($product['price_label'] ?? 'month') ?></div>
                                        </div>
                                        <form action="<?= base_url('admin/cart/add/' . $product['id']) ?>" method="POST" style="flex-shrink:0;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-cart-plus"></i> Add
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Cart -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3" style="position:sticky;top:80px;">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                        <h5 style="font-size:15px;font-weight:700;color:#111827;margin:0;">
                            <i class="bi bi-cart3 me-2" style="color:#1877f2;"></i>
                            Order Cart
                            <?php if (!empty($cart)): ?>
                                <span class="badge rounded-pill ms-1" style="background:#1877f2;font-size:11px;"><?= count($cart) ?></span>
                            <?php endif; ?>
                        </h5>
                        <?php if (!empty($cart)): ?>
                        <form action="<?= base_url('admin/cart/clear') ?>" method="POST" onsubmit="return confirm('Clear the entire cart?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash3"></i> Clear</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4">
                        <?php if (empty($cart)): ?>
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-cart-x" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                                Cart is empty.<br>
                                <small>Add products from the list on the left.</small>
                            </div>
                        <?php else: ?>
                            <?php $cartTotal = 0; ?>
                            <?php foreach ($cart as $index => $item): ?>
                                <?php $cartTotal += $item['total_price']; ?>
                                <div class="d-flex align-items-start gap-3 mb-3 pb-3" style="border-bottom:1px solid #f0f2f5;">
                                    <div class="product-icon" style="background:<?= esc($item['color'] ?? '#1877f2') ?>;width:36px;height:36px;font-size:16px;">
                                        <i class="bi <?= esc($item['icon'] ?? 'bi-box-seam') ?>"></i>
                                    </div>
                                    <div style="flex:1;min-width:0;">
                                        <div style="font-size:13px;font-weight:600;color:#111827;"><?= esc($item['product_name']) ?></div>
                                        <div style="font-size:12px;color:#6b7280;">$<?= number_format($item['unit_price'], 2) ?> / <?= esc($item['price_label'] ?? 'month') ?></div>
                                        <!-- Qty update form -->
                                        <form action="<?= base_url('admin/cart/update') ?>" method="POST" class="d-flex align-items-center gap-2 mt-2">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="index" value="<?= $index ?>">
                                            <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="1" max="99"
                                                   style="width:60px;" class="form-control form-control-sm text-center">
                                            <button type="submit" class="btn btn-outline-secondary btn-sm py-0 px-2">
                                                <i class="bi bi-arrow-repeat" style="font-size:12px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div style="text-align:right;flex-shrink:0;">
                                        <div style="font-size:14px;font-weight:700;color:#1877f2;">$<?= number_format($item['total_price'], 2) ?></div>
                                        <form action="<?= base_url('admin/cart/remove/' . $index) ?>" method="POST" class="mt-1">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm p-0" style="color:#dc2626;font-size:13px;" title="Remove">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Total -->
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top:2px solid #e5eaf5;">
                                <span style="font-size:14px;font-weight:600;color:#6b7280;">Total</span>
                                <span style="font-size:20px;font-weight:800;color:#1877f2;">$<?= number_format($cartTotal, 2) ?></span>
                            </div>

                            <a href="<?= base_url('admin/cart/checkout') ?>" class="btn btn-primary w-100 mt-4">
                                <i class="bi bi-arrow-right-circle me-2"></i> Proceed to Checkout
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
