<?php

namespace App\Controllers\Admin;

class Dashboard extends BaseAdmin
{
    public function index()
    {
        $data = $this->sharedData([
            'title'          => 'Dashboard',
            'activeNav'      => 'dashboard',
            'breadcrumb'     => 'Dashboard',
            'totalProducts'  => 0,   // replace with model call later
            'totalCustomers' => 0,
            'totalRevenue'   => 0,
            'openTickets'    => 0,
            'recentInvoices' => [],
        ]);

        return view('admin/dashboard', $data);
    }
}