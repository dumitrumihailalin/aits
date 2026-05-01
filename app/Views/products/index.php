<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($metaDesc) ?>">
  <link rel="canonical" href="<?= base_url('products') ?>">
  <meta property="og:title"       content="<?= esc($title) ?>">
  <meta property="og:description" content="<?= esc($metaDesc) ?>">
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= base_url('products') ?>">
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

    /* ── Navbar (shared) ── */
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

    /* ── Page header ── */
    .page-hero { background: var(--brand); padding: 64px 32px; text-align: center; }
    .page-hero .badge-tag { display: inline-block; background: rgba(255,255,255,.15); color: #fff; font-size: 12px; font-weight: 600; padding: 5px 16px; border-radius: 20px; margin-bottom: 16px; letter-spacing: .5px; }
    .page-hero h1 { font-size: clamp(26px, 4vw, 42px); font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.2; }
    .page-hero p  { font-size: 16px; color: rgba(255,255,255,.8); max-width: 560px; margin: 0 auto; line-height: 1.7; }

    /* ── Video section ── */
    .video-section { background: var(--white); border-bottom: 1px solid var(--border); padding: 56px 32px; }
    .video-wrap { max-width: 860px; margin: 0 auto; }
    .video-label { text-align: center; margin-bottom: 28px; }
    .video-label .section-tag { display: inline-block; background: #e8f0fe; color: var(--brand); font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px; }
    .video-label h2 { font-size: 24px; font-weight: 700; color: var(--text); }
    .video-label p  { font-size: 14px; color: var(--muted); margin-top: 6px; }
    .video-frame { position: relative; padding-bottom: 56.25%; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 48px rgba(0,0,0,.12); background: #000; }
    .video-frame iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

    /* ── Products grid ── */
    .section { padding: 64px 32px; }
    .section-header { text-align: center; margin-bottom: 48px; }
    .section-tag { display: inline-block; background: #e8f0fe; color: var(--brand); font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px; margin-bottom: 12px; letter-spacing: .5px; }
    .section-title { font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 10px; }
    .section-sub   { font-size: 15px; color: var(--muted); max-width: 500px; margin: 0 auto; line-height: 1.7; }
    .products-grid { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }
    .product-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 28px; transition: transform .2s, box-shadow .2s, border-color .2s; position: relative; overflow: hidden; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.08); border-color: var(--brand); }
    .product-card.featured { border-color: var(--brand); box-shadow: 0 4px 20px rgba(24,119,242,.12); }
    .featured-badge { position: absolute; top: 16px; right: 16px; background: var(--brand); color: #fff; font-size: 10px; font-weight: 700; padding: 3px 10px; border-radius: 20px; letter-spacing: .5px; text-transform: uppercase; }
    .product-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #fff; margin-bottom: 18px; }
    .product-name { font-size: 17px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .product-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 20px; }
    .product-price-row { display: flex; align-items: flex-end; gap: 4px; margin-bottom: 20px; padding-top: 16px; border-top: 1px solid var(--border); }
    .price-currency { font-size: 16px; font-weight: 600; color: var(--brand); margin-bottom: 4px; }
    .price-amount   { font-size: 32px; font-weight: 700; color: var(--brand); line-height: 1; }
    .price-label    { font-size: 13px; color: var(--muted); margin-bottom: 4px; }
    .btn-card-primary { flex: 1; background: var(--brand); color: #fff; border: none; border-radius: 8px; padding: 10px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background .2s; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-family: 'DM Sans', sans-serif; }
    .btn-card-primary:hover { background: var(--brand-dark); color: #fff; }

    /* ── CTA ── */
    .cta-section { background: var(--brand); padding: 64px 32px; text-align: center; }
    .cta-section h2 { font-size: 28px; font-weight: 700; color: #fff; margin-bottom: 12px; }
    .cta-section p  { font-size: 15px; color: rgba(255,255,255,.8); margin-bottom: 28px; }
    .hero-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .btn-hero-primary   { background: #fff; color: var(--brand); padding: 13px 28px; border-radius: 8px; font-size: 15px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background .2s; }
    .btn-hero-primary:hover { background: #e8f0fe; color: var(--brand); }
    .btn-hero-secondary { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.3); padding: 13px 28px; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background .2s; }
    .btn-hero-secondary:hover { background: rgba(255,255,255,.25); color: #fff; }

    /* ── Footer ── */
    .site-footer { background: var(--white); border-top: 1px solid var(--border); padding: 24px 32px; text-align: center; }
    .site-footer p { font-size: 13px; color: var(--muted); margin: 0; }
    .site-footer a { color: var(--brand); text-decoration: none; }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .site-nav { padding: 0 16px; height: 58px; }
      .hamburger { display: flex; }
      .nav-links { display: none; flex-direction: column; align-items: stretch; position: absolute; top: 58px; left: 0; right: 0; background: var(--white); border-bottom: 1px solid var(--border); padding: 12px 16px 16px; gap: 2px; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
      .nav-links.open { display: flex; }
      .nav-auth { flex-direction: column; align-items: stretch; margin-left: 0; margin-top: 8px; padding-top: 10px; border-top: 1px solid var(--border); gap: 8px; }
      .nav-link-item { padding: 10px 14px; }
      .btn-nav-login, .btn-nav-register { text-align: center; padding: 11px 18px; }
      .page-hero { padding: 48px 16px; }
      .video-section { padding: 40px 16px; }
      .section { padding: 48px 16px; }
    }
  </style>
</head>
<body>

<?= view('partials/public_nav', ['activeNav' => $activeNav]) ?>

<main>
  <!-- ═══ PAGE HERO ══════════════════════════════════════ -->
  <section class="page-hero">
    <div class="badge-tag">Our Services</div>
    <h1>IT Services & Plans</h1>
    <p>Transparent pricing. Dedicated support. Everything your business needs to grow faster.</p>
  </section>

  <!-- ═══ VIDEO PRESENTATION ════════════════════════════ -->
  <section class="video-section" aria-label="Product presentation video">
    <div class="video-wrap">
      <div class="video-label">
        <div class="section-tag">Watch & Learn</div>
        <h2>See AITS in Action</h2>
        <p>A quick overview of how our services work and what you get from day one.</p>
      </div>
      <div class="video-frame">
        <!--
          Replace YOUR_VIDEO_ID with your actual YouTube video ID.
          Example: https://www.youtube.com/watch?v=dQw4w9WgXcQ → ID is dQw4w9WgXcQ
        -->
        <iframe
          src="https://www.youtube-nocookie.com/embed/YOUR_VIDEO_ID?rel=0&modestbranding=1"
          title="AITS — IT Services presentation"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          loading="lazy">
        </iframe>
      </div>
    </div>
  </section>

  <!-- ═══ PRODUCTS ══════════════════════════════════════ -->
  <section class="section" aria-label="Service plans">
    <div class="section-header">
      <div class="section-tag">Pricing</div>
      <h2 class="section-title">Choose the right plan for your business</h2>
      <p class="section-sub">All plans include dedicated support, regular updates and a 30-day money-back guarantee.</p>
    </div>

    <div class="products-grid">
      <?php foreach ($products as $product): ?>
      <article class="product-card <?= $product['is_featured'] ? 'featured' : '' ?>">
        <?php if ($product['is_featured']): ?>
          <div class="featured-badge">Popular</div>
        <?php endif; ?>
        <div class="product-icon" style="background: <?= esc($product['color']) ?>">
          <i class="<?= esc($product['icon']) ?>"></i>
        </div>
        <h3 class="product-name"><?= esc($product['name']) ?></h3>
        <p class="product-desc"><?= esc($product['short_description'] ?: $product['description']) ?></p>
        <div class="product-price-row">
          <div class="price-currency">$</div>
          <div class="price-amount"><?= number_format($product['base_price'] ?? $product['price'], 0) ?></div>
          <div class="price-label">/ <?= esc($product['price_label']) ?></div>
        </div>
        <a href="<?= base_url('products/' . \App\Models\ProductModel::urlSlug($product)) ?>" class="btn-card-primary">
          <i class="bi bi-eye"></i> View Details
        </a>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ═══ CTA ═══════════════════════════════════════════ -->
  <section class="cta-section">
    <h2>Ready to get started?</h2>
    <p>Join 500+ companies already using AITS to power their operations.</p>
    <div class="hero-btns">
      <a href="<?= base_url('register') ?>" class="btn-hero-primary">
        <i class="bi bi-building-check"></i> Create Free Account
      </a>
      <a href="<?= base_url('contact') ?>" class="btn-hero-secondary">
        <i class="bi bi-envelope"></i> Talk to Sales
      </a>
    </div>
  </section>
</main>

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
