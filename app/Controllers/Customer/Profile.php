<?php
namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected UserModel $userModel;
    protected string    $userId;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->userModel = new UserModel();
        $this->userId    = session()->get('user_id');
    }

    public function index()
    {
        return view('customer/profile/index', [
            'title' => 'My Profile',
            'user'  => $this->userModel->find($this->userId),
        ]);
    }

    public function update()
    {
        if (! $this->validate([
            'name'         => 'required|min_length[2]|max_length[100]',
            'company_name' => 'permit_empty|max_length[150]',
            'phone'        => 'permit_empty|max_length[20]',
            'address'      => 'permit_empty|max_length[255]',
            'country'      => 'permit_empty|max_length[100]',
            'city'         => 'permit_empty|max_length[100]',
        ])) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = [
            'name'         => $this->request->getPost('name'),
            'company_name' => $this->request->getPost('company_name'),
            'phone'        => $this->request->getPost('phone'),
            'address'      => $this->request->getPost('address'),
            'country'      => $this->request->getPost('country'),
            'city'         => $this->request->getPost('city'),
        ];

        // Change password if provided
        $newPassword = $this->request->getPost('new_password');
        if (! empty($newPassword)) {
            if (! $this->validate([
                'new_password'         => 'min_length[8]',
                'new_password_confirm' => 'required|matches[new_password]',
            ])) {
                return redirect()->back()
                    ->with('errors', $this->validator->getErrors())
                    ->withInput();
            }

            $user = $this->userModel->find($this->userId);
            if (! password_verify($this->request->getPost('current_password'), $user['password'])) {
                return redirect()->back()
                    ->with('error', 'Current password is incorrect.')
                    ->withInput();
            }

            $data['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
        }

        $this->userModel->update($this->userId, $data);

        // Update session name
        session()->set('name', $data['name']);

        return redirect()->to(base_url('profile'))
            ->with('success', 'Profile updated successfully.');
    }

    public function updateLanguage(): \CodeIgniter\HTTP\RedirectResponse
    {
        $locale    = $this->request->getPost('preferred_language');
        $supported = config('App')->supportedLocales;
        if (in_array($locale, $supported, true)) {
            $this->userModel->update($this->userId, ['preferred_language' => $locale]);
            session()->set('locale', $locale);
        }
        return redirect()->to(base_url('profile'))->with('success', lang('Common.language_saved'));
    }

    // in Customer\Profile controller
    public function updateNotifications()
    {
        $this->userModel->update($this->userId, [
            'notify_ticket_updates' => $this->request->getPost('notify_ticket_updates') ? 1 : 0,
        ]);
        return redirect()->to(base_url('profile'))->with('success', 'Notification preference saved.');
    }
}