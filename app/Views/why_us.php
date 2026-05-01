<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($metaDesc) ?>">
  <link rel="canonical" href="<?= base_url('why-us') ?>">
  <meta property="og:title"       content="<?= esc($title) ?>">
  <meta property="og:description" content="<?= esc($metaDesc) ?>">
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= base_url('why-us') ?>">
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

    /* ── Page hero ── */
    .page-hero { background: var(--brand); padding: 64px 32px; text-align: center; }
    .page-hero .badge-tag { display: inline-block; background: rgba(255,255,255,.15); color: #fff; font-size: 12px; font-weight: 600; padding: 5px 16px; border-radius: 20px; margin-bottom: 16px; }
    .page-hero h1 { font-size: clamp(26px, 4vw, 42px); font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.2; }
    .page-hero p  { font-size: 16px; color: rgba(255,255,255,.8); max-width: 520px; margin: 0 auto; line-height: 1.7; }

    /* ── Section ── */
    .section { padding: 64px 32px; }
    .section-white { background: var(--white); border-bottom: 1px solid var(--border); }
    .section-header { text-align: center; margin-bottom: 48px; }
    .section-tag   { display: inline-block; background: #e8f0fe; color: var(--brand); font-size: 12px; font-weight: 600; padding: 5px 14px; border-radius: 20px; margin-bottom: 12px; }
    .section-title { font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 10px; }
    .section-sub   { font-size: 15px; color: var(--muted); max-width: 500px; margin: 0 auto; line-height: 1.7; }

    /* ── Why cards ── */
    .why-grid { max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; }
    .why-card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; padding: 28px 24px; transition: transform .2s, box-shadow .2s; }
    .why-card:hover { transform: translateY(-3px); box-shadow: 0 8px 32px rgba(0,0,0,.07); }
    .why-icon { width: 52px; height: 52px; background: #e8f0fe; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: var(--brand); margin-bottom: 16px; }
    .why-title { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
    .why-text  { font-size: 13px; color: var(--muted); line-height: 1.7; }

    /* ── Process ── */
    .process-list { max-width: 720px; margin: 0 auto; display: flex; flex-direction: column; gap: 0; }
    .process-item { display: flex; gap: 24px; align-items: flex-start; position: relative; padding-bottom: 36px; }
    .process-item:last-child { padding-bottom: 0; }
    .process-item:not(:last-child)::before { content: ''; position: absolute; left: 20px; top: 44px; bottom: 0; width: 2px; background: var(--border); }
    .process-num { width: 40px; height: 40px; background: var(--brand); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 15px; flex-shrink: 0; }
    .process-body h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; margin-top: 8px; }
    .process-body p  { font-size: 14px; color: var(--muted); line-height: 1.6; margin: 0; }

    /* ── Stats ── */
    .stats-row { max-width: 800px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 24px; }
    .stat-box  { text-align: center; }
    .stat-num  { font-size: 36px; font-weight: 700; color: var(--brand); }
    .stat-lbl  { font-size: 13px; color: var(--muted); margin-top: 4px; }

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
      .section { padding: 48px 16px; }
    }
  </style>
</head>
<body>

<?= view('partials/public_nav', ['activeNav' => $activeNav]) ?>

<main>
  <!-- ═══ HERO ══════════════════════════════════════════ -->
  <section class="page-hero">
    <div class="badge-tag">Why AITS</div>
    <h1>Built for Businesses Like Yours</h1>
    <p>We don't just sell software — we become part of your team. Here's why 500+ companies chose us.</p>
  </section>

  <!-- ═══ KEY BENEFITS ══════════════════════════════════ -->
  <section class="section">
    <div class="section-header">
      <div class="section-tag">Our Strengths</div>
      <h2 class="section-title">What sets us apart</h2>
      <p class="section-sub">Every decision we make is focused on helping your business run better.</p>
    </div>
    <div class="why-grid">
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-shield-check"></i></div>
        <h3 class="why-title">Enterprise Security</h3>
        <p class="why-text">Your data is protected with AES-256 encryption, automated backups, and 24/7 threat monitoring. We comply with industry standards so you don't have to worry.</p>
      </div>
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-headset"></i></div>
        <h3 class="why-title">24/7 Expert Support</h3>
        <p class="why-text">Our support team is always available — not a chatbot, real engineers who know your setup and can resolve issues fast, any time of day or night.</p>
      </div>
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        <h3 class="why-title">Fast Onboarding</h3>
        <p class="why-text">Get fully set up in hours, not weeks. We handle migration, configuration and training so your team hits the ground running from day one.</p>
      </div>
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-bar-chart-fill"></i></div>
        <h3 class="why-title">Scalable Plans</h3>
        <p class="why-text">Start with what you need today and scale as you grow. No lock-in contracts, no surprise fees — just flexible plans that grow with your business.</p>
      </div>
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-clock-history"></i></div>
        <h3 class="why-title">99.9% Uptime SLA</h3>
        <p class="why-text">We stand behind our infrastructure with a contractual 99.9% uptime guarantee. Redundant servers, failover routing, and proactive monitoring included.</p>
      </div>
      <div class="why-card">
        <div class="why-icon"><i class="bi bi-currency-dollar"></i></div>
        <h3 class="why-title">Transparent Pricing</h3>
        <p class="why-text">What you see is what you pay. No hidden setup fees, no annual traps. Every plan comes with a 30-day money-back guarantee — zero risk.</p>
      </div>
    </div>
  </section>

  <!-- ═══ STATS ═══════════════════════════════════════════ -->
  <section class="section section-white" aria-label="Company stats">
    <div class="section-header">
      <div class="section-tag">By the Numbers</div>
      <h2 class="section-title">Trusted by businesses across Europe</h2>
    </div>
    <div class="stats-row">
      <div class="stat-box"><div class="stat-num">500+</div><div class="stat-lbl">Happy Clients</div></div>
      <div class="stat-box"><div class="stat-num">99.9%</div><div class="stat-lbl">Uptime SLA</div></div>
      <div class="stat-box"><div class="stat-num">24/7</div><div class="stat-lbl">Support Available</div></div>
      <div class="stat-box"><div class="stat-num">30-day</div><div class="stat-lbl">Money-back Guarantee</div></div>
    </div>
  </section>

  <!-- ═══ HOW IT WORKS ══════════════════════════════════ -->
  <section class="section" aria-label="How it works">
    <div class="section-header">
      <div class="section-tag">The Process</div>
      <h2 class="section-title">Up and running in 4 simple steps</h2>
    </div>
    <div class="process-list">
      <div class="process-item">
        <div class="process-num">1</div>
        <div class="process-body">
          <h3>Register & Choose a Plan</h3>
          <p>Create your free account in under 2 minutes and browse plans that fit your business size and budget.</p>
        </div>
      </div>
      <div class="process-item">
        <div class="process-num">2</div>
        <div class="process-body">
          <h3>We Set Everything Up</h3>
          <p>Our team handles the full onboarding — configuration, data migration, domain setup — so you don't have to touch a single config file.</p>
        </div>
      </div>
      <div class="process-item">
        <div class="process-num">3</div>
        <div class="process-body">
          <h3>Your Team Gets Trained</h3>
          <p>We run a tailored walkthrough for your team so everyone is confident from day one. Documentation and video guides are always available.</p>
        </div>
      </div>
      <div class="process-item">
        <div class="process-num">4</div>
        <div class="process-body">
          <h3>Grow with Ongoing Support</h3>
          <p>We stay with you — monitoring, updates, feature requests and 24/7 support throughout your subscription.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ═══ CTA ═══════════════════════════════════════════ -->
  <section class="cta-section">
    <h2>Ready to experience the AITS difference?</h2>
    <p>Join 500+ businesses already running smarter with AITS.</p>
    <div class="hero-btns">
      <a href="<?= base_url('register') ?>" class="btn-hero-primary">
        <i class="bi bi-building-check"></i> Get Started Free
      </a>
      <a href="<?= base_url('contact') ?>" class="btn-hero-secondary">
        <i class="bi bi-chat-dots"></i> Talk to Us
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
