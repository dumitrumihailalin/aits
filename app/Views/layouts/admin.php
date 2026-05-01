<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $title ?? 'Admin' ?> — AITS</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Font Awesome Brands only (WhatsApp, Stripe, etc.) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/brands.min.css" rel="stylesheet" />
  <!-- DM Sans font -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>" />
</head>
<body>

<div class="aits-shell">

  <!-- ═══ SIDEBAR ════════════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar">

    <div class="sidebar-brand">
      <div class="brand-icon"><i class="bi bi-cpu-fill"></i></div>
      <div class="brand-text">
        <strong>AITS</strong>
        <small>Admin Panel</small>
      </div>
    </div>

    <nav class="sidebar-nav">

      <div class="nav-section-label">Main</div>
      <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= ($activeNav ?? '') === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-grid-fill"></i>
        <span class="nav-label">Dashboard</span>
      </a>

      <div class="nav-section-label">Catalogue</div>
      <a href="<?= base_url('admin/products') ?>" class="nav-item <?= ($activeNav ?? '') === 'products' ? 'active' : '' ?>">
        <i class="bi bi-box-seam-fill"></i>
        <span class="nav-label">Products</span>
      </a>
      <a href="<?= base_url('admin/features') ?>" class="nav-item <?= ($activeNav ?? '') === 'features' ? 'active' : '' ?>">
        <i class="bi bi-toggles"></i>
        <span class="nav-label">Features</span>
      </a>

      <div class="nav-section-label">Customers</div>
      <a href="<?= base_url('admin/customers') ?>" class="nav-item <?= ($activeNav ?? '') === 'customers' ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i>
        <span class="nav-label">Customers</span>
      </a>
      <a href="<?= base_url('admin/invoices') ?>" class="nav-item <?= ($activeNav ?? '') === 'invoices' ? 'active' : '' ?>">
        <i class="bi bi-receipt-cutoff"></i>
        <span class="nav-label">Invoices</span>
      </a>
      <a href="<?= base_url('admin/support') ?>" class="nav-item <?= ($activeNav ?? '') === 'support' ? 'active' : '' ?>">
        <i class="bi bi-headset"></i>
        <span class="nav-label">Support Tickets</span>
        <?php if (!empty($openTickets)): ?>
          <span class="nav-badge"><?= $openTickets ?></span>
        <?php endif; ?>
      </a>

      <div class="nav-section-label">System</div>
      <a href="<?= base_url('admin/profile') ?>" class="nav-item <?= ($activeNav ?? '') === 'profile' ? 'active' : '' ?>">
        <i class="bi bi-person-fill"></i>
        <span class="nav-label">Profile</span>
      </a>
      <a href="<?= base_url('admin/settings') ?>" class="nav-item <?= ($activeNav ?? '') === 'settings' ? 'active' : '' ?>">
        <i class="bi bi-gear-fill"></i>
        <span class="nav-label">Settings</span>
      </a>

    </nav>

    <div class="sidebar-footer">
      <a href="<?= base_url('admin/profile') ?>" class="admin-profile" style="text-decoration:none">
        <div class="avatar">
          <?= strtoupper(substr(session()->get('admin_name') ?? 'A', 0, 2)) ?>
        </div>
        <div class="admin-info">
          <div class="admin-name"><?= esc(session()->get('admin_name') ?? 'Admin') ?></div>
          <div class="admin-role">Administrator</div>
        </div>
      </a>
    </div>

    <div class="sidebar-toggle" id="sidebarToggle">
      <i class="bi bi-chevron-left" id="toggleIcon"></i>
    </div>

  </aside>

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <!-- ═══ MAIN AREA ══════════════════════════════════════════ -->
  <div class="main-area">

    <header class="topbar">
      <button class="topbar-btn d-md-none" id="mobileMenuBtn">
        <i class="bi bi-list"></i>
      </button>

      <nav class="topbar-breadcrumb d-none d-md-flex">
        <span>Admin</span>
        <i class="bi bi-chevron-right"></i>
        <span class="crumb-active"><?= esc($breadcrumb ?? ($title ?? 'Dashboard')) ?></span>
      </nav>

      <div class="topbar-right">
        <div class="topbar-search d-none d-sm-flex">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Search..." />
        </div>

        <button class="topbar-btn" title="Notifications">
          <i class="bi bi-bell"></i>
          <?php if (!empty($openTickets)): ?>
            <span class="badge-dot"></span>
          <?php endif; ?>
        </button>

        <a href="<?= base_url('admin/logout') ?>" class="topbar-btn" title="Logout">
          <i class="bi bi-box-arrow-right"></i>
        </a>
      </div>
    </header>

    <main class="page-content">
      <?= $this->renderSection('content') ?>
    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>