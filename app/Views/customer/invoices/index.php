<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AITS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body{background:#f0f2f5;}
        #sidebar{width:250px;min-height:100vh;background:#1877f2;position:fixed;top:0;left:0;z-index:100;}
        #sidebar .brand{padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.15);}
        #sidebar .brand span{font-size:20px;font-weight:700;color:#fff;}
        #sidebar .brand small{font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;display:block;}
        #sidebar .nav-link{color:rgba(255,255,255,.75);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px;}
        #sidebar .nav-link:hover,#sidebar .nav-link.active{color:#fff;background:rgba(255,255,255,.15);}
        #main{margin-left:250px;}
        #topbar{background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99;}
        .content{padding:28px;}
        .badge-paid{background:#d1fae5;color:#065f46;}
        .badge-unpaid{background:#fee2e2;color:#991b1b;}
        #sidebar{transition:left .3s;}
        @media(max-width:768px){#sidebar{left:-250px;}#sidebar.show{left:0;}#main{margin-left:0;}}
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
        <a href="<?= base_url('invoices') ?>" class="nav-link active"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="<?= base_url('support') ?>" class="nav-link"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('profile') ?>" class="nav-link"><i class="bi bi-person"></i> Profile</a>
        <hr style="border-color:rgba(255,255,255,.15);margin:12px 24px;">
        <a href="<?= base_url('logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>
<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;">Invoices</h1>
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

        <div class="mb-4">
            <h2 style="font-size:18px;font-weight:700;color:#111827;margin:0;">My Invoices</h2>
            <p style="font-size:14px;color:#6b7280;margin:0;">View and pay your invoices.</p>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <?php if (empty($invoices)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-receipt" style="font-size:40px;"></i>
                        <p class="mt-3">No invoices yet.</p>
                    </div>
                <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead style="background:#f8faff;font-size:12px;color:#6b7280;">
                        <tr>
                            <th class="ps-4 py-3">Invoice #</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px;">
                        <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td class="ps-4">
                                <span style="font-weight:600;color:#111827;"><?= esc($invoice['invoice_number']) ?></span>
                            </td>
                            <td style="color:#6b7280;font-size:13px;">
                                <?= esc(substr($invoice['description'] ?? 'N/A', 0, 50)) ?>
                            </td>
                            <td style="font-weight:700;color:#111827;">
                                $<?= number_format($invoice['amount'], 2) ?>
                            </td>
                            <td style="color:#6b7280;font-size:13px;">
                                <?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : 'N/A' ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3">
                                    <?= ucfirst($invoice['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('invoices/' . $invoice['id']) ?>" class="btn btn-sm btn-outline-primary">
                                    View
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