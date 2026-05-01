<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\CartModel;
use App\Models\InvoiceModel;
use App\Models\SupportTicketModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');

        $cartModel    = new CartModel();
        $invoiceModel = new InvoiceModel();
        $ticketModel  = new SupportTicketModel();

        $allProducts    = $cartModel->getActiveProducts($userId);
        $invoices       = $invoiceModel->getByUser($userId);
        $allTickets     = $ticketModel->getByUser($userId);

        $openTickets  = count(array_filter($allTickets, fn($t) => ! in_array($t['status'], ['closed', 'resolved'])));
        $activeOnly   = array_filter($allProducts, fn($p) => $p['status'] === 'active');
        $monthlyTotal = array_sum(array_column($activeOnly, 'price'));

        $unpaidInvoices = array_values(array_filter($invoices, fn($i) => $i['status'] === 'unpaid'));
        $nextInvoice    = ! empty($unpaidInvoices) ? $unpaidInvoices[0]['due_date'] : null;

        return view('customer/dashboard/index', [
            'activeProducts' => $allProducts,
            'activeCount'    => count($activeOnly),
            'monthlyTotal'   => $monthlyTotal,
            'openTickets'    => $openTickets,
            'nextInvoice'    => $nextInvoice,
            'recentInvoices' => array_slice($invoices, 0, 5),
        ]);
    }
}