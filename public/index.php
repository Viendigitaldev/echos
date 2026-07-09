<?php

declare(strict_types=1);

use App\Http\Request;
use App\Http\Router;
use App\Http\View;
use App\Services\AssetManager;
use App\Services\Logger;
use App\Support\Url;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/app.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['debug'] ? '1' : '0');

$isHttps = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

session_name($config['session_name']);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => $isHttps,
]);
session_start();

Url::boot($config['base_path'], $config['url']);
AssetManager::boot();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

set_exception_handler(function (\Throwable $e) use ($config): void {
    Logger::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
    http_response_code(500);

    if ($config['debug']) {
        echo '<pre>' . htmlspecialchars((string) $e, ENT_QUOTES, 'UTF-8') . '</pre>';
        return;
    }

    (new View())->render('errors/500', [], null);
});

$request = new Request($config['base_path']);

$router = new Router();
require dirname(__DIR__) . '/src/routes.php';

$router->dispatch($request);
