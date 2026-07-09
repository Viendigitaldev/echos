<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\Admin\ApplicationAdminController;
use App\Controllers\Admin\ApplicationCategoryController;
use App\Controllers\Admin\AuthController as AdminAuthController;
use App\Controllers\Admin\BlogAdminController;
use App\Controllers\Admin\BlogCategoryController;
use App\Controllers\Admin\ContactSubmissionController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\IndustryController;
use App\Controllers\Admin\MediaController;
use App\Controllers\Admin\MenuController;
use App\Controllers\Admin\OfficeLocationController;
use App\Controllers\Admin\PageController;
use App\Controllers\Admin\PasswordController;
use App\Controllers\Admin\SeoController;
use App\Controllers\Admin\SettingsController;
use App\Controllers\Admin\TeamMemberController;
use App\Controllers\ApplicationController;
use App\Controllers\BlogController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\LegalController;

/** @var \App\Http\Router $router */

// ------------------------------------------------------------------- public
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [AboutController::class, 'index']);
$router->get('/applications', [ApplicationController::class, 'index']);
$router->get('/perspectives', [BlogController::class, 'index']);
$router->get('/perspectives/{slug}', [BlogController::class, 'show']);
$router->get('/contact', [ContactController::class, 'index']);
$router->post('/contact/submit', [ContactController::class, 'submit']);
$router->get('/thank-you', [ContactController::class, 'thankYou']);
$router->get('/terms-of-service', [LegalController::class, 'terms']);
$router->get('/privacy-policy', [LegalController::class, 'privacyPolicy']);

// ---------------------------------------------------------------------- admin auth
$router->get('/admin/login', [AdminAuthController::class, 'showLogin']);
$router->post('/admin/login', [AdminAuthController::class, 'login']);
$router->post('/admin/logout', [AdminAuthController::class, 'logout']);
$router->get('/admin/password', [PasswordController::class, 'show']);
$router->post('/admin/password', [PasswordController::class, 'update']);

// -------------------------------------------------------------------- dashboard
$router->get('/admin', [DashboardController::class, 'index']);

// ------------------------------------------------------------------ pages/blocks
$router->get('/admin/pages', [PageController::class, 'index']);
$router->get('/admin/pages/{slug}/edit', [PageController::class, 'edit']);
$router->post('/admin/pages/{slug}', [PageController::class, 'update']);

// ---------------------------------------------------------------- applications
$router->get('/admin/applications', [ApplicationAdminController::class, 'index']);
$router->get('/admin/applications/create', [ApplicationAdminController::class, 'create']);
$router->post('/admin/applications', [ApplicationAdminController::class, 'store']);
$router->get('/admin/applications/{id}/edit', [ApplicationAdminController::class, 'edit']);
$router->post('/admin/applications/{id}', [ApplicationAdminController::class, 'update']);
$router->post('/admin/applications/{id}/delete', [ApplicationAdminController::class, 'delete']);

$router->get('/admin/application-categories', [ApplicationCategoryController::class, 'index']);
$router->get('/admin/application-categories/create', [ApplicationCategoryController::class, 'create']);
$router->post('/admin/application-categories', [ApplicationCategoryController::class, 'store']);
$router->get('/admin/application-categories/{id}/edit', [ApplicationCategoryController::class, 'edit']);
$router->post('/admin/application-categories/{id}', [ApplicationCategoryController::class, 'update']);
$router->post('/admin/application-categories/{id}/delete', [ApplicationCategoryController::class, 'delete']);

// ------------------------------------------------------------------- industries
$router->get('/admin/industries', [IndustryController::class, 'index']);
$router->get('/admin/industries/create', [IndustryController::class, 'create']);
$router->post('/admin/industries', [IndustryController::class, 'store']);
$router->get('/admin/industries/{id}/edit', [IndustryController::class, 'edit']);
$router->post('/admin/industries/{id}', [IndustryController::class, 'update']);
$router->post('/admin/industries/{id}/delete', [IndustryController::class, 'delete']);

// ------------------------------------------------------------------ team members
$router->get('/admin/team', [TeamMemberController::class, 'index']);
$router->get('/admin/team/create', [TeamMemberController::class, 'create']);
$router->post('/admin/team', [TeamMemberController::class, 'store']);
$router->get('/admin/team/{id}/edit', [TeamMemberController::class, 'edit']);
$router->post('/admin/team/{id}', [TeamMemberController::class, 'update']);
$router->post('/admin/team/{id}/delete', [TeamMemberController::class, 'delete']);

// --------------------------------------------------------------- office locations
$router->get('/admin/office-locations', [OfficeLocationController::class, 'index']);
$router->get('/admin/office-locations/create', [OfficeLocationController::class, 'create']);
$router->post('/admin/office-locations', [OfficeLocationController::class, 'store']);
$router->get('/admin/office-locations/{id}/edit', [OfficeLocationController::class, 'edit']);
$router->post('/admin/office-locations/{id}', [OfficeLocationController::class, 'update']);
$router->post('/admin/office-locations/{id}/delete', [OfficeLocationController::class, 'delete']);

// ------------------------------------------------------------------------- blog
$router->get('/admin/blog', [BlogAdminController::class, 'index']);
$router->get('/admin/blog/create', [BlogAdminController::class, 'create']);
$router->post('/admin/blog', [BlogAdminController::class, 'store']);
$router->get('/admin/blog/{id}/edit', [BlogAdminController::class, 'edit']);
$router->post('/admin/blog/{id}', [BlogAdminController::class, 'update']);
$router->post('/admin/blog/{id}/delete', [BlogAdminController::class, 'delete']);

$router->get('/admin/blog-categories', [BlogCategoryController::class, 'index']);
$router->get('/admin/blog-categories/create', [BlogCategoryController::class, 'create']);
$router->post('/admin/blog-categories', [BlogCategoryController::class, 'store']);
$router->get('/admin/blog-categories/{id}/edit', [BlogCategoryController::class, 'edit']);
$router->post('/admin/blog-categories/{id}', [BlogCategoryController::class, 'update']);
$router->post('/admin/blog-categories/{id}/delete', [BlogCategoryController::class, 'delete']);

// ------------------------------------------------------------- contact submissions
$router->get('/admin/contact-submissions', [ContactSubmissionController::class, 'index']);
$router->get('/admin/contact-submissions/{id}', [ContactSubmissionController::class, 'show']);
$router->post('/admin/contact-submissions/{id}/delete', [ContactSubmissionController::class, 'delete']);

// ------------------------------------------------------------------------ media
$router->get('/admin/media', [MediaController::class, 'index']);
$router->post('/admin/media/{id}', [MediaController::class, 'update']);
$router->post('/admin/media/{id}/delete', [MediaController::class, 'delete']);

// ------------------------------------------------------------------------- menu
$router->get('/admin/menu', [MenuController::class, 'index']);
$router->get('/admin/menu/create', [MenuController::class, 'create']);
$router->post('/admin/menu', [MenuController::class, 'store']);
$router->get('/admin/menu/{id}/edit', [MenuController::class, 'edit']);
$router->post('/admin/menu/{id}', [MenuController::class, 'update']);
$router->post('/admin/menu/{id}/delete', [MenuController::class, 'delete']);

// --------------------------------------------------------------------- settings
$router->get('/admin/settings', [SettingsController::class, 'index']);
$router->post('/admin/settings', [SettingsController::class, 'update']);

// -------------------------------------------------------------------------- seo
$router->get('/admin/seo', [SeoController::class, 'index']);
$router->post('/admin/seo/robots', [SeoController::class, 'saveRobots']);
$router->post('/admin/seo/sitemap/regenerate', [SeoController::class, 'regenerateSitemap']);
