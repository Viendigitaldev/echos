<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\Industry;

final class IndustryController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/industries/index', [
            'pageTitle' => 'Industries',
            'items' => Industry::all('sort_order ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/industries/form', ['pageTitle' => 'New Industry', 'item' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = Industry::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Industry not found.');
            redirect('/admin/industries');
        }
        $this->render('admin/industries/form', ['pageTitle' => 'Edit Industry', 'item' => $item]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $data = $this->collectFields($request);

        try {
            $icon = $this->resolveImage($_FILES['icon'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/industries/create');
        }
        if ($icon === null) {
            $this->flash('error', 'An icon image is required.');
            redirect('/admin/industries/create');
        }
        $data['icon_path'] = $icon;

        Industry::insert($data);
        $this->flash('success', 'Industry created.');
        redirect('/admin/industries');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = Industry::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Industry not found.');
            redirect('/admin/industries');
        }

        $data = $this->collectFields($request);

        try {
            $icon = $this->resolveImage($_FILES['icon'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/industries/' . $id . '/edit');
        }
        if ($icon !== null) {
            $data['icon_path'] = $icon;
        }

        Industry::update((int) $id, $data);
        $this->flash('success', 'Industry updated.');
        redirect('/admin/industries');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        Industry::delete((int) $id);
        $this->flash('success', 'Industry deleted.');
        redirect('/admin/industries');
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        return [
            'title' => $request->trimmedInput('title'),
            'description' => $request->trimmedInput('description'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'status' => $request->input('status') === 'draft' ? 'draft' : 'published',
        ];
    }
}
