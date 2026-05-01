<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\InvoiceItemModel;
use App\Models\InvoiceModel;

class Invoices extends BaseController
{
    protected InvoiceModel $invoiceModel;
    protected string       $userId;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->invoiceModel = new InvoiceModel();
        $this->userId       = session()->get('user_id');
    }

    public function index()
    {
        return view('customer/invoices/index', [
            'title'    => 'My Invoices',
            'invoices' => $this->invoiceModel->getByUser($this->userId),
        ]);
    }

    public function show(string $id)
    {
        $invoice = $this->invoiceModel->getInvoiceForUser($id, $this->userId);

        if (! $invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Invoice not found.');
        }

        if (! $invoice['is_read']) {
            $this->invoiceModel->update($id, ['is_read' => 1]);
        }

        $items = (new InvoiceItemModel())->getByInvoice($id);

        return view('customer/invoices/show', [
            'title'   => 'Invoice ' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'items'   => $items,
        ]);
    }

    public function pdf(string $id)
    {
        $invoice = $this->invoiceModel->getInvoiceForUser($id, $this->userId);

        if (! $invoice) {
            return redirect()->to(base_url('invoices'))->with('error', 'Invoice not found.');
        }

        $items = (new InvoiceItemModel())->getByInvoice($id);

        return view('customer/invoices/pdf', [
            'invoice' => $invoice,
            'items'   => $items,
        ]);
    }
}