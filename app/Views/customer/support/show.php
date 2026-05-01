<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        #sidebar {
            width: 250px; min-height: 100vh; background: #1877f2;
            position: fixed; top: 0; left: 0; z-index: 100; transition: all 0.3s;
        }
        #sidebar .sidebar-brand { padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,.15); }
        #sidebar .sidebar-brand span { font-size: 20px; font-weight: 700; color: #fff; }
        #sidebar .sidebar-brand small { font-size: 10px; color: rgba(255,255,255,.6); text-transform: uppercase; letter-spacing: 1px; display: block; }
        #sidebar .nav-link { color: rgba(255,255,255,.75); padding: 10px 24px; border-radius: 0; font-size: 14px; display: flex; align-items: center; gap: 10px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.15); }
        #sidebar .nav-link i { font-size: 16px; }
        #main { margin-left: 250px; min-height: 100vh; display: flex; flex-direction: column; }
        #topbar { background: #fff; border-bottom: 1px solid #e5eaf5; padding: 12px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 99; }
        #topbar .page-title { font-size: 16px; font-weight: 600; color: #111827; margin: 0; }
        #topbar .avatar { width: 36px; height: 36px; background: #1877f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 14px; font-weight: 700; }
        .content { padding: 28px; flex: 1; }
        @media (max-width: 768px) { #sidebar { left: -250px; } #sidebar.show { left: 0; } #main { margin-left: 0; } }

        .ticket-card { background: #fff; border: 1px solid #e5eaf5; border-radius: 14px; padding: 24px; }
        .priority-badge-low    { background: #d1fae5; color: #065f46; }
        .priority-badge-medium { background: #fef3c7; color: #92400e; }
        .priority-badge-high   { background: #fee2e2; color: #991b1b; }
        .status-badge-open     { background: #dbeafe; color: #1e40af; }
        .status-badge-closed   { background: #f3f4f6; color: #374151; }
        .status-badge-pending  { background: #fef3c7; color: #92400e; }

        .message-bubble {
            background: #f3f4f6; border-radius: 12px; padding: 16px 20px;
            font-size: 14px; color: #374151; line-height: 1.6;
            white-space: pre-wrap;
        }
        .message-bubble.admin { background: #eff6ff; }

        .reply-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .reply-avatar.customer { background: #1877f2; }
        .reply-avatar.admin    { background: #7c3aed; }

        .form-control:focus { border-color: #1877f2; box-shadow: 0 0 0 3px rgba(24,119,242,.12); }
        .btn-reply { background: #1877f2; color: #fff; border: none; border-radius: 8px; padding: 10px 24px; font-size: 14px; font-weight: 600; }
        .btn-reply:hover { background: #1259c3; color: #fff; }
    </style>
</head>
<body>

<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<div id="sidebarOverlay" onclick="toggleSidebar()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:99;"></div>

<div id="sidebar">
    <div class="sidebar-brand">
        <span>💻 AITS</span>
        <small>Alin IT Services</small>
    </div>
    <nav class="mt-3">
        <a href="<?= base_url('dashboard') ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= base_url('/') ?>#products" class="nav-link"><i class="bi bi-grid"></i> Products</a>
        <a href="<?= base_url('cart') ?>" class="nav-link">
            <i class="bi bi-basket2"></i> Basket
            <?php if ($cartCount > 0): ?>
                <span class="badge bg-danger rounded-pill ms-auto" style="font-size:11px;"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>
        <a href="<?= base_url('invoices') ?>"  class="nav-link"><i class="bi bi-receipt"></i> Invoices</a>
        <a href="<?= base_url('support') ?>"   class="nav-link active"><i class="bi bi-headset"></i> Support</a>
        <a href="<?= base_url('profile') ?>"   class="nav-link"><i class="bi bi-person"></i> Profile</a>
        <hr style="border-color:rgba(255,255,255,.15);margin:12px 24px;">
        <a href="<?= base_url('logout') ?>" class="nav-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
</div>

<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h1 class="page-title">Ticket #<?= esc($ticket['id']) ?></h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('cart') ?>" class="position-relative text-decoration-none" title="My Basket">
                <i class="bi bi-basket2" style="font-size:20px;color:#6b7280;"></i>
                <?php if ($cartCount > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;"><?= $cartCount ?></span>
                <?php endif; ?>
            </a>
            <div class="avatar"><?= strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?></div>
            <div>
                <div style="font-size:14px;font-weight:600;color:#111827;"><?= esc(session()->get('name') ?? 'Customer') ?></div>
                <div style="font-size:12px;color:#6b7280;"><?= esc(session()->get('email') ?? '') ?></div>
            </div>
        </div>
    </div>

    <div class="content">

        <div class="mb-4">
            <a href="<?= base_url('support') ?>" class="text-decoration-none" style="font-size:13px;color:#6b7280;">
                <i class="bi bi-arrow-left me-1"></i> Back to Tickets
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" style="font-size:13px;">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger mb-4" style="font-size:13px;">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Ticket header -->
        <div class="ticket-card mb-4">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
                <h2 style="font-size:17px;font-weight:700;color:#111827;margin:0;"><?= esc($ticket['subject']) ?></h2>
                <div class="d-flex gap-2">
                    <?php $p = $ticket['priority'] ?? 'medium'; ?>
                    <?php $s = $ticket['status']   ?? 'open'; ?>
                    <span class="badge priority-badge-<?= esc($p) ?>"><?= ucfirst(esc($p)) ?></span>
                    <span class="badge status-badge-<?= esc($s) ?>"><?= ucfirst(esc($s)) ?></span>
                </div>
            </div>
            <p style="font-size:12px;color:#9ca3af;margin-bottom:16px;">
                Opened <?= date('M d, Y \a\t H:i', strtotime($ticket['created_at'])) ?>
            </p>
            <?php foreach ($replies as $reply): ?>
                <div class="reply-bubble <?= $reply['is_admin_reply'] ? 'reply-admin' : 'reply-customer' ?>">
                    <div style="font-size:14px;color:#111827;"><?= nl2br(esc($reply['message'])) ?></div>
                    <div class="reply-meta">
                        <?= $reply['is_admin_reply'] ? '🛡️ AITS Support' : '👤 You' ?>
                        · <?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Replies thread -->
        <?php if (! empty($replies)): ?>
        <div class="mb-4">
            <p style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
                <?= count($replies) ?> <?= count($replies) === 1 ? 'Reply' : 'Replies' ?>
            </p>

            <?php foreach ($replies as $reply): ?>
            <?php $isAdmin = (bool) ($reply['is_admin'] ?? false); ?>
            <div class="d-flex gap-3 mb-3 <?= $isAdmin ? 'flex-row' : 'flex-row-reverse' ?>">
                <div class="reply-avatar <?= $isAdmin ? 'admin' : 'customer' ?>">
                    <?= $isAdmin ? 'A' : strtoupper(substr(session()->get('name') ?? 'C', 0, 1)) ?>
                </div>
                <div style="flex:1;max-width:85%;">
                    <p style="font-size:11px;color:#9ca3af;margin-bottom:6px;<?= $isAdmin ? '' : 'text-align:right;' ?>">
                        <?= $isAdmin ? 'Support Team' : esc(session()->get('name')) ?>
                        · <?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
                    </p>
                    <div class="message-bubble <?= $isAdmin ? 'admin' : '' ?>"><?= esc($reply['message']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Reply form -->
        <?php if (($ticket['status'] ?? 'open') !== 'closed'): ?>
        <div class="ticket-card">
            <h3 style="font-size:15px;font-weight:600;color:#111827;margin-bottom:16px;">Add a Reply</h3>
            <form action="<?= base_url('support/reply/' . $ticket['id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <textarea name="message" class="form-control" rows="4"
                        placeholder="Type your reply..." required></textarea>
                </div>
                <div class="mb-3">
                    <input type="file" name="attachment" class="form-control" accept="image/*,.pdf">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i> Send Reply
                </button>
            </form>
        </div>
        <?php else: ?>
        <div class="ticket-card text-center" style="color:#6b7280;font-size:14px;">
            <i class="bi bi-lock" style="font-size:24px;display:block;margin-bottom:8px;"></i>
            This ticket is closed. <a href="<?= base_url('support/create') ?>">Open a new ticket</a> if you need further assistance.
        </div>
        <?php endif; ?>

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
