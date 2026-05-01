<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\UserModel;

class Invoices extends BaseController
{
    protected InvoiceModel     $invoiceModel;
    protected InvoiceItemModel $itemModel;
    protected UserModel        $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->invoiceModel = new InvoiceModel();
        $this->itemModel    = new InvoiceItemModel();
        $this->userModel    = new UserModel();
    }

    // ── List all invoices ─────────────────────────────────
    public function index()
    {
        $status = $this->request->getGet('status') ?? 'all';

        $builder = $this->db->table('invoices')
            ->select('invoices.*, users.name as user_name, users.email as user_email, users.company_name')
            ->join('users', 'users.id = invoices.user_id')
            ->where('invoices.deleted_at', null)
            ->orderBy('invoices.created_at', 'DESC');

        if ($status !== 'all') {
            $builder->where('invoices.status', $status);
        }

        $invoices = $builder->get()->getResultArray();

        $counts = [
            'all'    => $this->db->table('invoices')->where('deleted_at', null)->countAllResults(),
            'unpaid' => $this->db->table('invoices')->where('deleted_at', null)->where('status', 'unpaid')->countAllResults(),
            'paid'   => $this->db->table('invoices')->where('deleted_at', null)->where('status', 'paid')->countAllResults(),
        ];

        return view('admin/invoices/index', [
            'title'    => 'Invoices',
            'invoices' => $invoices,
            'status'   => $status,
            'counts'   => $counts,
        ]);
    }

    // ── View single invoice ───────────────────────────────
    public function show(string $id)
    {
        $invoice = $this->db->table('invoices')
            ->select('invoices.*, users.name as user_name, users.email as user_email, users.company_name, users.phone, users.address, users.city, users.country, users.created_at as customer_since')
            ->join('users', 'users.id = invoices.user_id')
            ->where('invoices.id', $id)
            ->get()
            ->getRowArray();

        if (! $invoice) {
            return redirect()->to(base_url('admin/invoices'))->with('error', 'Invoice not found.');
        }

        $items = $this->itemModel->getByInvoice($id);

        return view('admin/invoices/show', [
            'title'   => 'Invoice ' . $invoice['invoice_number'],
            'invoice' => $invoice,
            'items'   => $items,
        ]);
    }

    // ── Create form ───────────────────────────────────────
    public function create()
    {
        $customers = $this->db->table('users')
            ->select('users.id, users.name, users.email, users.company_name')
            ->join('role_user', 'role_user.user_id = users.id')
            ->join('roles', 'roles.id = role_user.role_id')
            ->where('roles.slug', 'customer')
            ->get()
            ->getResultArray();

        return view('admin/invoices/create', [
            'title'     => 'Create Invoice',
            'customers' => $customers,
        ]);
    }

    // ── Store invoice ─────────────────────────────────────
    public function store()
    {
        if (! $this->validate([
            'user_id'     => 'required|integer',
            'amount'      => 'required|decimal',
            'due_date'    => 'required|valid_date',
            'description' => 'required|min_length[3]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        // Generate invoice number
        $count  = $this->db->table('invoices')->countAllResults() + 1;
        $number = 'INV-' . date('Ymd') . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $this->invoiceModel->insert([
            'user_id'        => $this->request->getPost('user_id'),
            'invoice_number' => $number,
            'amount'         => $this->request->getPost('amount'),
            'total_amount'   => $this->request->getPost('amount'),
            'description'    => $this->request->getPost('description'),
            'notes'          => $this->request->getPost('notes'),
            'due_date'       => $this->request->getPost('due_date'),
            'issue_date'     => date('Y-m-d'),
            'status'         => 'unpaid',
        ]);

        return redirect()->to(base_url('admin/invoices'))->with('success', 'Invoice created successfully.');
    }

    // ── Mark as paid ──────────────────────────────────────
    public function markPaid(string $id)
    {
        $this->invoiceModel->update($id, [
            'status'    => 'paid',
            'paid_date' => date('Y-m-d'),
        ]);

        return redirect()->to(base_url('admin/invoices/' . $id))->with('success', 'Invoice marked as paid.');
    }

    // ── Mark as unpaid ────────────────────────────────────
    public function markUnpaid(string $id)
    {
        $this->invoiceModel->update($id, [
            'status'    => 'unpaid',
            'paid_date' => null,
        ]);

        return redirect()->to(base_url('admin/invoices/' . $id))->with('success', 'Invoice marked as unpaid.');
    }

    // ── Delete invoice ────────────────────────────────────
    public function delete(string $id)
    {
        $this->invoiceModel->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
        return redirect()->to(base_url('admin/invoices'))->with('success', 'Invoice deleted.');
    }
}