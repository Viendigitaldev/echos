<?php
/** @var array<string, int> $counts */
?>
<div class="stat-grid">
    <a class="stat-card" href="<?= e(url('/admin/applications')) ?>">
        <span class="stat-icon"><i class="fa-solid fa-cubes"></i></span>
        <div>
            <div class="stat-value"><?= (int) $counts['applications'] ?></div>
            <div class="stat-label">Applications</div>
        </div>
    </a>
    <a class="stat-card" href="<?= e(url('/admin/blog')) ?>">
        <span class="stat-icon"><i class="fa-solid fa-newspaper"></i></span>
        <div>
            <div class="stat-value"><?= (int) $counts['blogPosts'] ?></div>
            <div class="stat-label">Blog posts</div>
        </div>
    </a>
    <a class="stat-card" href="<?= e(url('/admin/contact-submissions')) ?>">
        <span class="stat-icon<?= $counts['unreadSubmissions'] > 0 ? ' is-attention' : '' ?>"><i class="fa-solid fa-envelope"></i></span>
        <div>
            <div class="stat-value"><?= (int) $counts['unreadSubmissions'] ?></div>
            <div class="stat-label">Unread submissions</div>
        </div>
    </a>
    <a class="stat-card" href="<?= e(url('/admin/contact-submissions')) ?>">
        <span class="stat-icon"><i class="fa-solid fa-inbox"></i></span>
        <div>
            <div class="stat-value"><?= (int) $counts['totalSubmissions'] ?></div>
            <div class="stat-label">Total submissions</div>
        </div>
    </a>
</div>

<div class="admin-card">
    <h3 class="mt-0">Quick links</h3>
    <div class="quick-links">
        <a href="<?= e(url('/admin/pages')) ?>"><i class="fa-solid fa-file-lines"></i> Edit page content</a>
        <a href="<?= e(url('/admin/applications')) ?>"><i class="fa-solid fa-cubes"></i> Manage applications</a>
        <a href="<?= e(url('/admin/blog')) ?>"><i class="fa-solid fa-newspaper"></i> Manage blog posts</a>
        <a href="<?= e(url('/admin/seo')) ?>"><i class="fa-solid fa-chart-line"></i> SEO tools</a>
    </div>
</div>
