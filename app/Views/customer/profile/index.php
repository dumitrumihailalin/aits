<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AITS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f0f2f5; }
        #sidebar { width:250px;min-height:100vh;background:#1877f2;position:fixed;top:0;left:0;z-index:100; }
        #sidebar .brand { padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.15); }
        #sidebar .brand span { font-size:20px;font-weight:700;color:#fff; }
        #sidebar .brand small { font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;display:block; }
        #sidebar .nav-link { color:rgba(255,255,255,.75);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color:#fff;background:rgba(255,255,255,.15); }
        #sidebar { transition: left .3s; }
        #main { margin-left:250px; }
        @media (max-width: 768px) {
            #sidebar { left: -250px; }
            #sidebar.show { left: 0; }
            #main { margin-left: 0; }
        }
        #topbar { background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99; }
        .content { padding:28px; }
        .avatar { width:36px;height:36px;background:#1877f2;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:700;overflow:hidden; }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<div id="sidebar">
    <div class="brand"><span>💻 AITS</span><small>Alin IT Services</small></div>
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= base_url('/') ?>#products" class="nav-link"><i class="bi bi-grid"></i> Products</a>
        <a href="<?= base_url('cart') ?>" class="nav-link">
            <i class="bi bi-basket2"></i> Basket
            <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger rounded-pill ms-auto" style="font-size:11px;"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= base_url('invoices') ?>" class="nav-link"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="<?= base_url('support') ?>" class="nav-link"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('profile') ?>" class="nav-link active"><i class="bi bi-person"></i> Profile</a>
        <hr style="border-color:rgba(255,255,255,.15);margin:12px 24px;">
        <a href="<?= base_url('logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>

<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;">My Profile</h1>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="avatar">
                <?php if (! empty(session()->get('logo'))): ?>
                    <img src="<?= base_url('uploads/logos/' . session()->get('logo')) ?>"
                         style="width:36px;height:36px;object-fit:cover;">
                <?php else: ?>
                    <?= strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div>
                <div style="font-size:14px;font-weight:600;color:#111827;"><?= esc(session()->get('name')) ?></div>
                <div style="font-size:12px;color:#6b7280;"><?= esc(session()->get('email')) ?></div>
            </div>
        </div>
    </div>

    <div class="content">

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
                 style="background:#1877f2;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
                <i class="bi bi-check-circle-fill" style="font-size:18px;"></i>
                <span><?= session()->getFlashdata('success') ?></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 mb-4"
                 style="background:#dc2626;border:none;border-radius:10px;color:#fff;font-size:14px;padding:14px 20px;">
                <i class="bi bi-exclamation-circle-fill" style="font-size:18px;"></i>
                <span><?= session()->getFlashdata('error') ?></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="filter:brightness(0) invert(1);"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- Profile + Logo -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h5 style="font-weight:700;color:#111827;margin-bottom:24px;">Company Logo & Information</h5>

                        <!-- Logo preview -->
                        <div class="d-flex align-items-center gap-4 mb-4">
                            <div id="logoContainer"
                                 style="width:80px;height:80px;border-radius:12px;overflow:hidden;border:2px solid #e5eaf5;flex-shrink:0;">
                                <?php if (! empty($user['logo'])): ?>
                                    <img id="logoImg"
                                         src="<?= base_url('uploads/logos/' . $user['logo']) ?>"
                                         style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <div id="logoPlaceholder"
                                         style="width:100%;height:100%;background:#1877f2;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div style="font-size:15px;font-weight:600;color:#111827;"><?= esc($user['company_name'] ?? $user['name']) ?></div>
                                <div style="font-size:13px;color:#6b7280;margin-bottom:8px;">JPG, PNG or WebP. Max 2MB.</div>
                                <label for="logoInput" class="btn btn-sm btn-outline-primary" style="cursor:pointer;">
                                    <i class="bi bi-upload me-1"></i> Upload Logo
                                </label>
                            </div>
                        </div>

                        <form action="<?= base_url('profile') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <!-- Hidden file input triggered by label -->
                            <input type="file" name="logo" id="logoInput"
                                   accept="image/jpeg,image/png,image/webp"
                                   style="display:none;"
                                   onchange="previewLogo(this)">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Full Name</label>
                                    <input type="text" name="name" class="form-control"
                                           value="<?= esc($user['name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Email</label>
                                    <input type="email" class="form-control"
                                           value="<?= esc($user['email']) ?>" disabled>
                                    <div class="form-text">Email cannot be changed.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                           value="<?= esc($user['company_name'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                           value="<?= esc($user['phone'] ?? '') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Address</label>
                                    <input type="text" name="address" class="form-control"
                                           value="<?= esc($user['address'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">Country</label>
                                    <input type="text" name="country" class="form-control"
                                           value="<?= esc($user['country'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size:14px;">City</label>
                                    <input type="text" name="city" class="form-control"
                                           value="<?= esc($user['city'] ?? '') ?>">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column -->
            <div class="col-lg-4">

                <!-- Change password -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-4">
                        <h5 style="font-weight:700;color:#111827;margin-bottom:24px;">Change Password</h5>
                        <form action="<?= base_url('profile') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:14px;">Current Password</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:14px;">New Password</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="font-size:14px;">Confirm New Password</label>
                                <input type="password" name="new_password_confirm" class="form-control">
                            </div>
                            <input type="hidden" name="name" value="<?= esc($user['name']) ?>">
                            <input type="hidden" name="company_name" value="<?= esc($user['company_name'] ?? '') ?>">
                            <input type="hidden" name="phone" value="<?= esc($user['phone'] ?? '') ?>">
                            <input type="hidden" name="address" value="<?= esc($user['address'] ?? '') ?>">
                            <input type="hidden" name="country" value="<?= esc($user['country'] ?? '') ?>">
                            <input type="hidden" name="city" value="<?= esc($user['city'] ?? '') ?>">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-lock me-1"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-4">
                        <h5 style="font-weight:700;color:#111827;margin-bottom:16px;">Notifications</h5>
                        <form action="<?= base_url('profile/notifications') ?>" method="POST">
                            <?= csrf_field() ?>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       name="notify_ticket_updates" id="notifyTickets" value="1"
                                       <?= $user['notify_ticket_updates'] ? 'checked' : '' ?>
                                       onchange="this.form.submit()">
                                <label class="form-check-label" for="notifyTickets" style="font-size:14px;">
                                    Email me when my ticket is updated
                                </label>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account info -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 style="font-weight:700;color:#111827;margin-bottom:16px;">Account Info</h5>
                        <table style="font-size:13px;width:100%;">
                            <tr>
                                <td style="color:#6b7280;padding-bottom:8px;">Role</td>
                                <td class="text-end"><span class="badge bg-primary rounded-pill">Customer</span></td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280;padding-bottom:8px;">Verified</td>
                                <td class="text-end">
                                    <?php if ($user['email_verified_at']): ?>
                                        <span class="badge rounded-pill" style="background:#d1fae5;color:#065f46;">✓ Verified</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill" style="background:#fee2e2;color:#991b1b;">Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="color:#6b7280;">Member since</td>
                                <td class="text-end" style="color:#111827;">
                                    <?= date('M Y', strtotime($user['created_at'])) ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('show');
    o.style.display = s.classList.contains('show') ? 'block' : 'none';
}
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const container = document.getElementById('logoContainer');
            container.innerHTML = `<img src="${e.target.result}"
                style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>