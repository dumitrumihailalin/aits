<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --brand:      #1877f2;
      --brand-dark: #1259c3;
      --body-bg:    #f0f2f5;
      --white:      #ffffff;
      --text:       #111827;
      --muted:      #6b7280;
      --border:     #e5eaf5;
    }
    *, *::before, *::after { box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; background: var(--body-bg); color: var(--text); margin: 0; }

    /* ── Navbar ── */
    .navbar {
      background: var(--white); border-bottom: 1px solid var(--border);
      padding: 0 32px; height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .navbar-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
    .brand-icon { width: 36px; height: 36px; background: var(--brand); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; }
    .brand-name { font-size: 18px; font-weight: 700; color: var(--text); }
    .brand-sub  { font-size: 10px; color: var(--muted); letter-spacing: .8px; text-transform: uppercase; }
    .nav-links  { display: flex; align-items: center; gap: 8px; }
    .nav-link-item { padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; color: var(--muted); text-decoration: none; transition: all .2s; }
    .nav-link-item:hover { background: var(--body-bg); color: var(--text); }
    .btn-nav-login    { padding: 8px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid var(--border); color: var(--text); text-decoration: none; background: var(--white); }
    .btn-nav-login:hover { border-color: var(--brand); color: var(--brand); }
    .btn-nav-register { padding: 8px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; background: var(--brand); color: #fff; text-decoration: none; }
    .btn-nav-register:hover { background: var(--brand-dark); color: #fff; }

    /* ── Breadcrumb ── */
    .breadcrumb-bar { background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 32px; }
    .breadcrumb-bar a { color: var(--brand); text-decoration: none; font-size: 14px; }
    .breadcrumb-bar span { color: var(--muted); font-size: 14px; margin: 0 6px; }

    /* ── Product hero ── */
    .product-hero {
      background: var(--brand); padding: 56px 32px; text-align: center;
    }
    .product-hero-icon {
      width: 72px; height: 72px; border-radius: 18px;
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 34px; color: #fff; margin-bottom: 20px;
      background: rgba(255,255,255,.18);
    }
    .product-hero h1 { font-size: clamp(24px, 4vw, 36px); font-weight: 700; color: #fff; margin-bottom: 12px; }
    .product-hero p  { font-size: 16px; color: rgba(255,255,255,.85); max-width: 600px; margin: 0 auto; line-height: 1.7; }

    /* ── Main layout ── */
    .page-wrap { max-width: 1080px; margin: 48px auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 340px; gap: 32px; }
    @media (max-width: 768px) { .page-wrap { grid-template-columns: 1fr; } }

    /* ── Features list ── */
    .features-section h2 { font-size: 20px; font-weight: 700; margin-bottom: 24px; }
    .feature-card {
      background: var(--white); border: 1px solid var(--border); border-radius: 14px;
      padding: 20px 24px; margin-bottom: 16px;
      display: flex; align-items: flex-start; gap: 16px;
    }
    .feature-icon {
      width: 42px; height: 42px; min-width: 42px; border-radius: 10px;
      background: #e8f0fe; display: flex; align-items: center; justify-content: center;
      font-size: 18px; color: var(--brand);
    }
    .feature-name { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
    .feature-desc { font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 8px; }
    .feature-meta { display: flex; gap: 12px; flex-wrap: wrap; }
    .feature-badge {
      font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px;
      background: #f3f4f6; color: var(--muted);
    }
    .feature-badge.price { background: #e8f0fe; color: var(--brand); }
    .empty-features { text-align: center; color: var(--muted); padding: 48px 0; font-size: 15px; }

    /* ── Sidebar ── */
    .sidebar-card {
      background: var(--white); border: 1px solid var(--border); border-radius: 16px;
      padding: 28px; position: sticky; top: 80px;
    }
    .sidebar-product-icon {
      width: 56px; height: 56px; border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 26px; color: #fff; margin-bottom: 16px;
    }
    .sidebar-name  { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .sidebar-desc  { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }
    .price-row     { display: flex; align-items: flex-end; gap: 4px; margin-bottom: 20px; border-top: 1px solid var(--border); padding-top: 16px; }
    .price-currency { font-size: 16px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
    .price-amount   { font-size: 36px; font-weight: 700; color: var(--brand); line-height: 1; }
    .price-label    { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .btn-get-started {
      display: block; text-align: center; background: var(--brand); color: #fff;
      padding: 13px; border-radius: 10px; font-size: 15px; font-weight: 700;
      text-decoration: none; transition: background .2s; margin-bottom: 10px;
    }
    .btn-get-started:hover { background: var(--brand-dark); color: #fff; }
    .sidebar-note { font-size: 12px; color: var(--muted); text-align: center; line-height: 1.5; }

    /* ── Footer ── */
    .footer { background: var(--white); border-top: 1px solid var(--border); padding: 24px 32px; text-align: center; margin-top: 64px; }
    .footer p { font-size: 13px; color: var(--muted); margin: 0; }
    .footer a { color: var(--brand); text-decoration: none; }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a href="<?= base_url('/') ?>" class="navbar-brand">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </a>
  <div class="nav-links">
    <a href="<?= base_url('/') ?>#products" class="nav-link-item">Products</a>
    <a href="<?= base_url('/') ?>#why-us"   class="nav-link-item">Why Us</a>
    <a href="<?= base_url('/') ?>#contact"  class="nav-link-item">Contact</a>
    <?php if (session()->get('isLoggedIn')): ?>
      <a href="<?= base_url('dashboard') ?>" class="btn-nav-login">Dashboard</a>
      <a href="<?= base_url('logout') ?>"    class="btn-nav-register">Logout</a>
    <?php else: ?>
      <a href="<?= base_url('login') ?>"    class="btn-nav-login">Sign In</a>
      <a href="<?= base_url('register') ?>" class="btn-nav-register">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<!-- BREADCRUMB -->
<div class="breadcrumb-bar">
  <a href="<?= base_url('/') ?>">Home</a>
  <span>›</span>
  <a href="<?= base_url('/') ?>#products">Products</a>
  <span>›</span>
  <span style="color: var(--text); font-weight: 500;"><?= esc($product['name']) ?></span>
</div>

<!-- PRODUCT HERO -->
<div class="product-hero">
  <div class="product-hero-icon"><i class="<?= esc($product['icon']) ?>"></i></div>
  <h1><?= esc($product['name']) ?></h1>
  <p><?= esc($product['short_description'] ?: $product['description']) ?></p>
</div>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('success')): ?>
<div style="max-width:1080px;margin:20px auto 0;padding:0 24px;">
  <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;border-radius:10px;padding:12px 18px;font-size:14px;font-weight:500;display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <span><i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?></span>
    <a href="<?= base_url('dashboard') ?>" style="font-size:13px;font-weight:600;color:#166534;white-space:nowrap;text-decoration:underline;">
      View in Dashboard &rarr;
    </a>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('info')): ?>
<div style="max-width:1080px;margin:20px auto 0;padding:0 24px;">
  <div style="background:#dbeafe;border:1px solid #93c5fd;color:#1e40af;border-radius:10px;padding:12px 18px;font-size:14px;font-weight:500;display:flex;align-items:center;justify-content:space-between;gap:12px;">
    <span><i class="bi bi-info-circle-fill me-2"></i><?= esc(session()->getFlashdata('info')) ?></span>
    <a href="<?= base_url('cart') ?>" style="font-size:13px;font-weight:600;color:#1e40af;white-space:nowrap;text-decoration:underline;">Go to Basket &rarr;</a>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div style="max-width:1080px;margin:20px auto 0;padding:0 24px;">
  <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:10px;padding:12px 18px;font-size:14px;font-weight:500;">
    <i class="bi bi-exclamation-circle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
  </div>
</div>
<?php endif; ?>

<!-- MAIN CONTENT -->
<div class="page-wrap">

  <!-- Features -->
  <div class="features-section">
    <h2>What's included</h2>

    <?php if (empty($product['features'])): ?>
      <div class="empty-features">
        <i class="bi bi-box" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
        No features listed yet.
      </div>
    <?php else: ?>
      <?php foreach ($product['features'] as $feature): ?>
      <div class="feature-card">
        <div class="feature-icon"><i class="bi bi-check2-circle"></i></div>
        <div style="flex: 1;">
          <div class="feature-name"><?= esc($feature['name']) ?></div>
          <?php if ($feature['description']): ?>
            <div class="feature-desc"><?= esc($feature['description']) ?></div>
          <?php endif; ?>
          <div class="feature-meta">
            <?php if ($feature['price'] > 0): ?>
              <span class="feature-badge price">$<?= number_format($feature['price'], 2) ?></span>
            <?php endif; ?>
            <?php if ($feature['module_type']): ?>
              <span class="feature-badge"><?= esc(ucfirst($feature['module_type'])) ?></span>
            <?php endif; ?>
            <?php if ($feature['limit'] > 0): ?>
              <span class="feature-badge">Up to <?= esc($feature['limit']) ?> users</span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Sidebar -->
  <aside>
    <div class="sidebar-card">
      <div class="sidebar-product-icon" style="background: <?= esc($product['color']) ?>">
        <i class="<?= esc($product['icon']) ?>"></i>
      </div>
      <div class="sidebar-name"><?= esc($product['name']) ?></div>
      <div class="sidebar-desc"><?= esc($product['short_description'] ?: $product['description']) ?></div>

      <div class="price-row">
        <div class="price-currency">$</div>
        <div class="price-amount"><?= number_format($product['base_price'] ?? $product['price'], 0) ?></div>
        <div class="price-label">/ <?= esc($product['price_label'] ?: 'month') ?></div>
      </div>

      <?php if (session()->get('isLoggedIn')): ?>
        <?php if ($alreadyPurchased): ?>
          <!-- Already purchased — block the form entirely -->
          <div style="padding:12px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:14px;display:flex;align-items:center;gap:10px;">
            <i class="bi bi-patch-check-fill" style="color:#16a34a;font-size:18px;flex-shrink:0;"></i>
            <span style="font-size:14px;font-weight:500;color:var(--text);">Already Purchased</span>
            <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#16a34a;font-weight:600;margin-left:auto;">Active</span>
          </div>
          <button disabled class="btn-get-started" style="width:100%;border:none;cursor:not-allowed;opacity:0.55;">
            <i class="bi bi-lock-fill"></i> Purchased
          </button>
          <div class="sidebar-note">You already own this product. <a href="<?= base_url('invoices') ?>" style="color:var(--brand);">View your invoices →</a></div>
        <?php else: ?>
        <form action="<?= base_url('cart/save') ?>" method="POST" id="basketForm">
          <?= csrf_field() ?>
          <input type="hidden" name="product_id" value="<?= esc($product['id']) ?>">
          <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 0;border-top:1px solid var(--border);border-bottom:1px solid var(--border);margin-bottom:14px;">
            <input
              type="checkbox"
              id="addToBasket"
              name="add_to_basket"
              value="1"
              <?= $inCart ? 'checked' : '' ?>
              style="width:18px;height:18px;accent-color:var(--brand);cursor:pointer;flex-shrink:0;"
            >
            <span style="font-size:14px;font-weight:500;color:var(--text);">Add to Basket</span>
            <?php if ($inCart): ?>
              <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#16a34a;font-weight:600;margin-left:auto;">In Basket</span>
            <?php endif; ?>
          </label>
          <button type="submit" id="saveBtn" class="btn-get-started" style="width:100%;border:none;cursor:<?= $inCart ? 'not-allowed' : 'pointer' ?>;opacity:<?= $inCart ? '0.55' : '1' ?>;" <?= $inCart ? 'disabled' : '' ?>>
            <i class="bi bi-floppy"></i> Save
          </button>
        </form>
        <div class="sidebar-note">30-day money-back guarantee · No setup fees</div>
        <?php endif; ?>
      <?php else: ?>
        <a href="<?= base_url('register') ?>" class="btn-get-started">
          <i class="bi bi-building-check"></i> Get Started
        </a>
        <div class="sidebar-note">30-day money-back guarantee · No setup fees</div>
      <?php endif; ?>
    </div>
  </aside>
<script>
(function () {
    var cb  = document.getElementById('addToBasket');
    var btn = document.getElementById('saveBtn');
    if (!cb || !btn) return;
    var original = cb.checked;          // state saved in DB
    cb.addEventListener('change', function () {
        var changed = cb.checked !== original;
        btn.disabled = !changed;
        btn.style.opacity = changed ? '1' : '0.55';
        btn.style.cursor  = changed ? 'pointer' : 'not-allowed';
    });
})();
</script>

</div>

<!-- FOOTER -->
<footer class="footer">
  <p>
    © <?= date('Y') ?> AITS — Alin IT Services &nbsp;·&nbsp;
    <a href="<?= base_url('login') ?>">Sign In</a> &nbsp;·&nbsp;
    <a href="<?= base_url('register') ?>">Register</a> &nbsp;·&nbsp;
    <a href="mailto:customers@alinitservices.com">Contact</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
