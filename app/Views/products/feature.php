<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($feature['description'] ?: $feature['name']) ?>">
  <link rel="canonical" href="<?= base_url('products/' . esc($product['slug']) . '/features/' . esc($feature['id'])) ?>">
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
    .breadcrumb-bar .current { color: var(--text); font-size: 14px; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 220px; }

    /* ── Hero ── */
    .feature-hero { background: var(--brand); padding: 56px 32px; text-align: center; }
    .feature-hero-icon { width: 72px; height: 72px; border-radius: 18px; display: inline-flex; align-items: center; justify-content: center; font-size: 34px; color: #fff; margin-bottom: 20px; background: rgba(255,255,255,.18); }
    .feature-hero h1 { font-size: clamp(20px, 4vw, 32px); font-weight: 700; color: #fff; margin-bottom: 10px; }
    .feature-hero p  { font-size: 15px; color: rgba(255,255,255,.85); max-width: 580px; margin: 0 auto; line-height: 1.7; }

    /* ── Content ── */
    .page-wrap { max-width: 800px; margin: 40px auto; padding: 0 20px; }
    .content-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 36px; margin-bottom: 24px; }
    .content-card h2 { font-size: 17px; font-weight: 700; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }

    /* Meta grid */
    .meta-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; }
    .meta-item { background: #f8faff; border: 1px solid var(--border); border-radius: 10px; padding: 14px 16px; }
    .meta-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 4px; }
    .meta-value { font-size: 16px; font-weight: 700; color: var(--text); }
    .meta-value.brand { color: var(--brand); }

    /* Video embed */
    .video-wrap { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; }
    .video-wrap iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; border-radius: 12px; }

    /* Back button */
    .btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; background: var(--white); border: 1px solid var(--border); color: var(--text); text-decoration: none; transition: all .2s; margin-bottom: 24px; }
    .btn-back:hover { border-color: var(--brand); color: var(--brand); }

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
      .feature-hero { padding: 40px 16px; }
      .page-wrap { margin: 24px auto; padding: 0 16px; }
      .content-card { padding: 20px; }
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
  <a href="<?= base_url('products/' . esc($product['slug'])) ?>"><?= esc($product['name']) ?></a>
  <span class="sep">›</span>
  <span class="current"><?= esc($feature['name']) ?></span>
</nav>

<!-- HERO -->
<header class="feature-hero">
  <div class="feature-hero-icon"><i class="bi bi-puzzle-fill"></i></div>
  <h1><?= esc($feature['name']) ?></h1>
  <?php if ($feature['description']): ?>
    <p><?= esc($feature['description']) ?></p>
  <?php endif; ?>
</header>

<!-- CONTENT -->
<div class="page-wrap">

  <a href="<?= base_url('products/' . esc($product['slug'])) ?>" class="btn-back">
    <i class="bi bi-arrow-left"></i> Back to <?= esc($product['name']) ?>
  </a>

  <!-- Details -->
  <div class="content-card">
    <h2>Feature Details</h2>
    <div class="meta-grid">
      <?php if ($feature['price'] > 0): ?>
      <div class="meta-item">
        <div class="meta-label">Price</div>
        <div class="meta-value brand">$<?= number_format($feature['price'], 2) ?></div>
      </div>
      <?php endif; ?>

      <?php if ($feature['subscription_type']): ?>
      <div class="meta-item">
        <div class="meta-label">Billing</div>
        <div class="meta-value"><?= esc(ucfirst($feature['subscription_type'])) ?></div>
      </div>
      <?php endif; ?>

      <?php if ($feature['module_type']): ?>
      <div class="meta-item">
        <div class="meta-label">Module</div>
        <div class="meta-value"><?= esc(ucfirst($feature['module_type'])) ?></div>
      </div>
      <?php endif; ?>

      <?php if ($feature['limit'] > 0): ?>
      <div class="meta-item">
        <div class="meta-label">User Limit</div>
        <div class="meta-value">Up to <?= esc($feature['limit']) ?></div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Product context -->
  <div class="content-card">
    <h2>Part of <?= esc($product['name']) ?></h2>
    <p style="font-size:14px;color:var(--muted);line-height:1.7;margin-bottom:16px;">
      <?= esc($product['short_description'] ?: $product['description']) ?>
    </p>
    <a href="<?= base_url('products/' . esc($product['slug'])) ?>" style="display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:600;color:var(--brand);text-decoration:none;">
      View all features <i class="bi bi-arrow-right"></i>
    </a>
  </div>

  <?php if ($feature['video']): ?>
  <!-- Video -->
  <div class="content-card">
    <h2>Feature Demo</h2>
    <div class="video-wrap">
      <iframe src="https://www.youtube.com/embed/<?= esc($feature['video']) ?>" allowfullscreen loading="lazy"></iframe>
    </div>
  </div>
  <?php endif; ?>

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
