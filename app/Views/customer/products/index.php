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

        #sidebar {
            width: 250px; min-height: 100vh; background: #1877f2;
            position: fixed; top: 0; left: 0; z-index: 100; transition: all 0.3s;
        }
        #sidebar .sidebar-brand { padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,.15); }
        #sidebar .sidebar-brand span { font-size: 20px; font-weight: 700; color: #fff; }
        #sidebar .sidebar-brand small { font-size: 10px; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; display: block; }
        #sidebar .nav-link { color: rgba(255,255,255,.75); padding: 10px 24px; border-radius: 0; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        #sidebar .nav-link i { font-size: 16px; }

        #main { margin-left: 250px; min-height: 100vh; display: flex; flex-direction: column; }

        #topbar {
            background: #fff; border-bottom: 1px solid #e5eaf5; padding: 12px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 99;
        }
        #topbar .page-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
        #topbar .avatar { width: 36px; height: 36px; background: #1877f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 700; }

        .content { padding: 28px; flex: 1; }

        .product-card {
            background: #fff; border: 2px solid #e5eaf5; border-radius: 14px;
            padding: 24px; cursor: pointer; transition: border-color .15s, box-shadow .15s;
            position: relative;
        }
        .product-card:hover { border-color: #93c5fd; box-shadow: 0 4px 16px rgba(24,119,242,.08); }
        .product-card.selected { border-color: #1877f2; box-shadow: 0 4px 16px rgba(24,119,242,.15); }
        .product-card.locked { opacity: .75; cursor: default; }

        .product-icon {
            width: 52px; height: 52px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: #fff; margin-bottom: 16px;
        }
        .product-name  { font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px; }
        .product-desc  { font-size: 13px; color: #6b7280; line-height: 1.5; margin-bottom: 16px; }
        .product-price { font-size: 22px; font-weight: 700; color: #1877f2; }
        .product-price small { font-size: 13px; font-weight: 400; color: #6b7280; }

        .card-checkbox {
            position: absolute; top: 18px; right: 18px;
            width: 22px; height: 22px; accent-color: #1877f2; cursor: pointer;
        }
        .active-badge {
            display: inline-block; font-size: 11px; font-weight: 600;
            padding: 3px 10px; border-radius: 20px;
            background: #dcfce7; color: #16a34a; margin-top: 8px;
        }

        .save-bar {
            background: #fff; border-top: 1px solid #e5eaf5;
            padding: 16px 28px; display: flex; align-items: center; justify-content: flex-end; gap: 12px;
        }

        @media (max-width: 768px) {
            #sidebar { left: -250px; }
            #sidebar.show { left: 0; }
            #main { margin-left: 0; }
        }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<?= view('partials/customer_sidebar', ['activeNav' => 'products', 'cartCount' => $cartCount]) ?>

<!-- Main -->
<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h1 class="page-title"><?= lang('Customer.page_products') ?></h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="avatar"><?= strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?></div>
            <div>
                <div style="font-size:14px;font-weight:600;color:#111827;"><?= esc(session()->get('name') ?? 'Customer') ?></div>
            </div>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div style="margin:20px 28px 0;">
        <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;border-radius:10px;padding:12px 18px;font-size:14px;font-weight:500;">
            <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        </div>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('cart/save-all') ?>" method="POST" id="productsForm">
        <?= csrf_field() ?>

        <div class="content">
            <div class="mb-4">
                <p style="font-size:14px;color:#6b7280;margin:0;">
                    <?= lang('Customer.products_hint') ?>
                </p>
            </div>

            <div class="row g-3">
                <?php foreach ($products as $p): ?>
                <?php
                    $isActive  = in_array((int)$p['id'], $activeIds);
                    $isChecked = in_array((int)$p['id'], $userIds);
                ?>
                <div class="col-sm-6 col-lg-4">
                    <label class="product-card <?= $isChecked ? 'selected' : '' ?> <?= $isActive ? 'locked' : '' ?>"
                           for="product_<?= $p['id'] ?>"
                           onclick="toggleCard(this)">

                        <input
                            type="checkbox"
                            class="card-checkbox"
                            id="product_<?= $p['id'] ?>"
                            name="products[]"
                            value="<?= esc($p['id']) ?>"
                            <?= $isChecked ? 'checked' : '' ?>
                            <?= $isActive  ? 'disabled' : '' ?>
                            onclick="event.stopPropagation()"
                            onchange="syncCard(this)"
                        >
                        <?php if ($isActive): ?>
                            <!-- Hidden field so active products always get submitted -->
                            <input type="hidden" name="products[]" value="<?= esc($p['id']) ?>">
                        <?php endif; ?>

                        <div class="product-icon" style="background:<?= esc($p['color'] ?? '#1877f2') ?>">
                            <i class="bi <?= esc($p['icon'] ?? 'bi-box-seam') ?>"></i>
                        </div>
                        <div class="product-name"><?= esc($p['name']) ?></div>
                        <div class="product-desc"><?= esc($p['short_description'] ?? '') ?></div>
                        <div class="product-price">
                            $<?= number_format($p['price'], 0) ?>
                            <small>/ <?= esc($p['price_label'] ?? 'month') ?></small>
                        </div>
                        <?php if ($isActive): ?>
                            <div><span class="active-badge"><i class="bi bi-check-circle-fill me-1"></i><?= lang('Customer.status_active') ?></span></div>
                        <?php elseif ($isChecked): ?>
                            <div><span class="active-badge" style="background:#dbeafe;color:#1d4ed8;"><?= lang('Customer.status_in_basket') ?></span></div>
                        <?php endif; ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Save bar -->
        <div class="save-bar">
            <span style="font-size:13px;color:#6b7280;" id="selCount"></span>
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-floppy me-2"></i><?= lang('Customer.products_save') ?>
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleCard(label) {
    const cb = label.querySelector('input[type="checkbox"]:not([disabled])');
    if (! cb) return;
    cb.checked = ! cb.checked;
    syncCard(cb);
}
function syncCard(cb) {
    const label = cb.closest('.product-card');
    if (cb.checked) {
        label.classList.add('selected');
    } else {
        label.classList.remove('selected');
    }
    updateCount();
}
function updateCount() {
    const checked = document.querySelectorAll('input[type="checkbox"]:checked').length;
    document.getElementById('selCount').textContent = checked + ' product' + (checked !== 1 ? 's' : '') + ' selected';
}
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('show');
    o.style.display = s.classList.contains('show') ? 'block' : 'none';
}
updateCount();
</script>
</body>
</html>
