<?php
$testimonialsHeader = selectContent($conn, "settings_home_testimonials", ["visibility" => "show"])[0];
$testimonials       = selectContentAsc($conn, "panel_testimonials", ["visibility" => "show"], "input_order", 20);
?>
<?php if (count($testimonials) > 0): ?>
<section id="testimonials" class="section bg-white">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-label" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_label'] ?></span>
      <h2 class="section-title" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_title'] ?></h2>
      <p class="section-subtitle mx-auto" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['text_subtitle'] ?></p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3" data-admc-tb="panel_testimonials">
      <?php foreach ($testimonials as $review): ?>
        <div class="review-card reveal">
          <div class="mb-3.5 flex gap-[3px]">
            <?= str_repeat('<span class="review-star">★</span>', 5) ?>
          </div>
          <p class="review-text" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
            "<?= $review['text_review'] ?>"
          </p>
          <div class="flex items-center gap-3">
            <?php if (!empty($review['image_1'])): ?>
              <div data-admc-image="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
                <img src="<?= htmlspecialchars($review['image_1']) ?>" alt="<?= htmlspecialchars($review['input_author_name']) ?>" class="review-avatar object-cover">
              </div>
            <?php else: ?>
              <div class="review-avatar" style="background: <?= htmlspecialchars($review['bgcolor_avatar']) ?>;"
                   data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
                <?= $review['input_author_initials'] ?>
              </div>
            <?php endif; ?>
            <div>
              <div class="review-name" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><?= $review['input_author_name'] ?></div>
              <div class="review-role" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><?= $review['input_author_role'] ?></div>
            </div>
          </div>
          <div class="review-source" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><i class="fa-solid fa-star"></i> <?= $review['input_source'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-10 text-center">
      <div class="google-rating">
        <div class="google-g">G</div>
        <div>
          <div class="flex items-center gap-1.5">
            <span class="google-score" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_google_score'] ?></span>
            <span class="text-[0.9rem] text-[#f59e0b]">★★★★★</span>
          </div>
          <div class="google-text" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_google_text'] ?></div>
        </div>
      </div>
    </div>

  </div>
</section>
<?php else: ?>
<!-- No visible reviews yet -->
<section class="section text-center">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-label">Reviews</span>
      <h2 class="section-title">Real Reviews Coming Soon</h2>
      <p class="section-subtitle mx-auto">We are a new service and building our reputation one client at a time. Check back soon for reviews from real Edinburgh customers — or be our first!</p>
    </div>
    <a href="/contact" class="btn btn-primary btn-lg"><i class="fa-solid fa-clipboard-list"></i> Book Your First Clean</a>
  </div>
</section>
<?php endif; ?>
