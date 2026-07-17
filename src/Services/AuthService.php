<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AdminUser;

final class AuthService
{
    private const SESSION_KEY = 'admin_user_id';

    public static function attempt(string $username, string $password): bool
    {
        $user = AdminUser::findByUsername($username);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION[self::SESSION_KEY] = (int) $user['id'];
        AdminUser::touchLogin((int) $user['id']);

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::SESSION_KEY]);
    }

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return AdminUser::find((int) $_SESSION[self::SESSION_KEY]);
    }
}
