<?php
/**
 * Public site navbar — include via: <?= view('partials/public_nav', ['activeNav' => 'products']) ?>
 * activeNav: 'home' | 'products' | 'why-us' | 'contact'
 */
$active        = $activeNav ?? '';
$currentLocale = session()->get('locale') ?? 'en';
$locales = [
    'en' => ['flag' => '🇬🇧', 'code' => 'EN'],
    'tr' => ['flag' => '🇹🇷', 'code' => 'TR'],
    'it' => ['flag' => '🇮🇹', 'code' => 'IT'],
    'es' => ['flag' => '🇪🇸', 'code' => 'ES'],
    'el' => ['flag' => '🇬🇷', 'code' => 'EL'],
    'fr' => ['flag' => '🇫🇷', 'code' => 'FR'],
    'bg' => ['flag' => '🇧🇬', 'code' => 'BG'],
    'de' => ['flag' => '🇩🇪', 'code' => 'DE'],
];
?>
<style>
  .lang-select {
    border: 1px solid var(--border, #e5eaf5);
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 13px;
    font-weight: 600;
    color: var(--text, #111827);
    background: var(--white, #fff);
    cursor: pointer;
    outline: none;
    font-family: 'DM Sans', sans-serif;
    transition: border-color .2s;
    margin-left: 4px;
  }
  .lang-select:hover,
  .lang-select:focus { border-color: var(--brand, #1877f2); }
  @media (max-width: 768px) {
    .lang-select { width: 100%; margin: 8px 0 0; }
    form.lang-switcher-form { width: 100%; }
  }
</style>

<nav class="site-nav" id="siteNav">
  <a href="<?= base_url('/') ?>" class="nav-brand">
    <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
    <div>
      <div class="brand-name">AITS</div>
      <div class="brand-sub">Alin IT Services</div>
    </div>
  </a>

  <button class="hamburger" id="hamburger" aria-label="Menu" aria-expanded="false">
    <span></span><span></span><span></span>
  </button>

  <div class="nav-links" id="navLinks">
    <a href="<?= base_url('/') ?>"        class="nav-link-item <?= $active === 'home'     ? 'nav-active' : '' ?>"><?= lang('Nav.home') ?></a>
    <a href="<?= base_url('products') ?>" class="nav-link-item <?= $active === 'products'  ? 'nav-active' : '' ?>"><?= lang('Nav.products') ?></a>
    <a href="<?= base_url('why-us') ?>"   class="nav-link-item <?= $active === 'why-us'   ? 'nav-active' : '' ?>"><?= lang('Nav.why_us') ?></a>
    <a href="<?= base_url('contact') ?>"  class="nav-link-item <?= $active === 'contact'   ? 'nav-active' : '' ?>"><?= lang('Nav.contact') ?></a>

    <div class="nav-auth">
      <?php if (session()->get('isLoggedIn')): ?>
        <a href="<?= base_url('dashboard') ?>" class="btn-nav-login"><?= lang('Nav.dashboard') ?></a>
        <a href="<?= base_url('logout') ?>"    class="btn-nav-register"><?= lang('Nav.logout') ?></a>
      <?php else: ?>
        <a href="<?= base_url('login') ?>"    class="btn-nav-login"><?= lang('Nav.sign_in') ?></a>
        <a href="<?= base_url('register') ?>" class="btn-nav-register"><?= lang('Nav.get_started') ?></a>
      <?php endif; ?>
    </div>

    <form method="post" action="<?= base_url('lang') ?>" id="langSwitcherForm" class="lang-switcher-form">
      <?= csrf_field() ?>
      <select name="locale"
              class="lang-select"
              aria-label="<?= lang('Nav.language') ?>"
              onchange="document.getElementById('langSwitcherForm').submit()">
        <?php foreach ($locales as $code => $info): ?>
          <option value="<?= $code ?>" <?= $currentLocale === $code ? 'selected' : '' ?>>
            <?= $info['flag'] ?> <?= $info['code'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
</nav>

<script>
(function(){
  var btn  = document.getElementById('hamburger');
  var menu = document.getElementById('navLinks');
  if (!btn || !menu) return;
  btn.addEventListener('click', function(){
    var open = menu.classList.toggle('open');
    btn.classList.toggle('open', open);
    btn.setAttribute('aria-expanded', open);
  });
  document.addEventListener('click', function(e){
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('open');
      btn.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>
