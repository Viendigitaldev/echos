<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\OfficeLocation;
use App\Services\SlugService;

final class OfficeLocationController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/office-locations/index', [
            'pageTitle' => 'Office Locations',
            'items' => OfficeLocation::ordered(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/office-locations/form', ['pageTitle' => 'New Office Location', 'item' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = OfficeLocation::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Office location not found.');
            redirect('/admin/office-locations');
        }
        $this->render('admin/office-locations/form', ['pageTitle' => 'Edit Office Location', 'item' => $item]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $data = $this->collectFields($request);
        $data['slug'] = SlugService::unique('office_locations', 'slug', $request->trimmedInput('slug') ?: $request->trimmedInput('name'));

        $imagePath = $this->resolveImageOrUrl($request);
        if ($imagePath === null) {
            $this->flash('error', 'An image (upload or URL) is required.');
            redirect('/admin/office-locations/create');
        }
        $data['image_path'] = $imagePath;

        OfficeLocation::insert($data);
        $this->flash('success', 'Office location created.');
        redirect('/admin/office-locations');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = OfficeLocation::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Office location not found.');
            redirect('/admin/office-locations');
        }

        $data = $this->collectFields($request);
        $requestedSlug = $request->trimmedInput('slug');
        if ($requestedSlug !== '' && $requestedSlug !== $item['slug']) {
            $data['slug'] = SlugService::unique('office_locations', 'slug', $requestedSlug, (int) $id);
        }

        $imagePath = $this->resolveImageOrUrl($request);
        if ($imagePath !== null) {
            $data['image_path'] = $imagePath;
        }

        OfficeLocation::update((int) $id, $data);
        $this->flash('success', 'Office location updated.');
        redirect('/admin/office-locations');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        OfficeLocation::delete((int) $id);
        $this->flash('success', 'Office location deleted.');
        redirect('/admin/office-locations');
    }

    private function resolveImageOrUrl(Request $request): ?string
    {
        try {
            $resolved = $this->resolveImage($_FILES['image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect($_SERVER['HTTP_REFERER'] ?? '/admin/office-locations');
        }

        if ($resolved !== null) {
            return $resolved;
        }

        $url = $request->trimmedInput('image_url');
        return $url !== '' ? $url : null;
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        return [
            'name' => $request->trimmedInput('name'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }
}
