<?php
/** @var array<string, string> $values */
?>
<div class="admin-card max-w-640">
    <form action="<?= e(url('/admin/settings')) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <h3 class="mt-0">General</h3>
        <div class="form-row">
            <div class="form-group">
                <label for="site_name">Site name</label>
                <input type="text" id="site_name" name="site_name" value="<?= e($values['site_name']) ?>">
            </div>
            <div class="form-group">
                <label for="site_tagline">Site tagline</label>
                <input type="text" id="site_tagline" name="site_tagline" value="<?= e($values['site_tagline']) ?>">
            </div>
        </div>

        <?php
        $fileFieldName = 'site_logo';
        $altFieldName = 'site_logo_alt_text';
        $existingFieldName = 'site_logo_existing_media_id';
        $currentPath = $values['site_logo'] !== '' ? $values['site_logo'] : null;
        $required = false;
        $label = 'Site logo';
        require __DIR__ . '/../partials/media-field.php';
        ?>

        <div class="form-group checkbox-group">
            <label><input type="checkbox" name="blog_enabled" value="1" <?= $values['blog_enabled'] ? 'checked' : '' ?>> Show Perspectives (Blog) on the site</label>
            <div class="hint">Unchecked hides the Perspectives nav links, the homepage teaser, and the /perspectives pages (they 404) — nothing is deleted, so re-enable anytime.</div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="contact_phone">Contact phone</label>
                <input type="text" id="contact_phone" name="contact_phone" value="<?= e($values['contact_phone']) ?>">
            </div>
            <div class="form-group">
                <label for="contact_address">Contact address</label>
                <input type="text" id="contact_address" name="contact_address" value="<?= e($values['contact_address']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="footer_copyright">Footer copyright (HTML allowed)</label>
            <textarea id="footer_copyright" name="footer_copyright"><?= e($values['footer_copyright']) ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="social_x_url">X (Twitter) URL</label>
                <input type="text" id="social_x_url" name="social_x_url" value="<?= e($values['social_x_url']) ?>">
            </div>
            <div class="form-group">
                <label for="social_linkedin_url">LinkedIn URL</label>
                <input type="text" id="social_linkedin_url" name="social_linkedin_url" value="<?= e($values['social_linkedin_url']) ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="contact_notify_email">Contact form notification email</label>
            <input type="email" id="contact_notify_email" name="contact_notify_email" value="<?= e($values['contact_notify_email']) ?>">
            <div class="hint">Leave empty to disable email notifications for new contact submissions.</div>
        </div>

        <h3>SEO &amp; Analytics</h3>
        <?php
        $fileFieldName = 'default_og_image';
        $altFieldName = 'default_og_image_alt_text';
        $existingFieldName = 'default_og_image_existing_media_id';
        $currentPath = $values['default_og_image'] !== '' ? $values['default_og_image'] : null;
        $required = false;
        $label = 'Default OG image';
        require __DIR__ . '/../partials/media-field.php';
        ?>
        <div class="form-row">
            <div class="form-group">
                <label for="google_site_verification">Google Search Console verification tag</label>
                <input type="text" id="google_site_verification" name="google_site_verification" value="<?= e($values['google_site_verification']) ?>">
                <div class="hint">The content value from the meta tag Google gives you — leave empty to omit the tag.</div>
            </div>
            <div class="form-group">
                <label for="ga_measurement_id">GA4 measurement ID</label>
                <input type="text" id="ga_measurement_id" name="ga_measurement_id" value="<?= e($values['ga_measurement_id']) ?>" placeholder="G-XXXXXXXXXX">
                <div class="hint">Leave empty to skip loading Google Analytics entirely.</div>
            </div>
        </div>

        <h3>Email (SMTP)</h3>
        <div class="hint mb-8">Pre-filled with Hostinger's standard outgoing-mail server. Add your mailbox's username and password to start sending contact-form emails.</div>
        <div class="form-row">
            <div class="form-group">
                <label for="mail_host">SMTP host</label>
                <input type="text" id="mail_host" name="mail_host" value="<?= e($values['mail_host']) ?>" placeholder="smtp.hostinger.com">
            </div>
            <div class="form-group">
                <label for="mail_port">SMTP port</label>
                <input type="text" id="mail_port" name="mail_port" value="<?= e($values['mail_port']) ?>" placeholder="587">
            </div>
            <div class="form-group">
                <label for="mail_encryption">Encryption</label>
                <select id="mail_encryption" name="mail_encryption">
                    <option value="tls" <?= $values['mail_encryption'] === 'tls' ? 'selected' : '' ?>>TLS (port 587)</option>
                    <option value="ssl" <?= $values['mail_encryption'] === 'ssl' ? 'selected' : '' ?>>SSL (port 465)</option>
                    <option value="" <?= $values['mail_encryption'] === '' ? 'selected' : '' ?>>None</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="mail_username">Mailbox username</label>
                <input type="text" id="mail_username" name="mail_username" value="<?= e($values['mail_username']) ?>" placeholder="hello@yourdomain.com">
            </div>
            <div class="form-group">
                <label for="mail_password">Mailbox password</label>
                <input type="password" id="mail_password" name="mail_password" value="" placeholder="<?= $values['mail_password_set'] ? '••••••••  (set — leave blank to keep)' : 'Not set' ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="mail_from_address">From address</label>
                <input type="text" id="mail_from_address" name="mail_from_address" value="<?= e($values['mail_from_address']) ?>" placeholder="Defaults to the mailbox username">
            </div>
            <div class="form-group">
                <label for="mail_from_name">From name</label>
                <input type="text" id="mail_from_name" name="mail_from_name" value="<?= e($values['mail_from_name']) ?>">
            </div>
        </div>
        <div class="hint">Leave "SMTP host" or "Mailbox username" empty to disable sending — the contact form will keep working, it just won't email a notification.</div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
