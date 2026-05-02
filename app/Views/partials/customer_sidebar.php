<?php
$activeNav  = $activeNav  ?? '';
$cartCount  = $cartCount  ?? 0;
$nav = static fn(string $key): string => $activeNav === $key ? 'nav-link active' : 'nav-link';
?>
<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<div id="sidebar">
    <div class="sidebar-brand">
        <a href="<?= base_url('/') ?>">
            <span>💻 AITS</span>
            <small>Alin IT Services</small>
        </a>
    </div>
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>"   class="<?= $nav('dashboard') ?>">
            <i class="bi bi-speedometer2"></i> <?= lang('Customer.dashboard') ?>
        </a>
        <a href="<?= base_url('my-products') ?>" class="<?= $nav('products') ?>">
            <i class="bi bi-grid"></i> <?= lang('Customer.products') ?>
        </a>
        <a href="<?= base_url('cart') ?>"        class="<?= $nav('basket') ?>">
            <i class="bi bi-basket2"></i> <?= lang('Customer.basket') ?>
            <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger rounded-pill ms-auto" style="font-size:11px;"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= base_url('invoices') ?>"    class="<?= $nav('invoices') ?>">
            <i class="bi bi-receipt"></i> <?= lang('Customer.invoices') ?>
        </a>
        <a href="<?= base_url('support') ?>"     class="<?= $nav('support') ?>">
            <i class="bi bi-headset"></i> <?= lang('Customer.support') ?>
        </a>
        <a href="<?= base_url('profile') ?>"     class="<?= $nav('profile') ?>">
            <i class="bi bi-person"></i> <?= lang('Customer.profile') ?>
        </a>
        <hr style="border-color:rgba(255,255,255,.15);margin:12px 24px;">
        <a href="<?= base_url('logout') ?>" class="nav-link">
            <i class="bi bi-box-arrow-left"></i> <?= lang('Customer.logout') ?>
        </a>
    </nav>
</div>
