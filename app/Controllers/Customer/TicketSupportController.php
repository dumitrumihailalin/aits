<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class TicketSupportController extends BaseController
{
    // ── List all tickets for the logged-in user ─────────────────────
    public function index()
    {
        $userId  = session()->get('user_id');
        $tickets = $this->db->table('support_tickets')
                            ->where('user_id', $userId)
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->getResultArray();

        return view('customer/support/index', [
            'title'   => 'Support Tickets — AITS',
            'tickets' => $tickets,
        ]);
    }

    // ── Show create form ────────────────────────────────────────────
    public function create()
    {
        return view('customer/support/create', [
            'title'      => 'Open a Support Ticket — AITS',
            'validation' => \Config\Services::validation(),
        ]);
    }

    // ── Handle create form submission ───────────────────────────────
    public function store(): RedirectResponse
    {
        $rules = [
            'subject' => 'required|min_length[5]|max_length[255]',
            'message' => 'required|min_length[10]',
            'priority' => 'required|in_list[low,medium,high]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');

        $ticketId = $this->db->table('support_tickets')->insert([
            'user_id'    => $userId,
            'subject'    => $this->request->getPost('subject'),
            'message'    => $this->request->getPost('message'),
            'priority'   => $this->request->getPost('priority'),
            'status'     => 'open',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $newId = $this->db->insertID();

        return redirect()->to(base_url('support/' . $newId))
                         ->with('success', 'Your ticket has been submitted successfully.');
    }

    // ── Show a single ticket with replies ───────────────────────────
    public function show(int $id): RedirectResponse|string
    {
        $userId = session()->get('user_id');

        $ticket = $this->db->table('support_tickets')
                           ->where('id', $id)
                           ->where('user_id', $userId)
                           ->get()
                           ->getRowArray();

        if (! $ticket) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $replies = $this->db->table('ticket_replies')
                            ->where('ticket_id', $id)
                            ->orderBy('created_at', 'ASC')
                            ->get()
                            ->getResultArray();

        return view('customer/support/show', [
            'title'   => 'Ticket #' . $id . ' — AITS',
            'ticket'  => $ticket,
            'replies' => $replies,
        ]);
    }

    // ── Submit a reply ──────────────────────────────────────────────
    public function reply(int $id): RedirectResponse
    {
        $userId = session()->get('user_id');

        $ticket = $this->db->table('support_tickets')
                           ->where('id', $id)
                           ->where('user_id', $userId)
                           ->get()
                           ->getRowArray();

        if (! $ticket) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = ['reply' => 'required|min_length[5]'];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->db->table('ticket_replies')->insert([
            'ticket_id'  => $id,
            'user_id'    => $userId,
            'message'    => $this->request->getPost('reply'),
            'is_admin'   => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('support_tickets')->where('id', $id)->update([
            'status'     => 'open',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('support/' . $id))
                         ->with('success', 'Reply sent successfully.');
    }
}
