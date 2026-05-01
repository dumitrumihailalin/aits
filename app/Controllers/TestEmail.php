<?php

namespace App\Controllers;

class TestEmail extends BaseController
{
    public function index()
    {
        $emailService = \Config\Services::email();

        $emailService->setTo('support@alinitservices.com'); // ← change this to your email
        $emailService->setSubject('Your AITS Order Summary');
        $emailService->setMessage($this->buildTemplate());

        if ($emailService->send()) {
            echo 'Email sent successfully!';
        } else {
            echo '<pre>' . $emailService->printDebugger(['headers', 'subject', 'body']) . '</pre>';
        }
    }

    private function buildTemplate(): string
    {
        // Fake products for the test
        $products = [
            [
                'name'     => 'Business Starter Package',
                'features' => ['Domain Setup', 'Email Hosting (5 accounts)', 'SSL Certificate'],
                'price'    => 49.99,
                'icon'     => '🚀',
            ],
            [
                'name'     => 'CRM Integration',
                'features' => ['Customer Management', 'Lead Tracking', 'Monthly Reports'],
                'price'    => 89.99,
                'icon'     => '📊',
            ],
            [
                'name'     => 'Security Shield',
                'features' => ['Firewall Setup', 'Daily Backups', '24/7 Monitoring'],
                'price'    => 39.99,
                'icon'     => '🛡️',
            ],
        ];

        $total = array_sum(array_column($products, 'price'));

        $productRows = '';
        foreach ($products as $p) {
            $featureList = '';
            foreach ($p['features'] as $f) {
                $featureList .= "
                    <li style='margin:4px 0;color:#4b5563;font-size:13px;'>
                        <span style='color:#1877f2;margin-right:6px;'>✓</span>{$f}
                    </li>";
            }

            $productRows .= "
            <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:12px;padding:20px 24px;margin-bottom:16px;'>
              <div style='display:flex;align-items:center;gap:12px;margin-bottom:12px;'>
                <div style='width:42px;height:42px;background:#1877f2;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;'>
                  {$p['icon']}
                </div>
                <div style='flex:1;'>
                  <div style='font-size:15px;font-weight:600;color:#111827;'>{$p['name']}</div>
                  <div style='font-size:13px;color:#6b7280;margin-top:1px;'>Monthly subscription</div>
                </div>
                <div style='font-size:18px;font-weight:700;color:#1877f2;'>
                  \${$p['price']}
                </div>
              </div>
              <ul style='margin:0;padding:0 0 0 8px;list-style:none;border-top:1px solid #e5eaf5;padding-top:12px;'>
                {$featureList}
              </ul>
            </div>";
        }

        return "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1.0'>
  <title>Your AITS Order Summary</title>
</head>
<body style='margin:0;padding:0;background:#f0f2f5;font-family:DM Sans,Helvetica,Arial,sans-serif;'>

  <table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f2f5;padding:40px 16px;'>
    <tr>
      <td align='center'>
        <table width='100%' cellpadding='0' cellspacing='0' style='max-width:600px;'>

          <!-- Header -->
          <tr>
            <td style='background:#1877f2;border-radius:14px 14px 0 0;padding:28px 36px;'>
              <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                  <td>
                    <div style='display:inline-flex;align-items:center;gap:10px;'>
                      <div style='width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:9px;display:inline-block;text-align:center;line-height:36px;font-size:18px;'>💻</div>
                      <span style='font-size:20px;font-weight:700;color:#fff;vertical-align:middle;margin-left:10px;'>AITS</span>
                      <span style='font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;vertical-align:middle;margin-left:4px;'>Alin IT Services</span>
                    </div>
                  </td>
                  <td align='right'>
                    <span style='background:rgba(255,255,255,.15);color:#fff;font-size:12px;padding:4px 12px;border-radius:20px;'>Order Confirmation</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Hero -->
          <tr>
            <td style='background:#fff;padding:36px 36px 24px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;'>
              <div style='text-align:center;margin-bottom:28px;'>
                <div style='width:56px;height:56px;background:#e8f0fe;border-radius:50%;margin:0 auto 16px;text-align:center;line-height:56px;font-size:26px;'>✅</div>
                <h1 style='margin:0 0 8px;font-size:22px;font-weight:700;color:#111827;'>Thank you for your order!</h1>
                <p style='margin:0;font-size:14px;color:#6b7280;line-height:1.6;'>
                  Hi <strong>John Doe</strong>, here's a summary of the products and services you've selected.
                  Our team will be in touch shortly.
                </p>
              </div>

              <!-- Order info bar -->
              <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:14px 20px;margin-bottom:28px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr>
                    <td style='font-size:12px;color:#6b7280;'>Order number</td>
                    <td style='font-size:12px;color:#6b7280;'>Date</td>
                    <td style='font-size:12px;color:#6b7280;'>Company</td>
                  </tr>
                  <tr>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>#AITS-2026-001</td>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>" . date('M d, Y') . "</td>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>Acme Corp</td>
                  </tr>
                </table>
              </div>

              <!-- Products -->
              <h2 style='font-size:15px;font-weight:600;color:#111827;margin:0 0 16px;'>Selected Products</h2>
              {$productRows}

              <!-- Total -->
              <div style='background:#1877f2;border-radius:10px;padding:16px 24px;margin-top:8px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr>
                    <td style='color:rgba(255,255,255,.8);font-size:14px;'>Total Monthly</td>
                    <td align='right' style='color:#fff;font-size:22px;font-weight:700;'>\${$total}</td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>

          <!-- CTA -->
          <tr>
            <td style='background:#fff;padding:24px 36px 32px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;text-align:center;'>
              <p style='margin:0 0 20px;font-size:14px;color:#6b7280;'>
                You can view your full order, manage your subscription, and access all features from your dashboard.
              </p>
              <a href='https://alinitservices.com/dashboard'
                 style='display:inline-block;background:#1877f2;color:#fff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:14px;font-weight:700;'>
                Go to Dashboard →
              </a>
            </td>
          </tr>

          <!-- Next steps -->
          <tr>
            <td style='background:#f8faff;padding:24px 36px;border:1px solid #e5eaf5;border-top:none;'>
              <h3 style='margin:0 0 16px;font-size:14px;font-weight:600;color:#111827;'>What happens next?</h3>
              <table width='100%' cellpadding='0' cellspacing='0'>
                <tr>
                  <td width='32' valign='top'>
                    <div style='width:24px;height:24px;background:#1877f2;border-radius:50%;text-align:center;line-height:24px;color:#fff;font-size:12px;font-weight:700;'>1</div>
                  </td>
                  <td style='padding-left:12px;padding-bottom:12px;'>
                    <div style='font-size:13px;font-weight:600;color:#111827;'>Order Review</div>
                    <div style='font-size:12px;color:#6b7280;margin-top:2px;'>Our team reviews your selected services within 24 hours.</div>
                  </td>
                </tr>
                <tr>
                  <td width='32' valign='top'>
                    <div style='width:24px;height:24px;background:#1877f2;border-radius:50%;text-align:center;line-height:24px;color:#fff;font-size:12px;font-weight:700;'>2</div>
                  </td>
                  <td style='padding-left:12px;padding-bottom:12px;'>
                    <div style='font-size:13px;font-weight:600;color:#111827;'>Setup & Configuration</div>
                    <div style='font-size:12px;color:#6b7280;margin-top:2px;'>We configure and deploy your selected products.</div>
                  </td>
                </tr>
                <tr>
                  <td width='32' valign='top'>
                    <div style='width:24px;height:24px;background:#1877f2;border-radius:50%;text-align:center;line-height:24px;color:#fff;font-size:12px;font-weight:700;'>3</div>
                  </td>
                  <td style='padding-left:12px;'>
                    <div style='font-size:13px;font-weight:600;color:#111827;'>Go Live</div>
                    <div style='font-size:12px;color:#6b7280;margin-top:2px;'>You receive access credentials and your services go live.</div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style='background:#f0f2f5;border-radius:0 0 14px 14px;padding:24px 36px;text-align:center;'>
              <p style='margin:0 0 8px;font-size:12px;color:#9ca3af;'>
                © " . date('Y') . " AITS — Alin IT Services. All rights reserved.
              </p>
              <p style='margin:0;font-size:12px;color:#9ca3af;'>
                <a href='https://alinitservices.com' style='color:#1877f2;text-decoration:none;'>alinitservices.com</a>
                &nbsp;·&nbsp;
                <a href='https://alinitservices.com/support' style='color:#1877f2;text-decoration:none;'>Support</a>
                &nbsp;·&nbsp;
                <a href='https://alinitservices.com/unsubscribe' style='color:#9ca3af;text-decoration:none;'>Unsubscribe</a>
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>";
    }
}