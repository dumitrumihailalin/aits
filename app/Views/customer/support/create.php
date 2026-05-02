<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AITS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        #sidebar { width:250px;min-height:100vh;background:#1877f2;position:fixed;top:0;left:0;z-index:100; }
        #sidebar .brand { padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.15); }
        #sidebar .brand span { font-size:20px;font-weight:700;color:#fff; }
        #sidebar .brand small { font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;display:block; }
        #sidebar .nav-link { color:rgba(255,255,255,.75);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color:#fff;background:rgba(255,255,255,.15); }
        #sidebar { transition: left .3s; }
        #main { margin-left:250px; }
        @media (max-width: 768px) { #sidebar { left: -250px; } #sidebar.show { left: 0; } #main { margin-left: 0; } }
        #topbar { background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99; }
        .content { padding:28px; }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<?= view('partials/customer_sidebar', ['activeNav' => 'support', 'cartCount' => $cartCount]) ?>

<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <a href="<?= base_url('support') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;"><?= lang('Customer.ticket_new_title') ?></h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('cart') ?>" class="position-relative text-decoration-none" title="My Basket">
                <i class="bi bi-basket2" style="font-size:20px;color:#6b7280;"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <div style="font-size:14px;color:#6b7280;"><?= esc(session()->get('name')) ?></div>
        </div>
    </div>

    <div class="content">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h5 class="fw-700 mb-4" style="color:#111827;"><?= lang('Customer.ticket_details') ?></h5>

                        <form action="<?= base_url('support/store') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:14px;"><?= lang('Customer.ticket_subject_label') ?></label>
                                <input type="text" name="subject" class="form-control"
                                    placeholder="<?= lang('Customer.ticket_subject_ph') ?>"
                                    value="<?= old('subject') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:14px;"><?= lang('Customer.ticket_priority_label') ?></label>
                                <select name="priority" class="form-select" required>
                                    <option value="low"    <?= old('priority') === 'low'    ? 'selected' : '' ?>>🟢 Low</option>
                                    <option value="medium" <?= old('priority') === 'medium' ? 'selected' : '' ?> selected>🔵 Medium</option>
                                    <option value="high"   <?= old('priority') === 'high'   ? 'selected' : '' ?>>🟠 High</option>
                                    <option value="urgent" <?= old('priority') === 'urgent' ? 'selected' : '' ?>>🔴 Urgent</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" style="font-size:14px;"><?= lang('Customer.ticket_desc_label') ?></label>
                                <textarea name="description" class="form-control" rows="6"
                                    placeholder="<?= lang('Customer.ticket_desc_ph') ?>"
                                    required><?= old('description') ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" style="font-size:14px;"><?= lang('Customer.ticket_attachment_label') ?> <span class="text-muted fw-normal"><?= lang('Customer.ticket_attachment_opt') ?></span></label>
                                <input type="file" name="image" class="form-control" accept="image/*,.pdf">
                                <div class="form-text"><?= lang('Customer.ticket_attachment_hint') ?></div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-send me-1"></i> <?= lang('Customer.ticket_submit') ?>
                                </button>
                                <a href="<?= base_url('support') ?>" class="btn btn-outline-secondary px-4"><?= lang('Customer.btn_cancel') ?></a>
                            </div>
                        </form>
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
</script>
</body>
</html>