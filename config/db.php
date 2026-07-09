<?php

declare(strict_types=1);

use App\Support\Env;

Env::load(dirname(__DIR__) . '/.env');

$socket = Env::get('DB_SOCKET', '');

return [
    'driver' => 'mysql',
    'host' => Env::get('DB_HOST', '127.0.0.1'),
    'port' => Env::get('DB_PORT', '3306'),
    'socket' => $socket !== '' ? $socket : null,
    'database' => Env::get('DB_DATABASE', 'echos_dev'),
    'username' => Env::get('DB_USERNAME', 'root'),
    'password' => Env::get('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
];
