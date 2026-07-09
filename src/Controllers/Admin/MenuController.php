<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\MenuItem;

final class MenuController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/menu/index', [
            'pageTitle' => 'Menu',
            'items' => MenuItem::all('location ASC, sort_order ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/menu/form', [
            'pageTitle' => 'New Menu Item',
            'item' => null,
            'parents' => MenuItem::allWhere(['location' => 'header']),
        ]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = MenuItem::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Menu item not found.');
            redirect('/admin/menu');
        }
        $this->render('admin/menu/form', [
            'pageTitle' => 'Edit Menu Item',
            'item' => $item,
            'parents' => MenuItem::allWhere(['location' => 'header']),
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        MenuItem::insert($this->collectFields($request));
        $this->flash('success', 'Menu item created.');
        redirect('/admin/menu');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        MenuItem::update((int) $id, $this->collectFields($request));
        $this->flash('success', 'Menu item updated.');
        redirect('/admin/menu');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        MenuItem::delete((int) $id);
        $this->flash('success', 'Menu item deleted.');
        redirect('/admin/menu');
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        $parentId = (int) $request->input('parent_id', 0);
        $location = $request->input('location');
        if (!in_array($location, ['header', 'footer_left', 'footer_right'], true)) {
            $location = 'header';
        }

        return [
            'label' => $request->trimmedInput('label'),
            'url' => $request->trimmedInput('url', '#'),
            'icon_class' => $request->trimmedInput('icon_class') ?: null,
            'parent_id' => $parentId > 0 ? $parentId : null,
            'location' => $location,
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }
}
