<?php

use App\Models\MenuItem;
use App\Models\Setting;
use App\Services\AssetManager;
use App\Services\JsonLd;
use App\Support\Url;

/**
 * @var string $content
 * @var string $title
 * @var string|null $metaDescription
 * @var string|null $ogImage
 * @var bool|null $noindex
 * @var array<int, array<string, mixed>>|null $jsonLd additional per-page schema blocks (breadcrumb, article, ...)
 */

$headerMenu = MenuItem::headerTree();

$gaMeasurementId = Setting::get('ga_measurement_id');
if ($gaMeasurementId !== '') {
    AssetManager::enableAnalytics($gaMeasurementId);
}

$googleSiteVerification = Setting::get('google_site_verification');
?>
 <!DOCTYPE html>
<html lang="en">
<head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($title) ?></title>
        <?php if (!empty($noindex)): ?>
        <meta name="robots" content="noindex, nofollow">
        <?php endif; ?>
        <?php if (!empty($metaDescription)): ?>
        <meta name="description" content="<?= e($metaDescription) ?>">
        <meta property="og:description" content="<?= e($metaDescription) ?>">
        <?php endif; ?>
        <meta property="og:title" content="<?= e($title) ?>">
        <?php if (!empty($ogImage)): ?>
        <meta property="og:image" content="<?= e(Url::absolute($ogImage)) ?>">
        <?php endif; ?>
        <?php if ($googleSiteVerification !== ''): ?>
        <meta name="google-site-verification" content="<?= e($googleSiteVerification) ?>">
        <?php endif; ?>
        <link rel="canonical" href="<?= e(Url::currentAbsolute()) ?>">
        <!--<< Favcion >>-->
        <link rel="shortcut icon" href="<?= e(asset('img/logo/favicon.png')) ?>">
        <?= AssetManager::renderStyles() ?>
        <?= JsonLd::render(array_merge([JsonLd::organization()], $jsonLd ?? [])) ?>
    </head>
    <body>

        <div class="page-wrapper">
            <!-- ================= PRELOADER ================= -->

            <div id="page">

                <?php require __DIR__ . '/../partials/header.php'; ?>

                <?= $content ?>

            </div>
        </div>

        <?= AssetManager::renderScripts() ?>
    </body>

</html>
