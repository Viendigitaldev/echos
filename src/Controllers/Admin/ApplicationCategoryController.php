<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\ApplicationCategory;
use App\Services\SlugService;

final class ApplicationCategoryController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/application-categories/index', [
            'pageTitle' => 'Application Categories',
            'items' => ApplicationCategory::withPublishedCounts(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/application-categories/form', ['pageTitle' => 'New Category', 'item' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = ApplicationCategory::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Category not found.');
            redirect('/admin/application-categories');
        }
        $this->render('admin/application-categories/form', ['pageTitle' => 'Edit Category', 'item' => $item]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $name = $request->trimmedInput('name');

        ApplicationCategory::insert([
            'name' => $name,
            'slug' => SlugService::unique('application_categories', 'slug', $name),
        ]);

        $this->flash('success', 'Category created.');
        redirect('/admin/application-categories');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = ApplicationCategory::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Category not found.');
            redirect('/admin/application-categories');
        }

        $name = $request->trimmedInput('name');
        $data = ['name' => $name];
        if ($name !== $item['name']) {
            $data['slug'] = SlugService::unique('application_categories', 'slug', $name, (int) $id);
        }

        ApplicationCategory::update((int) $id, $data);
        $this->flash('success', 'Category updated.');
        redirect('/admin/application-categories');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        ApplicationCategory::delete((int) $id);
        $this->flash('success', 'Category deleted.');
        redirect('/admin/application-categories');
    }
}
