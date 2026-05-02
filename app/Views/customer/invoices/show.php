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
        #sidebar{width:250px;min-height:100vh;background:#1877f2;position:fixed;top:0;left:0;z-index:100;transition:left .3s;}
        #sidebar .brand{padding:20px 24px;border-bottom:1px solid rgba(255,255,255,.15);}
        #sidebar .brand span{font-size:20px;font-weight:700;color:#fff;}
        #sidebar .brand small{font-size:10px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;display:block;}
        #sidebar .nav-link{color:rgba(255,255,255,.75);padding:10px 24px;font-size:14px;display:flex;align-items:center;gap:10px;}
        #sidebar .nav-link:hover,#sidebar .nav-link.active{color:#fff;background:rgba(255,255,255,.15);}
        #main{margin-left:250px;}
        @media(max-width:768px){#sidebar{left:-250px;}#sidebar.show{left:0;}#main{margin-left:0;}}
        #topbar{background:#fff;border-bottom:1px solid #e5eaf5;padding:12px 28px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:99;}
        .content{padding:28px;}
        .invoice-box{background:#fff;border:1px solid #e5eaf5;border-radius:16px;padding:40px;}
        .badge-paid{background:#d1fae5;color:#065f46;}
        .badge-unpaid{background:#fee2e2;color:#991b1b;}
        /* Pay modal */
        .pay-modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center;}
        .pay-modal-overlay.show{display:flex;}
        .pay-modal{background:#fff;border-radius:16px;padding:36px 32px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,.2);}
        .pay-modal h3{font-size:18px;font-weight:700;color:#111827;margin-bottom:4px;}
        .pay-modal .subtitle{font-size:13px;color:#6b7280;margin-bottom:24px;}
        .form-label-sm{font-size:12px;font-weight:600;color:#374151;margin-bottom:4px;display:block;}
        .pay-input{width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;color:#111827;outline:none;transition:border-color .2s;}
        .pay-input:focus{border-color:#1877f2;box-shadow:0 0 0 3px rgba(24,119,242,.15);}
        .pay-input::placeholder{color:#9ca3af;}
        .card-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        .btn-pay{width:100%;padding:12px;background:#1877f2;color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:700;cursor:pointer;transition:background .2s;margin-top:20px;}
        .btn-pay:hover{background:#1462c8;}
        .btn-pay:disabled{background:#93c5fd;cursor:not-allowed;}
        .secure-note{display:flex;align-items:center;justify-content:center;gap:6px;font-size:11px;color:#9ca3af;margin-top:12px;}
    </style>
</head>
<body>
<?php $cartCount = model('App\Models\CartModel')->getCount(session()->get('user_id')); ?>

<?= view('partials/customer_sidebar', ['activeNav' => 'invoices', 'cartCount' => $cartCount]) ?>
<div id="main">
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm d-md-none" onclick="toggleSidebar()"><i class="bi bi-list fs-5"></i></button>
            <a href="<?= base_url('invoices') ?>" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex"><i class="bi bi-arrow-left"></i></a>
            <h1 style="font-size:16px;font-weight:600;color:#111827;margin:0;"><?= esc($invoice['invoice_number']) ?></h1>
            <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3"><?= ucfirst($invoice['status']) ?></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="<?= base_url('invoices/' . esc($invoice['id']) . '/pdf') ?>" target="_blank"
               class="btn btn-sm btn-primary d-none d-md-inline-flex align-items-center gap-1">
                <i class="bi bi-file-earmark-pdf"></i> <?= lang('Customer.invoice_download_pdf') ?>
            </a>
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

        <?php if ($msg = session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= esc($msg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="invoice-box shadow-sm">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 style="font-size:24px;font-weight:800;color:#1877f2;">💻 AITS</h2>
                            <p style="font-size:13px;color:#6b7280;margin:0;">Alin IT Services<br>alinitservices.com</p>
                        </div>
                        <div class="text-end">
                            <div style="font-size:20px;font-weight:700;color:#111827;"><?= esc($invoice['invoice_number']) ?></div>
                            <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3 mt-1"><?= ucfirst($invoice['status']) ?></span>
                        </div>
                    </div>

                    <hr style="border-color:#e5eaf5;">

                    <!-- Dates -->
                    <div class="row mb-4">
                        <div class="col-4">
                            <div style="font-size:12px;color:#6b7280;"><?= lang('Customer.invoice_issue_date') ?></div>
                            <div style="font-size:14px;font-weight:600;color:#111827;">
                                <?= $invoice['issue_date'] ? date('M d, Y', strtotime($invoice['issue_date'])) : date('M d, Y', strtotime($invoice['created_at'])) ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:12px;color:#6b7280;"><?= lang('Customer.col_due_date') ?></div>
                            <div style="font-size:14px;font-weight:600;color:<?= $invoice['status'] === 'unpaid' ? '#dc2626' : '#111827' ?>;">
                                <?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:12px;color:#6b7280;"><?= lang('Customer.col_status') ?></div>
                            <div style="font-size:14px;font-weight:600;">
                                <span class="badge badge-<?= $invoice['status'] ?> rounded-pill px-3"><?= ucfirst($invoice['status']) ?></span>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color:#e5eaf5;">

                    <!-- Line items -->
                    <div style="background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:16px 20px;margin-bottom:24px;">
                        <table class="table table-sm mb-0">
                            <thead style="font-size:12px;color:#6b7280;">
                                <tr>
                                    <th><?= lang('Customer.invoice_col_product') ?></th>
                                    <th class="text-center"><?= lang('Customer.invoice_col_qty') ?></th>
                                    <th class="text-end"><?= lang('Customer.invoice_col_unit_price') ?></th>
                                    <th class="text-end"><?= lang('Customer.invoice_col_line_total') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($items)): ?>
                                    <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td style="font-size:14px;color:#111827;font-weight:500;"><?= esc($item['product_name']) ?>
                                            <?php if ($item['description']): ?>
                                                <div style="font-size:12px;color:#6b7280;font-weight:400;"><?= esc($item['description']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center" style="font-size:14px;color:#6b7280;"><?= (int) $item['quantity'] ?></td>
                                        <td class="text-end" style="font-size:14px;color:#6b7280;">$<?= number_format($item['unit_price'], 2) ?></td>
                                        <td class="text-end" style="font-size:14px;font-weight:600;color:#111827;">$<?= number_format($item['total_price'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="font-size:14px;color:#111827;"><?= esc($invoice['description'] ?? 'Service') ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($invoice['notes']): ?>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:24px;">
                        <strong><?= lang('Customer.invoice_notes') ?>:</strong> <?= esc($invoice['notes']) ?>
                    </div>
                    <?php endif; ?>

                    <!-- Total -->
                    <div style="background:#1877f2;border-radius:10px;padding:16px 24px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color:rgba(255,255,255,.8);font-size:14px;"><?= lang('Customer.invoice_total_due') ?></span>
                            <span style="color:#fff;font-size:24px;font-weight:800;">$<?= number_format($invoice['total_amount'] ?? $invoice['amount'], 2) ?></span>
                        </div>
                    </div>

                    <?php if ($invoice['status'] === 'unpaid'): ?>
                    <div class="mt-4 p-3 rounded-3 d-flex align-items-center gap-3" style="background:#fef9c3;border:1px solid #fde047;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:#92400e;font-size:20px;flex-shrink:0;"></i>
                        <div style="font-size:13px;color:#92400e;">
                            <?= lang('Customer.invoice_unpaid_notice') ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action buttons -->
                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <?php if ($invoice['status'] === 'unpaid'): ?>
                        <button type="button"
                                onclick="openPayModal('<?= esc($invoice['id']) ?>', <?= (float)($invoice['total_amount'] ?? $invoice['amount']) ?>, '<?= esc($invoice['invoice_number']) ?>')"
                                class="btn btn-success d-flex align-items-center gap-2">
                            <i class="bi bi-credit-card fs-5"></i>
                            <span><?= lang('Customer.invoice_pay_now') ?></span>
                        </button>
                        <?php endif; ?>
                        <a href="<?= base_url('invoices/' . esc($invoice['id']) . '/pdf') ?>" target="_blank"
                           class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-pdf fs-5"></i>
                            <span><?= lang('Customer.invoice_download_full') ?></span>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Pay Modal -->
<div class="pay-modal-overlay" id="payModalOverlay" onclick="handleOverlayClick(event)">
    <div class="pay-modal">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <h3><?= lang('Customer.invoice_pay_title') ?></h3>
            <button onclick="closePayModal()" style="background:none;border:none;cursor:pointer;font-size:20px;color:#9ca3af;line-height:1;">&times;</button>
        </div>
        <p class="subtitle" id="paySubtitle"><?= lang('Customer.invoice_pay_subtitle') ?></p>

        <form id="payForm" onsubmit="handlePaySubmit(event)">
            <input type="hidden" id="payInvoiceId">

            <div class="mb-3">
                <label class="form-label-sm"><?= lang('Customer.invoice_card_number') ?></label>
                <input type="text" id="cardNumber" class="pay-input" placeholder="1234 5678 9012 3456"
                       maxlength="19" inputmode="numeric" autocomplete="cc-number" required>
            </div>

            <div class="mb-3">
                <label class="form-label-sm"><?= lang('Customer.invoice_cardholder') ?></label>
                <input type="text" id="cardName" class="pay-input" placeholder="Name on card"
                       autocomplete="cc-name" required>
            </div>

            <div class="card-row">
                <div>
                    <label class="form-label-sm"><?= lang('Customer.invoice_expiry') ?></label>
                    <input type="text" id="cardExpiry" class="pay-input" placeholder="MM / YY"
                           maxlength="7" inputmode="numeric" autocomplete="cc-exp" required>
                </div>
                <div>
                    <label class="form-label-sm"><?= lang('Customer.invoice_cvc') ?></label>
                    <input type="text" id="cardCvc" class="pay-input" placeholder="CVC"
                           maxlength="4" inputmode="numeric" autocomplete="cc-csc" required>
                </div>
            </div>

            <button type="submit" class="btn-pay" id="payBtn">
                <span id="payBtnText">Pay <span id="payAmount"></span></span>
            </button>
        </form>

        <div class="secure-note">
            <i class="bi bi-lock-fill"></i>
            <span><?= lang('Customer.invoice_secure') ?></span>
        </div>
    </div>
</div>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('sidebarOverlay');
    s.classList.toggle('show');
    o.style.display = s.classList.contains('show') ? 'block' : 'none';
}

function openPayModal(invoiceId, amount, invoiceNumber) {
    document.getElementById('payInvoiceId').value = invoiceId;
    document.getElementById('paySubtitle').textContent = 'Invoice ' + invoiceNumber;
    document.getElementById('payAmount').textContent = '$' + amount.toFixed(2);
    document.getElementById('payModalOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closePayModal() {
    document.getElementById('payModalOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('payModalOverlay')) closePayModal();
}

// Card number formatting: add spaces every 4 digits
document.getElementById('cardNumber').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});

// Expiry formatting: MM / YY
document.getElementById('cardExpiry').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 3) v = v.substring(0, 2) + ' / ' + v.substring(2);
    this.value = v;
});

// CVC: digits only
document.getElementById('cardCvc').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').substring(0, 4);
});

function handlePaySubmit(e) {
    e.preventDefault();
    const btn = document.getElementById('payBtn');
    const btnText = document.getElementById('payBtnText');
    btn.disabled = true;
    btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing…';
    // TODO: integrate Stripe PaymentIntent here
    setTimeout(() => {
        btn.disabled = false;
        btnText.innerHTML = 'Pay <span id="payAmount">' + document.getElementById('payAmount').textContent + '</span>';
        alert('Stripe integration coming soon.');
    }, 1500);
}
</script>
</body>
</html>
