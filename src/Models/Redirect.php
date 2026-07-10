<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\Connection;

final class Redirect extends Model
{
    protected static string $table = 'redirects';

    /** @return array<string, mixed>|null */
    public static function findByFromPath(string $path): ?array
    {
        return static::findWhere(['from_path' => $path]);
    }

    /**
     * Records that $oldPath now permanently redirects to $newPath, keeping
     * every existing redirect a single hop instead of chaining:
     * - any prior redirect INTO $oldPath is repointed straight to $newPath
     * - any stale redirect FROM $newPath is dropped ($newPath is live again)
     */
    public static function recordSlugChange(string $oldPath, string $newPath): void
    {
        if ($oldPath === $newPath) {
            return;
        }

        $pdo = Connection::get();

        $stmt = $pdo->prepare('DELETE FROM redirects WHERE from_path = :path');
        $stmt->execute(['path' => $newPath]);

        $stmt = $pdo->prepare('UPDATE redirects SET to_path = :new WHERE to_path = :old');
        $stmt->execute(['new' => $newPath, 'old' => $oldPath]);

        // Native (non-emulated) prepares don't allow reusing a named
        // parameter twice in one query, hence :to_insert / :to_update.
        $stmt = $pdo->prepare(
            'INSERT INTO redirects (from_path, to_path) VALUES (:from, :to_insert)
             ON DUPLICATE KEY UPDATE to_path = :to_update'
        );
        $stmt->execute(['from' => $oldPath, 'to_insert' => $newPath, 'to_update' => $newPath]);
    }
}
