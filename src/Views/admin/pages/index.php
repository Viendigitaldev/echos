<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-actions">
    <a href="<?= e(url('/admin/pages/create')) ?>" class="btn btn-primary">+ New Page</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Page</th><th>Type</th><th>SEO Title</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e(ucfirst($item['slug'])) ?></td>
            <td>
                <?php if (!empty($item['is_custom'])): ?>
                <span class="badge badge-published">Custom</span>
                <?php else: ?>
                <span class="badge badge-draft">Fixed</span>
                <?php endif; ?>
            </td>
            <td><?= e($item['seo_title'] ?: '—') ?></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/pages/' . $item['slug'] . '/edit')) ?>">Edit content</a>
                <a class="btn btn-sm" href="<?= e(url('/' . ($item['slug'] === 'home' ? '' : $item['slug']))) ?>" target="_blank">View live</a>
                <?php if (!empty($item['is_custom'])): ?>
                <form action="<?= e(url('/admin/pages/' . $item['slug'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this page? This cannot be undone.');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
