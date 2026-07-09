<?php

use App\Models\Setting;

/** @var array<int, array<string, mixed>> $headerMenu */

$customLogo = Setting::get('site_logo');
$logoUrl = $customLogo !== '' ? url($customLogo) : asset('img/logo/white-logo.svg');
?>
            <header class="header-section header-1" id="sticky-header">
                <div class="header-main">
                    <nav class="navbar p-0 navbar-expand-xl d-none d-xl-flex">
                        <a class="navbar-brand" href="<?= e(url('/')) ?>">
                                 <img src="<?= e($logoUrl) ?>" alt="img">
                            </a>

                            <button class="navbar-toggler" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mb-lg-0">
                                    <?php foreach ($headerMenu as $item): ?>
                                    <li class="nav-item">
                                        <?php if ($item['children'] !== []): ?>
                                        <a class="nav-link" href="#">
                                            <?= e($item['label']) ?> <i class="fas fa-chevron-down"></i>
                                        </a>
                                        <ul class="sub-menu list-unstyled">
                                            <?php foreach ($item['children'] as $child): ?>
                                            <li><a href="<?= e(url($child['url'])) ?>"><?php if (!empty($child['icon_class'])): ?><i class="<?= e($child['icon_class']) ?>"></i><?php endif; ?> <?= e($child['label']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php else: ?>
                                        <a class="nav-link" href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>
                    </nav>
                </div>

                <!-- ===================== MOBILE MENU ===================== -->
                <div class="mobile-menu-area d-block d-xl-none">

                    <div class="container">
                        <div class="mobile-topbar">
                            <div class="d-flex justify-content-between align-items-center">

                                <div class="logo">
                                    <a href="<?= e(url('/')) ?>">
                                        <img src="<?= e($logoUrl) ?>" alt="logo">
                                    </a>
                                </div>

                                <div class="menu-search d-flex align-items-center gap-4">

                                <div class="bars">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                               </div>

                            </div>

                        </div>
                    </div>

                    <div class="mobile-menu-overlay"></div>

                    <div class="mobile-menu-main">

                        <div class="logo">
                            <a href="<?= e(url('/')) ?>">
                                <img src="<?= e($logoUrl) ?>" alt="logo">
                            </a>
                        </div>

                        <div class="close-mobile-menu">
                            <i class="fas fa-times"></i>
                        </div>

                        <div class="menu-body">
                            <div class="menu-list">
                                <ul class="list-unstyled">
                                    <?php foreach ($headerMenu as $item): ?>
                                    <li class="nav-item">
                                        <?php if ($item['children'] !== []): ?>
                                        <a class="nav-link" href="#">
                                            <?= e($item['label']) ?> <i class="fas fa-chevron-down"></i>
                                        </a>
                                        <ul class="sub-menu list-unstyled">
                                            <?php foreach ($item['children'] as $child): ?>
                                            <li><a href="<?= e(url($child['url'])) ?>"><?php if (!empty($child['icon_class'])): ?><i class="<?= e($child['icon_class']) ?>"></i><?php endif; ?> <?= e($child['label']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php else: ?>
                                        <a class="nav-link" href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- ===================== MOBILE MENU END ===================== -->

            </header>
