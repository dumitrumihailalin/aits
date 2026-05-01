<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= esc($title) ?></title>
  <meta name="description" content="<?= esc($metaDesc) ?>">
  <link rel="canonical" href="<?= base_url('contact') ?>">
  <meta property="og:title"       content="<?= esc($title) ?>">
  <meta property="og:description" content="<?= esc($metaDesc) ?>">
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= base_url('contact') ?>">
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

    /* ── Hero ── */
    .page-hero { background: var(--brand); padding: 64px 32px; text-align: center; }
    .page-hero .badge-tag { display: inline-block; background: rgba(255,255,255,.15); color: #fff; font-size: 12px; font-weight: 600; padding: 5px 16px; border-radius: 20px; margin-bottom: 16px; }
    .page-hero h1 { font-size: clamp(26px, 4vw, 42px); font-weight: 700; color: #fff; margin-bottom: 12px; line-height: 1.2; }
    .page-hero p  { font-size: 16px; color: rgba(255,255,255,.8); max-width: 480px; margin: 0 auto; line-height: 1.7; }

    /* ── Contact layout ── */
    .contact-section { padding: 64px 32px; max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: 1fr 360px; gap: 48px; align-items: start; }

    /* ── Form ── */
    .form-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 36px; }
    .form-card h2 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .form-card .sub { font-size: 14px; color: var(--muted); margin-bottom: 28px; }
    .field { margin-bottom: 18px; }
    label.field-label { display: block; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 6px; }
    .field input, .field textarea, .field select {
      width: 100%; border: 1px solid var(--border); border-radius: 8px;
      padding: 10px 14px; font-size: 14px; font-family: 'DM Sans', sans-serif;
      color: var(--text); background: var(--white);
      outline: none; transition: border-color .2s;
    }
    .field input:focus, .field textarea:focus { border-color: var(--brand); }
    .field textarea { resize: vertical; min-height: 130px; }
    .btn-submit { width: 100%; background: var(--brand); color: #fff; border: none; border-radius: 8px; padding: 13px; font-size: 15px; font-weight: 700; font-family: 'DM Sans', sans-serif; cursor: pointer; transition: background .2s; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: var(--brand-dark); }
    .alert-success { background: #1877f2; border: none; color: #000; border-radius: 10px; padding: 12px 16px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .alert-error   { background: #dc2626; border: none; color: #fff; border-radius: 10px; padding: 12px 16px; font-size: 13px; margin-bottom: 20px; }

    /* ── Info sidebar ── */
    .info-card { background: var(--white); border: 1px solid var(--border); border-radius: 16px; padding: 32px; }
    .info-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 20px; }
    .info-item { display: flex; gap: 14px; align-items: flex-start; margin-bottom: 20px; }
    .info-item:last-child { margin-bottom: 0; }
    .info-icon { width: 40px; height: 40px; background: #e8f0fe; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--brand); flex-shrink: 0; }
    .info-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: var(--muted); margin-bottom: 4px; }
    .info-value { font-size: 14px; color: var(--text); font-weight: 500; }
    .info-value a { color: var(--brand); text-decoration: none; }
    .info-value a:hover { text-decoration: underline; }
    .response-badge { background: #e8f0fe; border-radius: 10px; padding: 14px 16px; margin-top: 24px; display: flex; align-items: center; gap: 10px; }
    .response-badge i { color: var(--brand); font-size: 18px; }
    .response-badge span { font-size: 13px; color: var(--text); }

    /* ── Footer ── */
    .site-footer { background: var(--white); border-top: 1px solid var(--border); padding: 24px 32px; text-align: center; margin-top: 0; }
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
      .contact-section { grid-template-columns: 1fr; padding: 32px 16px; gap: 24px; }
      .form-card, .info-card { padding: 24px; }
    }
  </style>
</head>
<body>

<?= view('partials/public_nav', ['activeNav' => $activeNav]) ?>

<main>
  <!-- ═══ HERO ══════════════════════════════════════════ -->
  <section class="page-hero">
    <div class="badge-tag">Get in Touch</div>
    <h1>Contact Us</h1>
    <p>Have a question or want to start a project? We reply within one business day.</p>
  </section>

  <!-- ═══ CONTACT FORM + INFO ═══════════════════════════ -->
  <div class="contact-section">

    <!-- Form -->
    <div class="form-card">
      <h2>Send us a message</h2>
      <p class="sub">Fill in the form and our team will get back to you shortly.</p>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success">
          <i class="bi bi-check-circle-fill"></i>
          <?= esc(session()->getFlashdata('success')) ?>
        </div>
      <?php endif; ?>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-error">
          <?php foreach (session()->getFlashdata('errors') as $err): ?>
            <div><?= esc($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('contact') ?>" method="POST" novalidate>
        <?= csrf_field() ?>

        <div class="field">
          <label class="field-label" for="name">Full Name</label>
          <input type="text" id="name" name="name" placeholder="John Smith"
                 value="<?= old('name') ?>" required />
        </div>

        <div class="field">
          <label class="field-label" for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="john@company.com"
                 value="<?= old('email') ?>" required />
        </div>

        <div class="field">
          <label class="field-label" for="subject">Subject</label>
          <input type="text" id="subject" name="subject" placeholder="What can we help you with?"
                 value="<?= old('subject') ?>" required />
        </div>

        <div class="field">
          <label class="field-label" for="message">Message</label>
          <textarea id="message" name="message" placeholder="Tell us more about your project or question..." required><?= old('message') ?></textarea>
        </div>

        <button type="submit" class="btn-submit">
          <i class="bi bi-send"></i> Send Message
        </button>
      </form>
    </div>

    <!-- Sidebar -->
    <aside>
      <div class="info-card">
        <h3>Contact Information</h3>

        <div class="info-item">
          <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
          <div>
            <div class="info-label">Email</div>
            <div class="info-value">
              <a href="mailto:customers@alinitservices.com">customers@alinitservices.com</a>
            </div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="bi bi-globe"></i></div>
          <div>
            <div class="info-label">Website</div>
            <div class="info-value">
              <a href="<?= base_url('/') ?>">alinitservices.com</a>
            </div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="bi bi-clock-fill"></i></div>
          <div>
            <div class="info-label">Business Hours</div>
            <div class="info-value">Mon – Fri, 9:00 – 18:00 EET</div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon"><i class="bi bi-headset"></i></div>
          <div>
            <div class="info-label">Support</div>
            <div class="info-value">24/7 for active subscribers</div>
          </div>
        </div>

        <div class="response-badge">
          <i class="bi bi-lightning-charge-fill"></i>
          <span><strong>Fast response:</strong> we reply within 1 business day.</span>
        </div>
      </div>
    </aside>
  </div>
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
