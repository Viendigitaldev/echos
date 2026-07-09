<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\BlogCategory;
use App\Services\SlugService;

final class BlogCategoryController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/blog-categories/index', [
            'pageTitle' => 'Blog Categories',
            'items' => BlogCategory::withPublishedCounts(),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/blog-categories/form', ['pageTitle' => 'New Category', 'item' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = BlogCategory::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Category not found.');
            redirect('/admin/blog-categories');
        }
        $this->render('admin/blog-categories/form', ['pageTitle' => 'Edit Category', 'item' => $item]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $name = $request->trimmedInput('name');

        BlogCategory::insert([
            'name' => $name,
            'slug' => SlugService::unique('blog_categories', 'slug', $name),
        ]);

        $this->flash('success', 'Category created.');
        redirect('/admin/blog-categories');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = BlogCategory::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Category not found.');
            redirect('/admin/blog-categories');
        }

        $name = $request->trimmedInput('name');
        $data = ['name' => $name];
        if ($name !== $item['name']) {
            $data['slug'] = SlugService::unique('blog_categories', 'slug', $name, (int) $id);
        }

        BlogCategory::update((int) $id, $data);
        $this->flash('success', 'Category updated.');
        redirect('/admin/blog-categories');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        BlogCategory::delete((int) $id);
        $this->flash('success', 'Category deleted.');
        redirect('/admin/blog-categories');
    }
}
