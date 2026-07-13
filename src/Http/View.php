<?php

declare(strict_types=1);

namespace App\Http;

final class View
{
    private const VIEWS_PATH = __DIR__ . '/../Views';

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $content = $this->capture($view, $data);

        if ($layout === null) {
            echo $content;
            return;
        }

        echo $this->capture($layout, array_merge($data, ['content' => $content]));
    }

    /**
     * Renders a view with no layout wrapper and returns the markup instead
     * of echoing it — used for email bodies, which aren't a page response.
     *
     * @param array<string, mixed> $data
     */
    public function renderToString(string $view, array $data = []): string
    {
        return $this->capture($view, $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function capture(string $view, array $data): string
    {
        $file = self::VIEWS_PATH . '/' . $view . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
