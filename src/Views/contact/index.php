<?php
/**
 * @var array<string, mixed> $intro
 * @var array<string, mixed> $locationsHeading
 * @var array<int, array<string, mixed>> $locations
 * @var array{type: string, message: string}|null $flash
 * @var array<string, mixed> $old
 */
?>
            <div id="smooth-wrapper">
                <div id="smooth-content">


      <section class="contact-area">
     <div class="container">
   <div class="contact-content">



            <div class="section-title">
                                     <h2 class="wa_title_spilt_1">
                               <?= nl2br(e($intro['heading'])) ?>
                                     </h2>
                                 </div>



         </div>
          <div class="divider-line">
                 <span></span>
             </div>

         <?php if ($flash !== null): ?>
         <div class="contact-flash contact-flash-<?= e($flash['type']) ?>" role="alert">
             <?= e($flash['message']) ?>
         </div>
         <?php endif; ?>

         <div class="contact-form-wrap">

             <form action="<?= e(url('/contact/submit')) ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="text" name="website" value="" class="visually-hidden-field" tabindex="-1" autocomplete="off">
                    <div class="input-group">
                     <select name="reason">
                        <option value="">Reason For Contacting Us?</option>
                          <option<?= ($old['reason'] ?? '') === 'Learn More' ? ' selected' : '' ?>>Learn More</option>
                          <option<?= ($old['reason'] ?? '') === 'Schedule a demo' ? ' selected' : '' ?>>Schedule a demo</option>
                          <option<?= ($old['reason'] ?? '') === 'Press' ? ' selected' : '' ?>>Press</option>
                          <option<?= ($old['reason'] ?? '') === 'Other' ? ' selected' : '' ?>>Other</option>
                    </select>
                 </div>
                 <div class="input-group">
                     <i class="bi bi-person"></i>
                     <input type="text" name="name" placeholder="Name" value="<?= e($old['name'] ?? '') ?>" required>
                 </div>
                <div class="input-group">
                     <i class="bi bi-envelope"></i>
                     <input type="email" name="email" placeholder="Email Address" value="<?= e($old['email'] ?? '') ?>" required>
                 </div>
                 <div class="input-group">
                     <i class="bi bi-telephone"></i>
                     <input type="tel" name="phone" placeholder="Phone Number" value="<?= e($old['phone'] ?? '') ?>">
                 </div>
                    <div class="input-group">
                     <i class="bi bi-person"></i>
                     <input type="text" name="company" placeholder="Company" value="<?= e($old['company'] ?? '') ?>">
                 </div>


                 <div class="input-group textarea-group">
                     <i class="bi bi-pencil"></i>
                     <textarea name="message" placeholder="Message" required><?= e($old['message'] ?? '') ?></textarea>
                 </div>

                <button type="submit" class="start-building-btn contact-btn">
     <span>Submit</span>

     <span class="icon-wrap">
         <span class="dot"></span>

         <svg class="arrow" viewBox="0 0 24 24" fill="none">
             <path d="M5 12H19" stroke="currentColor" stroke-width="2"/>
             <path d="M13 6L19 12L13 18" stroke="currentColor" stroke-width="2"/>
         </svg>
     </span>
 </button>

             </form>

         </div>



     </div>
 </section>




                <section class="locations-section">
                     <div class="container">
                         <div class="locations-right wow fadeInUp" data-wow-delay=".7s">
             <?php foreach ($locations as $location): ?>
             <div class="location-card <?= e($location['slug']) ?>-card">
                 <div class="card-inner">

                     <div class="card-image ">
                         <img src="<?= e(media_url($location['image_path'])) ?>" alt="">
                     </div>

                     <div class="card-content">

                         <div>
                             <h4><?= e($location['name']) ?></h4>
                             <p></p>
                         </div>
                     </div>

                 </div>

             </div>
             <?php endforeach; ?>

         </div>
                         <div class="locations-left">
                                 <div class="section-title">
                                     <h2 class="wa_title_spilt_1">
                                   <?= e($locationsHeading['heading']) ?>
                                     </h2>
                                 </div>

                         </div>


                    </div>
                 </section>

                <?php require __DIR__ . '/../partials/footer.php'; ?>
                </div>
            </div>
