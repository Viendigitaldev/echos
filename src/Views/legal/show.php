<?php
/** @var array<string, mixed> $block */
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

                    <!-- Breadcrumb Section Start -->
                    <div class="breadcrumb-wrapper bg-cover">

                        <div class="container">
                            <div class="page-heading">
                              <div class="breadcrumb-sub-title">
                                     <h1 class="title wa_title_spilt_1">
                                       <?= nl2br(e($block['heading'])) ?>
                                    </h1>
                                    <?php if (!empty($block['subheading'])): ?>
                                     <h2 class="wa_title_spilt_1"><?= nl2br(e($block['subheading'])) ?>
                                    </h2>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </div>
                    </div>

          <section class="news-standard-section section-padding">
                    <div class="container">
                        <div class="news-details-area">
                            <div class="row g-4">
                                <div class="col-12 col-lg-8 mx-auto">
                                    <div class="blog-post-details">
                                        <div class="single-blog-post">
                                            <div class="post-content">
                                                <?= $block['body'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
