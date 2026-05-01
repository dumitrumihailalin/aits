<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\OrderModel;

class Cart extends BaseController
{
    protected CartModel $cartModel;
    protected string    $userId;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->cartModel = new CartModel();
        $this->userId    = session()->get('user_id');
    }

    public function index()
    {
        return view('customer/cart/index', [
            'title' => 'My Basket',
            'items' => $this->cartModel->getCartItems($this->userId),
        ]);
    }

    public function saveAll()
    {
        $selected    = $this->request->getPost('products') ?? [];
        $selectedIds = array_values(array_filter(array_map('trim', (array) $selected)));

        $this->cartModel->syncCart($this->userId, $selectedIds);

        return redirect()->to(base_url('my-products'))
                         ->with('success', 'Your product selection has been saved.');
    }

    public function save()
    {
        $productId = trim((string) $this->request->getPost('product_id'));

        if ($productId === '') {
            return redirect()->back()->with('error', 'Invalid product.');
        }

        $checked = (bool) $this->request->getPost('add_to_basket');

        if ($checked && (new InvoiceModel())->userHasProduct($this->userId, $productId)) {
            return redirect()->back()->with('error', 'You have already purchased this product.');
        }

        if ($checked) {
            $added = $this->cartModel->addToCart($this->userId, $productId);
            $msg   = $added ? 'Product added to basket.' : 'Already in your basket.';
            $type  = $added ? 'success' : 'info';
        } else {
            $this->cartModel->removeFromCart($this->userId, $productId);
            $msg  = 'Product removed from basket.';
            $type = 'success';
        }

        return redirect()->back()->with($type, $msg);
    }

    public function add(string $productId)
    {
        if ((new InvoiceModel())->userHasProduct($this->userId, $productId)) {
            return redirect()->back()->with('error', 'You have already purchased this product.');
        }

        $added = $this->cartModel->addToCart($this->userId, $productId);

        if (! $added) {
            return redirect()->back()->with('info', 'This product is already in your basket.');
        }

        return redirect()->back()->with('success', 'Product added to basket.');
    }

    public function remove(string $productId)
    {
        $this->cartModel->removeFromCart($this->userId, $productId);
        return redirect()->back()->with('success', 'Item removed from basket.');
    }

    public function checkout()
    {
        $items = $this->cartModel->getCartItems($this->userId);

        if (empty($items)) {
            return redirect()->to(base_url('cart'))->with('error', 'Your basket is empty.');
        }

        $invoiceModel     = new InvoiceModel();
        $invoiceItemModel = new InvoiceItemModel();
        $orderModel       = new OrderModel();

        $year          = date('Y');
        $count         = $invoiceModel->like('invoice_number', "AITS-{$year}-", 'after')->countAllResults();
        $invoiceNumber = 'AITS-' . $year . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $total = 0;
        foreach ($items as $item) {
            $total += (float) $item['price'];
        }

        // Create the order row (required by order_items FK)
        $orderId = $orderModel->insert([
            'user_id'      => $this->userId,
            'order_number' => $invoiceNumber,
            'total_amount' => $total,
            'status'       => 'pending',
        ]);

        // Create order line items
        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'product_id' => $item['product_id'],
                'quantity'   => 1,
                'unit_price' => (float) $item['price'],
            ];
        }
        $invoiceItemModel->insertItems((string) $orderId, $lineItems);

        // Create the invoice
        $invoiceId = $invoiceModel->insert([
            'user_id'        => $this->userId,
            'invoice_number' => $invoiceNumber,
            'amount'         => $total,
            'total_amount'   => $total,
            'status'         => 'unpaid',
            'is_read'        => 0,
            'issue_date'     => date('Y-m-d'),
            'due_date'       => date('Y-m-d', strtotime('+30 days')),
            'description'    => 'Order from basket',
        ]);

        $this->cartModel->markAsOrdered($this->userId);

        return redirect()->to(base_url('invoices'))
            ->with('success', 'Order placed! Invoice ' . $invoiceNumber . ' has been created.');
    }
}
