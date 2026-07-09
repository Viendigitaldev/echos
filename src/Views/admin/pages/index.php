<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Page</th><th>SEO Title</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?= e(ucfirst($item['slug'])) ?></td>
            <td><?= e($item['seo_title'] ?: '—') ?></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/pages/' . $item['slug'] . '/edit')) ?>">Edit content</a>
                <a class="btn btn-sm" href="<?= e(url('/' . ($item['slug'] === 'home' ? '' : $item['slug']))) ?>" target="_blank">View live</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
