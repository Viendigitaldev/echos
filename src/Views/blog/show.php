<?php
/**
 * @var array<string, mixed> $post
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
                                     <?= e($post['title']) ?>
                                    </h1>
   <ul class="post-list d-flex align-items-center justify-content-center gap-4">
                                                    <?php if (!empty($post['read_minutes'])): ?>
                                                    <li>
                                                        <i class="fa-regular fa-clock"></i>
                                                        <?= e((string) $post['read_minutes']) ?> min read
                                                    </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <i class="fa-solid fa-calendar-days"></i>
                                                        <?= e(date('d M, Y', strtotime((string) $post['published_at']))) ?>
                                                    </li>
                                                    <?php if (!empty($post['category_name'])): ?>
                                                    <li>
                                                        <i class="fa-solid fa-tag"></i>
                                                       <?= e($post['category_name']) ?>
                                                    </li>
                                                    <?php endif; ?>
                                                </ul>
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
                                            <div class="post-featured-thumb fix">
                                                <img src="<?= e(url($post['featured_image'])) ?>" alt="<?= e(Media::altTextFor($post['featured_image'], $post['title'])) ?>">
                                            </div>
                                            <div class="post-content">

                                                <?= $post['body'] ?>
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
