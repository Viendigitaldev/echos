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

    /**
     * Generates a random reset token, stores only its hash + a 1-hour
     * expiry, and returns the raw token for the caller to email — the raw
     * value is never persisted, so a DB compromise alone can't be used to
     * reset a password.
     */
    public static function createPasswordResetToken(int $id): string
    {
        $token = bin2hex(random_bytes(32));

        static::update($id, [
            'password_reset_token_hash' => hash('sha256', $token),
            'password_reset_expires_at' => date('Y-m-d H:i:s', time() + 3600),
        ]);

        return $token;
    }

    /** @return array<string, mixed>|null Null if the token is unknown or expired. */
    public static function findByValidResetToken(string $token): ?array
    {
        $user = static::findWhere(['password_reset_token_hash' => hash('sha256', $token)]);

        if ($user === null || $user['password_reset_expires_at'] === null) {
            return null;
        }

        if (strtotime($user['password_reset_expires_at']) < time()) {
            return null;
        }

        return $user;
    }

    public static function clearPasswordResetToken(int $id): void
    {
        static::update($id, ['password_reset_token_hash' => null, 'password_reset_expires_at' => null]);
    }
}
