<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Http\View;
use App\Models\Media;
use App\Services\AuthService;
use App\Services\CsrfService;
use App\Services\UploadService;

abstract class AdminController
{
    /** @var array<string, mixed> */
    protected array $currentUser;

    public function __construct()
    {
        if (!AuthService::check()) {
            redirect('/admin/login');
        }

        $this->currentUser = AuthService::user();

        if ($this->currentUser['must_change_password'] && static::class !== PasswordController::class) {
            redirect('/admin/password');
        }
    }

    /**
     * Resolves an image path for a form submission: a freshly uploaded file
     * takes priority (alt text required, enforced by UploadService), then a
     * Media Library pick (already has alt text), else null — callers decide
     * whether null means "keep the existing image" (update) or an error
     * (create).
     *
     * @param array<string, mixed> $file One $_FILES entry
     * @throws \RuntimeException from UploadService::store() — missing alt
     *         text, unsupported type, oversized file, etc.
     */
    protected function resolveImage(array $file, string $altText, mixed $existingMediaId): ?string
    {
        $uploaded = UploadService::store($file, $altText);
        if ($uploaded !== null) {
            return $uploaded['path'];
        }

        $existingMediaId = (int) $existingMediaId;
        if ($existingMediaId > 0) {
            $media = Media::find($existingMediaId);
            if ($media !== null) {
                return $media['path'];
            }
        }

        return null;
    }

    protected function requireCsrf(Request $request): void
    {
        if (!CsrfService::validate($request->input('_csrf'))) {
            $this->flash('error', 'Your session expired. Please try again.');
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin');
        }
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
    }

    /** @return array{type: string, message: string}|null */
    protected function consumeFlash(): ?array
    {
        $flash = $_SESSION['admin_flash'] ?? null;
        unset($_SESSION['admin_flash']);
        return $flash;
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        $data['flash'] = $this->consumeFlash();
        $data['currentUser'] = $this->currentUser;
        (new View())->render($view, $data, 'admin/layout');
    }
}
