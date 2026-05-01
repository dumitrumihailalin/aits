<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SupportTicketModel;
use App\Models\TicketReplyModel;
use App\Models\UserModel;

class Support extends BaseController
{
    protected SupportTicketModel $ticketModel;
    protected TicketReplyModel   $replyModel;
    protected UserModel          $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->ticketModel = new SupportTicketModel();
        $this->replyModel  = new TicketReplyModel();
        $this->userModel   = new UserModel();
    }

    // ── List all tickets with status filter ───────────────
    public function index()
    {
        $status = $this->request->getGet('status') ?? 'all';

        $builder = $this->db->table('support_tickets')
            ->select('support_tickets.*, users.name as user_name, users.email as user_email, users.company_name')
            ->join('users', 'users.id = support_tickets.user_id')
            ->where('support_tickets.deleted_at', null)
            ->orderBy('support_tickets.created_at', 'DESC');

        if ($status !== 'all') {
            $builder->where('support_tickets.status', $status);
        }

        $tickets = $builder->get()->getResultArray();

        $counts = [
            'all'         => $this->db->table('support_tickets')->where('deleted_at', null)->countAllResults(),
            'open'        => $this->db->table('support_tickets')->where('deleted_at', null)->where('status', 'open')->countAllResults(),
            'in_progress' => $this->db->table('support_tickets')->where('deleted_at', null)->where('status', 'in_progress')->countAllResults(),
            'resolved'    => $this->db->table('support_tickets')->where('deleted_at', null)->where('status', 'resolved')->countAllResults(),
            'closed'      => $this->db->table('support_tickets')->where('deleted_at', null)->where('status', 'closed')->countAllResults(),
        ];

        return view('admin/support/index', [
            'title'   => 'Support Tickets',
            'tickets' => $tickets,
            'status'  => $status,
            'counts'  => $counts,
        ]);
    }

    // ── View single ticket ────────────────────────────────
    public function show(int $id)
    {
        $ticket = $this->db->table('support_tickets')
            ->select('support_tickets.*, users.name as user_name, users.email as user_email, users.company_name, users.notify_ticket_updates')
            ->join('users', 'users.id = support_tickets.user_id')
            ->where('support_tickets.id', $id)
            ->get()
            ->getRowArray();

        if (! $ticket) {
            return redirect()->to(base_url('admin/support'))->with('error', 'Ticket not found.');
        }

        if (! $ticket['is_read']) {
            $this->ticketModel->update($id, ['is_read' => 1]);
        }

        return view('admin/support/show', [
            'title'   => 'Ticket #' . $id,
            'ticket'  => $ticket,
            'replies' => $this->replyModel->getByTicket($id),
        ]);
    }

    // ── Reply to ticket ───────────────────────────────────
    public function reply(int $id)
    {
        if (! $this->validate(['message' => 'required|min_length[3]'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $ticket = $this->ticketModel
            ->select('support_tickets.*, users.name as user_name, users.email as user_email, users.notify_ticket_updates')
            ->join('users', 'users.id = support_tickets.user_id')
            ->where('support_tickets.id', $id)
            ->first();

        $this->replyModel->insert([
            'support_ticket_id' => $id,
            'user_id'           => session()->get('user_id'),
            'message'           => $this->request->getPost('message'),
            'is_admin_reply'    => 1,
        ]);

        $this->ticketModel->update($id, ['status' => 'in_progress']);

        // Send email if customer opted in
        if ($ticket && $ticket['notify_ticket_updates']) {
            $this->sendTicketEmail(
                $ticket['user_email'],
                $ticket['user_name'],
                $ticket['subject'],
                $id,
                'New reply on your ticket',
                $this->request->getPost('message')
            );
        }

        return redirect()->to(base_url('admin/support/' . $id))->with('success', 'Reply sent.');
    }

    // ── Update status ─────────────────────────────────────
    public function updateStatus(int $id)
    {
        $status  = $this->request->getPost('status');
        $allowed = ['open', 'in_progress', 'resolved', 'closed'];

        if (! in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $ticket = $this->ticketModel
            ->select('support_tickets.*, users.name as user_name, users.email as user_email, users.notify_ticket_updates')
            ->join('users', 'users.id = support_tickets.user_id')
            ->where('support_tickets.id', $id)
            ->first();

        $this->ticketModel->update($id, ['status' => $status]);

        // Send email if customer opted in
        if ($ticket && $ticket['notify_ticket_updates']) {
            $this->sendTicketEmail(
                $ticket['user_email'],
                $ticket['user_name'],
                $ticket['subject'],
                $id,
                'Your ticket status has been updated',
                'Your ticket status has been changed to: <strong>' . ucfirst(str_replace('_', ' ', $status)) . '</strong>'
            );
        }

        return redirect()->to(base_url('admin/support/' . $id))->with('success', 'Status updated.');
    }

    // ── Close ticket ──────────────────────────────────────
    public function close(int $id)
    {
        $ticket = $this->ticketModel
            ->select('support_tickets.*, users.name as user_name, users.email as user_email, users.notify_ticket_updates')
            ->join('users', 'users.id = support_tickets.user_id')
            ->where('support_tickets.id', $id)
            ->first();

        $this->ticketModel->update($id, ['status' => 'closed']);

        if ($ticket && $ticket['notify_ticket_updates']) {
            $this->sendTicketEmail(
                $ticket['user_email'],
                $ticket['user_name'],
                $ticket['subject'],
                $id,
                'Your ticket has been closed',
                'Your support ticket has been closed by our team. If you need further assistance, feel free to open a new ticket.'
            );
        }

        return redirect()->to(base_url('admin/support/' . $id))->with('success', 'Ticket closed.');
    }

    // ── Private: send ticket notification email ───────────
    private function sendTicketEmail(
        string $email,
        string $name,
        string $subject,
        int    $ticketId,
        string $heading,
        string $message
    ): void {
        $link = base_url('support/' . $ticketId);

        try {
            $svc = \Config\Services::email();
            $svc->setTo($email);
            $svc->setSubject($heading . ' — AITS');
            $svc->setMessage("<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f0f2f5;font-family:Helvetica,Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f2f5;padding:40px 16px;'>
<tr><td align='center'>
<table width='100%' cellpadding='0' cellspacing='0' style='max-width:560px;'>

    <tr><td style='background:#1877f2;border-radius:14px 14px 0 0;padding:24px 36px;'>
        <span style='font-size:20px;font-weight:700;color:#fff;'>💻 AITS</span>
        <span style='font-size:11px;color:rgba(255,255,255,.6);margin-left:8px;text-transform:uppercase;letter-spacing:1px;'>Alin IT Services</span>
    </td></tr>

    <tr><td style='background:#fff;padding:36px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;'>
        <h1 style='margin:0 0 8px;font-size:20px;font-weight:700;color:#111827;'>{$heading}</h1>
        <p style='margin:0 0 20px;font-size:14px;color:#6b7280;'>Hi <strong>{$name}</strong>,</p>

        <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:16px 20px;margin-bottom:24px;'>
            <div style='font-size:12px;color:#9ca3af;margin-bottom:4px;'>Ticket #{$ticketId} — {$subject}</div>
            <div style='font-size:14px;color:#111827;'>{$message}</div>
        </div>

        <div style='text-align:center;'>
            <a href='{$link}' style='display:inline-block;background:#1877f2;color:#fff;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:14px;font-weight:700;'>
                View Ticket →
            </a>
        </div>
    </td></tr>

    <tr><td style='background:#f8faff;border:1px solid #e5eaf5;border-top:none;border-radius:0 0 14px 14px;padding:20px 36px;text-align:center;'>
        <p style='margin:0;font-size:12px;color:#9ca3af;'>
            You received this because you have ticket notifications enabled.<br>
            You can disable this in your <a href='" . base_url('profile') . "' style='color:#1877f2;'>profile settings</a>.<br>
            &copy; " . date('Y') . " AITS — <a href='https://alinitservices.com' style='color:#1877f2;text-decoration:none;'>alinitservices.com</a>
        </p>
    </td></tr>

</table>
</td></tr>
</table>
</body></html>");
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Ticket email failed: ' . $e->getMessage());
        }
    }
}