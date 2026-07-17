<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\Media;

final class MediaController extends AdminController
{
    public function index(Request $request): void
    {
        $items = Media::all('created_at DESC');
        $referenced = [];
        foreach ($items as $item) {
            $referenced[$item['id']] = Media::isReferenced($item['path']);
        }

        $this->render('admin/media/index', [
            'pageTitle' => 'Media Library',
            'items' => $items,
            'referenced' => $referenced,
        ]);
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);

        $altText = $request->trimmedInput('alt_text');
        if ($altText === '') {
            $this->flash('error', 'Alt text is required.');
            redirect('/admin/media');
        }

        Media::updateAltText((int) $id, $altText);
        $this->flash('success', 'Alt text updated.');
        redirect('/admin/media');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);

        $item = Media::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Image not found.');
            redirect('/admin/media');
        }

        if (Media::isReferenced($item['path'])) {
            $this->flash('error', 'This image is still in use and cannot be deleted.');
            redirect('/admin/media');
        }

        $absolute = dirname(__DIR__, 3) . '/public/' . ltrim($item['path'], '/');
        if (is_file($absolute)) {
            unlink($absolute);
        }

        Media::delete((int) $id);
        $this->flash('success', 'Image deleted.');
        redirect('/admin/media');
    }
}
