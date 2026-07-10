<?php

declare(strict_types=1);

namespace App\Http;

use App\Models\Redirect;

final class Router
{
    /** @var array<int, array{method: string, pattern: string, regex: string, params: array<int, string>, handler: array{0: class-string, 1: string}}> */
    private array $routes = [];

    public function get(string $pattern, array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    private function add(string $method, string $pattern, array $handler): void
    {
        $paramNames = [];
        $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function (array $matches) use (&$paramNames) {
            $paramNames[] = $matches[1];
            return '([^/]+)';
        }, $pattern);

        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'regex' => '#^' . $regex . '$#',
            'params' => $paramNames,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        $path = $request->path();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = array_combine($route['params'], $matches);

            [$controllerClass, $action] = $route['handler'];
            $controller = new $controllerClass();
            $controller->$action($request, ...array_values($params));
            return;
        }

        self::renderNotFound($method === 'GET' ? $path : null);
    }

    /**
     * Also used by controllers when a specific record lookup fails (e.g.
     * BlogController::show() on an unknown slug) — not just the router's
     * own "no route pattern matched" fallback — so pass the current path
     * whenever it's known, to give a slug-changed redirect a chance to
     * fire before falling through to a real 404.
     */
    public static function renderNotFound(?string $path = null): void
    {
        if ($path !== null) {
            $redirect = Redirect::findByFromPath($path);
            if ($redirect !== null) {
                header('Location: ' . url($redirect['to_path']), true, 301);
                return;
            }
        }

        http_response_code(404);
        (new View())->render('errors/404', [
            'title' => '404 — Page Not Found',
            'noindex' => true,
        ]);
    }
}
