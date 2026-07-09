<?php

declare(strict_types=1);

use App\Support\Env;

Env::load(dirname(__DIR__) . '/.env');

return [
    'name' => Env::get('APP_NAME', 'Echos'),
    'env' => Env::get('APP_ENV', 'production'),
    'debug' => Env::get('APP_DEBUG', 'false') === 'true',
    'url' => rtrim(Env::get('APP_URL', ''), '/'),
    'base_path' => rtrim(Env::get('APP_BASE_PATH', ''), '/'),
    'session_name' => Env::get('SESSION_NAME', 'echos_session'),
];
