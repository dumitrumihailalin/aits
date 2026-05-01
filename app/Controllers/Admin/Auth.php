<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
    }

    // ── Login ────────────────────────────────────────────
    public function login()
    {
        if (session()->get('isLoggedIn') && session()->get('role') === 'admin') {
            return redirect()->to(base_url('admin/dashboard'));
        }
        return view('admin/auth/login', ['title' => 'Admin Login']);
    }

    public function loginPost()
    {
        if (! $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmailWithRole($email);

        if (! $user) {
            return redirect()->back()->with('error', 'Invalid email or password.')->withInput();
        }

        if (! password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password.')->withInput();
        }

        if (empty($user['role']) || $user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Access denied. Not an admin.')->withInput();
        }

        session()->set([
            'isLoggedIn' => true,
            'role'       => 'admin',
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
        ]);

        return redirect()->to(base_url('admin/dashboard'));
    }

    // ── Logout ───────────────────────────────────────────
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('admin/login'))->with('success', 'You have been logged out.');
    }

    // ── Forgot Password ──────────────────────────────────
    public function forgotPassword()
    {
        return view('admin/auth/forgot_password', ['title' => 'Forgot Password']);
    }

    public function forgotPasswordPost()
    {
        if (! $this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        // Use RBAC join instead of role column
        $user = $this->userModel->findByEmailWithRole($this->request->getPost('email'));

        if ($user && $user['role'] === 'admin') {
            $token = bin2hex(random_bytes(32));
            $this->userModel->update($user['id'], [
                'reset_token'      => $token,
                'reset_expires_at' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ]);
            $this->sendResetEmail($user['email'], $user['name'], $token);
        }

        return redirect()->back()->with('success', 'If that email exists, we sent a password reset link.');
    }

    public function resetPassword(string $token)
    {
        $user = $this->userModel
            ->where('reset_token', $token)
            ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (! $user) {
            return redirect()->to(base_url('admin/forgot-password'))
                ->with('error', 'Reset link is invalid or expired.');
        }

        return view('admin/auth/reset_password', ['title' => 'Reset Password', 'token' => $token]);
    }

    public function resetPasswordPost(string $token)
    {
        if (! $this->validate([
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $user = $this->userModel
            ->where('reset_token', $token)
            ->where('reset_expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (! $user) {
            return redirect()->to(base_url('admin/forgot-password'))
                ->with('error', 'Reset link is invalid or expired.');
        }

        $this->userModel->update($user['id'], [
            'password'         => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);

        return redirect()->to(base_url('admin/login'))->with('success', 'Password reset! You can now log in.');
    }

    // ── Private: Reset Email ─────────────────────────────
    private function sendResetEmail(string $email, string $name, string $token): void
    {
        $link = base_url('admin/reset-password/' . $token);

        try {
            $svc = \Config\Services::email();
            $svc->setTo($email);
            $svc->setSubject('Admin password reset — AITS');
            $svc->setMessage("<!DOCTYPE html>
<html lang='en'>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f0f2f5;font-family:Helvetica,Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f2f5;padding:40px 16px;'>
<tr><td align='center'>
<table width='100%' cellpadding='0' cellspacing='0' style='max-width:560px;'>
    <tr><td style='background:#111827;border-radius:14px 14px 0 0;padding:24px 36px;'>
        <span style='font-size:20px;font-weight:700;color:#fff;'>💻 AITS</span>
        <span style='font-size:11px;color:rgba(255,255,255,.4);margin-left:8px;text-transform:uppercase;letter-spacing:1px;'>Admin Panel</span>
    </td></tr>
    <tr><td style='background:#fff;padding:40px 36px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;'>
        <h1 style='margin:0 0 12px;font-size:22px;font-weight:700;color:#111827;'>Reset your password 🔑</h1>
        <p style='margin:0 0 28px;font-size:14px;color:#6b7280;line-height:1.7;'>Hi <strong>{$name}</strong>, click below to reset your admin password. This link expires in 1 hour.</p>
        <div style='text-align:center;margin-bottom:28px;'>
            <a href='{$link}' style='display:inline-block;background:#111827;color:#fff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:15px;font-weight:700;'>Reset Password</a>
        </div>
        <p style='font-size:12px;color:#9ca3af;'>Or copy: <a href='{$link}' style='color:#1877f2;word-break:break-all;'>{$link}</a></p>
    </td></tr>
    <tr><td style='background:#f8faff;border:1px solid #e5eaf5;border-top:none;border-radius:0 0 14px 14px;padding:20px 36px;text-align:center;'>
        <p style='margin:0;font-size:12px;color:#9ca3af;'>If you did not request this, ignore this email.<br>
        &copy; " . date('Y') . " AITS — <a href='https://alinitservices.com' style='color:#1877f2;text-decoration:none;'>alinitservices.com</a></p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>");
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Admin reset email failed: ' . $e->getMessage());
        }
    }
}