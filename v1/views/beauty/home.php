<?php
/**
 * NextShine Beauty: the one-page site at /beauty.
 */
$hero       = selectContent($conn, "settings_beauty_hero", ["visibility" => "show"])[0];
$highlights = selectContentAsc($conn, "panel_beauty_highlights", ["visibility" => "show"], "input_order", 10);

$servicesHeader = selectContent($conn, "settings_beauty_services", ["visibility" => "show"])[0];
$services       = selectContentAsc($conn, "panel_beauty_services", ["visibility" => "show"], "input_order", 30);
$serviceNotes   = selectContentAsc($conn, "panel_beauty_notes", ["visibility" => "show"], "input_order", 10);

$shop     = selectContent($conn, "settings_beauty_shop", ["visibility" => "show"])[0];
$products = selectContentAsc($conn, "panel_beauty_products", ["visibility" => "show"], "input_order", 10);

// Product feature lists, pre-indexed by the parent product's hash_id.
$productFeatures = [];
foreach (selectContentAsc($conn, "addition_beauty_product_features", ["visibility" => "show"], "input_order", 200) as $feature) {
    $productFeatures[$feature['tb_link']][] = $feature;
}

$booking         = selectContent($conn, "settings_beauty_booking", ["visibility" => "show"])[0];
$channels        = selectContentAsc($conn, "panel_beauty_channels", ["visibility" => "show"], "input_order", 10);
$bookingServices = selectContentAsc($conn, "selection_beauty_booking_services", ["visibility" => "show"], "input_order", 50);

$bookingNotes = array_filter(array_map('trim', explode("\n", $booking['text_notes'] ?? '')));

$gallery      = selectContent($conn, "settings_beauty_gallery", ["visibility" => "show"])[0] ?? null;
$galleryItems = selectContentAsc($conn, "panel_beauty_gallery", ["visibility" => "show"], "input_order", 48);

// Photos are optional per record. Visitors only see the ones that exist;
// signed-in admins also get an empty slot to upload into through live edit.
$beautyAdmin = isset($_SESSION['admin_id']);

include APP_PATH . "/views/beauty/includes/header.php";

$beautyWhatsappUrl = !empty($beautySite['input_whatsapp_number'])
    ? 'https://wa.me/' . preg_replace('/\D/', '', $beautySite['input_whatsapp_number'])
    : '';
?>

<div class="template-home-wrapper">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>

<?php/*##cbcode_30001o##*/?>
<div data-cbcodesection="cbcode_30001">
  <!-- HERO -->
  <?php $heroHasPhoto = !empty($hero['image_1']) || $beautyAdmin; ?>
  <section class="hero" id="home">
    <div class="hero-pattern"></div>
    <?php if ($heroHasPhoto): ?><div class="hero-inner"><?php endif; ?>
    <div class="hero-content">
      <span class="hero-eyebrow" data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_eyebrow'] ?></span>
      <h1 class="hero-title">
        <span data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_1'] ?></span><br>
        <em data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_2'] ?></em><br>
        <span data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_3'] ?></span>
      </h1>
      <p class="hero-text" data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['text_description'] ?></p>
      <div class="flex flex-col gap-4 xs:flex-row xs:flex-wrap">
        <a href="#booking" class="btn-primary" data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_cta_primary_text'] ?></a>
        <a href="#services" class="btn-outline" data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_cta_secondary_text'] ?></a>
      </div>
      <div class="hero-badges">
        <?php foreach ([1, 2, 3] as $n):
          if (empty($hero["input_badge_{$n}_value"])) continue;
        ?>
          <div class="hero-badge">
            <strong data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero["input_badge_{$n}_value"] ?></strong>
            <span data-admc-manage="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>"><?= nl2br($hero["text_badge_{$n}_label"] ?? '') ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if ($heroHasPhoto): ?>
      <div class="hero-photo" data-admc-image="settings_beauty_hero" data-admc-id="<?= $hero['id'] ?>">
        <?php if (!empty($hero['image_1'])): ?>
          <img src="<?= htmlspecialchars($hero['image_1']) ?>" alt="African hair styling at NextShine Beauty" fetchpriority="high">
        <?php else: ?>
          <span class="media-placeholder">Add a hero photo</span>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- WHY US STRIP -->
  <div class="about-strip" data-admc-tb="panel_beauty_highlights">
    <?php foreach ($highlights as $point): ?>
      <div class="about-point">
        <span class="icon" data-admc-manage="panel_beauty_highlights" data-admc-id="<?= $point['id'] ?>"><i class="<?= htmlspecialchars($point['input_icon']) ?>" aria-hidden="true"></i></span>
        <h4 data-admc-manage="panel_beauty_highlights" data-admc-id="<?= $point['id'] ?>"><?= $point['input_title'] ?></h4>
        <p data-admc-manage="panel_beauty_highlights" data-admc-id="<?= $point['id'] ?>"><?= $point['text_description'] ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php/*##cbcode_30001c##*/?>

<?php/*##cbcode_30002o##*/?>
<div data-cbcodesection="cbcode_30002">
  <!-- SERVICES -->
  <section class="services" id="services">
    <span class="section-label" data-admc-manage="settings_beauty_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_label'] ?></span>
    <h2 class="section-title" data-admc-manage="settings_beauty_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_title'] ?></h2>
    <div class="divider"></div>
    <p class="section-sub" data-admc-manage="settings_beauty_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['text_subtitle'] ?></p>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(260px,1fr))] gap-[2px]" data-admc-tb="panel_beauty_services">
      <?php foreach (array_values($services) as $i => $svc):
        $svcMedia = !empty($svc['image_1']) || $beautyAdmin;
      ?>
        <div class="service-card<?= $svcMedia ? ' has-media' : '' ?>">
          <?php if ($svcMedia): ?>
            <div class="card-media" data-admc-image="panel_beauty_services" data-admc-id="<?= $svc['id'] ?>">
              <?php if (!empty($svc['image_1'])): ?>
                <img src="<?= htmlspecialchars($svc['image_1']) ?>" alt="<?= htmlspecialchars($svc['input_title']) ?>" loading="lazy">
              <?php else: ?>
                <span class="media-placeholder">Add a photo</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="card-body">
            <span class="service-num"><?= sprintf('%02d', $i + 1) ?></span>
            <h3 data-admc-manage="panel_beauty_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_title'] ?></h3>
            <?php if (!empty($svc['input_price'])): ?>
              <div class="service-price" data-admc-manage="panel_beauty_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_price'] ?></div>
            <?php else: ?>
              <a href="#booking" class="sales-enquire" data-admc-manage="settings_beauty_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_enquire_text'] ?></a>
            <?php endif; ?>
            <p class="mt-3" data-admc-manage="panel_beauty_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['text_description'] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="services-notes" data-admc-tb="panel_beauty_notes">
      <?php foreach ($serviceNotes as $note): ?>
        <p data-admc-manage="panel_beauty_notes" data-admc-id="<?= $note['id'] ?>">✦ <?php if (!empty($note['input_highlight'])): ?><strong><?= $note['input_highlight'] ?></strong> <?php endif; ?><?= $note['input_text'] ?></p>
      <?php endforeach; ?>
    </div>
  </section>
</div>
<?php/*##cbcode_30002c##*/?>

<?php/*##cbcode_30003o##*/?>
<div data-cbcodesection="cbcode_30003">
  <!-- HAIR SALES -->
  <section class="hair-sales" id="hair">
    <span class="section-label" data-admc-manage="settings_beauty_shop" data-admc-id="<?= $shop['id'] ?>"><?= $shop['input_label'] ?></span>
    <h2 class="section-title" data-admc-manage="settings_beauty_shop" data-admc-id="<?= $shop['id'] ?>"><?= $shop['input_title'] ?></h2>
    <div class="divider"></div>
    <p class="section-sub" data-admc-manage="settings_beauty_shop" data-admc-id="<?= $shop['id'] ?>"><?= $shop['text_subtitle'] ?></p>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(280px,1fr))] gap-6" data-admc-tb="panel_beauty_products">
      <?php foreach ($products as $product):
        $productHasPhoto = !empty($product['image_1']);
        $productMedia    = $productHasPhoto || $beautyAdmin;
      ?>
        <div class="sales-card<?= $productMedia ? ' has-media' : '' ?>">
          <?php if ($productMedia): ?>
            <div class="card-media" data-admc-image="panel_beauty_products" data-admc-id="<?= $product['id'] ?>">
              <?php if ($productHasPhoto): ?>
                <img src="<?= htmlspecialchars($product['image_1']) ?>" alt="<?= htmlspecialchars($product['input_title']) ?>" loading="lazy">
              <?php else: ?>
                <span class="media-placeholder">Add a photo</span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <?php if (!$productHasPhoto): ?>
            <span class="sales-icon" data-admc-manage="panel_beauty_products" data-admc-id="<?= $product['id'] ?>"><i class="<?= htmlspecialchars($product['input_icon']) ?>" aria-hidden="true"></i></span>
          <?php endif; ?>
          <h3 data-admc-manage="panel_beauty_products" data-admc-id="<?= $product['id'] ?>"><?= $product['input_title'] ?></h3>
          <p data-admc-manage="panel_beauty_products" data-admc-id="<?= $product['id'] ?>"><?= $product['text_description'] ?></p>
          <ul data-admc-tb="addition_beauty_product_features"
              data-admc-tbadd="panel_beauty_products"
              data-admc-tblink="<?= $product['hash_id'] ?>">
            <?php foreach ($productFeatures[$product['hash_id']] ?? [] as $feature): ?>
              <li data-admc-manage="addition_beauty_product_features" data-admc-id="<?= $feature['id'] ?>"><?= $feature['input_feature'] ?></li>
            <?php endforeach; ?>
          </ul>
          <a href="#booking" class="sales-enquire" data-admc-manage="panel_beauty_products" data-admc-id="<?= $product['id'] ?>"><?= $product['input_link_text'] ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</div>
<?php/*##cbcode_30003c##*/?>

<?php if ($gallery && (count($galleryItems) > 0 || $beautyAdmin)): ?>
<?php/*##cbcode_30006o##*/?>
<div data-cbcodesection="cbcode_30006">
  <!-- GALLERY -->
  <section class="gallery" id="gallery">
    <span class="section-label" data-admc-manage="settings_beauty_gallery" data-admc-id="<?= $gallery['id'] ?>"><?= $gallery['input_label'] ?></span>
    <h2 class="section-title" data-admc-manage="settings_beauty_gallery" data-admc-id="<?= $gallery['id'] ?>"><?= $gallery['input_title'] ?></h2>
    <div class="divider"></div>
    <p class="section-sub" data-admc-manage="settings_beauty_gallery" data-admc-id="<?= $gallery['id'] ?>"><?= $gallery['text_subtitle'] ?></p>

    <div class="grid grid-cols-2 gap-[2px] md:grid-cols-3 lg:grid-cols-4" data-admc-tb="panel_beauty_gallery">
      <?php foreach ($galleryItems as $item):
        if (empty($item['image_1']) && !$beautyAdmin) continue;
      ?>
        <figure class="gallery-item">
          <div class="gallery-photo" data-admc-image="panel_beauty_gallery" data-admc-id="<?= $item['id'] ?>">
            <?php if (!empty($item['image_1'])): ?>
              <img src="<?= htmlspecialchars($item['image_1']) ?>" alt="<?= htmlspecialchars($item['input_caption']) ?>" loading="lazy">
            <?php else: ?>
              <span class="media-placeholder">Add a photo</span>
            <?php endif; ?>
          </div>
          <?php if (!empty($item['input_caption'])): ?>
            <figcaption class="gallery-caption" data-admc-manage="panel_beauty_gallery" data-admc-id="<?= $item['id'] ?>"><?= $item['input_caption'] ?></figcaption>
          <?php endif; ?>
        </figure>
      <?php endforeach; ?>
    </div>
  </section>
</div>
<?php/*##cbcode_30006c##*/?>
<?php endif; ?>

<?php/*##cbcode_30004o##*/?>
<div data-cbcodesection="cbcode_30004">
  <!-- BOOKING -->
  <section class="booking" id="booking">
    <span class="section-label" data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['input_label'] ?></span>
    <h2 class="section-title" data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['input_title'] ?></h2>
    <div class="divider"></div>
    <p class="section-sub" data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['text_subtitle'] ?></p>

    <div class="grid items-start gap-12 md:grid-cols-2">
      <!-- Contact channels -->
      <div>
        <div class="flex flex-col gap-3.5" data-admc-tb="panel_beauty_channels">
          <?php foreach ($channels as $channel):
            $external = str_starts_with($channel['input_link'], 'http');
          ?>
            <a href="<?= htmlspecialchars($channel['input_link']) ?>"<?= $external ? ' target="_blank" rel="noopener"' : '' ?> class="channel-btn">
              <span class="channel-icon" data-admc-manage="panel_beauty_channels" data-admc-id="<?= $channel['id'] ?>"><i class="<?= htmlspecialchars($channel['input_icon']) ?>" aria-hidden="true"></i></span>
              <div class="channel-info">
                <strong data-admc-manage="panel_beauty_channels" data-admc-id="<?= $channel['id'] ?>"><?= $channel['input_title'] ?></strong>
                <span data-admc-manage="panel_beauty_channels" data-admc-id="<?= $channel['id'] ?>"><?= $channel['input_subtitle'] ?></span>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
        <p class="booking-notes" data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>">
          <?= implode('<br>', array_map(function ($line) { return '✦ ' . $line; }, $bookingNotes)) ?>
        </p>
      </div>

      <!-- Booking form -->
      <div>
        <form class="booking-form flex flex-col gap-3.5" data-booking-form>
          <h3 data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['input_form_title'] ?></h3>
          <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="form-group">
              <label for="booking-first-name">First Name</label>
              <input type="text" id="booking-first-name" name="first_name" class="form-control" placeholder="Amara" required>
            </div>
            <div class="form-group">
              <label for="booking-last-name">Last Name</label>
              <input type="text" id="booking-last-name" name="last_name" class="form-control" placeholder="Johnson" required>
            </div>
          </div>
          <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="form-group">
              <label for="booking-phone">Phone / WhatsApp Number</label>
              <input type="tel" id="booking-phone" name="phone" class="form-control" placeholder="+44 7xxx xxxxxx" required>
            </div>
            <div class="form-group">
              <label for="booking-email">Email Address (optional)</label>
              <input type="email" id="booking-email" name="email" class="form-control" placeholder="you@example.com">
            </div>
          </div>
          <div class="form-group">
            <label for="booking-service">Service Interested In</label>
            <select id="booking-service" name="service" class="form-control" required>
              <option value="" disabled selected>Select a service...</option>
              <?php foreach ($bookingServices as $option): ?>
                <option><?= htmlspecialchars($option['input_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="booking-notes">Preferred Dates &amp; Any Notes</label>
            <textarea id="booking-notes" name="notes" class="form-control" placeholder="e.g. Weekends preferred, mid-length knotless braids, natural colour..."></textarea>
          </div>
          <?= captchaWidget('dark', 'gold') ?>
          <button type="submit" class="form-submit" data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['input_submit_text'] ?></button>
          <p class="form-note" data-form-note data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>"><?= $booking['input_form_note'] ?></p>
        </form>
        <div class="form-success hidden" data-booking-success data-admc-manage="settings_beauty_booking" data-admc-id="<?= $booking['id'] ?>">
          ✦ <?= $booking['input_success_message'] ?>
        </div>
      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_30004c##*/?>

<?php/*##cbcode_30005o##*/?>
<div data-cbcodesection="cbcode_30005">
  <!-- SOCIAL STRIP -->
  <div class="social-strip">
    <p data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_social_text'] ?></p>
    <div class="flex flex-wrap justify-center gap-4">
      <?php if (!empty($beautySite['input_instagram_url'])): ?>
        <a href="<?= htmlspecialchars($beautySite['input_instagram_url']) ?>" target="_blank" rel="noopener" class="social-link"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagram</a>
      <?php endif; ?>
      <?php if ($beautyWhatsappUrl): ?>
        <a href="<?= $beautyWhatsappUrl ?>" target="_blank" rel="noopener" class="social-link"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php/*##cbcode_30005c##*/?>

<?php/*##cb1c##*/?>
</div>
</div>

<?php include APP_PATH . "/views/beauty/includes/footer.php"; ?>
