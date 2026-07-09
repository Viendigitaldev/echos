<?php
/** @var array<int, array<string, mixed>> $items */
?>
<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th></th><th>Name</th><th>Email</th><th>Reason</th><th>Received</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php if (!$item['is_read']): ?><span class="badge badge-unread">New</span><?php endif; ?></td>
            <td><a href="<?= e(url('/admin/contact-submissions/' . $item['id'])) ?>"><?= e($item['name']) ?></a></td>
            <td><?= e($item['email']) ?></td>
            <td><?= e($item['reason'] ?: '—') ?></td>
            <td><?= e(date('d M Y H:i', strtotime((string) $item['created_at']))) ?></td>
            <td class="row-actions">
                <a class="btn btn-sm" href="<?= e(url('/admin/contact-submissions/' . $item['id'])) ?>">View</a>
                <form action="<?= e(url('/admin/contact-submissions/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this submission?');">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($items === []): ?>
        <tr><td colspan="6" class="muted">No submissions yet.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
