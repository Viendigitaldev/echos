<?php
/**
 * @var array<string, array<string, mixed>> $blocks
 * @var array<int, array<string, mixed>> $team
 */

use App\Models\Media;

$vision = $blocks['vision'];
$mindset = $blocks['mindset'];
$builders = $blocks['builders'];
$ecosystem = $blocks['ecosystem'];
$leadership = $blocks['leadership'];
$letsDeal = $blocks['lets_deal'];
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">

          <section class="outcome-section1">
     <div class="container">

         <div class="section-header">

             <h2 class="wa_title_spilt_1">
                 <?= e($vision['heading']) ?>
             </h2>

             <div class="vision-content">

                 <p class="wow fadeInUp" data-wow-delay=".3s">
                     <?= e($vision['body']) ?>
                 </p>
             </div>


         </div>

     </div>
 </section>


 <section class="bgimg fix">
     <img src="<?= e(asset('img/home-1/video-banner.jpg')) ?>" alt="">
 </section>

                     <section class="mindset-section">
     <div class="container">

         <div class="mindset-grid">

             <!-- Left Side -->
             <div class="mindset-label wa_title_spilt_1">
                <?= nl2br(e($mindset['heading'])) ?>
             </div>

             <!-- Right Side -->
             <div class="mindset-content">

                 <p>
 <?= e($mindset['body']) ?></p>


             </div>

         </div>

     </div>
 </section>



   <section class="builders-section">
     <div class="container">
         <div class="builders-wrapper">

             <!-- Left Logos -->
             <div class="builders-logos">
                 <div class="logo-card1 active">
                     <img src="<?= e(asset('img/inner-page/Palantir-Logo.png')) ?>" alt="Logo 1">
                 </div>

                 <div class="logo-card1 active">
                     <img src="<?= e(asset('img/inner-page/Goldman-Sachs-Logo.png')) ?>" alt="Logo 2">
                 </div>

                 <div class="logo-card1 active">
                     <img src="<?= e(asset('img/inner-page/colombia-logo.png')) ?>" alt="Logo 3">
                 </div>
                 <div class="logo-card1 active">
                     <img src="<?= e(asset('img/inner-page/IIT-logo.png')) ?>" alt="Logo 4">
                 </div>
             </div>

             <!-- Right Content -->
             <div class="builders-content">

    <h2 class="wa_title_spilt_1">
                                 <?= e($builders['heading']) ?>
                                     </h2>


                 <p>
                   <?= e($builders['body']) ?>
                 </p>

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
                                        <?= e($ecosystem['heading']) ?>
                                     </h2>
                  <p class="small-content">
                    <?= e($ecosystem['body']) ?>
                 </p>
                                 </div>




                                     </div>

                                     <!-- CENTER LOGOS -->
                                     <div class="elite-logos inner">


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



                      <div class="sec-line-shape">
                         <img src="<?= e(asset('img/home-1/line-shape.png')) ?>" alt="img">
                     </div>


       <section class="leadership-section">
     <div class="container">

         <div class="section-heading">
             <h2 class="wa_title_spilt_1"><?= e($leadership['heading']) ?></h2>
         </div>

         <?php foreach ($team as $i => $member): ?>
         <!-- Leader <?= $i + 1 ?> -->

         <div class="leader-row<?= $i % 2 === 1 ? ' reverse' : '' ?>">

             <div class="leader-image">
                 <img src="<?= e(url($member['image_path'])) ?>" alt="<?= e(Media::altTextFor($member['image_path'], $member['name'])) ?>">
             </div>

             <div class="leader-content">

                 <span class="designation">
                     <?= e($member['designation']) ?>
                 </span>

                 <div class="leader-name-row">
                     <h3><?= e($member['name']) ?></h3>

                     <a href="<?= e($member['linkedin_url']) ?>" class="linkedin-btn">
                         <i class="fab fa-linkedin-in"></i>
                     </a>
                 </div>

                 <p>
                    <?= e($member['bio']) ?>

                 </p>

             </div>

         </div>
         <?php endforeach; ?>

     </div>
 </section>


                     <!-- Lets Deal Section Start -->
                     <section class="lets-deal-section fix section-padding fix section-padding">
                         <div class="container">
                             <div class="lets-deal-wrapper">
                                  <div class="section-title text-center mb-0">

                                     <h2 class="wa_title_spilt_1">
                                        <?= nl2br(e($letsDeal['heading'])) ?>
                                     </h2>
                                 </div>
                         <a href="<?= e(url($letsDeal['cta_url'])) ?>" class="start-building-btn mt-5">
                                 <span><?= e($letsDeal['cta_label']) ?></span>

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
