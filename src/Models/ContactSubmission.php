<?php

declare(strict_types=1);

namespace App\Models;

final class ContactSubmission extends Model
{
    protected static string $table = 'contact_submissions';

    /** @return array<int, array<string, mixed>> */
    public static function recent(): array
    {
        return static::all('created_at DESC');
    }

    public static function unreadCount(): int
    {
        return static::count('is_read = 0');
    }
}
