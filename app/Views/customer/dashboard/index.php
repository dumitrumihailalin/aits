<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Customer.page_dashboard') ?> — AITS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }

        /* Sidebar */
        #sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1877f2;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: all 0.3s;
        }
        #sidebar .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        #sidebar .sidebar-brand span {
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        #sidebar .sidebar-brand small {
            font-size: 10px;
            color: rgba(255,255,255,.6);
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 10px 24px;
            border-radius: 0;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.15);
        }
        #sidebar .nav-link i { font-size: 16px; }

        /* Main content */
        #main {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e5eaf5;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        #topbar .page-title {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }
        #topbar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #topbar .avatar {
            width: 36px;
            height: 36px;
            background: #1877f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e5eaf5;
            border-radius: 12px;
            padding: 20px 24px;
        }
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }
        .stat-card .stat-label {
            font-size: 13px;
            color: #6b7280;
        }

        /* Content area */
        .content { padding: 28px; flex: 1; }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { left: -250px; }
            #sidebar.show { left: 0; }
            #main { margin-left: 0; }
        }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<?= view('partials/customer_sidebar', ['activeNav' => 'dashboard', 'cartCount' => $cartCount]) ?>

<!-- Main -->
<div id="main">

    <!-- Topbar -->
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h1 class="page-title"><?= lang('Customer.page_dashboard') ?></h1>
        </div>
        <div class="user-info">
            <a href="<?= base_url('cart') ?>" class="position-relative text-decoration-none me-1" title="My Basket">
                <i class="bi bi-basket2" style="font-size:20px;color:#6b7280;"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <div class="avatar">
                <?= strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?>
            </div>
            <div>
                <div style="font-size:14px;font-weight:600;color:#111827;">
                    <?= esc(session()->get('name') ?? 'Customer') ?>
                </div>
                <div style="font-size:12px;color:#6b7280;">
                    <?= esc(session()->get('email') ?? '') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- Welcome -->
        <div class="mb-4">
            <h2 style="font-size:18px;font-weight:700;color:#111827;">
                <?= lang('Customer.welcome_back') ?>, <?= esc(session()->get('name') ?? 'Customer') ?>
            </h2>
            <p style="font-size:14px;color:#6b7280;margin:0;">
                <?= lang('Customer.account_summary') ?>
            </p>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="stat-icon bg-primary bg-opacity-10">
                            <i class="bi bi-box-seam text-primary"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $activeCount ?></div>
                    <div class="stat-label"><?= lang('Customer.active_services') ?></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="stat-icon bg-success bg-opacity-10">
                            <i class="bi bi-receipt text-success"></i>
                        </div>
                    </div>
                    <div class="stat-value">$<?= number_format($monthlyTotal, 2) ?></div>
                    <div class="stat-label"><?= lang('Customer.monthly_total') ?></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="stat-icon bg-warning bg-opacity-10">
                            <i class="bi bi-headset text-warning"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $openTickets ?></div>
                    <div class="stat-label"><?= lang('Customer.open_tickets') ?></div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="stat-icon bg-info bg-opacity-10">
                            <i class="bi bi-clock-history text-info"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $nextInvoice ? date('M d', strtotime($nextInvoice)) : '—' ?></div>
                    <div class="stat-label"><?= lang('Customer.next_invoice_due') ?></div>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <?php if (! empty($activeProducts)): ?>
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 style="font-size:15px;font-weight:600;color:#111827;margin:0;"><?= lang('Customer.my_services') ?></h3>
                        <a href="<?= base_url('cart') ?>" class="btn btn-sm btn-outline-primary"><?= lang('Customer.manage_basket') ?></a>
                    </div>
                    <div class="row g-3">
                        <?php foreach ($activeProducts as $item): ?>
                        <div class="col-sm-6 col-lg-4">
                            <div style="border:1px solid #e5eaf5;border-radius:10px;padding:16px;display:flex;align-items:center;gap:14px;">
                                <div style="width:42px;height:42px;min-width:42px;border-radius:10px;background:<?= esc($item['color'] ?? '#1877f2') ?>22;display:flex;align-items:center;justify-content:center;font-size:20px;color:<?= esc($item['color'] ?? '#1877f2') ?>;">
                                    <i class="bi <?= esc($item['icon'] ?? 'bi-box-seam') ?>"></i>
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:14px;font-weight:600;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= esc($item['product_name']) ?></div>
                                    <div style="font-size:12px;color:#6b7280;">$<?= number_format($item['price'], 2) ?> / month</div>
                                    <?php if ($item['status'] === 'active'): ?>
                                        <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dcfce7;color:#16a34a;font-weight:600;"><?= lang('Customer.status_active') ?></span>
                                    <?php else: ?>
                                        <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#dbeafe;color:#1d4ed8;font-weight:600;"><?= lang('Customer.status_in_basket') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Invoices + Quick Actions -->
        <div class="row g-3">
            <div class="col-lg-8">
                <div class="stat-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 style="font-size:15px;font-weight:600;color:#111827;margin:0;"><?= lang('Customer.recent_invoices') ?></h3>
                        <a href="<?= base_url('invoices') ?>" class="btn btn-sm btn-outline-primary"><?= lang('Customer.view_all') ?></a>
                    </div>
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr style="font-size:12px;color:#6b7280;">
                                <th><?= lang('Customer.col_invoice') ?></th>
                                <th><?= lang('Customer.col_date') ?></th>
                                <th><?= lang('Customer.col_amount') ?></th>
                                <th><?= lang('Customer.col_status') ?></th>
                            </tr>
                        </thead>
                        <tbody style="font-size:13px;">
                            <?php if (! empty($recentInvoices)): ?>
                                <?php foreach ($recentInvoices as $inv): ?>
                                <tr>
                                    <td><?= esc($inv['invoice_number']) ?></td>
                                    <td><?= date('M d, Y', strtotime($inv['issue_date'] ?? $inv['created_at'])) ?></td>
                                    <td>$<?= number_format($inv['total_amount'] ?? $inv['amount'], 2) ?></td>
                                    <td>
                                        <?php if ($inv['status'] === 'paid'): ?>
                                            <span class="badge bg-success"><?= lang('Customer.status_paid') ?></span>
                                        <?php elseif ($inv['status'] === 'unpaid'): ?>
                                            <span class="badge bg-warning text-dark"><?= lang('Customer.status_unpaid') ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= esc(ucfirst($inv['status'])) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3"><?= lang('Customer.no_invoices_yet') ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="stat-card">
                    <h3 style="font-size:15px;font-weight:600;color:#111827;margin:0 0 16px;"><?= lang('Customer.quick_actions') ?></h3>
                    <div class="d-grid gap-2">
                        <a href="<?= base_url('support/create') ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-2"></i><?= lang('Customer.open_ticket_btn') ?>
                        </a>
                        <a href="<?= base_url('invoices') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-download me-2"></i><?= lang('Customer.download_invoice_btn') ?>
                        </a>
                        <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-2"></i><?= lang('Customer.edit_profile_btn') ?>
                        </a>
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