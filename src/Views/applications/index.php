<?php
/**
 * @var array<string, mixed> $breadcrumb
 * @var array<int, array<string, mixed>> $applications
 * @var array<int, array<string, mixed>> $categories
 */

use App\Models\Media;
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

                    <!-- Breadcrumb Section Start -->
                    <div class="breadcrumb-wrapper bg-cover">

                        <div class="container">
                            <div class="page-heading">
                              <div class="breadcrumb-sub-title">
                                     <h1 class="title wa_title_spilt_1">
                                         <?= nl2br(e($breadcrumb['heading'])) ?>
                                    </h1>
                                     <h2 class="wa_title_spilt_1"><?= nl2br(e($breadcrumb['subheading'])) ?>
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>


<section class="production-section">

    <div class="container">



        <div class="production-tabs">

            <button class="tab-btn active" data-filter="all">
                All
            </button>

            <?php foreach ($categories as $cat): ?>
            <button class="tab-btn" data-filter="<?= e($cat['slug']) ?>">
                <?= e($cat['name']) ?>
            </button>
            <?php endforeach; ?>

        </div>

        <!-- Grid -->

        <div class="production-grid">

            <?php foreach ($applications as $app): ?>
            <!-- Card -->

            <div class="production-card app-popup-trigger"
                data-category="<?= e($app['category_slug'] ?? '') ?>"
                data-title="<?= e($app['title']) ?>"
                data-image="<?= e(url($app['image_path'])) ?>"
                data-description="<?= e($app['full_description']) ?>">

                <img src="<?= e(url($app['image_path'])) ?>" alt="<?= e(Media::altTextFor($app['image_path'], $app['title'])) ?>">

              <div class="production-card-content">

                    <span class="card-category">
                        <?= e($app['category_name'] ?? 'Uncategorized') ?>
                    </span>

                    <h3>
                        <?= e($app['title']) ?>
                    </h3>

                    <p>
                        <?= e($app['short_description']) ?>
                    </p>

                </div>

            </div>
            <?php endforeach; ?>

        </div>

    </div>

</section>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
