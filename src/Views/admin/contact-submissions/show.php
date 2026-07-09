<?php
/** @var array<string, mixed> $item */
?>
<div class="admin-card max-w-600">
    <p><strong>Name:</strong> <?= e($item['name']) ?></p>
    <p><strong>Email:</strong> <?= e($item['email']) ?></p>
    <p><strong>Phone:</strong> <?= e($item['phone'] ?: '—') ?></p>
    <p><strong>Company:</strong> <?= e($item['company'] ?: '—') ?></p>
    <p><strong>Reason:</strong> <?= e($item['reason'] ?: '—') ?></p>
    <p><strong>Received:</strong> <?= e(date('d M Y H:i', strtotime((string) $item['created_at']))) ?></p>
    <p><strong>IP:</strong> <?= e($item['ip_address'] ?: '—') ?></p>
    <hr class="hr-subtle">
    <p class="pre-wrap"><?= e($item['message']) ?></p>
</div>
<a href="<?= e(url('/admin/contact-submissions')) ?>" class="btn">Back to list</a>
