<?php
/**
 * Rendered directly by Router::dispatch() (no controller), so this view must
 * be fully self-contained — same reasoning as partials/footer.php.
 */
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

                    <!-- Breadcrumb Section Start -->
                    <div class="breadcrumb-wrapper bg-cover">

                        <div class="container">
                            <div class="page-heading">
                              <div class="breadcrumb-sub-title">
                                     <h1 class="title wa_title_spilt_1">
                                         404
                                    </h1>
                                     <h2 class="wa_title_spilt_1">The page you're looking for doesn't exist.
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>

                    <section class="section-padding">
                        <div class="container text-center">
                            <img src="<?= e(asset('img/inner-page/404.png')) ?>" alt="Page not found" class="error-page-image">
                            <p class="hero-para">It might have been moved, renamed, or never existed. Let's get you back on track.</p>
                            <div class="hero-button wow fadeInUp mt-5">
                                <a href="<?= e(url('/')) ?>" class="start-building-btn">
                                    <span>Back to Home</span>
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
