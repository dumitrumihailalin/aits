<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\InvoiceModel;
use App\Models\SupportTicketModel;

class Customers extends BaseController
{
    protected UserModel          $userModel;
    protected InvoiceModel       $invoiceModel;
    protected SupportTicketModel $ticketModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel    = new UserModel();
        $this->invoiceModel = new InvoiceModel();
        $this->ticketModel  = new SupportTicketModel();
    }

    // ── List all customers ────────────────────────────────
    public function index()
    {
        $search = $this->request->getGet('search') ?? '';

        $builder = $this->db->table('users')
            ->select('users.*, roles.slug as role')
            ->join('role_user', 'role_user.user_id = users.id', 'left')
            ->join('roles', 'roles.id = role_user.role_id', 'left')
            ->where('roles.slug', 'customer')
            ->orderBy('users.created_at', 'DESC');

        if ($search) {
            $builder->groupStart()
                ->like('users.name', $search)
                ->orLike('users.email', $search)
                ->orLike('users.company_name', $search)
                ->groupEnd();
        }

        $customers = $builder->get()->getResultArray();

        return view('admin/customers/index', [
            'title'     => 'Customers',
            'customers' => $customers,
            'search'    => $search,
        ]);
    }

    // ── View single customer ──────────────────────────────
    public function show(string $id)
    {
        $customer = $this->userModel->findWithRole($id);

        if (! $customer || $customer['role'] !== 'customer') {
            return redirect()->to(base_url('admin/customers'))->with('error', 'Customer not found.');
        }

        $invoices = $this->db->table('invoices')
            ->where('user_id', $id)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        $tickets = $this->db->table('support_tickets')
            ->where('user_id', $id)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/customers/show', [
            'title'    => esc($customer['name']),
            'customer' => $customer,
            'invoices' => $invoices,
            'tickets'  => $tickets,
        ]);
    }
}