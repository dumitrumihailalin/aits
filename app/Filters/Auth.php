<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Not logged in at all
        if (! $session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'))
                             ->with('error', 'Please log in to access your dashboard.');
        }

        // Logged in but not a customer (e.g. admin trying to access customer area)
        if ($session->get('role') !== 'customer') {
            return redirect()->to(base_url('/'))
                             ->with('error', 'Access denied.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing needed after
    }
}