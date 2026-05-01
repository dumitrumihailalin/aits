<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\SupportTicketModel;
use App\Models\TicketReplyModel;

class Support extends BaseController
{
    protected SupportTicketModel $ticketModel;
    protected TicketReplyModel   $replyModel;
    protected string             $userId;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->ticketModel = new SupportTicketModel();
        $this->replyModel  = new TicketReplyModel();
        $this->userId      = session()->get('user_id');
    }

    // ── List tickets ─────────────────────────────────────
    public function index()
    {
        return view('customer/support/index', [
            'title'   => 'Support Tickets',
            'tickets' => $this->ticketModel->getByUser($this->userId),
        ]);
    }

    // ── Create form ──────────────────────────────────────
    public function create()
    {
        return view('customer/support/create', [
            'title' => 'Open New Ticket',
        ]);
    }

    // ── Store ticket ─────────────────────────────────────
    public function store()
    {
        if (! $this->validate([
            'subject'     => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[10]',
            'priority'    => 'required|in_list[low,medium,high,urgent]',
        ])) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // Handle optional image upload
        $imagePath = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName   = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/tickets', $newName);
            $imagePath = 'tickets/' . $newName;
        }

        $this->ticketModel->insert([
            'user_id'     => $this->userId,
            'subject'     => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'priority'    => $this->request->getPost('priority'),
            'image_path'  => $imagePath,
            'status'      => 'open',
            'is_read'     => 0,
        ]);

        return redirect()->to(base_url('support'))
            ->with('success', 'Ticket created successfully. We will get back to you shortly.');
    }

    // ── View ticket + replies ────────────────────────────
    public function show(string $id)
    {
        $ticket = $this->ticketModel->getTicketForUser($id, $this->userId);

        if (! $ticket) {
            return redirect()->to(base_url('support'))
                ->with('error', 'Ticket not found.');
        }

        return view('customer/support/show', [
            'title'   => 'Ticket #' . $id,
            'ticket'  => $ticket,
            'replies' => $this->replyModel->getByTicket($id),
        ]);
    }

    // ── Reply to ticket ──────────────────────────────────
    public function reply(string $id)
    {
        $ticket = $this->ticketModel->getTicketForUser($id, $this->userId);

        if (! $ticket) {
            return redirect()->to(base_url('support'))->with('error', 'Ticket not found.');
        }

        if (in_array($ticket['status'], ['resolved', 'closed'])) {
            return redirect()->to(base_url('support/' . $id))
                ->with('error', 'Cannot reply to a resolved or closed ticket.');
        }

        if (! $this->validate(['message' => 'required|min_length[3]'])) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        // Handle optional attachment
        $attachmentPath = null;
        $file = $this->request->getFile('attachment');
        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/tickets', $newName);
            $attachmentPath = 'tickets/' . $newName;
        }

        $this->replyModel->insert([
            'support_ticket_id' => $id,
            'user_id'           => $this->userId,
            'message'           => $this->request->getPost('message'),
            'attachment_path'   => $attachmentPath,
            'is_admin_reply'    => 0,
        ]);

        // Reopen ticket if resolved
        if ($ticket['status'] === 'resolved') {
            $this->ticketModel->update($id, ['status' => 'open']);
        }

        return redirect()->to(base_url('support/' . $id))
            ->with('success', 'Reply sent.');
    }
}