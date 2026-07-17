<?php
/** @var array{type: string, message: string}|null $flash */

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
    <title>Forgot Password — Echos Admin</title>
    <link rel="shortcut icon" href="<?= e(asset('img/logo/favicon.png')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/all.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="login-wrap">
    <div class="login-card">
        <img src="<?= e($logoUrl) ?>" class="login-logo-img" alt="<?= e($logoAlt) ?>">
        <p class="login-subtitle">Reset your admin password</p>

        <?php if ($flash !== null): ?>
        <div class="admin-flash admin-flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('/admin/forgot-password')) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
        </form>

        <p class="login-subtitle"><a href="<?= e(url('/admin/login')) ?>">Back to login</a></p>
    </div>
</body>
</html>
