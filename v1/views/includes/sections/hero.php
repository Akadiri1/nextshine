<?php
$hero = selectContent($conn, "settings_home_hero", ["visibility" => "show"])[0];

$heroWhatsappUrl = !empty($site_whatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $site_whatsapp) : '#';

// An uploaded hero photo sits under the same navy/teal wash as the default.
$heroStyle = !empty($hero['image_1'])
    ? ' style="background-image: linear-gradient(135deg, rgba(26,51,92,0.92) 0%, rgba(0,135,138,0.80) 100%), url(\'' . htmlspecialchars($hero['image_1']) . '\');"'
    : '';
?>
<section id="hero" class="hero"<?= $heroStyle ?>>
  <div class="container">
    <div class="grid items-start gap-8 py-10 md:grid-cols-[1.15fr_1fr] md:gap-12 md:pb-20 md:pt-[60px]">

      <!-- Left: headline & calls to action -->
      <div>
        <div class="hero-badge"
             data-admc-manage="settings_home_hero"
             data-admc-id="<?= $hero['id'] ?>">
          <span class="hero-badge-dot"></span>
          <?= $hero['input_badge_text'] ?>
        </div>

        <h1 class="hero-headline">
          <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_1'] ?></span><br>
          <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><span><?= $hero['input_headline_2'] ?></span></span><br>
          <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_3'] ?></span>
        </h1>

        <p class="hero-desc"
           data-admc-manage="settings_home_hero"
           data-admc-id="<?= $hero['id'] ?>">
          <?= $hero['text_description'] ?>
        </p>

        <div class="mb-11 flex flex-col gap-3.5 xs:flex-row xs:flex-wrap">
          <a href="<?= $heroWhatsappUrl ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
            <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="btn btn-outline btn-lg">
            <i class="fa-solid fa-phone"></i> Call Us Now
          </a>
        </div>

        <div class="flex flex-wrap gap-6 xs:flex-nowrap md:gap-8">
          <?php foreach ([1, 2, 3] as $n):
            if (empty($hero["input_stat_{$n}_value"])) continue;
          ?>
            <div class="flex flex-col">
              <span class="hero-stat-num" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero["input_stat_{$n}_value"] ?></span>
              <span class="hero-stat-label" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero["input_stat_{$n}_label"] ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Right: pricing call to action -->
      <div class="hero-card flex min-h-[320px] flex-col items-center justify-center text-center">
        <div class="mb-5 text-[3.5rem] text-teal-light"><i class="fa-solid fa-tag"></i></div>
        <h2 class="mb-3 font-display text-[1.5rem] font-extrabold text-white">Transparent Pricing</h2>
        <p class="mb-7 max-w-[320px] text-[0.95rem] leading-[1.6] text-white/70">
          Fixed prices for end-of-tenancy. Competitive hourly rates for domestic and commercial. No hidden fees.
        </p>
        <a href="/cleaning#pricing" class="btn btn-primary btn-lg w-full max-w-[280px] justify-center">
          <i class="fa-solid fa-tag"></i> Get Current Pricing
        </a>
        <a href="/contact" class="btn btn-outline mt-3 w-full max-w-[280px] justify-center">
          <i class="fa-solid fa-clipboard-list"></i> Request a Free Quote
        </a>
      </div>

    </div>
  </div>
</section>
