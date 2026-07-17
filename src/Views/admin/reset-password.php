<?php
/**
 * @var string $token
 * @var array{type: string, message: string}|null $flash
 */

use App\Models\Media;
use App\Models\Setting;

$customLogo = Setting::get('site_logo');
$logoUrl = $customLogo !== '' ? url($customLogo) : asset('img/logo/white-logo.svg');
$logoAlt = $customLogo !== '' ? Media::altTextFor($customLogo, Setting::get('site_name', 'Echos')) : Setting::get('site_name', 'Echos');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password — Echos Admin</title>
    <link rel="shortcut icon" href="<?= e(asset('img/logo/favicon.png')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/all.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="login-wrap">
    <div class="login-card">
        <img src="<?= e($logoUrl) ?>" class="login-logo-img" alt="<?= e($logoAlt) ?>">
        <p class="login-subtitle">Set a new password</p>

        <?php if ($flash !== null): ?>
        <div class="admin-flash admin-flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('/admin/reset-password')) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <div class="form-group">
                <label for="password">New password</label>
                <input type="password" id="password" name="password" minlength="10" required autofocus>
                <div class="hint">At least 10 characters.</div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm new password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="10" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Password</button>
        </form>
    </div>
</body>
</html>
