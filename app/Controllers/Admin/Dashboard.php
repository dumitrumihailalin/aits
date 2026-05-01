<?php

namespace App\Controllers\Admin;

class Dashboard extends BaseAdmin
{
    public function index()
    {
        $db = \Config\Database::connect();

        $thisMonthStart = date('Y-m-01 00:00:00');
        $lastMonthStart = date('Y-m-01 00:00:00', strtotime('-1 month'));
        $lastMonthEnd   = date('Y-m-t 23:59:59',  strtotime('-1 month'));

        // ── Total products ────────────────────────────────
        $totalProducts    = $db->table('products')->countAllResults();
        $productsThisMonth = $db->table('products')
            ->where('created_at >=', $thisMonthStart)
            ->countAllResults();

        // ── Total customers ───────────────────────────────
        $totalCustomers    = $db->table('users')
            ->join('role_user', 'role_user.user_id = users.id')
            ->join('roles', 'roles.id = role_user.role_id')
            ->where('roles.slug', 'customer')
            ->countAllResults();
        $customersThisMonth = $db->table('users')
            ->join('role_user', 'role_user.user_id = users.id')
            ->join('roles', 'roles.id = role_user.role_id')
            ->where('roles.slug', 'customer')
            ->where('users.created_at >=', $thisMonthStart)
            ->countAllResults();

        // ── Total revenue (paid invoices) ─────────────────
        $totalRevenue = (float) ($db->table('invoices')
            ->selectSum('amount')
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->get()->getRowArray()['amount'] ?? 0);

        $revenueThisMonth = (float) ($db->table('invoices')
            ->selectSum('amount')
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->where('paid_date >=', date('Y-m-01'))
            ->get()->getRowArray()['amount'] ?? 0);

        $revenueLastMonth = (float) ($db->table('invoices')
            ->selectSum('amount')
            ->where('status', 'paid')
            ->where('deleted_at', null)
            ->where('paid_date >=', $lastMonthStart)
            ->where('paid_date <=', $lastMonthEnd)
            ->get()->getRowArray()['amount'] ?? 0);

        $revenueChange = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : null;

        // ── Open tickets ──────────────────────────────────
        $openTickets = $db->table('support_tickets')
            ->whereIn('status', ['open', 'in_progress'])
            ->where('deleted_at', null)
            ->countAllResults();

        // ── Unpaid invoices ───────────────────────────────
        $recentInvoices = $db->table('invoices')
            ->select('invoices.id, invoices.invoice_number, invoices.amount, invoices.due_date, invoices.created_at, invoices.status, users.name as customer_name, users.company_name')
            ->join('users', 'users.id = invoices.user_id')
            ->where('invoices.status', 'unpaid')
            ->where('invoices.deleted_at', null)
            ->orderBy('invoices.due_date', 'ASC')
            ->get()->getResultArray();

        $data = $this->sharedData([
            'title'              => 'Dashboard',
            'activeNav'          => 'dashboard',
            'totalProducts'      => $totalProducts,
            'productsThisMonth'  => $productsThisMonth,
            'totalCustomers'     => $totalCustomers,
            'customersThisMonth' => $customersThisMonth,
            'totalRevenue'       => $totalRevenue,
            'revenueThisMonth'   => $revenueThisMonth,
            'revenueChange'      => $revenueChange,
            'openTickets'        => $openTickets,
            'recentInvoices'     => $recentInvoices,
        ]);

        return view('admin/dashboard', $data);
    }
}
