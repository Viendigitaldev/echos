<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Http\View;
use App\Services\AuthService;
use App\Services\CsrfService;

final class AuthController
{
    public function showLogin(Request $request): void
    {
        if (AuthService::check()) {
            redirect('/admin');
        }

        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);

        (new View())->render('admin/login', ['flash' => $flash], null);
    }

    public function login(Request $request): void
    {
        if (!CsrfService::validate($request->input('_csrf'))) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
            redirect('/admin/login');
        }

        $email = $request->trimmedInput('email');
        $password = (string) $request->input('password', '');

        if (!AuthService::attempt($email, $password)) {
            $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Invalid email or password.'];
            redirect('/admin/login');
        }

        $user = AuthService::user();
        if ($user['must_change_password']) {
            redirect('/admin/password');
        }

        redirect('/admin');
    }

    public function logout(Request $request): void
    {
        if (CsrfService::validate($request->input('_csrf'))) {
            AuthService::logout();
        }

        redirect('/admin/login');
    }
}
