<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BaseAdmin extends BaseController
{
    protected $session;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
    }

    // Data shared across every admin view
    protected function sharedData(array $extra = []): array
    {
        // Later you'll pull $openTickets from the DB
        // For now it's hardcoded to 0
        return array_merge([
            'openTickets' => 0,
        ], $extra);
    }
}