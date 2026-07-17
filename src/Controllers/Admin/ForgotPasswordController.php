<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Http\View;
use App\Models\AdminUser;
use App\Services\CsrfService;
use App\Services\Mailer;
use App\Support\Url;

final class ForgotPasswordController
{
    public function showForgotForm(Request $request): void
    {
        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);

        (new View())->render('admin/forgot-password', ['flash' => $flash], null);
    }

    public function sendResetLink(Request $request): void
    {
        if (!CsrfService::validate($request->input('_csrf'))) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
            redirect('/admin/forgot-password');
        }

        $email = $request->trimmedInput('email');
        $user = AdminUser::findByEmail($email);

        if ($user !== null) {
            $token = AdminUser::createPasswordResetToken((int) $user['id']);
            $resetUrl = Url::absolute('/admin/reset-password?token=' . $token);

            Mailer::send(
                $user['email'],
                'Reset your Echos admin password',
                "A password reset was requested for this account.\n\n"
                    . "Reset your password using the link below (valid for 1 hour):\n{$resetUrl}\n\n"
                    . "If you didn't request this, you can safely ignore this email."
            );
        }

        // Same message regardless of whether the email matched an account,
        // so this can't be used to enumerate which addresses have admin access.
        $_SESSION['admin_flash'] = ['type' => 'success', 'message' => 'If that email has an account, a reset link has been sent.'];
        redirect('/admin/login');
    }

    public function showResetForm(Request $request): void
    {
        $token = (string) $request->input('token', '');
        $user = $token !== '' ? AdminUser::findByValidResetToken($token) : null;

        if ($user === null) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'This reset link is invalid or has expired.'];
            redirect('/admin/login');
        }

        (new View())->render('admin/reset-password', ['token' => $token, 'flash' => null], null);
    }

    public function resetPassword(Request $request): void
    {
        if (!CsrfService::validate($request->input('_csrf'))) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
            redirect('/admin/login');
        }

        $token = (string) $request->input('token', '');
        $user = $token !== '' ? AdminUser::findByValidResetToken($token) : null;

        if ($user === null) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'This reset link is invalid or has expired.'];
            redirect('/admin/login');
        }

        $password = (string) $request->input('password', '');
        $confirm = (string) $request->input('password_confirmation', '');

        if (strlen($password) < 10) {
            (new View())->render('admin/reset-password', [
                'token' => $token,
                'flash' => ['type' => 'error', 'message' => 'Password must be at least 10 characters.'],
            ], null);
            return;
        }

        if ($password !== $confirm) {
            (new View())->render('admin/reset-password', [
                'token' => $token,
                'flash' => ['type' => 'error', 'message' => 'Passwords do not match.'],
            ], null);
            return;
        }

        AdminUser::update((int) $user['id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'must_change_password' => 0,
        ]);
        AdminUser::clearPasswordResetToken((int) $user['id']);

        $_SESSION['admin_flash'] = ['type' => 'success', 'message' => 'Password updated. You can now log in.'];
        redirect('/admin/login');
    }
}
