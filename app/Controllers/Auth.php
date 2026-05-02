<?php
namespace App\Controllers;

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

    // ── Register ─────────────────────────────────────────
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/register', ['title' => 'Create Account']);
    }

    public function registerPost()
    {
        $rules = [
            'company_name'     => 'required|min_length[2]|max_length[150]',
            'name'             => 'required|min_length[2]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'phone'            => 'required|min_length[6]|max_length[20]',
            'address'          => 'required|min_length[5]|max_length[255]',
            'country'          => 'required',
            'city'             => 'required|min_length[2]|max_length[100]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules, [
            'email'            => ['is_unique' => 'This email is already registered.'],
            'password_confirm' => ['matches'   => 'Passwords do not match.'],
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $token = bin2hex(random_bytes(32));

        $userId = $this->userModel->insert([
            'name'               => $this->request->getPost('name'),
            'email'              => $this->request->getPost('email'),
            'password'           => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'company_name'       => $this->request->getPost('company_name'),
            'phone'              => $this->request->getPost('phone'),
            'address'            => $this->request->getPost('address'),
            'country'            => $this->request->getPost('country'),
            'city'               => $this->request->getPost('city'),
            'verification_token' => $token,
            'email_verified_at'  => null,
        ]);

        // Assign customer role via RBAC
        $db           = \Config\Database::connect();
        $customerRole = $db->table('roles')->where('slug', 'customer')->get()->getRow();
        if ($customerRole) {
            $db->table('role_user')->insert([
                'user_id'    => $userId,
                'role_id'    => $customerRole->id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        $this->sendVerificationEmail(
            $this->request->getPost('email'),
            $this->request->getPost('name'),
            $token
        );
        if (! $userId) {
            return redirect()->back()->with('errors', ['registration' => 'Registration failed. Please try again.'])->withInput();
        }
        return redirect()->to(base_url('register/success'));
    }

    public function registerSuccess()
    {
        return view('auth/register_success', ['title' => 'Check Your Email']);
    }

    // ── Email Verification ───────────────────────────────
    public function verify(string $token)
    {
        $email = $this->request->getGet('email');

        if (! $token || ! $email) {
            return redirect()->to(base_url('login'))->with('error', 'Invalid verification link.');
        }

        $user = $this->userModel
            ->where('email', $email)
            ->where('verification_token', $token)
            ->first();

        if (! $user) {
            return redirect()->to(base_url('login'))->with('error', 'Invalid or already used verification link.');
        }

        if (! empty($user['email_verified_at'])) {
            return redirect()->to(base_url('login'))->with('info', 'Email already verified. Please log in.');
        }

        $this->userModel->update($user['id'], [
            'email_verified_at'  => date('Y-m-d H:i:s'),
            'verification_token' => null,
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Email verified! You can now log in.');
    }

    public function resendVerification()
    {
        return view('auth/resend_verification', ['title' => 'Resend Verification']);
    }

    public function resendVerificationPost()
    {
        $email = $this->request->getPost('email');
        $user  = $this->userModel->where('email', $email)->first();

        if ($user && empty($user['email_verified_at'])) {
            $token = bin2hex(random_bytes(32));
            $this->userModel->update($user['id'], ['verification_token' => $token]);
            $this->sendVerificationEmail($user['email'], $user['name'], $token);
        }

        return redirect()->back()->with('success', 'If that email exists and is unverified, we sent a new link.');
    }

    // ── Login ────────────────────────────────────────────
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to(base_url('dashboard'));
        }
        return view('auth/login', [
            'title'    => 'Login',
            'redirect' => $this->request->getGet('redirect') ?? '',
        ]);
    }

    public function loginPost()
    {
        if (! $this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $user = $this->userModel->findByEmailWithRole($this->request->getPost('email'));

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->with('error', 'Invalid email or password.')->withInput();
        }

        if (empty($user['email_verified_at'])) {
            return redirect()->back()
                ->with('error', 'Please verify your email first. <a href="' . base_url('resend-verification') . '">Resend link</a>')
                ->withInput();
        }

        if (empty($user['role']) || $user['role'] !== 'customer') {
            return redirect()->back()->with('error', 'Invalid email or password.')->withInput();
        }

        session()->set([
            'isLoggedIn' => true,
            'role'       => $user['role'],
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'locale'     => $user['preferred_language'] ?? 'en',
        ]);

        $redirectTo = $this->request->getPost('redirect');
        if ($redirectTo && str_starts_with($redirectTo, '/')) {
            return redirect()->to(base_url(ltrim($redirectTo, '/')));
        }

        return redirect()->to(base_url('dashboard'));
    }

    // ── Logout ───────────────────────────────────────────
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'You have been logged out.');
    }

    // ── Forgot Password ──────────────────────────────────
    public function forgotPassword()
    {
        return view('auth/forgot_password', ['title' => 'Forgot Password']);
    }

    public function forgotPasswordPost()
    {
        if (! $this->validate(['email' => 'required|valid_email'])) {
            return redirect()->back()->with('errors', $this->validator->getErrors())->withInput();
        }

        $email = $this->request->getPost('email');
        $user  = $this->userModel->findByEmailWithRole($email);
        if ($user && ($user['role'] ?? '') !== 'customer') {
            $user = null;
        }

        if ($user) {
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
            return redirect()->to(base_url('forgot-password'))->with('error', 'Reset link is invalid or expired.');
        }

        return view('auth/reset_password', ['title' => 'Reset Password', 'token' => $token]);
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
            return redirect()->to(base_url('forgot-password'))->with('error', 'Reset link is invalid or expired.');
        }

        $this->userModel->update($user['id'], [
            'password'         => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'reset_token'      => null,
            'reset_expires_at' => null,
        ]);

        return redirect()->to(base_url('login'))->with('success', 'Password reset! You can now log in.');
    }

    // ── Private: Verification Email ──────────────────────
    private function sendVerificationEmail(string $email, string $name, string $token): void
    {
        $link = base_url('verify-email/' . $token . '?email=' . urlencode($email));

        try {
            $svc = \Config\Services::email();
            $svc->setTo($email);
            $svc->setSubject('Verify your email — AITS');
            $svc->setMessage($this->emailTemplate(
                'Verify your email ✉️',
                "Hi <strong>{$name}</strong>, thanks for registering with AITS.<br>Click the button below to activate your account.",
                $link,
                'Verify Email Address',
                'This link expires in <strong>24 hours</strong>. If you did not create an account, ignore this email.'
            ));
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Verification email failed: ' . $e->getMessage());
        }
    }

    // ── Private: Reset Email ─────────────────────────────
    private function sendResetEmail(string $email, string $name, string $token): void
    {
        $link = base_url('reset-password/' . $token);

        try {
            $svc = \Config\Services::email();
            $svc->setTo($email);
            $svc->setSubject('Reset your password — AITS');
            $svc->setMessage($this->emailTemplate(
                'Reset your password 🔑',
                "Hi <strong>{$name}</strong>, we received a request to reset your password.<br>Click the button below to choose a new one.",
                $link,
                'Reset Password',
                'This link expires in <strong>1 hour</strong>. If you did not request this, ignore this email.'
            ));
            $svc->send();
        } catch (\Exception $e) {
            log_message('error', 'Reset email failed: ' . $e->getMessage());
        }
    }

    // ── Private: Reusable Email Template ─────────────────
    private function emailTemplate(string $heading, string $body, string $link, string $btnText, string $note): string
    {
        return "<!DOCTYPE html>
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
    <tr><td style='background:#fff;padding:40px 36px;border-left:1px solid #e5eaf5;border-right:1px solid #e5eaf5;'>
        <h1 style='margin:0 0 12px;font-size:22px;font-weight:700;color:#111827;'>{$heading}</h1>
        <p style='margin:0 0 28px;font-size:14px;color:#6b7280;line-height:1.7;'>{$body}</p>
        <div style='text-align:center;margin-bottom:28px;'>
            <a href='{$link}' style='display:inline-block;background:#1877f2;color:#fff;text-decoration:none;padding:14px 36px;border-radius:8px;font-size:15px;font-weight:700;'>{$btnText}</a>
        </div>
        <div style='background:#f8faff;border:1px solid #e5eaf5;border-radius:10px;padding:14px 18px;'>
            <p style='margin:0 0 4px;font-size:12px;color:#9ca3af;'>Or copy this link:</p>
            <a href='{$link}' style='font-size:12px;color:#1877f2;word-break:break-all;'>{$link}</a>
        </div>
    </td></tr>
    <tr><td style='background:#f8faff;border:1px solid #e5eaf5;border-top:none;border-radius:0 0 14px 14px;padding:20px 36px;text-align:center;'>
        <p style='margin:0;font-size:12px;color:#9ca3af;'>{$note}<br>
        &copy; " . date('Y') . " AITS — <a href='https://alinitservices.com' style='color:#1877f2;text-decoration:none;'>alinitservices.com</a></p>
    </td></tr>
</table>
</td></tr>
</table>
</body></html>";
    }
}