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
    <title>Admin Login — Echos</title>
    <link rel="shortcut icon" href="<?= e(asset('img/logo/favicon.png')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/all.min.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="login-wrap">
    <div class="login-card">
        <img src="<?= e($logoUrl) ?>" class="login-logo-img" alt="<?= e($logoAlt) ?>">
        <p class="login-subtitle">Sign in to manage your site</p>

        <?php if ($flash !== null): ?>
        <div class="admin-flash admin-flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <form action="<?= e(url('/admin/login')) ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>

        <p class="login-subtitle"><a href="<?= e(url('/admin/forgot-password')) ?>">Forgot password?</a></p>
    </div>
</body>
</html>
