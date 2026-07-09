<?php

declare(strict_types=1);

namespace App\Models;

final class AdminUser extends Model
{
    protected static string $table = 'admin_users';

    /** @return array<string, mixed>|null */
    public static function findByEmail(string $email): ?array
    {
        return static::findWhere(['email' => $email]);
    }

    public static function touchLogin(int $id): void
    {
        static::update($id, ['last_login_at' => date('Y-m-d H:i:s')]);
    }
}
