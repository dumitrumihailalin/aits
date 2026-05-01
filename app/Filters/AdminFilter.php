<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in at all
        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('admin/login'))
                             ->with('error', 'Please log in to access the admin area.');
        }

        // Logged in but not admin
        if ($session->get('role') !== 'admin') {
            return redirect()->to(base_url('/'))
                             ->with('error', 'Access denied.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing needed after
    }
}