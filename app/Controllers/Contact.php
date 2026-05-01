<?php

namespace App\Controllers;

class Contact extends BaseController
{
    public function index()
    {
        return view('contact', [
            'title'     => 'Contact Us — Alin IT Services',
            'metaDesc'  => 'Get in touch with the AITS team. We reply within one business day.',
            'activeNav' => 'contact',
        ]);
    }

    public function send()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[100]',
            'email'   => 'required|valid_email',
            'subject' => 'required|min_length[3]|max_length[150]',
            'message' => 'required|min_length[10]|max_length[3000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $name    = $this->request->getPost('name');
        $email   = $this->request->getPost('email');
        $subject = $this->request->getPost('subject');
        $message = nl2br(esc($this->request->getPost('message')));

        try {
            $svc = \Config\Services::email();
            $svc->setTo('support@alinitservices.com');
            $svc->setReplyTo($email, $name);
            $svc->setSubject('[Contact] ' . $subject);
            $svc->setMessage("
                <p><strong>From:</strong> {$name} &lt;{$email}&gt;</p>
                <p><strong>Subject:</strong> " . esc($subject) . "</p>
                <hr>
                <p>{$message}</p>
            ");
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Contact email failed: ' . $e->getMessage());
        }

        return redirect()->to(base_url('contact'))
            ->with('success', "Thanks, {$name}! We received your message and will reply soon.");
    }
}
