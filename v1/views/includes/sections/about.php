<?php
$about        = selectContent($conn, "settings_home_about", ["visibility" => "show"])[0];
$aboutBullets = selectContentAsc($conn, "panel_about_bullets", ["visibility" => "show"], "input_order", 10);
?>
<section id="about" class="section bg-white">
  <div class="container">
    <div class="grid grid-cols-1 items-center gap-[72px] md:grid-cols-2">

      <div class="relative overflow-hidden rounded-[20px]">
        <div class="about-img" data-admc-image="settings_home_about" data-admc-id="<?= $about['id'] ?>">
          <?php if (!empty($about['image_1'])): ?>
            <img src="<?= htmlspecialchars($about['image_1']) ?>" alt="About NextShine">
          <?php else: ?>
            <span class="text-[5rem] opacity-30"><i class="fa-solid fa-people-group"></i></span>
            <p class="px-8 text-center text-[0.85rem] text-white/50"><i class="fa-solid fa-camera"></i> Replace with a professional photo</p>
          <?php endif; ?>
        </div>
        <div class="about-floatcard">
          <div class="about-floatcard-icon"><i class="fa-solid fa-house"></i></div>
          <div>
            <div class="about-floatcard-num" data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>"><?= $about['input_floatcard_title'] ?></div>
            <div class="about-floatcard-label" data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>"><?= $about['input_floatcard_label'] ?></div>
          </div>
        </div>
      </div>

      <div>
        <span class="section-label" data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>"><?= $about['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>"><?= $about['input_title'] ?></h2>

        <div class="about-quote" data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
          "<?= $about['text_quote'] ?>"
          <strong class="mt-2 block text-teal"
                  data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
            <?= $about['input_quote_author'] ?>
          </strong>
        </div>

        <p class="mb-5 text-[0.95rem] leading-[1.75] text-grey-dark"
           data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
          <?= $about['text_description'] ?>
        </p>

        <div class="mb-8 mt-6 flex flex-col gap-3" data-admc-tb="panel_about_bullets">
          <?php foreach ($aboutBullets as $bullet): ?>
            <div class="about-bullet">
              <div class="about-bullet-check">✓</div>
              <span data-admc-manage="panel_about_bullets" data-admc-id="<?= $bullet['id'] ?>"><?= $bullet['input_text'] ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="flex flex-wrap gap-3.5">
          <a href="<?= htmlspecialchars($about['input_cta_primary_url']) ?>" class="btn btn-primary"
             data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
            <?= $about['input_cta_primary_text'] ?>
          </a>
          <a href="<?= htmlspecialchars($about['input_cta_secondary_url']) ?>" class="btn btn-outline-navy"
             data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
            <?= $about['input_cta_secondary_text'] ?>
          </a>
        </div>
      </div>

    </div>
  </div>
</section>
