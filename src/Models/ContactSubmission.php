<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

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

    /**
     * Number of submissions received from $ip in the last $minutes — backs
     * the contact form's per-IP rate limit.
     */
    public static function countRecentByIp(string $ip, int $minutes): int
    {
        if ($ip === '') {
            return 0;
        }

        $cutoff = date('Y-m-d H:i:s', time() - ($minutes * 60));

        $stmt = Connection::get()->prepare(
            'SELECT COUNT(*) FROM contact_submissions WHERE ip_address = :ip AND created_at >= :cutoff'
        );
        $stmt->execute(['ip' => $ip, 'cutoff' => $cutoff]);

        return (int) $stmt->fetchColumn();
    }
}
