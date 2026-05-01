<!DOCTYPE html>
<html lang="en">
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
    .navbar {
      background: var(--white);
      border-bottom: 1px solid var(--border);
      padding: 0 32px;
      height: 64px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }
    .navbar-brand {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
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
    .nav-links { display: flex; align-items: center; gap: 8px; }
    .nav-link-item {
      padding: 8px 16px; border-radius: 8px;
      font-size: 14px; font-weight: 500; color: var(--muted);
      text-decoration: none; transition: all .2s;
    }
    .nav-link-item:hover { background: var(--body-bg); color: var(--text); }
    .btn-nav-login {
      padding: 8px 20px; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      border: 1px solid var(--border); color: var(--text);
      text-decoration: none; transition: all .2s;
      background: var(--white);
    }
    .btn-nav-login:hover { border-color: var(--brand); color: var(--brand); }
    .btn-nav-register {
      padding: 8px 20px; border-radius: 8px;
      font-size: 14px; font-weight: 600;
      background: var(--brand); color: #fff;
      text-decoration: none; transition: background .2s;
    }
    .btn-nav-register:hover { background: var(--brand-dark); color: #fff; }

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
    .footer {
      background: var(--white);
      border-top: 1px solid var(--border);
      padding: 24px 32px;
      text-align: center;
    }
    .footer p { font-size: 13px; color: var(--muted); margin: 0; }
    .footer a  { color: var(--brand); text-decoration: none; }

    @media (max-width: 600px) {
      .navbar { padding: 0 16px; }
      .nav-links .nav-link-item { display: none; }
      .hero { padding: 48px 16px; }
      .section { padding: 48px 16px; }
      .stats-inner { gap: 24px; }
    }
  </style>
</head>
<body>

<!-- ═══ NAVBAR ════════════════════════════════════════ -->
<nav class="navbar">
  <a href="<?= base_url('/') ?>" class="navbar-brand">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </a>
  <div class="nav-links">
    <a href="#products" class="nav-link-item">Products</a>
    <a href="#why-us" class="nav-link-item">Why Us</a>
    <a href="#contact" class="nav-link-item">Contact</a>
    <?php if (session()->get('isLoggedIn')): ?>
      <a href="<?= base_url('dashboard') ?>" class="btn-nav-login">Dashboard</a>
      <a href="<?= base_url('logout') ?>" class="btn-nav-register">Logout</a>
    <?php else: ?>
      <a href="<?= base_url('login') ?>" class="btn-nav-login">Sign In</a>
      <a href="<?= base_url('register') ?>" class="btn-nav-register">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<!-- ═══ HERO ══════════════════════════════════════════ -->
<section class="hero">
  <div class="hero-badge">🚀 Trusted IT Solutions for Growing Businesses</div>
  <h1>IT Services That<br>Power Your Business</h1>
  <p>From cloud hosting to CRM integration — we deliver the technology your company needs to grow faster and work smarter.</p>
  <div class="hero-btns">
    <a href="<?= base_url('register') ?>" class="btn-hero-primary">
      <i class="bi bi-building-check"></i> Start Free Trial
    </a>
    <a href="#products" class="btn-hero-secondary">
      <i class="bi bi-grid"></i> View Products
    </a>
  </div>
</section>

<!-- ═══ STATS ═════════════════════════════════════════ -->
<div class="stats-bar">
  <div class="stats-inner">
    <div class="stat-item">
      <div class="stat-num">500+</div>
      <div class="stat-lbl">Happy Clients</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">99.9%</div>
      <div class="stat-lbl">Uptime SLA</div>
    </div>
    <div class="stat-item">
      <div class="stat-num">24/7</div>
      <div class="stat-lbl">Support</div>
    </div>
    <div class="stat-item">
      <div class="stat-num"><?= count($products) ?>+</div>
      <div class="stat-lbl">Services Available</div>
    </div>
  </div>
</div>

<!-- ═══ PRODUCTS ══════════════════════════════════════ -->
<section class="section" id="products">
  <div class="section-header">
    <div class="section-tag">Our Services</div>
    <h2 class="section-title">Choose the right plan for your business</h2>
    <p class="section-sub">All plans include dedicated support, regular updates and a 30-day money-back guarantee.</p>
  </div>

  <div class="products-grid">
    <?php foreach ($products as $product): ?>
    <div class="product-card <?= $product['is_featured'] ? 'featured' : '' ?>">

      <?php if ($product['is_featured']): ?>
        <div class="featured-badge">Popular</div>
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
          <i class="bi bi-eye"></i> View Details
        </a>
      </div>

    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ═══ WHY US ════════════════════════════════════════ -->
<section class="section" id="why-us" style="background: var(--white); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
  <div class="section-header">
    <div class="section-tag">Why AITS</div>
    <h2 class="section-title">Built for businesses like yours</h2>
  </div>
  <div class="why-grid">
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-shield-check"></i></div>
      <div class="why-title">Enterprise Security</div>
      <div class="why-text">Your data is protected with enterprise-grade encryption and monitoring.</div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-headset"></i></div>
      <div class="why-title">24/7 Support</div>
      <div class="why-text">Our team is always available to help you resolve any issues fast.</div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-lightning-charge-fill"></i></div>
      <div class="why-title">Fast Setup</div>
      <div class="why-text">Get up and running in hours, not weeks. We handle everything for you.</div>
    </div>
    <div class="why-card">
      <div class="why-icon"><i class="bi bi-bar-chart-fill"></i></div>
      <div class="why-title">Scalable Plans</div>
      <div class="why-text">Start small and scale as your business grows. No lock-in contracts.</div>
    </div>
  </div>
</section>

<!-- ═══ CTA ═══════════════════════════════════════════ -->
<section class="cta-section" id="contact">
  <h2>Ready to grow your business?</h2>
  <p>Join 500+ companies already using AITS to power their operations.</p>
  <div class="hero-btns">
    <a href="<?= base_url('register') ?>" class="btn-hero-primary">
      <i class="bi bi-building-check"></i> Create Free Account
    </a>
    <a href="mailto:customers@alinitservices.com" class="btn-hero-secondary">
      <i class="bi bi-envelope"></i> Contact Us
    </a>
  </div>
</section>

<!-- ═══ FOOTER ════════════════════════════════════════ -->
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