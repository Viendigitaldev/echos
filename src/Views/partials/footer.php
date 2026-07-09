<?php

use App\Models\MenuItem;
use App\Models\Setting;

// Self-contained on purpose: this partial is `require`d from the tail end of
// each page view (inside #smooth-content, not from the shared layout) so it
// stays nested where GSAP ScrollSmoother expects it — see main.js, which
// transforms everything inside #smooth-content to drive the smooth-scroll
// effect. It can't rely on variables computed in a parent scope.
$footerLeft = MenuItem::footerLeft();
$footerRight = MenuItem::footerRight();
$footerCopyright = Setting::get('footer_copyright', '&copy; Echos. All rights reserved.');
$socialX = Setting::get('social_x_url', '#');
$socialLinkedin = Setting::get('social_linkedin_url', '#');
?>
<footer class="footer">

    <div class="footer-top">

        <div class="line"></div>

        <div class="brand">
            <div class="certifications">
                <div class="cert-circle">SOC2</div>

            </div>
        </div>

        <div class="line"></div>

    </div>

    <div class="footer-content">

        <!-- Left Links -->
        <div class="footer-links">
            <?php foreach ($footerLeft as $link): ?>
            <a href="<?= e($link['url'] === '#' ? '#' : url($link['url'])) ?>"><?= e($link['label']) ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Center Social -->
        <div class="footer-center">

            <div class="socials">
                <a href="<?= e($socialX) ?>">𝕏</a>
                <a href="<?= e($socialLinkedin) ?>">in</a>

            </div>

        </div>

        <!-- Right Links -->
        <div class="footer-links right">
            <?php foreach ($footerRight as $link): ?>
            <a href="<?= e($link['url'] === '#' ? '#' : url($link['url'])) ?>"><?= e($link['label']) ?></a>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- BIG BRAND -->
    <div class="footer-logo">
        echos
    </div>

    <div class="footer-bottom">
       <?= $footerCopyright ?>
    </div>

</footer>

<!-- APP MODAL -->
<div class="app-modal">

    <div class="app-modal-overlay"></div>

    <div class="app-modal-content">

        <button class="app-modal-close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <img id="modalImage" src="" alt="">

        <div class="app-modal-body">

            <h2 id="modalTitle"></h2>

            <p id="modalDescription"></p>

        </div>

    </div>

</div>
