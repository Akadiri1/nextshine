<?php
/**
 * Home hero: the NextShine Group headline and calls to action, with a card
 * linking to each division.
 */
$hero = selectContent($conn, "settings_home_hero", ["visibility" => "show"])[0];

// The divisions themselves are edited in their section further down the page.
$heroDivisions = selectContentAsc($conn, "panel_home_divisions", ["visibility" => "show"], "input_order", 6);

// Stats are optional; the row only shows when at least one has a value.
$heroStats = array_filter([1, 2, 3], function ($n) use ($hero) {
    return !empty($hero["input_stat_{$n}_value"]);
});

$heroWhatsappUrl = !empty($site_whatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $site_whatsapp) : '#';

// An uploaded hero photo sits under the same navy/teal wash as the default.
$heroStyle = !empty($hero['image_1'])
    ? ' style="background-image: linear-gradient(135deg, rgba(26,51,92,0.92) 0%, rgba(0,135,138,0.80) 100%), url(\'' . htmlspecialchars($hero['image_1']) . '\');"'
    : '';
?>
<section id="hero" class="hero"<?= $heroStyle ?>>
  <div class="container">
    <div class="grid items-center gap-8 py-10 md:grid-cols-[1.15fr_1fr] md:gap-12 md:pb-20 md:pt-[60px]">

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

        <div class="<?= $heroStats ? 'mb-11 ' : '' ?>flex flex-col gap-3.5 xs:flex-row xs:flex-wrap">
          <a href="<?= $heroWhatsappUrl ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
            <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="btn btn-outline btn-lg">
            <i class="fa-solid fa-phone"></i> Call Us Now
          </a>
        </div>

        <?php if ($heroStats): ?>
          <div class="flex flex-wrap gap-6 xs:flex-nowrap md:gap-8">
            <?php foreach ($heroStats as $n): ?>
              <div class="flex flex-col">
                <span class="hero-stat-num" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero["input_stat_{$n}_value"] ?></span>
                <span class="hero-stat-label" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero["input_stat_{$n}_label"] ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right: a way into each division -->
      <?php if ($heroDivisions): ?>
        <div class="hero-card">
          <p class="hero-card-title"
             data-admc-manage="settings_home_hero"
             data-admc-id="<?= $hero['id'] ?>">
            <?= $hero['input_card_title'] ?>
          </p>
          <div class="flex flex-col gap-3.5">
            <?php foreach ($heroDivisions as $division): ?>
              <a href="<?= htmlspecialchars($division['input_link']) ?>" class="hero-division<?= $division['input_theme'] === 'beauty' ? ' hero-division-beauty' : '' ?>">
                <span class="hero-division-icon"><i class="<?= htmlspecialchars($division['input_icon']) ?>" aria-hidden="true"></i></span>
                <span class="flex min-w-0 flex-col">
                  <span class="hero-division-title"><?= $division['input_title'] ?></span>
                  <span class="hero-division-link"><?= $division['input_link_text'] ?></span>
                </span>
                <i class="fa-solid fa-arrow-right ml-auto shrink-0 text-white/60" aria-hidden="true"></i>
              </a>
            <?php endforeach; ?>
          </div>
          <a href="/contact" class="btn btn-outline mt-5 w-full justify-center">
            <i class="fa-solid fa-clipboard-list"></i> Request a Free Quote
          </a>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>
