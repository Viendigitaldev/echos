<?php
/** @var array<string, mixed> $block */
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

                    <div class="breadcrumb-wrapper bg-cover">

                        <div class="container">
                            <div class="page-heading">
                              <div class="breadcrumb-sub-title">
                                     <h1 class="title wa_title_spilt_1">
                                         <?= e($block['heading']) ?>
                                    </h1>
                                     <h2 class="wa_title_spilt_1"><?= e($block['body']) ?>
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>

                    <section class="section-padding">
                        <div class="container text-center">
                            <div class="hero-button wow fadeInUp">
                                <a href="<?= e(url($block['cta_url'])) ?>" class="start-building-btn">
                                    <span><?= e($block['cta_label']) ?></span>
                                    <span class="icon-wrap">
                                        <span class="dot"></span>
                                        <svg class="arrow" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 12H19" stroke="currentColor" stroke-width="2"/>
                                            <path d="M13 6L19 12L13 18" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </section>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
