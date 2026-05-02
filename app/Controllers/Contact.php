<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Contact extends BaseController
{
    public function index()
    {
        $products = (new ProductModel())->getActive();

        return view('contact', [
            'title'     => 'Contact Us — Alin IT Services',
            'metaDesc'  => 'Get in touch with the AITS team. We reply within one business day.',
            'activeNav' => 'contact',
            'products'  => $products,
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

        $name       = $this->request->getPost('name');
        $email      = $this->request->getPost('email');
        $subject    = $this->request->getPost('subject');
        $message    = nl2br(esc($this->request->getPost('message')));
        $productId  = $this->request->getPost('product_id');

        $productModel = new ProductModel();
        $allProducts  = $productModel->getActive();

        $selectedProduct = null;
        if ($productId) {
            foreach ($allProducts as $p) {
                if ($p['id'] === $productId) {
                    $selectedProduct = $p;
                    break;
                }
            }
        }

        $productLine = $selectedProduct
            ? "\n<p><strong>Interested in:</strong> " . esc($selectedProduct['name']) . "</p>"
            : '';

        // Internal notification to support
        try {
            $svc = \Config\Services::email();
            $svc->setTo('support@alinitservices.com');
            $svc->setReplyTo($email, $name);
            $svc->setSubject('[Contact] ' . $subject);
            $svc->setMessage("
                <p><strong>From:</strong> {$name} &lt;{$email}&gt;</p>
                <p><strong>Subject:</strong> " . esc($subject) . "</p>
                {$productLine}
                <hr>
                <p>{$message}</p>
            ");
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Contact internal email failed: ' . $e->getMessage());
        }

        // Confirmation email to the customer
        try {
            $otherProducts = $selectedProduct
                ? array_filter($allProducts, fn($p) => $p['id'] !== $selectedProduct['id'])
                : $allProducts;

            $html = view('emails/contact_confirmation', [
                'name'            => $name,
                'selectedProduct' => $selectedProduct,
                'otherProducts'   => array_values($otherProducts),
            ]);

            $svc = \Config\Services::email();
            $svc->setTo($email, $name);
            $svc->setFrom('no-reply@alinitservices.com', 'Alin IT Services');
            $svc->setSubject('Thanks for reaching out — AITS');
            $svc->setMessage($html);
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Contact confirmation email failed: ' . $e->getMessage());
        }

        return redirect()->to(base_url('contact'))
            ->with('success', "Thanks, {$name}! We received your message and will reply soon.");
    }
}
