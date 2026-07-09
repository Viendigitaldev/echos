<?php

declare(strict_types=1);

namespace App\Support;

final class Url
{
    private static ?string $basePath = null;
    private static string $origin = '';

    public static function boot(string $basePath, string $appUrl = ''): void
    {
        self::$basePath = rtrim($basePath, '/');

        $parts = parse_url($appUrl);
        if ($parts !== false && isset($parts['scheme'], $parts['host'])) {
            $port = isset($parts['port']) ? ':' . $parts['port'] : '';
            self::$origin = $parts['scheme'] . '://' . $parts['host'] . $port;
        }
    }

    public static function to(string $path): string
    {
        [$pathPart, $query] = array_pad(explode('?', $path, 2), 2, null);

        $pathPart = '/' . ltrim((string) $pathPart, '/');
        $encoded = implode('/', array_map(
            static fn (string $segment): string => rawurlencode($segment),
            explode('/', $pathPart)
        ));

        if ($query !== null) {
            $encoded .= '?' . $query;
        }

        return self::$basePath . $encoded;
    }

    public static function asset(string $path): string
    {
        $relativePath = ltrim($path, '/');
        $url = self::to('assets/' . $relativePath);

        // Cache-bust with the file's mtime so an edit is picked up on the next
        // load instead of being served from a browser's cache indefinitely
        // (this project sends no Cache-Control header on static assets).
        $filePath = dirname(__DIR__, 2) . '/public/assets/' . $relativePath;
        if (is_file($filePath)) {
            $url .= '?v=' . filemtime($filePath);
        }

        return $url;
    }

    /**
     * Absolute, fully-qualified URL — required for og:image tags (crawlers
     * should not be handed a root-relative path).
     */
    public static function absolute(string $path): string
    {
        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        return self::$origin . self::to($path);
    }

    /**
     * Absolute canonical URL for the current request (query string dropped).
     */
    public static function currentAbsolute(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        return self::$origin . $path;
    }
}
