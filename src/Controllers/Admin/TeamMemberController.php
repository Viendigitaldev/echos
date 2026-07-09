<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\TeamMember;

final class TeamMemberController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/team/index', [
            'pageTitle' => 'Team Members',
            'items' => TeamMember::all('sort_order ASC'),
        ]);
    }

    public function create(Request $request): void
    {
        $this->render('admin/team/form', ['pageTitle' => 'New Team Member', 'item' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $item = TeamMember::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Team member not found.');
            redirect('/admin/team');
        }
        $this->render('admin/team/form', ['pageTitle' => 'Edit Team Member', 'item' => $item]);
    }

    public function store(Request $request): void
    {
        $this->requireCsrf($request);
        $data = $this->collectFields($request);

        try {
            $image = $this->resolveImage($_FILES['image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/team/create');
        }
        if ($image === null) {
            $this->flash('error', 'A photo is required.');
            redirect('/admin/team/create');
        }
        $data['image_path'] = $image;

        TeamMember::insert($data);
        $this->flash('success', 'Team member added.');
        redirect('/admin/team');
    }

    public function update(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        $item = TeamMember::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Team member not found.');
            redirect('/admin/team');
        }

        $data = $this->collectFields($request);

        try {
            $image = $this->resolveImage($_FILES['image'] ?? [], $request->trimmedInput('alt_text'), $request->input('existing_media_id'));
        } catch (\RuntimeException $e) {
            $this->flash('error', $e->getMessage());
            redirect('/admin/team/' . $id . '/edit');
        }
        if ($image !== null) {
            $data['image_path'] = $image;
        }

        TeamMember::update((int) $id, $data);
        $this->flash('success', 'Team member updated.');
        redirect('/admin/team');
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        TeamMember::delete((int) $id);
        $this->flash('success', 'Team member removed.');
        redirect('/admin/team');
    }

    /** @return array<string, mixed> */
    private function collectFields(Request $request): array
    {
        return [
            'name' => $request->trimmedInput('name'),
            'designation' => $request->trimmedInput('designation'),
            'bio' => $request->trimmedInput('bio'),
            'linkedin_url' => $request->trimmedInput('linkedin_url', '#'),
            'sort_order' => (int) $request->input('sort_order', 0),
            'status' => $request->input('status') === 'draft' ? 'draft' : 'published',
        ];
    }
}
