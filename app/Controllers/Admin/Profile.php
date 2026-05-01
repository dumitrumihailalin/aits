<?php
namespace App\Controllers\Admin;

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
        return view('admin/profile/index', [
            'title' => 'Admin Profile',
            'user'  => $this->userModel->find($this->userId),
        ]);
    }

    public function update()
    {
        if (! $this->validate([
            'name'  => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email',
        ])) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
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

        session()->set('name', $data['name']);
        session()->set('email', $data['email']);

        return redirect()->to(base_url('admin/profile'))
            ->with('success', 'Profile updated successfully.');
    }
}