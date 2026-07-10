<?php
/**
 * @var array<string, mixed> $breadcrumb
 * @var array<int, array<string, mixed>> $posts
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
                                       <?= e($breadcrumb['heading']) ?>
                                    </h1>
                                     <h2 class="wa_title_spilt_1"><?= e($breadcrumb['subheading']) ?>
                                    </h2>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Blog Filter Bar Start: search, multi-select category, sort — all
                         client-side, no page reload (same UX as the Applications tab-filter). -->
                    <div class="blog-filter-bar">
                        <div class="blog-search-form">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="blog-search" placeholder="Search articles...">
                        </div>

                        <div class="blog-category-tabs d-none d-lg-flex">
                            <button type="button" class="blog-tab active" data-filter="all">All</button>
                            <?php foreach ($categories as $cat): ?>
                            <button type="button" class="blog-tab" data-filter="<?= e($cat['slug']) ?>"><?= e(strtoupper($cat['name'])) ?></button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Mobile: category tabs above are replaced by a single icon that
                             opens a bottom sheet (matches distyl.ai/blog's mobile pattern),
                             since a wrapped row of pill buttons doesn't fit well on phones. -->
                        <button type="button" class="blog-category-trigger d-lg-none" id="blog-category-trigger" aria-label="Filter by category" aria-haspopup="true">
                            <i class="fa-solid fa-sliders"></i>
                        </button>

                        <div class="blog-sort-form">
                            <i class="fa-solid fa-arrow-down-short-wide"></i>
                            <select id="blog-sort" class="blog-sort-select">
                                <option value="latest">Latest</option>
                                <option value="oldest">Oldest</option>
                                <option value="az">A-Z</option>
                            </select>
                        </div>
                    </div>
                    <!-- Blog Filter Bar End -->

                    <!-- Mobile category bottom sheet -->
                    <div class="blog-category-sheet-overlay" id="blog-category-sheet-overlay"></div>
                    <div class="blog-category-sheet" id="blog-category-sheet" role="dialog" aria-modal="true" aria-label="Filter by category">
                        <div class="blog-category-sheet-header">
                            <span>Category</span>
                            <button type="button" class="blog-category-sheet-close" id="blog-category-sheet-close" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <ul class="blog-category-sheet-list">
                            <li>
                                <button type="button" class="blog-category-sheet-item active" data-filter="all">
                                    <i class="fa-solid fa-check"></i> All
                                </button>
                            </li>
                            <?php foreach ($categories as $cat): ?>
                            <li>
                                <button type="button" class="blog-category-sheet-item" data-filter="<?= e($cat['slug']) ?>">
                                    <i class="fa-solid fa-check"></i> <?= e($cat['name']) ?>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

 <!-- News Section Start -->
                    <section class="news-section-5 fix section-padding">
                        <div class="container">
                            <div class="row g-4" id="blog-grid">
                                <?php foreach ($posts as $post): ?>
                                <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp blog-post-card" data-wow-delay=".3s" data-category="<?= e($post['category_slug'] ?? '') ?>" data-title="<?= e(strtolower($post['title'])) ?>" data-date="<?= e($post['published_at']) ?>">
                                    <div class="news-box-items-2 mt-0">
                                        <div class="thumb">
                                            <img src="<?= e(url($post['featured_image'])) ?>" alt="<?= e(Media::altTextFor($post['featured_image'], $post['title'])) ?>">
                                            <img src="<?= e(url($post['featured_image'])) ?>" alt="<?= e(Media::altTextFor($post['featured_image'], $post['title'])) ?>">
                                        </div>
                                        <div class="content">
                                            <ul>
                                                <li>
                                                    <a href="#" class="blog-card-category" data-filter="<?= e($post['category_slug'] ?? 'all') ?>"><?= e($post['category_name'] ?? 'General') ?></a>
                                                </li>
                                                <li class="blog-card-date">
                                                    <p><i class="fa-solid fa-calendar-days"></i> <?= e(date('d M, Y', strtotime((string) $post['published_at']))) ?></p>
                                                </li>
                                            </ul>
                                            <h3 class="title"><a href="<?= e(url('/perspectives/' . $post['slug'])) ?>"><?= e($post['title']) ?></a></h3>
                                           <a href="<?= e(url('/perspectives/' . $post['slug'])) ?>" class="start-building-btn">
    <span>View More</span>

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
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-12 blog-empty-state" id="blog-empty-state" style="display:none">
                                <p>No articles match your search — try a different keyword or category.</p>
                            </div>
                        </div>
                    </section>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
