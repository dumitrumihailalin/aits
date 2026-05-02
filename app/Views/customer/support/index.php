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
        #sidebar {
            width: 250px; min-height: 100vh; background: #1877f2;
            position: fixed; top: 0; left: 0; z-index: 100;
        }
        #sidebar .brand { padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,.15); }
        #sidebar .brand span { font-size: 20px; font-weight: 700; color: #fff; }
        #sidebar .brand small { font-size: 10px; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; display: block; }
        #sidebar .nav-link { color: rgba(255,255,255,.75); padding: 10px 24px; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        #main { margin-left: 250px; }
        #topbar { background: #fff; border-bottom: 1px solid #e5eaf5; padding: 12px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 99; }
        .content { padding: 28px; }
        .badge-status-open        { background: #dbeafe; color: #1d4ed8; }
        .badge-status-in_progress { background: #fef3c7; color: #92400e; }
        .badge-status-resolved    { background: #d1fae5; color: #065f46; }
        .badge-status-closed      { background: #f3f4f6; color: #374151; }
        .badge-priority-low       { background: #f3f4f6; color: #374151; }
        .badge-priority-medium    { background: #dbeafe; color: #1d4ed8; }
        .badge-priority-high      { background: #fef3c7; color: #92400e; }
        .badge-priority-urgent    { background: #fee2e2; color: #991b1b; }
        #sidebar { transition: left .3s; }
        @media (max-width: 768px) { #sidebar { left: -250px; } #sidebar.show { left: 0; } #main { margin-left: 0; } }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<?= view('partials/customer_sidebar', ['activeNav' => 'support', 'cartCount' => $cartCount]) ?>

<!-- Main -->
<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;"><?= lang('Customer.page_support') ?></h1>
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

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 style="font-size:18px;font-weight:700;color:#111827;margin:0;"><?= lang('Customer.tickets_my') ?></h2>
                <p style="font-size:14px;color:#6b7280;margin:0;"><?= lang('Customer.tickets_subtitle') ?></p>
            </div>
            <a href="<?= base_url('support/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i> <?= lang('Customer.tickets_new_btn') ?>
            </a>
        </div>

        <!-- Tickets table -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <?php if (empty($tickets)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-headset" style="font-size:40px;"></i>
                        <p class="mt-3"><?= lang('Customer.tickets_empty') ?> <a href="<?= base_url('support/create') ?>"><?= lang('Customer.tickets_open_first') ?></a>.</p>
                    </div>
                <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
                        <tr>
                            <th class="ps-4 py-3"><?= lang('Customer.tickets_col_id') ?></th>
                            <th><?= lang('Customer.tickets_col_subject') ?></th>
                            <th><?= lang('Customer.tickets_col_priority') ?></th>
                            <th><?= lang('Customer.col_status') ?></th>
                            <th><?= lang('Customer.tickets_col_created') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td class="ps-4 text-muted">#<?= $ticket['id'] ?></td>
                            <td>
                                <div style="font-weight:600;color:#111827;"><?= esc($ticket['subject']) ?></div>
                                <div style="font-size:12px;color:#6b7280;"><?= esc(substr($ticket['description'], 0, 60)) ?>...</div>
                            </td>
                            <td>
                                <span class="badge badge-priority-<?= $ticket['priority'] ?> rounded-pill px-3">
                                    <?= ucfirst($ticket['priority']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-status-<?= $ticket['status'] ?> rounded-pill px-3">
                                    <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                </span>
                            </td>
                            <td style="color:#6b7280;font-size:13px;">
                                <?= date('M d, Y', strtotime($ticket['created_at'])) ?>
                            </td>
                            <td>
                                <a href="<?= base_url('support/' . $ticket['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    <?= lang('Customer.btn_view') ?>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
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