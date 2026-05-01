<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Cart extends BaseController
{
    protected ProductModel     $productModel;
    protected InvoiceModel     $invoiceModel;
    protected InvoiceItemModel $itemModel;
    protected UserModel        $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
        $this->invoiceModel = new InvoiceModel();
        $this->itemModel    = new InvoiceItemModel();
        $this->userModel    = new UserModel();
    }

    // ── Show cart + product list ──────────────────────────
    public function index()
    {
        $products = $this->productModel->where('is_active', 1)->orderBy('sort_order', 'ASC')->findAll();
        $cart     = session()->get('admin_cart') ?? [];

        return view('admin/cart/index', [
            'title'    => 'Shopping Cart',
            'products' => $products,
            'cart'     => $cart,
        ]);
    }

    // ── Add product to cart ───────────────────────────────
    public function add(int $productId)
    {
        $product = $this->productModel->find($productId);

        if (! $product) {
            return redirect()->to(base_url('admin/cart'))->with('error', 'Product not found.');
        }

        $cart = session()->get('admin_cart') ?? [];

        // If already in cart, increment quantity
        $found = false;
        foreach ($cart as &$item) {
            if ((int) $item['product_id'] === $productId) {
                $item['quantity']++;
                $item['total_price'] = $item['quantity'] * $item['unit_price'];
                $found = true;
                break;
            }
        }
        unset($item);

        if (! $found) {
            $cart[] = [
                'product_id'   => $product['id'],
                'product_name' => $product['name'],
                'description'  => $product['short_description'] ?? $product['description'] ?? '',
                'quantity'     => 1,
                'unit_price'   => (float) ($product['base_price'] ?? $product['price']),
                'total_price'  => (float) ($product['base_price'] ?? $product['price']),
                'icon'         => $product['icon'],
                'color'        => $product['color'],
                'price_label'  => $product['price_label'] ?? 'month',
            ];
        }

        session()->set('admin_cart', $cart);

        return redirect()->to(base_url('admin/cart'))->with('success', 'Product added to cart.');
    }

    // ── Update quantity ───────────────────────────────────
    public function updateQty()
    {
        $index = (int) $this->request->getPost('index');
        $qty   = (int) $this->request->getPost('quantity');

        $cart = session()->get('admin_cart') ?? [];

        if (isset($cart[$index])) {
            if ($qty <= 0) {
                array_splice($cart, $index, 1);
            } else {
                $cart[$index]['quantity']    = $qty;
                $cart[$index]['total_price'] = $qty * $cart[$index]['unit_price'];
            }
            session()->set('admin_cart', array_values($cart));
        }

        return redirect()->to(base_url('admin/cart'))->with('success', 'Cart updated.');
    }

    // ── Remove item from cart ─────────────────────────────
    public function remove(int $index)
    {
        $cart = session()->get('admin_cart') ?? [];

        if (isset($cart[$index])) {
            array_splice($cart, $index, 1);
            session()->set('admin_cart', array_values($cart));
        }

        return redirect()->to(base_url('admin/cart'))->with('success', 'Item removed from cart.');
    }

    // ── Clear cart ────────────────────────────────────────
    public function clear()
    {
        session()->remove('admin_cart');
        return redirect()->to(base_url('admin/cart'))->with('success', 'Cart cleared.');
    }

    // ── Checkout form ─────────────────────────────────────
    public function checkout()
    {
        $cart = session()->get('admin_cart') ?? [];

        if (empty($cart)) {
            return redirect()->to(base_url('admin/cart'))->with('error', 'Your cart is empty.');
        }

        $customers = $this->db->table('users')
            ->select('users.id, users.name, users.email, users.company_name')
            ->join('role_user', 'role_user.user_id = users.id')
            ->join('roles', 'roles.id = role_user.role_id')
            ->where('roles.slug', 'customer')
            ->orderBy('users.name', 'ASC')
            ->get()
            ->getResultArray();

        $total = array_sum(array_column($cart, 'total_price'));

        return view('admin/cart/checkout', [
            'title'     => 'Checkout',
            'cart'      => $cart,
            'customers' => $customers,
            'total'     => $total,
        ]);
    }

    // ── Place order ───────────────────────────────────────
    public function placeOrder()
    {
        $cart = session()->get('admin_cart') ?? [];

        if (empty($cart)) {
            return redirect()->to(base_url('admin/cart'))->with('error', 'Your cart is empty.');
        }

        if (! $this->validate([
            'user_id'  => 'required|integer',
            'due_date' => 'required|valid_date',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $userId   = (int) $this->request->getPost('user_id');
        $dueDate  = $this->request->getPost('due_date');
        $notes    = $this->request->getPost('notes') ?? '';
        $total    = array_sum(array_column($cart, 'total_price'));

        // Generate invoice number
        $count  = $this->db->table('invoices')->countAllResults() + 1;
        $number = 'INV-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        // Build description from cart items
        $desc = implode(', ', array_column($cart, 'product_name'));

        $invoiceId = $this->invoiceModel->insert([
            'user_id'        => $userId,
            'invoice_number' => $number,
            'amount'         => $total,
            'total_amount'   => $total,
            'notes'          => $notes,
            'due_date'       => $dueDate,
            'issue_date'     => date('Y-m-d'),
            'status'         => 'unpaid',
        ]);

        // Insert line items
        $this->itemModel->insertItems((int) $invoiceId, $cart);

        // Get customer info for email
        $customer = $this->userModel->find($userId);

        if ($customer) {
            $this->sendOrderEmail($customer, $cart, $number, $total, $dueDate);
        }

        // Clear cart
        session()->remove('admin_cart');

        return redirect()->to(base_url('admin/invoices/' . $invoiceId))
            ->with('success', 'Order placed successfully! Invoice ' . $number . ' created and email sent to ' . ($customer['email'] ?? ''));
    }

    // ── Build & send order email ──────────────────────────
    private function sendOrderEmail(array $customer, array $cart, string $invoiceNumber, float $total, string $dueDate): void
    {
        $productRows = '';
        foreach ($cart as $item) {
            $productRows .= "
            <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:12px;padding:20px 24px;margin-bottom:16px;'>
              <div style='display:flex;align-items:center;gap:12px;margin-bottom:8px;'>
                <div style='width:42px;height:42px;background:" . esc($item['color'] ?? '#1877f2') . ";border-radius:10px;display:inline-block;text-align:center;line-height:42px;font-size:18px;color:#fff;flex-shrink:0;'>
                  <i class=\"" . esc($item['icon'] ?? 'bi-box') . "\"></i>
                </div>
                <div style='flex:1;'>
                  <div style='font-size:15px;font-weight:600;color:#111827;'>" . esc($item['product_name']) . "</div>
                  <div style='font-size:12px;color:#6b7280;margin-top:2px;'>Qty: " . (int) $item['quantity'] . " &times; \$" . number_format($item['unit_price'], 2) . " / " . esc($item['price_label'] ?? 'month') . "</div>
                </div>
                <div style='font-size:16px;font-weight:700;color:#1877f2;'>\$" . number_format($item['total_price'], 2) . "</div>
              </div>
              " . ($item['description'] ? "<div style='font-size:12px;color:#6b7280;border-top:1px solid #e5eaf5;padding-top:8px;'>" . esc($item['description']) . "</div>" : '') . "
            </div>";
        }

        $customerName    = esc($customer['name']);
        $companyName     = $customer['company_name'] ? esc($customer['company_name']) : 'N/A';
        $formattedTotal  = number_format($total, 2);
        $formattedDue    = date('M d, Y', strtotime($dueDate));
        $formattedDate   = date('M d, Y');
        $dashboardUrl    = base_url('invoices');
        $year            = date('Y');

        $html = "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width,initial-scale=1.0'>
  <title>Your AITS Order — {$invoiceNumber}</title>
</head>
<body style='margin:0;padding:0;background:#f0f2f5;font-family:Helvetica,Arial,sans-serif;'>
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
                    <span style='font-size:20px;font-weight:700;color:#fff;'>&#x1F4BB; AITS</span>
                    <span style='font-size:11px;color:rgba(255,255,255,.6);text-transform:uppercase;letter-spacing:1px;margin-left:8px;'>Alin IT Services</span>
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
                <div style='width:56px;height:56px;background:#e8f0fe;border-radius:50%;margin:0 auto 16px;text-align:center;line-height:56px;font-size:26px;'>&#x2705;</div>
                <h1 style='margin:0 0 8px;font-size:22px;font-weight:700;color:#111827;'>Your order is confirmed!</h1>
                <p style='margin:0;font-size:14px;color:#6b7280;line-height:1.6;'>
                  Hi <strong>{$customerName}</strong>, here is a summary of your order.
                  Our team will be in touch shortly to get everything set up.
                </p>
              </div>

              <!-- Order info -->
              <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:14px 20px;margin-bottom:28px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr>
                    <td style='font-size:12px;color:#6b7280;'>Invoice number</td>
                    <td style='font-size:12px;color:#6b7280;'>Date</td>
                    <td style='font-size:12px;color:#6b7280;'>Due date</td>
                    <td style='font-size:12px;color:#6b7280;'>Company</td>
                  </tr>
                  <tr>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>{$invoiceNumber}</td>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>{$formattedDate}</td>
                    <td style='font-size:14px;font-weight:600;color:#dc2626;padding-top:4px;'>{$formattedDue}</td>
                    <td style='font-size:14px;font-weight:600;color:#111827;padding-top:4px;'>{$companyName}</td>
                  </tr>
                </table>
              </div>

              <!-- Products -->
              <h2 style='font-size:15px;font-weight:600;color:#111827;margin:0 0 16px;'>Selected Products &amp; Services</h2>
              {$productRows}

              <!-- Total -->
              <div style='background:#1877f2;border-radius:10px;padding:16px 24px;margin-top:8px;'>
                <table width='100%' cellpadding='0' cellspacing='0'>
                  <tr>
                    <td style='color:rgba(255,255,255,.8);font-size:14px;'>Total Amount Due</td>
                    <td align='right' style='color:#fff;font-size:22px;font-weight:700;'>\${$formattedTotal}</td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>

          <!-- CTA -->
          <tr>
            <td style='background:#fff;padding:24px 36px 32px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;text-align:center;'>
              <p style='margin:0 0 20px;font-size:14px;color:#6b7280;'>
                You can view and manage your invoice from your customer dashboard.
              </p>
              <a href='{$dashboardUrl}'
                 style='display:inline-block;background:#1877f2;color:#fff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:14px;font-weight:700;'>
                View Invoice &#8594;
              </a>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style='background:#f0f2f5;border-radius:0 0 14px 14px;padding:24px 36px;text-align:center;border:1px solid #e5eaf5;border-top:none;'>
              <p style='margin:0 0 8px;font-size:12px;color:#9ca3af;'>
                &copy; {$year} AITS &mdash; Alin IT Services. All rights reserved.
              </p>
              <p style='margin:0;font-size:12px;color:#9ca3af;'>
                <a href='https://alinitservices.com' style='color:#1877f2;text-decoration:none;'>alinitservices.com</a>
                &nbsp;&middot;&nbsp;
                <a href='" . base_url('support') . "' style='color:#1877f2;text-decoration:none;'>Support</a>
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>";

        $emailService = \Config\Services::email();
        $emailService->setTo($customer['email'], $customer['name']);
        $emailService->setSubject('Your AITS Order — ' . $invoiceNumber);
        $emailService->setMessage($html);
        $emailService->send();
    }
}
