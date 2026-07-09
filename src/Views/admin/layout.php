<?php
/**
 * @var string $content
 * @var array<string, mixed> $currentUser
 * @var array{type: string, message: string}|null $flash
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$isActive = static fn (string $prefix): string => str_contains($path, $prefix) ? ' active' : '';

$userName = (string) ($currentUser['name'] ?? 'Admin');
$initials = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Echos Admin</title>
    <link rel="stylesheet" href="<?= e(asset('css/all.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand">
            <span class="brand-mark">E</span>
            Echos Admin
        </div>
        <nav>
            <a href="<?= e(url('/admin')) ?>" class="<?= $path === url('/admin') ? 'active' : '' ?>"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>

            <div class="group-label">Content</div>
            <a href="<?= e(url('/admin/pages')) ?>" class="<?= $isActive('/admin/pages') ?>"><i class="fa-solid fa-file-lines"></i> Pages &amp; Sections</a>
            <a href="<?= e(url('/admin/applications')) ?>" class="<?= $isActive('/admin/applications') !== '' && !str_contains($path, 'application-categories') ? 'active' : '' ?>"><i class="fa-solid fa-cubes"></i> Applications</a>
            <a href="<?= e(url('/admin/application-categories')) ?>" class="<?= $isActive('/admin/application-categories') ?>"><i class="fa-solid fa-tags"></i> App Categories</a>
            <a href="<?= e(url('/admin/industries')) ?>" class="<?= $isActive('/admin/industries') ?>"><i class="fa-solid fa-building"></i> Industries</a>
            <a href="<?= e(url('/admin/team')) ?>" class="<?= $isActive('/admin/team') ?>"><i class="fa-solid fa-users"></i> Team Members</a>
            <a href="<?= e(url('/admin/office-locations')) ?>" class="<?= $isActive('/admin/office-locations') ?>"><i class="fa-solid fa-location-dot"></i> Office Locations</a>
            <a href="<?= e(url('/admin/media')) ?>" class="<?= $isActive('/admin/media') ?>"><i class="fa-solid fa-images"></i> Media Library</a>

            <div class="group-label">Blog</div>
            <a href="<?= e(url('/admin/blog')) ?>" class="<?= $isActive('/admin/blog') !== '' && !str_contains($path, 'blog-categories') ? 'active' : '' ?>"><i class="fa-solid fa-newspaper"></i> Posts</a>
            <a href="<?= e(url('/admin/blog-categories')) ?>" class="<?= $isActive('/admin/blog-categories') ?>"><i class="fa-solid fa-tags"></i> Categories</a>

            <div class="group-label">Site</div>
            <a href="<?= e(url('/admin/contact-submissions')) ?>" class="<?= $isActive('/admin/contact-submissions') ?>"><i class="fa-solid fa-envelope"></i> Contact Submissions</a>
            <a href="<?= e(url('/admin/menu')) ?>" class="<?= $isActive('/admin/menu') ?>"><i class="fa-solid fa-bars"></i> Menu</a>
            <a href="<?= e(url('/admin/settings')) ?>" class="<?= $isActive('/admin/settings') ?>"><i class="fa-solid fa-gear"></i> Settings</a>
            <a href="<?= e(url('/admin/seo')) ?>" class="<?= $isActive('/admin/seo') ?>"><i class="fa-solid fa-chart-line"></i> SEO Tools</a>

            <div class="group-label">Account</div>
            <a href="<?= e(url('/admin/password')) ?>" class="<?= $isActive('/admin/password') ?>"><i class="fa-solid fa-lock"></i> Change Password</a>
            <form action="<?= e(url('/admin/logout')) ?>" method="post" class="sidebar-logout-form">
                <?= csrf_field() ?>
                <button type="submit" class="sidebar-logout-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
            </form>
        </nav>
    </aside>
    <main class="admin-main">
        <div class="admin-topbar">
            <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
            <div class="user-info">
                <span class="avatar"><?= e($initials) ?></span>
                <?= e($userName) ?>
            </div>
        </div>

        <?php if ($flash !== null): ?>
        <div class="admin-flash admin-flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?= $content ?>
    </main>
</div>
</body>
</html>
