<?php

/**
 * Reusable image field: file upload (with required alt text) + a "choose
 * from library" picker of every previously uploaded image. Included (not
 * requested) from each content form, so it shares that view's variable
 * scope — set the variables below before requiring this file.
 *
 * @var string $fileFieldName Full name="" for the file input, e.g. "image" or "blocks[hero][image]"
 * @var string $altFieldName Full name="" for the alt text input
 * @var string $existingFieldName Full name="" for the existing-media-id radios
 * @var string|null $currentPath Current image path, if any
 * @var bool $required Whether an image (new or picked) is mandatory
 * @var string|null $label Field label, defaults to "Image"
 */

use App\Models\Media;

$label ??= 'Image';
$currentAlt = Media::altTextFor($currentPath ?? null);
?>
<div class="form-group">
    <label><?= e($label) ?></label>
    <?php if (!empty($currentPath)): ?>
    <div class="mb-8"><img src="<?= e(media_url($currentPath)) ?>" class="thumb-preview" alt="<?= e($currentAlt) ?>"></div>
    <?php endif; ?>

    <input type="file" name="<?= e($fileFieldName) ?>" accept="image/png,image/jpeg,image/webp">
    <input type="text" name="<?= e($altFieldName) ?>" placeholder="Alt text (required for a new upload)" class="mt-8">
    <div class="hint">
        <?= $required
            ? 'Upload a new image or pick one from the library below — required.'
            : 'Leave empty to keep the current image, or pick a different one below.' ?>
    </div>

    <details class="media-picker">
        <summary>Choose from library</summary>
        <div class="media-picker-grid">
            <?php foreach (Media::all('created_at DESC') as $mediaItem): ?>
            <label class="media-picker-item">
                <input type="radio" name="<?= e($existingFieldName) ?>" value="<?= (int) $mediaItem['id'] ?>">
                <img src="<?= e(url($mediaItem['path'])) ?>" alt="<?= e($mediaItem['alt_text']) ?>">
                <span><?= e($mediaItem['alt_text']) ?></span>
            </label>
            <?php endforeach; ?>
        </div>
    </details>
</div>
