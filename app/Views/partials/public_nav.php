<?php
/**
 * Public site navbar — include via: <?= view('partials/public_nav', ['activeNav' => 'products']) ?>
 * activeNav: 'home' | 'products' | 'why-us' | 'contact'
 */
$active = $activeNav ?? '';
?>
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
    <a href="<?= base_url('/') ?>"        class="nav-link-item <?= $active === 'home'     ? 'nav-active' : '' ?>">Home</a>
    <a href="<?= base_url('products') ?>" class="nav-link-item <?= $active === 'products'  ? 'nav-active' : '' ?>">Products</a>
    <a href="<?= base_url('why-us') ?>"   class="nav-link-item <?= $active === 'why-us'   ? 'nav-active' : '' ?>">Why Us</a>
    <a href="<?= base_url('contact') ?>"  class="nav-link-item <?= $active === 'contact'   ? 'nav-active' : '' ?>">Contact</a>

    <div class="nav-auth">
      <?php if (session()->get('isLoggedIn')): ?>
        <a href="<?= base_url('dashboard') ?>" class="btn-nav-login">Dashboard</a>
        <a href="<?= base_url('logout') ?>"    class="btn-nav-register">Logout</a>
      <?php else: ?>
        <a href="<?= base_url('login') ?>"    class="btn-nav-login">Sign In</a>
        <a href="<?= base_url('register') ?>" class="btn-nav-register">Get Started</a>
      <?php endif; ?>
    </div>
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
  // Close on outside click
  document.addEventListener('click', function(e){
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
      menu.classList.remove('open');
      btn.classList.remove('open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>
