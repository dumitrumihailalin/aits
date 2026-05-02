<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css" rel="stylesheet" />
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
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--body-bg);
      color: var(--text);
      margin: 0;
    }

    /* ── Navbar ─────────────────────────────────────── */
    .site-nav {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 200;
    }
    .nav-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; flex-shrink: 0;
    }
    .brand-icon {
      width: 36px; height: 36px;
      background: var(--brand);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; color: #fff;
    }
    .brand-name { font-size: 18px; font-weight: 700; color: var(--text); }
    .brand-sub  { font-size: 10px; color: var(--muted); letter-spacing: .8px; text-transform: uppercase; }
    .nav-links { display: flex; align-items: center; gap: 4px; }
    .nav-auth   { display: flex; align-items: center; gap: 8px; margin-left: 8px; }
    .nav-link-item {
      padding: 8px 14px; border-radius: 8px;
      font-size: 14px; font-weight: 500; color: var(--muted);
      text-decoration: none; transition: all .2s;
    }
    .nav-link-item:hover { background: var(--body-bg); color: var(--text); }
    .nav-link-item.nav-active { color: var(--brand); font-weight: 600; }
    .btn-nav-login {
      padding: 8px 18px; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      border: 1px solid var(--border); color: var(--text);
      text-decoration: none; transition: all .2s;
      background: var(--white);
    }
    .btn-nav-login:hover { border-color: var(--brand); color: var(--brand); }
    .btn-nav-register {
      padding: 8px 18px; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      background: var(--brand); color: #fff;
      text-decoration: none; transition: background .2s;
    }
    .btn-nav-register:hover { background: var(--brand-dark); color: #fff; }
    /* ── Hamburger ── */
    .hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; cursor: pointer; padding: 6px; border: none; background: none; border-radius: 6px; }
    .hamburger span { display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px; transition: transform .3s, opacity .3s; }
    .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
    .hamburger.open span:nth-child(2) { opacity: 0; }
    .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

    /* ── Hero ───────────────────────────────────────── */
    .hero {
      background: var(--brand);
      padding: 80px 32px;
      text-align: center;
    }
    .hero-badge {
      display: inline-block;
      background: rgba(255,255,255,.15);
      color: #fff;
      font-size: 12px; font-weight: 600;
      padding: 6px 16px; border-radius: 20px;
      margin-bottom: 20px; letter-spacing: .5px;
    }
    .hero h1 {
      font-size: clamp(28px, 5vw, 48px);
      font-weight: 700; color: #fff;
      margin-bottom: 16px; line-height: 1.2;
    }
    .hero p {
      font-size: 16px; color: rgba(255,255,255,.8);
      max-width: 560px; margin: 0 auto 32px;
      line-height: 1.7;
    }
    .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-hero-primary {
      background: #fff; color: var(--brand);
      padding: 13px 28px; border-radius: 8px;
      font-size: 15px; font-weight: 700;
      text-decoration: none; transition: all .2s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-hero-primary:hover { background: #e8f0fe; color: var(--brand); }
    .btn-hero-secondary {
      background: rgba(255,255,255,.15); color: #fff;
      border: 1px solid rgba(255,255,255,.3);
      padding: 13px 28px; border-radius: 8px;
      font-size: 15px; font-weight: 600;
      text-decoration: none; transition: all .2s;
      display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-hero-secondary:hover { background: rgba(255,255,255,.25); color: #fff; }

    /* ── Stats bar ──────────────────────────────────── */
    .stats-bar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 20px 32px;
    }
    .stats-inner {
      max-width: 900px; margin: 0 auto;
      display: flex; justify-content: center; gap: 48px;
      flex-wrap: wrap;
    }
    .stat-item { text-align: center; }
    .stat-num  { font-size: 24px; font-weight: 700; color: var(--brand); }
    .stat-lbl  { font-size: 12px; color: var(--muted); margin-top: 2px; }

    /* ── Section ────────────────────────────────────── */
    .section { padding: 64px 32px; }
    .section-header { text-align: center; margin-bottom: 48px; }
    .section-tag {
      display: inline-block;
      background: #e8f0fe; color: var(--brand);
      font-size: 12px; font-weight: 600;
      padding: 5px 14px; border-radius: 20px;
      margin-bottom: 12px; letter-spacing: .5px;
    }
    .section-title { font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 10px; }
    .section-sub   { font-size: 15px; color: var(--muted); max-width: 500px; margin: 0 auto; line-height: 1.7; }

    /* ── Product cards ──────────────────────────────── */
    .products-grid {
      max-width: 1100px; margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 24px;
    }
    .product-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 28px;
      transition: transform .2s, box-shadow .2s, border-color .2s;
      position: relative;
      overflow: hidden;
    }
    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0,0,0,.08);
      border-color: var(--brand);
    }
    .product-card.featured {
      border-color: var(--brand);
      box-shadow: 0 4px 20px rgba(24,119,242,.12);
    }
    .featured-badge {
      position: absolute; top: 16px; right: 16px;
      background: var(--brand); color: #fff;
      font-size: 10px; font-weight: 700;
      padding: 3px 10px; border-radius: 20px;
      letter-spacing: .5px; text-transform: uppercase;
    }
    .product-icon {
      width: 52px; height: 52px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 24px; color: #fff;
      margin-bottom: 18px;
    }
    .product-name {
      font-size: 17px; font-weight: 700;
      color: var(--text); margin-bottom: 8px;
    }
    .product-desc {
      font-size: 13px; color: var(--muted);
      line-height: 1.6; margin-bottom: 20px;
    }
    .product-price-row {
      display: flex; align-items: flex-end; gap: 4px;
      margin-bottom: 20px;
      padding-top: 16px;
      border-top: 1px solid var(--border);
    }
    .price-currency { font-size: 16px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
    .price-amount   { font-size: 32px; font-weight: 700; color: var(--brand); line-height: 1; }
    .price-label    { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .product-actions { display: flex; gap: 10px; }
    .btn-card-primary {
      flex: 1; background: var(--brand); color: #fff;
      border: none; border-radius: 8px; padding: 10px;
      font-size: 13px; font-weight: 600;
      cursor: pointer; transition: background .2s;
      text-align: center; text-decoration: none;
      display: flex; align-items: center; justify-content: center; gap: 6px;
      font-family: 'DM Sans', sans-serif;
    }
    .btn-card-primary:hover { background: var(--brand-dark); color: #fff; }
    .btn-card-ghost {
      padding: 10px 14px; border-radius: 8px;
      border: 1px solid var(--border); color: var(--muted);
      background: transparent; cursor: pointer;
      transition: all .2s; font-size: 14px;
      text-decoration: none;
      display: flex; align-items: center; justify-content: center;
    }
    .btn-card-ghost:hover { border-color: var(--brand); color: var(--brand); }

    /* ── Why us ─────────────────────────────────────── */
    .why-grid {
      max-width: 900px; margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 24px;
    }
    .why-card {
      background: var(--white);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 24px;
      text-align: center;
    }
    .why-icon {
      width: 48px; height: 48px;
      background: #e8f0fe; border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px; color: var(--brand);
      margin: 0 auto 14px;
    }
    .why-title { font-size: 14px; font-weight: 600; margin-bottom: 6px; }
    .why-text  { font-size: 13px; color: var(--muted); line-height: 1.6; }

    /* ── CTA ────────────────────────────────────────── */
    .cta-section {
      background: var(--brand);
      padding: 64px 32px;
      text-align: center;
    }
    .cta-section h2 { font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 12px; }
    .cta-section p  { font-size: 15px; color: rgba(255,255,255,.8); margin-bottom: 28px; }

    /* ── Footer ─────────────────────────────────────── */
    .site-footer {
      background: var(--white);
      border-top: 1px solid var(--border);
      padding: 24px 32px;
      text-align: center;
    }
    .site-footer p { font-size: 13px; color: var(--muted); margin: 0; }
    .site-footer a  { color: var(--brand); text-decoration: none; }

    @media (max-width: 768px) {
      .navbar { padding: 0 16px; height: 58px; }
      .hamburger { display: flex; }
      .nav-links { display: none; flex-direction: column; align-items: stretch; position: absolute; top: 58px; left: 0; right: 0; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 16px 16px; gap: 2px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
      .nav-links.open { display: flex; }
      .nav-auth { flex-direction: column; align-items: stretch; margin-left: 0; margin-top: 8px; padding-top: 10px; border-top: 1px solid var(--border); gap: 8px; }
      .nav-link-item { padding: 10px 14px; }
      .btn-nav-login, .btn-nav-register { text-align: center; padding: 11px 18px; }
      .hero { padding: 48px 16px; }
      .section { padding: 48px 16px; }
      .stats-inner { gap: 24px; }
      .stats-bar { padding: 20px 16px; }
    }
  </style>
</head>
<body>

<?= view('partials/public_nav', ['activeNav' => 'home']) ?>

<!-- ═══ HERO ══════════════════════════════════════════ -->
<section class="hero">
  <div class="hero-badge"><?= lang('Home.hero_badge') ?></div>
  <h1><?= lang('Home.hero_title_1') ?><br><?= lang('Home.hero_title_2') ?></h1>
  <p><?= lang('Home.hero_subtitle') ?></p>
  <div class="hero-btns">
    <a href="<?= base_url('register') ?>" class="btn-hero-primary">
      <i class="bi bi-building-check"></i> <?= lang('Home.start_trial') ?>
    </a>
    <a href="<?= base_url('products') ?>" class="btn-hero-secondary">
      <i class="bi bi-grid"></i> <?= lang('Home.view_products') ?>
    </a>
  </div>
</section>

<!-- ═══ STATS ═════════════════════════════════════════ -->
<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item">
      <div class="stat-num">500+</div>
      <div class="stat-lbl"><?= lang('Home.happy_clients') ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-num">99.9%</div>
      <div class="stat-lbl"><?= lang('Home.uptime_sla') ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-num">24/7</div>
      <div class="stat-lbl"><?= lang('Home.support_247') ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-num"><?= count($products) ?>+</div>
      <div class="stat-lbl"><?= lang('Home.services_available') ?></div>
    </div>
  </div>
</div>

<!-- ═══ PRODUCTS ══════════════════════════════════════ -->
<section class="section" id="products">
  <div class="section-header">
    <div class="section-tag"><?= lang('Home.our_services') ?></div>
    <h2 class="section-title"><?= lang('Home.choose_plan') ?></h2>
    <p class="section-sub"><?= lang('Home.plan_subtitle') ?></p>
  </div>

  <div class="products-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card <?= $product['is_featured'] ? 'featured' : '' ?>">

      <?php if ($product['is_featured']): ?>
        <div class="featured-badge"><?= lang('Home.popular') ?></div>
      <?php endif; ?>

      <div class="product-icon" style="background: <?= esc($product['color']) ?>">
        <i class="<?= esc($product['icon']) ?>"></i>
      </div>

      <div class="product-name"><?= esc($product['name']) ?></div>
      <div class="product-desc"><?= esc($product['short_description'] ?: $product['description']) ?></div>

      <div class="product-price-row">
        <div class="price-currency">$</div>
        <div class="price-amount"><?= number_format($product['base_price'] ?? $product['price'], 0) ?></div>
        <div class="price-label">/ <?= esc($product['price_label']) ?></div>
      </div>

      <?php $productUrl = base_url('products/' . $product['slug']); ?>
      <div class="product-actions">
        <a href="<?= $productUrl ?>" class="btn-card-primary">
          <i class="bi bi-eye"></i> <?= lang('Home.view_details') ?>
        </a>
      </div>

    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ WHY US ════════════════════════════════════════ -->
<section class="section" id="why-us" style="background: var(--white); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="section-header">
    <div class="section-tag"><?= lang('Home.why_aits') ?></div>
    <h2 class="section-title"><?= lang('Home.built_for') ?></h2>
  </div>
  <div class="why-grid">
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-shield-check"></i></div>
      <div class="why-title"><?= lang('Home.security_title') ?></div>
      <div class="why-text"><?= lang('Home.security_text') ?></div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-headset"></i></div>
      <div class="why-title"><?= lang('Home.support_title') ?></div>
      <div class="why-text"><?= lang('Home.support_text') ?></div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-lightning-charge-fill"></i></div>
      <div class="why-title"><?= lang('Home.setup_title') ?></div>
      <div class="why-text"><?= lang('Home.setup_text') ?></div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-bar-chart-fill"></i></div>
      <div class="why-title"><?= lang('Home.scale_title') ?></div>
      <div class="why-text"><?= lang('Home.scale_text') ?></div>
    </div>
  </div>
</section>

<!-- ═══ CTA ═══════════════════════════════════════════ -->
<section class="cta-section" id="contact">
  <h2><?= lang('Home.cta_title') ?></h2>
  <p><?= lang('Home.cta_subtitle') ?></p>
  <div class="hero-btns">
    <a href="<?= base_url('register') ?>" class="btn-hero-primary">
      <i class="bi bi-building-check"></i> <?= lang('Home.create_account') ?>
    </a>
    <a href="<?= base_url('contact') ?>" class="btn-hero-secondary">
      <i class="bi bi-envelope"></i> <?= lang('Home.contact_us') ?>
    </a>
  </div>
</section>

<!-- ═══ FOOTER ════════════════════════════════════════ -->
<footer class="site-footer">
  <p>
    © <?= date('Y') ?> AITS — Alin IT Services &nbsp;·&nbsp;
    <a href="<?= base_url('products') ?>"><?= lang('Nav.products') ?></a> &nbsp;·&nbsp;
    <a href="<?= base_url('why-us') ?>"><?= lang('Nav.why_us') ?></a> &nbsp;·&nbsp;
    <a href="<?= base_url('contact') ?>"><?= lang('Nav.contact') ?></a> &nbsp;·&nbsp;
    <a href="<?= base_url('login') ?>"><?= lang('Nav.sign_in') ?></a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>