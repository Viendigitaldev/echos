<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\ContactSubmission;

final class ContactSubmissionController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/contact-submissions/index', [
            'pageTitle' => 'Contact Submissions',
            'items' => ContactSubmission::recent(),
        ]);
    }

    public function show(Request $request, string $id): void
    {
        $item = ContactSubmission::find((int) $id);
        if ($item === null) {
            $this->flash('error', 'Submission not found.');
            redirect('/admin/contact-submissions');
        }

        if (!$item['is_read']) {
            ContactSubmission::update((int) $id, ['is_read' => 1]);
            $item['is_read'] = 1;
        }

        $this->render('admin/contact-submissions/show', [
            'pageTitle' => 'Submission from ' . $item['name'],
            'item' => $item,
        ]);
    }

    public function delete(Request $request, string $id): void
    {
        $this->requireCsrf($request);
        ContactSubmission::delete((int) $id);
        $this->flash('success', 'Submission deleted.');
        redirect('/admin/contact-submissions');
    }
}
