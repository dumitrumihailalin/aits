<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= esc($invoice['invoice_number']) ?> — AITS</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #111827; background: #fff; }

        /* Screen wrapper */
        .page-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .print-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary { background: #1877f2; color: #fff; }
        .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

        /* Invoice layout */
        .invoice {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }
        .invoice-header {
            background: #1877f2;
            color: #fff;
            padding: 32px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-name { font-size: 24px; font-weight: 800; }
        .company-sub  { font-size: 12px; opacity: .75; margin-top: 4px; }
        .invoice-title { text-align: right; }
        .invoice-title .inv-num { font-size: 20px; font-weight: 700; }
        .invoice-title .inv-status {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-paid   { background: #d1fae5; color: #065f46; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }

        .invoice-body { padding: 36px 40px; }

        .meta-row {
            display: flex;
            gap: 40px;
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        .meta-block label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 4px; }
        .meta-block span  { font-size: 14px; font-weight: 600; color: #111827; }
        .meta-block.bill-to { margin-left: auto; text-align: right; }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.items thead tr {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
        }
        table.items th {
            padding: 10px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6b7280;
            font-weight: 600;
        }
        table.items td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
            color: #374151;
        }
        table.items td.name { color: #111827; font-weight: 500; }
        table.items td.desc { font-size: 11px; color: #9ca3af; }
        .text-center { text-align: center; }
        .text-right  { text-align: right; }
        .fw-bold     { font-weight: 700; color: #111827; }

        .totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .totals-box { width: 260px; }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
            color: #6b7280;
            border-bottom: 1px solid #f3f4f6;
        }
        .totals-row.grand {
            background: #1877f2;
            color: #fff;
            padding: 10px 14px;
            border-radius: 8px;
            margin-top: 8px;
            border-bottom: none;
            font-size: 15px;
            font-weight: 700;
        }
        .totals-row.grand span:last-child { font-size: 18px; }

        .notes-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px 20px;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
        }
        .notes-box strong { color: #374151; }

        .unpaid-notice {
            background: #fef9c3;
            border: 1px solid #fde047;
            border-radius: 8px;
            padding: 14px 20px;
            margin-top: 20px;
            font-size: 12px;
            color: #92400e;
        }

        .invoice-footer {
            border-top: 1px solid #e5e7eb;
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            color: #9ca3af;
        }

        /* Print styles */
        @media print {
            body { background: #fff; }
            .page-wrapper { max-width: 100%; padding: 0; margin: 0; }
            .print-toolbar { display: none !important; }
            .invoice { border: none; border-radius: 0; }
            .invoice-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .totals-row.grand { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <!-- Toolbar (hidden when printing) -->
    <div class="print-toolbar">
        <a href="<?= base_url('invoices/' . esc($invoice['id'])) ?>" class="btn btn-secondary">
            ← Back to Invoice
        </a>
        <button class="btn btn-primary" onclick="window.print()">
            ⬇ Download / Print PDF
        </button>
    </div>

    <!-- Invoice -->
    <div class="invoice">

        <!-- Blue header -->
        <div class="invoice-header">
            <div>
                <div class="company-name">💻 AITS</div>
                <div class="company-sub">Alin IT Services<br>alinitservices.com<br>support@alinitservices.com</div>
            </div>
            <div class="invoice-title">
                <div class="inv-num"><?= esc($invoice['invoice_number']) ?></div>
                <div>
                    <span class="inv-status status-<?= esc($invoice['status']) ?>">
                        <?= ucfirst(esc($invoice['status'])) ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="invoice-body">

            <!-- Dates / Bill To -->
            <div class="meta-row">
                <div class="meta-block">
                    <label>Issue Date</label>
                    <span><?= $invoice['issue_date'] ? date('M d, Y', strtotime($invoice['issue_date'])) : date('M d, Y', strtotime($invoice['created_at'])) ?></span>
                </div>
                <div class="meta-block">
                    <label>Due Date</label>
                    <span><?= $invoice['due_date'] ? date('M d, Y', strtotime($invoice['due_date'])) : '—' ?></span>
                </div>
                <?php if ($invoice['status'] === 'paid' && $invoice['paid_date']): ?>
                <div class="meta-block">
                    <label>Paid Date</label>
                    <span><?= date('M d, Y', strtotime($invoice['paid_date'])) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Line items table -->
            <table class="items">
                <thead>
                    <tr>
                        <th>Product / Service</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="name"><?= esc($item['product_name']) ?></div>
                                <?php if ($item['description']): ?>
                                    <div class="desc"><?= esc($item['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= (int) $item['quantity'] ?></td>
                            <td class="text-right">$<?= number_format($item['unit_price'], 2) ?></td>
                            <td class="text-right fw-bold">$<?= number_format($item['total_price'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4"><?= esc($invoice['description'] ?? 'Service') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="totals">
                <div class="totals-box">
                    <?php
                        $subtotal = array_sum(array_column($items, 'total_price')) ?: ($invoice['amount'] ?? 0);
                        $total    = $invoice['total_amount'] ?? $invoice['amount'] ?? $subtotal;
                    ?>
                    <div class="totals-row">
                        <span>Subtotal</span>
                        <span>$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="totals-row">
                        <span>Tax (0%)</span>
                        <span>$0.00</span>
                    </div>
                    <div class="totals-row grand">
                        <span>Total Due</span>
                        <span>$<?= number_format($total, 2) ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($invoice['notes']): ?>
            <div class="notes-box">
                <strong>Notes:</strong> <?= esc($invoice['notes']) ?>
            </div>
            <?php endif; ?>

            <!-- Unpaid notice -->
            <?php if ($invoice['status'] === 'unpaid'): ?>
            <div class="unpaid-notice">
                ⚠ This invoice is <strong>unpaid</strong>. Please contact us at
                <strong>support@alinitservices.com</strong> to arrange payment.
            </div>
            <?php endif; ?>

        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <span>Alin IT Services — alinitservices.com</span>
            <span>Generated <?= date('M d, Y') ?></span>
        </div>

    </div><!-- /.invoice -->

</div><!-- /.page-wrapper -->

<script>
    // Auto-trigger print dialog when page loads (optional — remove if you only want the button)
    // window.addEventListener('load', () => setTimeout(() => window.print(), 400));
</script>
</body>
</html>
