<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Http\Request;
use App\Models\ApplicationItem;
use App\Models\BlogPost;
use App\Models\ContactSubmission;

final class DashboardController extends AdminController
{
    public function index(Request $request): void
    {
        $this->render('admin/dashboard', [
            'pageTitle' => 'Dashboard',
            'counts' => [
                'applications' => ApplicationItem::count(),
                'blogPosts' => BlogPost::count(),
                'unreadSubmissions' => ContactSubmission::unreadCount(),
                'totalSubmissions' => ContactSubmission::count(),
            ],
        ]);
    }
}
