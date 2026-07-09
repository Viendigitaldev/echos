<?php
/**
 * @var array<int, array<string, mixed>> $items
 * @var array<int, bool> $referenced
 */
?>
<div class="admin-card">
    <?php if ($items === []): ?>
    <p>No images uploaded yet. Images are added to the library automatically the first time they're uploaded from any content form.</p>
    <?php else: ?>
    <div class="media-library-grid">
        <?php foreach ($items as $item): ?>
        <div class="media-library-card">
            <img src="<?= e(url($item['path'])) ?>" class="media-library-thumb" alt="<?= e($item['alt_text']) ?>">
            <div class="media-library-meta">
                <?php if (!empty($item['width']) && !empty($item['height'])): ?>
                <span><?= (int) $item['width'] ?>&times;<?= (int) $item['height'] ?></span>
                <?php endif; ?>
                <?php if (!empty($item['size_bytes'])): ?>
                <span><?= number_format((int) $item['size_bytes'] / 1024, 0) ?> KB</span>
                <?php endif; ?>
            </div>

            <form action="<?= e(url('/admin/media/' . $item['id'])) ?>" method="post" class="media-library-alt-form">
                <?= csrf_field() ?>
                <input type="text" name="alt_text" value="<?= e($item['alt_text']) ?>" placeholder="Alt text">
                <button type="submit" class="btn">Save</button>
            </form>

            <?php if ($referenced[$item['id']]): ?>
            <span class="media-library-badge">In use</span>
            <?php else: ?>
            <form action="<?= e(url('/admin/media/' . $item['id'] . '/delete')) ?>" method="post" onsubmit="return confirm('Delete this image?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
