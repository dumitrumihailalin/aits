<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($product['short_description'] ?: $product['description']) ?>">
  <link rel="canonical" href="<?= base_url('products/' . \App\Models\ProductModel::urlSlug($product)) ?>">
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
    .site-nav { background: var(--white); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 200; }
    .nav-brand { display: flex; align-items: center; gap: 10px; text-decoration: none; flex-shrink: 0; }
    .brand-icon { width: 36px; height: 36px; background: var(--brand); border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; }
    .brand-name { font-size: 18px; font-weight: 700; color: var(--text); }
    .brand-sub  { font-size: 10px; color: var(--muted); letter-spacing: .8px; text-transform: uppercase; }
    .nav-links  { display: flex; align-items: center; gap: 4px; }
    .nav-auth   { display: flex; align-items: center; gap: 8px; margin-left: 8px; }
    .nav-link-item { padding: 8px 14px; border-radius: 8px; font-size: 14px; font-weight: 500; color: var(--muted); text-decoration: none; transition: all .2s; }
    .nav-link-item:hover { background: var(--body-bg); color: var(--text); }
    .nav-link-item.nav-active { color: var(--brand); font-weight: 600; }
    .btn-nav-login    { padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; border: 1px solid var(--border); color: var(--text); text-decoration: none; background: var(--white); transition: all .2s; }
    .btn-nav-login:hover { border-color: var(--brand); color: var(--brand); }
    .btn-nav-register { padding: 8px 18px; border-radius: 8px; font-size: 14px; font-weight: 600; background: var(--brand); color: #fff; text-decoration: none; transition: background .2s; }
    .btn-nav-register:hover { background: var(--brand-dark); color: #fff; }
    .hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; cursor: pointer; padding: 6px; border: none; background: none; border-radius: 6px; }
    .hamburger span { display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px; transition: transform .3s, opacity .3s; }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* ── Breadcrumb ── */
    .breadcrumb-bar { background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 32px; display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
    .breadcrumb-bar a    { color: var(--brand); text-decoration: none; font-size: 14px; }
    .breadcrumb-bar a:hover { text-decoration: underline; }
    .breadcrumb-bar .sep { color: var(--muted); font-size: 14px; }
    .breadcrumb-bar .current { color: var(--text); font-size: 14px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px; }

    /* ── Product hero ── */
    .product-hero { background: var(--brand); padding: 56px 32px; text-align: center; }
    .product-hero-icon { width: 72px; height: 72px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 34px; color: #fff; margin-bottom: 20px; background: rgba(255,255,255,.18); }
    .product-hero h1 { font-size: clamp(22px, 4vw, 36px); font-weight: 700; color: #fff; margin-bottom: 12px; }
    .product-hero p  { font-size: 16px; color: rgba(255,255,255,.85); max-width: 600px; margin: 0 auto; line-height: 1.7; }

    /* ── Flash messages ── */
    .flash-wrap { max-width: 1080px; margin: 20px auto 0; padding: 0 20px; }
    .flash-success { background: #1877f2; border: none; color: #000; border-radius: 10px; padding: 12px 16px; font-size: 14px; font-weight: 500; display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
    .flash-info    { background: #1877f2; border: none; color: #000; border-radius: 10px; padding: 12px 16px; font-size: 14px; font-weight: 500; display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
    .flash-error   { background: #dc2626; border: none; color: #fff; border-radius: 10px; padding: 12px 16px; font-size: 14px; font-weight: 500; }
    .flash-link    { font-size: 13px; font-weight: 600; text-decoration: underline; margin-left: auto; white-space: nowrap; }
    .flash-link:hover { opacity: .8; }

    /* ── Main layout ── */
    .page-wrap { max-width: 1080px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start; }

    /* ── Features ── */
    .features-section h2 { font-size: 20px; font-weight: 700; margin-bottom: 24px; }
    .feature-card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 20px; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 14px; }
    .feature-icon { width: 42px; height: 42px; min-width: 42px; border-radius: 10px; background: #e8f0fe; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--brand); }
    .feature-name { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
    .feature-desc { font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 8px; }
    .feature-meta { display: flex; gap: 8px; flex-wrap: wrap; }
    .feature-badge { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; background: #f3f4f6; color: var(--muted); }
    .feature-badge.price { background: #e8f0fe; color: var(--brand); }
    .empty-features { text-align: center; color: var(--muted); padding: 48px 0; font-size: 15px; }

    /* ── Sidebar ── */
    .sidebar-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 28px; position: sticky; top: 80px; }
    .sidebar-product-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; color: #fff; margin-bottom: 16px; }
    .sidebar-name { font-size: 18px; font-weight: 700; margin-bottom: 6px; }
    .sidebar-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }
    .price-row    { display: flex; align-items: flex-end; gap: 4px; margin-bottom: 20px; border-top: 1px solid var(--border); padding-top: 16px; }
    .price-currency { font-size: 16px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
    .price-amount   { font-size: 36px; font-weight: 700; color: var(--brand); line-height: 1; }
    .price-label    { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .btn-get-started { display: block; text-align: center; background: var(--brand); color: #fff; padding: 13px; border-radius: 10px; font-size: 15px; font-weight: 700; text-decoration: none; transition: background .2s; margin-bottom: 10px; width: 100%; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .btn-get-started:hover { background: var(--brand-dark); color: #fff; }
    .btn-get-started:disabled { opacity: .55; cursor: not-allowed; }
    .btn-secondary-action { width: 100%; background: transparent; border: 1px solid var(--border); border-radius: 8px; padding: 10px; font-size: 14px; font-weight: 500; color: var(--muted); cursor: pointer; font-family: 'DM Sans', sans-serif; transition: all .2s; }
    .btn-secondary-action:hover { border-color: var(--brand); color: var(--brand); }
    .sidebar-status { padding: 12px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); margin-bottom: 14px; display: flex; align-items: center; gap: 10px; }
    .sidebar-note { font-size: 12px; color: var(--muted); text-align: center; line-height: 1.5; margin-top: 10px; }
    .sidebar-note a { color: var(--brand); }

    /* ── Footer ── */
    .site-footer { background: var(--white); border-top: 1px solid var(--border); padding: 24px 32px; text-align: center; margin-top: 64px; }
    .site-footer p { font-size: 13px; color: var(--muted); margin: 0; }
    .site-footer a { color: var(--brand); text-decoration: none; }
    .site-footer a:hover { text-decoration: underline; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .site-nav { padding: 0 16px; height: 58px; }
      .hamburger { display: flex; }
      .nav-links { display: none; flex-direction: column; align-items: stretch; position: absolute; top: 58px; left: 0; right: 0; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 16px 16px; gap: 2px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
      .nav-links.open { display: flex; }
      .nav-auth { flex-direction: column; align-items: stretch; margin-left: 0; margin-top: 8px; padding-top: 10px; border-top: 1px solid var(--border); gap: 8px; }
      .nav-link-item { padding: 10px 14px; }
      .btn-nav-login, .btn-nav-register { text-align: center; padding: 11px 18px; }

      .breadcrumb-bar { padding: 10px 16px; }
      .product-hero { padding: 40px 16px; }
      .product-hero p { font-size: 15px; }

      .page-wrap { grid-template-columns: 1fr; margin: 24px auto; padding: 0 16px; gap: 20px; }
      .sidebar-card { position: static; }
      /* Show sidebar FIRST on mobile (price/action above features) */
      .page-wrap { display: flex; flex-direction: column; }
      .page-wrap aside { order: -1; }

      .site-footer { padding: 20px 16px; margin-top: 40px; }
    }
  </style>
</head>
<body>

<?= view('partials/public_nav', ['activeNav' => 'products']) ?>

<!-- BREADCRUMB -->
<nav class="breadcrumb-bar" aria-label="Breadcrumb">
  <a href="<?= base_url('/') ?>">Home</a>
  <span class="sep">›</span>
  <a href="<?= base_url('products') ?>">Products</a>
  <span class="sep">›</span>
  <span class="current"><?= esc($product['name']) ?></span>
</nav>

<!-- PRODUCT HERO -->
<header class="product-hero">
  <div class="product-hero-icon"><i class="<?= esc($product['icon']) ?>"></i></div>
  <h1><?= esc($product['name']) ?></h1>
  <p><?= esc($product['short_description'] ?: $product['description']) ?></p>
</header>

<!-- FLASH MESSAGES -->
<?php if (session()->getFlashdata('success')): ?>
<div class="flash-wrap">
  <div class="flash-success">
    <span><i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?></span>
    <a href="<?= base_url('dashboard') ?>" class="flash-link" style="color:#166534;">View in Dashboard →</a>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('info')): ?>
<div class="flash-wrap">
  <div class="flash-info">
    <span><i class="bi bi-info-circle-fill me-2"></i><?= esc(session()->getFlashdata('info')) ?></span>
    <a href="<?= base_url('cart') ?>" class="flash-link" style="color:#1e40af;">Go to Basket →</a>
  </div>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="flash-wrap">
  <div class="flash-error">
    <i class="bi bi-exclamation-circle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
  </div>
</div>
<?php endif; ?>

<!-- MAIN CONTENT -->
<div class="page-wrap">

  <!-- Sidebar (shows first on mobile) -->
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
          <div class="sidebar-status">
            <i class="bi bi-patch-check-fill" style="color:#16a34a;font-size:18px;flex-shrink:0;"></i>
            <span style="font-size:14px;font-weight:500;">Already Purchased</span>
            <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#16a34a;font-weight:600;margin-left:auto;">Active</span>
          </div>
          <button disabled class="btn-get-started">
            <i class="bi bi-lock-fill"></i> Purchased
          </button>
          <div class="sidebar-note">You already own this product. <a href="<?= base_url('invoices') ?>">View your invoices →</a></div>

        <?php elseif ($inCart): ?>
          <div class="sidebar-status">
            <i class="bi bi-cart-check-fill" style="color:#16a34a;font-size:18px;flex-shrink:0;"></i>
            <span style="font-size:14px;font-weight:500;">In your basket</span>
            <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#16a34a;font-weight:600;margin-left:auto;">Added</span>
          </div>
          <a href="<?= base_url('cart') ?>" class="btn-get-started">
            <i class="bi bi-cart3"></i> View Basket
          </a>
          <form action="<?= base_url('cart/remove/' . esc($product['id'])) ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn-secondary-action">
              <i class="bi bi-x-circle"></i> Remove from Basket
            </button>
          </form>
          <div class="sidebar-note">30-day money-back guarantee · No setup fees</div>

        <?php else: ?>
          <form action="<?= base_url('cart/add/' . esc($product['id'])) ?>" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn-get-started">
              <i class="bi bi-cart-plus"></i> Add to Basket
            </button>
          </form>
          <div class="sidebar-note">30-day money-back guarantee · No setup fees</div>
        <?php endif; ?>

      <?php else: ?>
        <?php $returnUrl = '/' . ltrim(str_replace(base_url(), '', current_url()), '/'); ?>
        <div class="sidebar-status">
          <i class="bi bi-lock-fill" style="color:var(--muted);font-size:18px;flex-shrink:0;"></i>
          <span style="font-size:14px;font-weight:500;">Sign in to purchase</span>
        </div>
        <a href="<?= base_url('login') ?>?redirect=<?= urlencode($returnUrl) ?>" class="btn-get-started">
          <i class="bi bi-box-arrow-in-right"></i> Sign In to Purchase
        </a>
        <a href="<?= base_url('register') ?>" style="display:block;text-align:center;font-size:13px;color:var(--muted);text-decoration:none;padding:8px;">
          No account? <span style="color:var(--brand);font-weight:600;">Register →</span>
        </a>
        <div class="sidebar-note">30-day money-back guarantee · No setup fees</div>
      <?php endif; ?>
    </div>
  </aside>

  <!-- Features -->
  <section class="features-section" aria-label="What's included">
    <h2>What's included</h2>

    <?php if (empty($product['features'])): ?>
      <div class="empty-features">
        <i class="bi bi-box" style="font-size:32px;display:block;margin-bottom:10px;"></i>
        No features listed yet.
      </div>
    <?php else: ?>
      <?php foreach ($product['features'] as $feature): ?>
      <div class="feature-card">
        <div class="feature-icon"><i class="bi bi-check2-circle"></i></div>
        <div style="flex:1;min-width:0;">
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
  </section>

</div>

<footer class="site-footer">
  <p>
    © <?= date('Y') ?> AITS — Alin IT Services &nbsp;·&nbsp;
    <a href="<?= base_url('products') ?>">Products</a> &nbsp;·&nbsp;
    <a href="<?= base_url('why-us') ?>">Why Us</a> &nbsp;·&nbsp;
    <a href="<?= base_url('contact') ?>">Contact</a> &nbsp;·&nbsp;
    <a href="<?= base_url('login') ?>">Sign In</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
