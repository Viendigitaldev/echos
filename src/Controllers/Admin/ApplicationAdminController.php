<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\ApplicationCategory;
use App\Models\ApplicationItem;
use App\Services\SitemapGenerator;
use App\Services\SlugService;

final class ApplicationAdminController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/applications/index', [
            'pageTitle' => 'Applications',
            'items' => ApplicationItem::allWithCategoryNames(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/applications/form', [
            'pageTitle' => 'New Application',
            'item' => null,
            'categories' => ApplicationCategory::all('name ASC'),
        ]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = ApplicationItem::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Application not found.');
            redirect('/admin/applications');
        }

        $this->render('admin/applications/form', [
            'pageTitle' => 'Edit Application',
            'item' => $item,
            'categories' => ApplicationCategory::all('name ASC'),
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $data = $this->collectFields($request);
        $data['slug'] = SlugService::unique('applications', 'slug', $request->trimmedInput('title'));

        try {
            $image = $this->resolveImage($_FILES['image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/applications/create');
        }
        if ($image === null) {
            $this->flash('error', 'An image is required.');
            redirect('/admin/applications/create');
        }
        $data['image_path'] = $image;

        ApplicationItem::insert($data);
        SitemapGenerator::regenerate();

        $this->flash('success', 'Application created.');
        redirect('/admin/applications');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = ApplicationItem::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Application not found.');
            redirect('/admin/applications');
        }

        $data = $this->collectFields($request);
        if ($request->trimmedInput('title') !== $item['title']) {
            $data['slug'] = SlugService::unique('applications', 'slug', $request->trimmedInput('title'), (int) $id);
        }

        try {
            $image = $this->resolveImage($_FILES['image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/applications/' . $id . '/edit');
        }
        if ($image !== null) {
            $data['image_path'] = $image;
        }

        ApplicationItem::update((int) $id, $data);
        SitemapGenerator::regenerate();

        $this->flash('success', 'Application updated.');
        redirect('/admin/applications');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        ApplicationItem::delete((int) $id);
        SitemapGenerator::regenerate();

        $this->flash('success', 'Application deleted.');
        redirect('/admin/applications');
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        $categoryId = (int) $request->input('category_id', 0);

        return [
            'title' => $request->trimmedInput('title'),
            'category_id' => $categoryId > 0 ? $categoryId : null,
            'short_description' => $request->trimmedInput('short_description'),
            'full_description' => $request->trimmedInput('full_description'),
            'show_on_home' => $request->input('show_on_home') ? 1 : 0,
            'show_on_applications_page' => $request->input('show_on_applications_page') ? 1 : 0,
            'sort_order' => (int) $request->input('sort_order', 0),
            'status' => $request->input('status') === 'draft' ? 'draft' : 'published',
            'seo_title' => $request->trimmedInput('seo_title'),
            'seo_description' => $request->trimmedInput('seo_description'),
        ];
    }
}
