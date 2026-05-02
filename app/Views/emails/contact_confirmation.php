<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thanks for reaching out — AITS</title>
</head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f2f5;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

        <!-- Header -->
        <tr>
          <td style="background:#1877f2;border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;">
              <span style="display:inline-block;width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:10px;line-height:40px;text-align:center;font-size:20px;">💻</span>
              <span style="font-size:22px;font-weight:700;color:#fff;letter-spacing:-.3px;">AITS</span>
            </div>
            <p style="margin:8px 0 0;font-size:12px;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:1px;">Alin IT Services</p>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="background:#ffffff;padding:40px 40px 32px;">

            <h1 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#111827;">Thanks for getting in touch, <?= esc($name) ?>! 👋</h1>
            <p style="margin:0 0 28px;font-size:15px;color:#6b7280;line-height:1.6;">
              We received your message and will get back to you within <strong>1 business day</strong>.<br>
              In the meantime, here's what we offer.
            </p>

            <?php if ($selectedProduct): ?>
            <!-- Selected product highlight -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
              <tr>
                <td style="background:#eff6ff;border:2px solid #1877f2;border-radius:14px;padding:28px 28px 24px;">
                  <p style="margin:0 0 16px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:#1877f2;">You enquired about</p>
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="vertical-align:middle;padding-right:16px;width:52px;">
                        <div style="width:52px;height:52px;border-radius:13px;background:<?= esc($selectedProduct['color'] ?? '#1877f2') ?>;display:flex;align-items:center;justify-content:center;font-size:24px;text-align:center;line-height:52px;">
                          <?php
                            $icon = $selectedProduct['icon'] ?? 'bi-box-seam';
                            $icon = str_replace('bi-', '', $icon);
                          ?>
                          📦
                        </div>
                      </td>
                      <td style="vertical-align:middle;">
                        <div style="font-size:17px;font-weight:700;color:#111827;"><?= esc($selectedProduct['name']) ?></div>
                        <?php if (! empty($selectedProduct['short_description'])): ?>
                          <div style="font-size:13px;color:#6b7280;margin-top:3px;"><?= esc($selectedProduct['short_description']) ?></div>
                        <?php endif; ?>
                      </td>
                      <td style="vertical-align:middle;text-align:right;white-space:nowrap;padding-left:16px;">
                        <div style="font-size:22px;font-weight:800;color:#1877f2;">
                          $<?= number_format((float)($selectedProduct['price'] ?? $selectedProduct['base_price'] ?? 0), 0) ?>
                        </div>
                        <div style="font-size:12px;color:#9ca3af;">/ <?= esc($selectedProduct['price_label'] ?? 'month') ?></div>
                      </td>
                    </tr>
                  </table>
                  <div style="margin-top:20px;">
                    <a href="<?= base_url('products/' . esc($selectedProduct['slug'])) ?>"
                       style="display:inline-block;background:#1877f2;color:#fff;font-size:14px;font-weight:700;padding:11px 24px;border-radius:8px;text-decoration:none;">
                      View Product Details →
                    </a>
                  </div>
                </td>
              </tr>
            </table>
            <?php endif; ?>

            <?php if (! empty($otherProducts)): ?>
            <!-- Other products -->
            <p style="margin:0 0 16px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af;">
              <?= $selectedProduct ? 'Other products you might like' : 'Our products' ?>
            </p>

            <?php foreach ($otherProducts as $product): ?>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
              <tr>
                <td style="background:#f8faff;border:1px solid #e5eaf5;border-radius:12px;padding:16px 20px;">
                  <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="vertical-align:middle;width:42px;padding-right:14px;">
                        <div style="width:42px;height:42px;border-radius:10px;background:<?= esc($product['color'] ?? '#1877f2') ?>22;text-align:center;line-height:42px;font-size:18px;">
                          📦
                        </div>
                      </td>
                      <td style="vertical-align:middle;">
                        <div style="font-size:14px;font-weight:700;color:#111827;"><?= esc($product['name']) ?></div>
                        <?php if (! empty($product['short_description'])): ?>
                          <div style="font-size:12px;color:#6b7280;margin-top:2px;"><?= esc(mb_substr($product['short_description'], 0, 70)) ?><?= mb_strlen($product['short_description'] ?? '') > 70 ? '…' : '' ?></div>
                        <?php endif; ?>
                      </td>
                      <td style="vertical-align:middle;text-align:right;white-space:nowrap;padding-left:12px;">
                        <div style="font-size:15px;font-weight:700;color:#1877f2;">
                          $<?= number_format((float)($product['price'] ?? $product['base_price'] ?? 0), 0) ?>/mo
                        </div>
                        <a href="<?= base_url('products/' . esc($product['slug'])) ?>"
                           style="font-size:12px;color:#1877f2;text-decoration:none;font-weight:600;">
                          Learn more →
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <?php endforeach; ?>

            <div style="margin-top:24px;text-align:center;">
              <a href="<?= base_url('products') ?>"
                 style="display:inline-block;background:#f0f2f5;color:#374151;font-size:14px;font-weight:600;padding:11px 28px;border-radius:8px;text-decoration:none;border:1px solid #e5eaf5;">
                Browse All Products
              </a>
            </div>
            <?php endif; ?>

          </td>
        </tr>

        <!-- Divider -->
        <tr>
          <td style="background:#ffffff;padding:0 40px;">
            <hr style="border:none;border-top:1px solid #e5eaf5;margin:0;">
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#ffffff;border-radius:0 0 16px 16px;padding:24px 40px;text-align:center;">
            <p style="margin:0 0 6px;font-size:13px;color:#9ca3af;">
              Questions? Reply to this email or contact us at
              <a href="mailto:support@alinitservices.com" style="color:#1877f2;text-decoration:none;">support@alinitservices.com</a>
            </p>
            <p style="margin:0;font-size:12px;color:#d1d5db;">
              © <?= date('Y') ?> Alin IT Services &nbsp;·&nbsp;
              <a href="<?= base_url('/') ?>" style="color:#9ca3af;text-decoration:none;">alinitservices.com</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
