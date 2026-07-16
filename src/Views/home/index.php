<?php
/**
 * @var array<string, array<string, mixed>> $blocks
 * @var array<int, array<string, mixed>> $featured
 * @var array<int, array<string, mixed>> $industries
 * @var array<int, array<string, mixed>> $latestPosts
 */

use App\Models\Media;
use App\Models\Setting;
$hero = $blocks['hero'];
$aboutTeaser = $blocks['about_teaser'];
$partnerElite = $blocks['partner_elite'];
$thinkingSystems = $blocks['thinking_systems'];
$industriesBlock = $blocks['industries'];
$media = $blocks['media'];
$perspectives = $blocks['perspectives_teaser'];
$blogEnabled = Setting::get('blog_enabled', '0') === '1';
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

                    <!-- Hero Section Start -->
                    <section class="hero-section-1 hero-1">
                        <div class="container">
                            <div class="hero-line">
                            <img src="<?= e(asset('img/home-1/hero-line.png')) ?>" alt="">
                        </div>
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <div class="hero-content">

                                    <h1 class="wa_title_spilt_1">
                                      <?= nl2br(e($hero['heading'])) ?>
                                    </h1>
                                    <div class="hero-circle-mobile">
                                        <img src="<?= e(asset('img/home-1/hero-circle.png')) ?>" alt="img">
                                    </div>
                                    <p class="hero-para"><?= e($hero['body']) ?>
</p>
                                    <div class="hero-button wow fadeInUp" data-wow-delay=".3s">

                                       <a href="<?= e(url($hero['cta_url'])) ?>" class="start-building-btn">
                            <span><?= e($hero['cta_label']) ?></span>

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
                            <div class="col-xl-5">
                                <div class="hero-circle">
                            <img src="<?= e(asset('img/home-1/hero-circle.png')) ?>" alt="img">
                        </div>

                            </div>
                        </div>
                        </div>
                    </section>



            <section class="about-section section-padding reveal-section">
                    <div class="container">
                        <div class="about-wrapper">

                            <div class="row g-5 align-items-center">

                                <!-- Image Side -->
                                <div class="col-lg-6">
                                    <div class="about-image wow fadeInUp">

                                        <div class="about-circle">
                                            <img src="<?= e(asset('img/home-1/about-circle.png')) ?>" alt="">
                                        </div>

                                        <img src="<?= e(url($aboutTeaser['image_path'])) ?>" alt="<?= e(Media::altTextFor($aboutTeaser['image_path'], 'About Echos')) ?>">

                                    </div>
                                </div>

                                <!-- Content Side -->
                                <div class="col-lg-6">

                                  <div class="about-content">

                    <p class="text reveal-text">
                        <?= e($aboutTeaser['body']) ?>
                    </p>

                </div>

                </div>

            </div>

        </div>
    </div>
</section>


    <section class="partner-elite-section">
                            <div class="container">
                                <div class="row">
                                       <div class="partner-elite-card align-items-center">

        <!-- LEFT CONTENT -->
        <div class="elite-content">
             <div class="section-title">

                                    <h2 class="wa_title_spilt_1">
                                     <?= nl2br(e($partnerElite['heading'])) ?>
                                    </h2>
                                </div>


            <p>
             <?= e($partnerElite['body']) ?>
            </p>

        </div>

        <!-- CENTER LOGOS -->
        <div class="elite-logos">


            <div class="logo-card active">
                <img src="<?= e(asset('img/logo/partner-logo.jpeg')) ?>" alt="">

            </div>

            <div class="logo-divider"></div>

            <div class="logo-card active">
                <img src="<?= e(asset('img/logo/anthropic.png')) ?>" alt="" class="filter-invert">

            </div>

        </div>



    </div>
                                </div>
                                 <!-- BOTTOM TRUST BAR -->

                            </div>


</section>


                    <section class="service-section-3 fix section-padding">
                        <div class="container">
                            <div class="section-title-area">
                                <div class="section-title">
                              <h2 class="wa_title_spilt_1">
                                  <?= nl2br(e($thinkingSystems['heading'])) ?>
                                    </h2>
                                </div>
                                <div class="array-button wow fadeInUp" data-wow-delay=".3s">
                                <button class="array-prev"><i class="fa-solid fa-chevron-left"></i></button>

                                <button class="array-next"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>

                            </div>
                            <div class="service-wrapper-3">
                                <div class="swiper service-slider-3">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($featured as $i => $item): ?>
                                        <div class="swiper-slide wow fadeInUp" data-wow-delay=".<?= ($i + 1) * 2 ?>s">
                                            <div class="service-box-items-3 app-popup-trigger" data-title="<?= e($item['title']) ?>"
     data-image="<?= e(url($item['image_path'])) ?>"
     data-description="<?= e($item['full_description']) ?>">
                                                <img src="<?= e(url($item['image_path'])) ?>" alt="<?= e(Media::altTextFor($item['image_path'], $item['title'])) ?>">
                                                <div class="card-btn d-flex justify-content-between align-items-center">
                                                    <h3><a href="javascript:void(0)"><?= e($item['title']) ?></a>
                                                </h3>

                                                </div>

                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="hero-button wow fadeInUp mt-5 text-center" data-wow-delay=".3s">

                                       <a href="<?= e(url($thinkingSystems['cta_url'])) ?>" class="start-building-btn">
    <span><?= e($thinkingSystems['cta_label']) ?></span>

    <span class="icon-wrap">
        <span class="dot"></span>

        <svg class="arrow" viewBox="0 0 24 24" fill="none">
            <path d="M5 12H19" stroke="currentColor" stroke-width="2"/>
            <path d="M13 6L19 12L13 18" stroke="currentColor" stroke-width="2"/>
        </svg>
    </span>
</a>
                                    </div>
                    </section>



                       <!-- Work Process Section Start -->
                    <section class="work-process-section-3 fix section-padding">
                        <div class="container">
                            <div class="about-wrapper-2 about-page-style-3">
                                <div class="section-title-area">

                                    <div class="section-title mb-0">
                                         <h2 class="wa_title_spilt_1">
                                          <?= nl2br(e($industriesBlock['heading'])) ?>

                                                </h2>

                                    </div>
                                </div>
                            </div>
                            <div class="work-process-wrapper-3 d-none d-lg-grid">
                                <div class="line-1">
                                    <img src="<?= e(asset('img/home-3/line.png')) ?>" alt="img">
                                </div>
                                <?php foreach ($industries as $i => $industry): ?>
                                <div class="work-process-items-3<?= $i === 0 ? ' active' : '' ?> wow fadeInUp"<?= $i > 0 ? ' data-wow-delay=".' . ($i * 2) . 's"' : '' ?>>
                                    <div class="icon">
                                        <img src="<?= e(url($industry['icon_path'])) ?>" alt="<?= e(Media::altTextFor($industry['icon_path'], $industry['title'])) ?>">
                                    </div>
                                    <div class="content">
                                        <h3 class="title"><?= e($industry['title']) ?></h3>
                                        <p><?= e($industry['description']) ?>
</p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Mobile/tablet: swipeable carousel instead of the hover-reveal grid above -->
                            <div class="industries-slider-mobile d-lg-none">
                                <div class="swiper industries-swiper">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($industries as $industry): ?>
                                        <div class="swiper-slide">
                                            <div class="industries-slide-card">
                                                <div class="industries-slide-icon">
                                                    <img src="<?= e(url($industry['icon_path'])) ?>" alt="<?= e(Media::altTextFor($industry['icon_path'], $industry['title'])) ?>">
                                                </div>
                                                <h3 class="industries-slide-title"><?= e($industry['title']) ?></h3>
                                                <p class="industries-slide-desc"><?= e($industry['description']) ?></p>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="industries-slider-nav">
                                    <button class="industries-prev" aria-label="Previous industry"><i class="fa-solid fa-chevron-left"></i></button>
                                    <div class="swiper-pagination industries-pagination"></div>
                                    <button class="industries-next" aria-label="Next industry"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section class="media-section">
    <div class="container">

        <div class="media-wrapper">

            <!-- Left Image -->
            <div class="media-image">
                <img src="<?= e(url($media['image_path'])) ?>" alt="<?= e($media['heading']) ?>">
            </div>

            <!-- Right Content -->
            <div class="media-content">
                <h2 class="wa_title_spilt_1"><?= nl2br(e($media['heading'])) ?></h2>
                <div class="media-image-mobile">
<img src="<?= e(asset('img/inner-page/media2.jpg')) ?>" alt="In The Media">
</div>

                <p>
                    <?= e($media['body']) ?>
                </p>


                <a href="<?= e($media['cta_url']) ?>" target="_blank" class="start-building-btn">
                    <span><?= e($media['cta_label']) ?></span>

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
</section>


                    <?php if ($blogEnabled): ?>
                    <!-- News Section Start -->
                    <section class="news-section fix section-padding section-bg oit-panel-pin-area">
                        <div class="container">
                            <div class="section-title-area mb-0">
                                <div class="text-items wow fadeInUp" data-wow-delay=".3s">
                                    <p>
                                      <?= e($perspectives['body']) ?>
                                    </p>
                                    <a href="<?= e(url($perspectives['cta_url'])) ?>" class="news-btn">
                                        <span class="text">
                                            <span class="text-default"><?= e($perspectives['cta_label']) ?>  <i class="fa-regular fa-arrow-up-right"></i></span>
                                            <span class="text-hover"><?= e($perspectives['cta_label']) ?>  <i class="fa-regular fa-arrow-up-right"></i></span>
                                        </span>
                                    </a>
                                </div>
                                <div class="section-title">
                                    <h2 class="wa_title_spilt_1">
                                       <?= nl2br(e($perspectives['heading'])) ?>
                                    </h2>
                                </div>
                            </div>
                            <?php foreach ($latestPosts as $latestPost): ?>
                            <div class="news-box-items oit-panel-pin">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="thumb">
                                            <img src="<?= e(url($latestPost['featured_image'])) ?>" alt="<?= e(Media::altTextFor($latestPost['featured_image'], $latestPost['title'])) ?>">
                                            <img src="<?= e(url($latestPost['featured_image'])) ?>" alt="<?= e(Media::altTextFor($latestPost['featured_image'], $latestPost['title'])) ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content">
                                            <h3 class="title">
                                                <a href="<?= e(url('/perspectives/' . $latestPost['slug'])) ?>"><?= e($latestPost['title']) ?></a>
                                            </h3>
                                            <ul>
                                                <li>
                                                    <div class="client-info">
                                                        <div class="client-content">
                                                            <p class="name"><?= e($latestPost['category_name'] ?? 'General') ?></p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="news-line"></div>
                                                </li>
                                                <li>
                                                    <span><?= e((string) $latestPost['read_minutes']) ?> Min Read</span>
                                                    <span class="color-2"><?= e(date('F Y', strtotime((string) $latestPost['published_at']))) ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
