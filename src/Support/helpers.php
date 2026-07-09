<?php

declare(strict_types=1);

use App\Services\CsrfService;
use App\Support\Url;

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = '/'): string
    {
        return Url::to($path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return Url::asset($path);
    }
}

if (!function_exists('media_url')) {
    /**
     * For DB-stored image paths that may be either a full external URL
     * (e.g. a hotlinked stock photo) or a local root-relative path
     * (uploaded via the admin or a theme asset) — passes external URLs
     * through unchanged and routes local ones through url().
     */
    function media_url(?string $path): string
    {
        $path = (string) $path;

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        return Url::to($path);
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        $token = e(CsrfService::token());

        return '<input type="hidden" name="_csrf" value="' . $token . '">';
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        header('Location: ' . Url::to($path));
        exit;
    }
}
