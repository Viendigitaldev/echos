<?php
/**
 * @var string $robotsContent
 * @var string $sitemapUrl
 * @var string|null $sitemapGeneratedAt
 */
?>
<div class="card-row">
    <div class="admin-card">
        <h3 class="mt-0">robots.txt</h3>
        <form action="<?= e(url('/admin/seo/robots')) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <textarea name="robots_content" class="tall"><?= e($robotsContent) ?></textarea>
                <div class="hint">The "Sitemap:" line is appended automatically and doesn't need to be included here.</div>
            </div>
            <button type="submit" class="btn btn-primary">Save robots.txt</button>
        </form>
    </div>

    <div class="admin-card">
        <h3 class="mt-0">Sitemap</h3>
        <p class="muted">
            Live at <a href="<?= e($sitemapUrl) ?>" target="_blank"><?= e($sitemapUrl) ?></a>.
            <?= $sitemapGeneratedAt !== null ? 'Last generated ' . e($sitemapGeneratedAt) . '.' : 'Not generated yet.' ?>
        </p>
        <p class="muted">Regenerates automatically whenever an application or blog post is saved — use this if you've edited content directly in the database or want to force a refresh.</p>
        <form action="<?= e(url('/admin/seo/sitemap/regenerate')) ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary">Regenerate Sitemap Now</button>
        </form>
    </div>
</div>
