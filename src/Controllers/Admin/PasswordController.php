<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\AdminUser;

final class PasswordController extends AdminController
{
    public function show(Request $request): void
    {
        $this->render('admin/password', []);
    }

    public function update(Request $request): void
    {
        $this->requireCsrf($request);

        $password = (string) $request->input('password', '');
        $confirm = (string) $request->input('password_confirmation', '');

        if (strlen($password) < 10) {
            $this->flash('error', 'Password must be at least 10 characters.');
            redirect('/admin/password');
        }

        if ($password !== $confirm) {
            $this->flash('error', 'Passwords do not match.');
            redirect('/admin/password');
        }

        AdminUser::update((int) $this->currentUser['id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'must_change_password' => 0,
        ]);

        $this->flash('success', 'Password updated.');
        redirect('/admin');
    }
}
