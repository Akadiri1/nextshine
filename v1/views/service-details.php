<?php
/**
 * Service detail page: /services/{hash_id}/{slug}
 *
 * The router sets $serviceId from the URL. The slug is there for readers and
 * search engines; the hash_id alone decides which service is shown.
 */
$allServices = selectContentAsc($conn, "panel_services", ["visibility" => "show"], "input_order", 50);

$service = null;
foreach ($allServices as $s) {
    if ($s['hash_id'] === $serviceId) {
        $service = $s;
        break;
    }
}

if (!$service) {
    include APP_PATH . "/views/404.php";
    die;
}

$page_title      = $service['input_title'];
$page_meta_title = $service['input_meta_title'] ?: null;
$page_meta       = $service['text_meta_description'] ?: $service['text_description'];

$otherServices = array_filter($allServices, function ($s) use ($service) {
    return $s['hash_id'] !== $service['hash_id'];
});

$heroStart = $service['bgcolor_card_start'] ?: 'rgb(var(--color-navy))';
$heroEnd   = $service['bgcolor_card_end'] ?: 'rgb(var(--color-teal))';

$propertySizes = selectContentAsc($conn, "selection_form_property_sizes", ["visibility" => "show"], "input_order", 50);

include APP_PATH . "/views/includes/header.php";
?>

<div class="template-home-wrapper">
<div class="page-content-home-page">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>


<?php/*##cbcode_20001o##*/?>
<div data-cbcodesection="cbcode_20001">

  <!-- SERVICE HERO -->
  <section class="service-hero" style="background: linear-gradient(135deg, <?= htmlspecialchars($heroStart) ?> 0%, <?= htmlspecialchars($heroEnd) ?> 100%);">
    <div class="container">
      <div class="grid items-center gap-6 md:grid-cols-[1.2fr_1fr] md:gap-12">

        <div>
          <?php if (!empty($service['input_badge'])): ?>
            <span class="service-hero-badge"><?= $service['input_badge'] ?></span>
          <?php endif; ?>

          <h1 class="service-hero-title"
              data-admc-manage="panel_services"
              data-admc-id="<?= $service['id'] ?>">
            <?= $service['input_title'] ?>
          </h1>

          <p class="service-hero-intro"
             data-admc-manage="panel_services"
             data-admc-id="<?= $service['id'] ?>">
            <?= $service['text_description'] ?>
          </p>

          <div class="mb-7 flex flex-wrap gap-6">
            <?php if (!empty($service['input_starting_price'])): ?>
              <div class="service-hero-meta-item">
                <i class="fa-solid fa-tag"></i>
                <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= $service['input_starting_price'] ?></span>
              </div>
            <?php endif; ?>
            <?php if (!empty($service['input_duration'])): ?>
              <div class="service-hero-meta-item">
                <i class="fa-solid fa-clock"></i>
                <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= $service['input_duration'] ?></span>
              </div>
            <?php endif; ?>
          </div>

          <div class="flex flex-wrap gap-3.5">
            <a href="<?= htmlspecialchars($service['input_cta_url'] ?: '#contact') ?>" class="btn btn-primary btn-lg">
              <i class="fa-solid fa-clipboard-list"></i>
              <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= $service['input_cta_text'] ?: 'Get a Free Quote' ?></span>
            </a>
            <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="btn btn-outline btn-lg">
              <i class="fa-solid fa-phone"></i> Call Us Now
            </a>
          </div>
        </div>

        <div class="service-hero-media"
             data-admc-image="panel_services"
             data-admc-id="<?= $service['id'] ?>">
          <?php if (!empty($service['image_1'])): ?>
            <img src="<?= htmlspecialchars($service['image_1']) ?>" alt="<?= htmlspecialchars($service['input_title']) ?>">
          <?php else: ?>
            <div class="service-hero-icon">
              <i class="<?= htmlspecialchars($service['input_icon']) ?>"></i>
            </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </section>

  <!-- SERVICE BODY -->
  <section class="section">
    <div class="container">
      <div class="grid items-start gap-12 md:grid-cols-[1.4fr_1fr]">

        <!-- Main content -->
        <div>
          <?php if (!empty($service['text_full_description'])): ?>
            <div class="mb-10">
              <h2 class="service-detail-heading">About This Service</h2>
              <div class="service-detail-text"
                   data-admc-manage="panel_services"
                   data-admc-id="<?= $service['id'] ?>">
                <?= nl2br($service['text_full_description']) ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($service['text_whats_included'])): ?>
            <div class="mb-10">
              <h2 class="service-detail-heading">What's Included</h2>
              <ul class="service-checklist flex flex-col gap-3"
                  data-admc-manage="panel_services"
                  data-admc-id="<?= $service['id'] ?>">
                <?php foreach (preg_split('/\n|\\\\n/', $service['text_whats_included']) as $item):
                  $item = trim($item);
                  if ($item === '') continue;
                ?>
                  <li><i class="fa-solid fa-circle-check"></i> <?= $item ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="flex flex-col gap-5 md:sticky md:top-[100px]">
          <div class="service-quote-card">
            <h3><i class="fa-solid fa-clipboard-list"></i> Get a Quote for This Service</h3>
            <p>Fill in a few details and we'll get back to you within 3 hours.</p>

            <form class="flex flex-col gap-3" data-quote-form>
              <input type="hidden" name="service" value="<?= htmlspecialchars($service['input_title']) ?>">

              <label class="field">
                <span class="field-label">First Name *</span>
                <input type="text" name="first_name" class="field-control field-control-sm" placeholder="Your first name" required>
              </label>
              <label class="field">
                <span class="field-label">Phone Number *</span>
                <input type="tel" name="phone" class="field-control field-control-sm" placeholder="07xxx xxx xxx" required>
              </label>
              <label class="field">
                <span class="field-label">Email</span>
                <input type="email" name="email" class="field-control field-control-sm" placeholder="your@email.com">
              </label>
              <label class="field">
                <span class="field-label">Property Size</span>
                <select name="property_size" class="field-control field-control-sm">
                  <option value="">Select...</option>
                  <?php foreach ($propertySizes as $opt): ?>
                    <option value="<?= htmlspecialchars($opt['input_name']) ?>"><?= htmlspecialchars($opt['input_name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
              <label class="field">
                <span class="field-label">Postcode *</span>
                <input type="text" name="postcode" class="field-control field-control-sm" placeholder="e.g. EH1 1AA" required>
              </label>
              <label class="field">
                <span class="field-label">Notes</span>
                <textarea name="notes" rows="3" class="field-control field-control-sm" placeholder="Any details about your property..."></textarea>
              </label>

              <?= captchaWidget('dark') ?>
              <button type="submit" class="btn btn-primary btn-lg mt-1 w-full justify-center">
                Send Quote Request →
              </button>
            </form>
          </div>

          <div class="service-info-card">
            <h4><i class="fa-solid fa-phone"></i> Prefer to Talk?</h4>
            <p>Call us directly for an instant quote:</p>
            <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="service-phone"><?= htmlspecialchars($site_phone) ?></a>
            <p class="mt-2 !text-[0.82rem]">Mon – Sat · 7am – 7pm</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- OTHER SERVICES -->
  <?php if (count($otherServices) > 0): ?>
    <section class="section bg-off-white">
      <div class="container">
        <div class="section-header text-center">
          <span class="section-label">Explore More</span>
          <h2 class="section-title">Our Other Services</h2>
        </div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
          <?php foreach ($otherServices as $svc): ?>
            <?php include APP_PATH . "/views/includes/partials/service-card.php"; ?>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

</div>
<?php/*##cbcode_20001c##*/?>


<?php/*##cb1c##*/?>
</div>
</div>
</div>

<?php include APP_PATH . "/views/includes/footer.php"; ?>
