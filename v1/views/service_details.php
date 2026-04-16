<?php
$serviceId = $uri[2] ?? '';
$allServices = selectContent($conn, "panel_services", ["visibility" => "show"]);

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

$page_title = $service['input_title'];
$metaTitle = !empty($service['input_meta_title'] ?? '') ? $service['input_meta_title'] : $service['input_title'] . ' | ' . ($site_name ?? '');
$metaDescription = !empty($service['text_meta_description'] ?? '') ? $service['text_meta_description'] : $service['text_description'];

include("includes/header.php");

$otherServices = [];
foreach ($allServices as $s) {
    if ($s['hash_id'] !== $service['hash_id']) {
        $otherServices[] = $s;
    }
}
?>

<div class="template-home-wrapper">
<div class="page-content-home-page">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>


<?php/*##cbcode_20001o##*/?>
<div data-cbcodesection="cbcode_20001">

  <!-- SERVICE HERO -->
  <section class="service-detail-hero" style="background: linear-gradient(135deg, <?= $service['bgcolor_card_start'] ?: 'var(--navy)' ?> 0%, <?= $service['bgcolor_card_end'] ?: 'var(--teal)' ?> 100%);">
    <div class="container">
      <div class="service-detail-hero-inner">
        <div>
          <?php if (!empty($service['input_badge'])) { ?>
            <span class="service-detail-badge"><?= $service['input_badge'] ?></span>
          <?php } ?>
          <h1 class="service-detail-title"
              data-admc-manage="panel_services"
              data-admc-id="<?= $service['id'] ?>">
            <?= $service['input_title'] ?>
          </h1>
          <p class="service-detail-intro"
             data-admc-manage="panel_services"
             data-admc-id="<?= $service['id'] ?>">
            <?= $service['text_description'] ?>
          </p>
          <div class="service-detail-meta">
            <?php if (!empty($service['input_starting_price'] ?? '')) { ?>
              <div class="service-detail-meta-item">
                <i class="fa-solid fa-tag"></i>
                <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= $service['input_starting_price'] ?></span>
              </div>
            <?php } ?>
            <?php if (!empty($service['input_duration'] ?? '')) { ?>
              <div class="service-detail-meta-item">
                <i class="fa-solid fa-clock"></i>
                <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= $service['input_duration'] ?></span>
              </div>
            <?php } ?>
          </div>
          <div class="service-detail-actions">
            <a href="<?= ($service['input_cta_url'] ?? '') ?: '#contact' ?>" class="btn btn-primary btn-lg">
              <i class="fa-solid fa-clipboard-list"></i>
              <span data-admc-manage="panel_services" data-admc-id="<?= $service['id'] ?>"><?= ($service['input_cta_text'] ?? '') ?: 'Get a Free Quote' ?></span>
            </a>
            <a href="tel:<?= $site_phone ?? '' ?>" class="btn btn-outline btn-lg">
              <i class="fa-solid fa-phone"></i> Call Us Now
            </a>
          </div>
        </div>
        <div class="service-detail-hero-img"
             data-admc-image="panel_services"
             data-admc-id="<?= $service['id'] ?>">
          <?php if (!empty($service['image_1'])) { ?>
            <img src="<?= $service['image_1'] ?>" alt="<?= $service['input_title'] ?>" />
          <?php } else { ?>
            <div class="service-detail-hero-icon">
              <i class="<?= $service['input_icon'] ?>"></i>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

  <!-- SERVICE BODY -->
  <section class="section service-detail-body">
    <div class="container">
      <div class="service-detail-grid">

        <!-- Main content -->
        <div>
          <?php if (!empty($service['text_full_description'] ?? '')) { ?>
            <div class="service-detail-section">
              <h2 class="section-title">About This Service</h2>
              <div class="service-detail-text"
                   data-admc-manage="panel_services"
                   data-admc-id="<?= $service['id'] ?>">
                <?= nl2br($service['text_full_description']) ?>
              </div>
            </div>
          <?php } ?>

          <?php if (!empty($service['text_whats_included'] ?? '')) { ?>
            <div class="service-detail-section">
              <h2 class="section-title">What's Included</h2>
              <ul class="service-detail-checklist"
                  data-admc-manage="panel_services"
                  data-admc-id="<?= $service['id'] ?>">
                <?php
                $items = preg_split('/\n|\\\\n/', $service['text_whats_included']);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (!empty($item)) { ?>
                      <li><i class="fa-solid fa-circle-check"></i> <?= $item ?></li>
                <?php }
                } ?>
              </ul>
            </div>
          <?php } ?>
        </div>

        <!-- Sidebar -->
        <div class="service-detail-sidebar">
          <div class="service-detail-quote-card">
            <h3><i class="fa-solid fa-clipboard-list"></i> Get a Quote for This Service</h3>
            <p>Fill in a few details and we'll get back to you within 3 hours.</p>
            <form class="contact-form" onsubmit="submitForm(event)">
              <div class="form-group">
                <label>First Name *</label>
                <input type="text" placeholder="Your first name" required />
              </div>
              <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" placeholder="07xxx xxx xxx" required />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="your@email.com" />
              </div>
              <input type="hidden" value="<?= $service['input_title'] ?>" />
              <div class="form-group">
                <label>Property Size</label>
                <select name="property_size">
                  <option value="">Select...</option>
                  <?php
                  $svcFormPropertyOptions = selectContentAsc($conn, "selection_form_property_sizes", ["visibility" => "show"], "input_order", 50);
                  foreach ($svcFormPropertyOptions as $opt) { ?>
                    <option value="<?= $opt['input_name'] ?>"><?= $opt['input_name'] ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label>Postcode *</label>
                <input type="text" placeholder="e.g. EH1 1AA" required />
              </div>
              <div class="form-group">
                <label>Notes</label>
                <textarea rows="3" placeholder="Any details about your property..."></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-lg form-submit" style="width:100%;justify-content:center;">
                Send Quote Request →
              </button>
            </form>
          </div>

          <div class="service-detail-info-card">
            <h4><i class="fa-solid fa-phone"></i> Prefer to Talk?</h4>
            <p>Call us directly for an instant quote:</p>
            <a href="tel:<?= $site_phone ?? '' ?>" class="service-detail-phone"><?= $site_phone ?? '' ?></a>
            <p style="font-size:0.82rem;color:var(--grey);margin-top:8px;">Mon – Sat · 7am – 7pm</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- OTHER SERVICES -->
  <?php if (count($otherServices) > 0) { ?>
    <section class="section" style="background:var(--off-white);">
      <div class="container">
        <div class="section-header center">
          <span class="section-label">Explore More</span>
          <h2 class="section-title">Our Other Services</h2>
        </div>
        <div class="services-grid">
          <?php foreach ($otherServices as $svc) { ?>
            <?php $svcDetailUrl = '/services/' . $svc['hash_id'] . '/' . ($svc['input_slug'] ?? $svc['hash_id']); ?>
            <div class="service-card">
              <a href="<?= $svcDetailUrl ?>" style="display:block;">
                <div class="service-card-img" style="background: linear-gradient(135deg, <?= $svc['bgcolor_card_start'] ?> 0%, <?= $svc['bgcolor_card_end'] ?> 100%);">
                  <?php if (!empty($svc['image_1'])) { ?>
                    <img src="<?= $svc['image_1'] ?>" alt="<?= $svc['input_title'] ?>" style="width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;" />
                  <?php } else { ?>
                    <span><i class="<?= $svc['input_icon'] ?>"></i></span>
                  <?php } ?>
                  <?php if (!empty($svc['input_badge'])) { ?>
                    <span class="service-card-badge"><?= $svc['input_badge'] ?></span>
                  <?php } ?>
                </div>
              </a>
              <div class="service-card-body">
                <h3 class="service-card-title"><a href="<?= $svcDetailUrl ?>" style="color:inherit;"><?= $svc['input_title'] ?></a></h3>
                <p class="service-card-desc"><?= $svc['text_description'] ?></p>
                <div class="service-card-price">
                  <span class="service-price-tag"><?= $svc['input_price_tag'] ?></span>
                  <a class="service-card-link" href="<?= $svcDetailUrl ?>">Learn more →</a>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </section>
  <?php } ?>

</div>
<?php/*##cbcode_20001c##*/?>


<?php/*##cb1c##*/?>
</div>
</div>
</div>

<?php include("includes/footer.php"); ?>
