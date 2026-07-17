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

    public function updateUsername(Request $request): void
    {
        $this->requireCsrf($request);

        $currentPassword = (string) $request->input('current_password', '');
        if (!password_verify($currentPassword, $this->currentUser['password_hash'])) {
            $this->flash('error', 'Current password is incorrect.');
            redirect('/admin/password');
        }

        $username = $request->trimmedInput('username');
        if ($username === '') {
            $this->flash('error', 'Enter a username.');
            redirect('/admin/password');
        }

        $existing = AdminUser::findByUsername($username);
        if ($existing !== null && (int) $existing['id'] !== (int) $this->currentUser['id']) {
            $this->flash('error', 'That username is already in use.');
            redirect('/admin/password');
        }

        AdminUser::update((int) $this->currentUser['id'], ['username' => $username]);

        $this->flash('success', 'Username updated.');
        redirect('/admin/password');
    }

    /**
     * The recovery email used for forgot-password links — separate from
     * the username used to log in.
     */
    public function updateEmail(Request $request): void
    {
        $this->requireCsrf($request);

        $currentPassword = (string) $request->input('current_password', '');
        if (!password_verify($currentPassword, $this->currentUser['password_hash'])) {
            $this->flash('error', 'Current password is incorrect.');
            redirect('/admin/password');
        }

        $email = $request->trimmedInput('email');
        if ($email === '' || !str_contains($email, '@')) {
            $this->flash('error', 'Enter a valid email address.');
            redirect('/admin/password');
        }

        $existing = AdminUser::findByEmail($email);
        if ($existing !== null && (int) $existing['id'] !== (int) $this->currentUser['id']) {
            $this->flash('error', 'That email is already in use.');
            redirect('/admin/password');
        }

        AdminUser::update((int) $this->currentUser['id'], ['email' => $email]);

        $this->flash('success', 'Recovery email updated.');
        redirect('/admin/password');
    }
}
