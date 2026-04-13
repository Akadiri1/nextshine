<?php
$page_title = "Home";
include("includes/header.php");

// ── Fetch all section data ─────────────────────────────────────────────
$hero = selectContent($conn, "settings_home_hero", ["visibility" => "show"])[0];
$trustItems = selectContentAsc($conn, "panel_trust_items", ["visibility" => "show"], "input_order", 20);
$servicesHeader = selectContent($conn, "settings_home_services", ["visibility" => "show"])[0];
$services = selectContentAsc($conn, "panel_services", ["visibility" => "show"], "input_order", 20);
$howHeader = selectContent($conn, "settings_home_how", ["visibility" => "show"])[0];
$steps = selectContentAsc($conn, "panel_how_steps", ["visibility" => "show"], "input_order", 10);
$pricingHeader = selectContent($conn, "settings_home_pricing", ["visibility" => "show"])[0];
$eotPricing = selectContentAsc($conn, "panel_pricing_eot", ["visibility" => "show"], "input_order", 20);
$hourlyCards = selectContentAsc($conn, "panel_pricing_hourly", ["visibility" => "show"], "input_order", 10);
$whyHeader = selectContent($conn, "settings_home_why", ["visibility" => "show"])[0];
$whyCards = selectContentAsc($conn, "panel_why_us", ["visibility" => "show"], "input_order", 20);
$about = selectContent($conn, "settings_home_about", ["visibility" => "show"])[0];
$aboutBullets = selectContentAsc($conn, "panel_about_bullets", ["visibility" => "show"], "input_order", 10);
$coverageHeader = selectContent($conn, "settings_home_coverage", ["visibility" => "show"])[0];
$coverageAreas = selectContentAsc($conn, "panel_coverage_areas", ["visibility" => "show"], "input_order", 50);
$testimonialsHeader = selectContent($conn, "settings_home_testimonials", ["visibility" => "show"])[0];
$testimonials = selectContentAsc($conn, "panel_testimonials", ["visibility" => "show"], "input_order", 20);
$contactHeader = selectContent($conn, "settings_home_contact", ["visibility" => "show"])[0];

// ── Pre-index: pricing features by parent hash_id ──────────────────────
$featuresRaw = selectContent($conn, "addition_pricing_features", ["visibility" => "show"]);
$indexedFeatures = [];
foreach ($featuresRaw as $f) {
    $indexedFeatures[$f['tb_link']][] = $f;
}

// ── Pre-index: coverage areas by group ─────────────────────────────────
$cityAreas = [];
$surroundingAreas = [];
foreach ($coverageAreas as $area) {
    if ($area['input_group'] === 'city') {
        $cityAreas[] = $area;
    } else {
        $surroundingAreas[] = $area;
    }
}
?>

<div class="template-home-wrapper">
<div class="page-content-home-page">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>


<!-- ═══════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10001o##*/?>
<div data-cbcodesection="cbcode_10001">
  <section id="hero" <?php if (!empty($hero['image_1'])) { ?>style="background-image: linear-gradient(135deg, rgba(26,51,92,0.92) 0%, rgba(0,135,138,0.80) 100%), url('<?= $hero['image_1'] ?>');" class="has-image"<?php } ?>>
    <div class="container">
      <div class="hero-content">
        <!-- Left: Headline & CTA -->
        <div>
          <div class="hero-badge"
               data-admc-manage="settings_home_hero"
               data-admc-id="<?= $hero['id'] ?>">
            <span class="hero-badge-dot"></span>
            <?= $hero['input_badge_text'] ?>
          </div>

          <h1 class="hero-headline">
            <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_1'] ?></span><br/>
            <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><span><?= $hero['input_headline_2'] ?></span></span><br/>
            <span data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_headline_3'] ?></span>
          </h1>

          <p class="hero-desc"
             data-admc-manage="settings_home_hero"
             data-admc-id="<?= $hero['id'] ?>">
            <?= $hero['text_description'] ?>
          </p>

          <div class="hero-actions">
            <a href="#contact" class="btn btn-primary btn-lg">
              <i class="fa-solid fa-clipboard-list"></i> <?= $hero['input_cta_primary'] ?>
            </a>
            <a href="tel:<?= $site_phone ?? '' ?>" class="btn btn-outline btn-lg">
              <i class="fa-solid fa-phone"></i> <?= $hero['input_cta_secondary'] ?>
            </a>
          </div>

          <div class="hero-stats">
            <div class="hero-stat-item">
              <span class="hero-stat-num" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_1_value'] ?></span>
              <span class="hero-stat-label" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_1_label'] ?></span>
            </div>
            <div class="hero-stat-item">
              <span class="hero-stat-num" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_2_value'] ?></span>
              <span class="hero-stat-label" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_2_label'] ?></span>
            </div>
            <div class="hero-stat-item">
              <span class="hero-stat-num" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_3_value'] ?></span>
              <span class="hero-stat-label" data-admc-manage="settings_home_hero" data-admc-id="<?= $hero['id'] ?>"><?= $hero['input_stat_3_label'] ?></span>
            </div>
          </div>
        </div>

        <!-- Right: Quick Quote Card (form — NOT editable) -->
        <div class="hero-card">
          <p class="hero-card-title"
             data-admc-manage="settings_home_hero"
             data-admc-id="<?= $hero['id'] ?>">
            <i class="fa-solid fa-bolt"></i> <?= $hero['input_card_title'] ?>
          </p>
          <form class="quote-form">
            <div class="form-row">
              <div class="form-group">
                <label>First Name</label>
                <input type="text" placeholder="e.g. James" />
              </div>
              <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" placeholder="07xxx xxx xxx" />
              </div>
            </div>
            <div class="form-group">
              <label>Service Needed</label>
              <select>
                <option value="">Select a service...</option>
                <option>End-of-Tenancy Clean (Fixed Price)</option>
                <option>Regular Domestic Cleaning</option>
                <option>Commercial / Office Clean</option>
                <option>Deep Clean (One-off)</option>
                <option>Post-Construction Clean</option>
                <option>Move-In Welcome Clean</option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Property Size</label>
                <select>
                  <option value="">Select...</option>
                  <option>Studio / Bedsit</option>
                  <option>1 Bedroom</option>
                  <option>2 Bedrooms</option>
                  <option>3 Bedrooms</option>
                  <option>4+ Bedrooms</option>
                  <option>Office / Commercial</option>
                </select>
              </div>
              <div class="form-group">
                <label>Postcode</label>
                <input type="text" placeholder="e.g. EH1 1AA" />
              </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
              Get My Quote →
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10001c##*/?>


<!-- ═══════════════════════════════════════════════════
     TRUST STRIP
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10002o##*/?>
<div data-cbcodesection="cbcode_10002">
  <div id="trust">
    <div class="container">
      <div class="trust-items" data-admc-tb="panel_trust_items">
        <?php foreach ($trustItems as $item) { ?>
          <div class="trust-item">
            <span class="trust-icon" data-admc-manage="panel_trust_items" data-admc-id="<?= $item['id'] ?>"><i class="<?= $item['input_icon'] ?>"></i></span>
            <span class="trust-text" data-admc-manage="panel_trust_items" data-admc-id="<?= $item['id'] ?>"><?= $item['input_text'] ?></span>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<?php/*##cbcode_10002c##*/?>


<!-- ═══════════════════════════════════════════════════
     SERVICES
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10003o##*/?>
<div data-cbcodesection="cbcode_10003">
  <section id="services" class="section">
    <div class="container">
      <div class="section-header">
        <span class="section-label" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['text_subtitle'] ?></p>
      </div>

      <div class="services-grid" data-admc-tb="panel_services">
        <?php foreach ($services as $svc) { ?>
          <?php $svcUrl = '/services/' . $svc['hash_id'] . '/' . ($svc['input_slug'] ?? $svc['hash_id']); ?>
          <div class="service-card">
            <a href="<?= $svcUrl ?>" style="display:block;">
              <div class="service-card-img"
                   style="background: linear-gradient(135deg, <?= $svc['bgcolor_card_start'] ?> 0%, <?= $svc['bgcolor_card_end'] ?> 100%);"
                   data-admc-image="panel_services"
                   data-admc-id="<?= $svc['id'] ?>">
                <?php if (!empty($svc['image_1'])) { ?>
                  <img src="<?= $svc['image_1'] ?>" alt="<?= $svc['input_title'] ?>" style="width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;" />
                <?php } else { ?>
                  <span><i class="<?= $svc['input_icon'] ?>"></i></span>
                <?php } ?>
                <?php if (!empty($svc['input_badge'])) { ?>
                  <span class="service-card-badge" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_badge'] ?></span>
                <?php } ?>
              </div>
            </a>
            <div class="service-card-body">
              <h3 class="service-card-title"><a href="<?= $svcUrl ?>" style="color:inherit;" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_title'] ?></a></h3>
              <p class="service-card-desc" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['text_description'] ?></p>
              <div class="service-card-price">
                <span class="service-price-tag" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_price_tag'] ?></span>
                <a class="service-card-link" href="<?= $svcUrl ?>">Learn more →</a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10003c##*/?>


<!-- ═══════════════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10004o##*/?>
<div data-cbcodesection="cbcode_10004">
  <section id="how" class="section">
    <div class="container">
      <div class="section-header center">
        <span class="section-label" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['text_subtitle'] ?></p>
      </div>

      <div class="steps-grid" data-admc-tb="panel_how_steps">
        <?php foreach ($steps as $step) { ?>
          <div class="step-item">
            <div class="step-num" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['input_step_number'] ?></div>
            <h3 class="step-title" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['input_title'] ?></h3>
            <p class="step-desc" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['text_description'] ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10004c##*/?>


<!-- ═══════════════════════════════════════════════════
     PRICING
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10005o##*/?>
<div data-cbcodesection="cbcode_10005">
  <section id="pricing" class="section">
    <div class="container">
      <div class="section-header">
        <span class="section-label" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['text_subtitle'] ?></p>
      </div>

      <!-- Tabs -->
      <div class="pricing-tabs">
        <button class="pricing-tab active" onclick="switchTab('eot', this)"
                data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
          <?= $pricingHeader['input_tab_eot'] ?>
        </button>
        <button class="pricing-tab" onclick="switchTab('hourly', this)"
                data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
          <?= $pricingHeader['input_tab_hourly'] ?>
        </button>
      </div>

      <!-- EOT Panel -->
      <div class="pricing-panel active" id="panel-eot">
        <div class="eot-table">
          <div class="eot-table-header">
            <span class="eot-th">Property Size</span>
            <span class="eot-th">Approx. Duration</span>
            <span class="eot-th">Our Fixed Price</span>
            <span class="eot-th">Edinburgh Market Range</span>
          </div>

          <div data-admc-tb="panel_pricing_eot">
            <?php foreach ($eotPricing as $row) { ?>
              <div class="eot-row">
                <div>
                  <div class="eot-property" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_property'] ?></div>
                  <div class="eot-detail" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_detail'] ?></div>
                </div>
                <div class="eot-time" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_duration'] ?></div>
                <div class="eot-price" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_price'] ?></div>
                <div class="eot-market" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_market'] ?></div>
              </div>
            <?php } ?>
          </div>

          <div class="eot-note" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
            <i class="fa-solid fa-lightbulb"></i> <?= $pricingHeader['text_eot_note'] ?>
          </div>
        </div>

        <div style="margin-top:28px; display:flex; gap:16px; flex-wrap:wrap;">
          <a href="#contact" class="btn btn-primary btn-lg"><i class="fa-solid fa-clipboard-list"></i> Get My EOT Quote</a>
          <p style="font-size:0.85rem;color:var(--grey);align-self:center;"
             data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
            <?= $pricingHeader['input_agent_note'] ?>
          </p>
        </div>
      </div>

      <!-- Hourly Panel -->
      <div class="pricing-panel" id="panel-hourly">
        <div class="hourly-grid" data-admc-tb="panel_pricing_hourly">
          <?php foreach ($hourlyCards as $card) { ?>
            <div class="hourly-card <?= $card['input_featured'] === 'yes' ? 'featured' : '' ?>">
              <?php if (!empty($card['input_badge'])) { ?>
                <div class="hourly-badge" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_badge'] ?></div>
              <?php } ?>
              <div <?php if (!empty($card['input_badge'])) { ?>style="margin-top:28px;"<?php } ?>>
                <div class="hourly-icon" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><i class="<?= $card['input_icon'] ?>"></i></div>
                <h3 class="hourly-title" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_title'] ?></h3>
                <div class="hourly-price-wrap">
                  <div class="hourly-price" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><sup>£</sup><?= $card['input_price'] ?></div>
                  <div class="hourly-per" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_per'] ?></div>
                </div>
                <p class="hourly-min" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_minimum'] ?></p>
                <ul class="hourly-features"
                    data-admc-tb="addition_pricing_features"
                    data-admc-tbadd="panel_pricing_hourly"
                    data-admc-tblink="<?= $card['hash_id'] ?>">
                  <?php if (isset($indexedFeatures[$card['hash_id']])) { ?>
                    <?php foreach ($indexedFeatures[$card['hash_id']] as $feat) { ?>
                      <li class="hourly-feature" data-admc-manage="addition_pricing_features" data-admc-id="<?= $feat['id'] ?>"><?= $feat['input_feature'] ?></li>
                    <?php } ?>
                  <?php } ?>
                </ul>
                <a href="<?= $card['input_cta_url'] ?>" class="btn <?= $card['input_featured'] === 'yes' ? 'btn-primary' : 'btn-navy' ?>" style="width:100%;justify-content:center;"
                   data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>">
                  <?= $card['input_cta_text'] ?>
                </a>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>

    </div>
  </section>
</div>
<?php/*##cbcode_10005c##*/?>


<!-- ═══════════════════════════════════════════════════
     WHY CHOOSE US
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10006o##*/?>
<div data-cbcodesection="cbcode_10006">
  <section id="why" class="section">
    <div class="container">
      <div class="section-header center">
        <span class="section-label" style="color:var(--teal-light)" data-admc-manage="settings_home_why" data-admc-id="<?= $whyHeader['id'] ?>"><?= $whyHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_why" data-admc-id="<?= $whyHeader['id'] ?>"><?= $whyHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_why" data-admc-id="<?= $whyHeader['id'] ?>"><?= $whyHeader['text_subtitle'] ?></p>
      </div>

      <div class="why-grid" data-admc-tb="panel_why_us">
        <?php foreach ($whyCards as $card) { ?>
          <div class="why-card">
            <div class="why-icon" data-admc-manage="panel_why_us" data-admc-id="<?= $card['id'] ?>"><i class="<?= $card['input_icon'] ?>"></i></div>
            <h3 class="why-title" data-admc-manage="panel_why_us" data-admc-id="<?= $card['id'] ?>"><?= $card['input_title'] ?></h3>
            <p class="why-desc" data-admc-manage="panel_why_us" data-admc-id="<?= $card['id'] ?>"><?= $card['text_description'] ?></p>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10006c##*/?>


<!-- ═══════════════════════════════════════════════════
     ABOUT
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10007o##*/?>
<div data-cbcodesection="cbcode_10007">
  <section id="about" class="section">
    <div class="container">
      <div class="about-grid">

        <div class="about-img-wrap">
          <div class="about-img" data-admc-image="settings_home_about" data-admc-id="<?= $about['id'] ?>">
            <?php if (!empty($about['image_1'])) { ?>
              <img src="<?= $about['image_1'] ?>" alt="About NextShine" />
            <?php } else { ?>
              <span class="about-img-icon"><i class="fa-solid fa-people-group"></i></span>
              <p class="about-img-placeholder"><i class="fa-solid fa-camera"></i> Replace with a professional photo</p>
            <?php } ?>
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
            <strong style="display:block;margin-top:8px;color:var(--teal);"
                    data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
              <?= $about['input_quote_author'] ?>
            </strong>
          </div>

          <p style="font-size:0.95rem;color:var(--grey-dark);line-height:1.75;margin-bottom:20px;"
             data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
            <?= $about['text_description'] ?>
          </p>

          <div class="about-bullets" data-admc-tb="panel_about_bullets">
            <?php foreach ($aboutBullets as $bullet) { ?>
              <div class="about-bullet">
                <div class="about-bullet-check">✓</div>
                <span data-admc-manage="panel_about_bullets" data-admc-id="<?= $bullet['id'] ?>"><?= $bullet['input_text'] ?></span>
              </div>
            <?php } ?>
          </div>

          <div style="display:flex;gap:14px;flex-wrap:wrap;">
            <a href="<?= $about['input_cta_primary_url'] ?>" class="btn btn-primary"
               data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
              <?= $about['input_cta_primary_text'] ?>
            </a>
            <a href="<?= $about['input_cta_secondary_url'] ?>" class="btn btn-outline" style="color:var(--navy);border-color:var(--navy);"
               data-admc-manage="settings_home_about" data-admc-id="<?= $about['id'] ?>">
              <?= $about['input_cta_secondary_text'] ?>
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10007c##*/?>


<!-- ═══════════════════════════════════════════════════
     COVERAGE
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10008o##*/?>
<div data-cbcodesection="cbcode_10008">
  <section id="coverage" class="section">
    <div class="container">
      <div class="coverage-grid">

        <div class="coverage-areas">
          <span class="section-label" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>"><?= $coverageHeader['input_label'] ?></span>
          <h2 class="section-title" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>"><?= $coverageHeader['input_title'] ?></h2>
          <p style="font-size:0.95rem;color:var(--grey);line-height:1.7;margin-bottom:24px;"
             data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
            <?= $coverageHeader['text_description'] ?>
          </p>

          <div>
            <p style="font-size:0.82rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;"
               data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
              <?= $coverageHeader['input_city_label'] ?>
            </p>
            <div class="area-tags" data-admc-tb="panel_coverage_areas">
              <?php foreach ($cityAreas as $area) { ?>
                <span class="area-tag primary" data-admc-manage="panel_coverage_areas" data-admc-id="<?= $area['id'] ?>"><?= $area['input_name'] ?></span>
              <?php } ?>
            </div>
          </div>
          <div style="margin-top:20px;">
            <p style="font-size:0.82rem;font-weight:700;color:var(--navy);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;"
               data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
              <?= $coverageHeader['input_surrounding_label'] ?>
            </p>
            <div class="area-tags">
              <?php foreach ($surroundingAreas as $area) { ?>
                <span class="area-tag" data-admc-manage="panel_coverage_areas" data-admc-id="<?= $area['id'] ?>"><?= $area['input_name'] ?></span>
              <?php } ?>
            </div>
          </div>
          <div style="margin-top:28px;">
            <p style="font-size:0.88rem;color:var(--grey);"
               data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
              <i class="fa-solid fa-location-dot"></i> <?= $coverageHeader['input_footer_note'] ?>
            </p>
          </div>
        </div>

        <div class="coverage-map">
          <div class="coverage-map-pin"></div>
          <p class="map-placeholder-text">
            <i class="fa-solid fa-map-location-dot"></i> Replace with an embedded Google Maps iframe<br/>centred on Edinburgh
          </p>
        </div>

      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10008c##*/?>


<!-- ═══════════════════════════════════════════════════
     TESTIMONIALS
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10009o##*/?>
<div data-cbcodesection="cbcode_10009">
  <section id="testimonials" class="section">
    <div class="container">
      <div class="section-header center">
        <span class="section-label" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['text_subtitle'] ?></p>
      </div>

      <div class="reviews-grid" data-admc-tb="panel_testimonials">
        <?php foreach ($testimonials as $review) { ?>
          <div class="review-card">
            <div class="review-stars">
              <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
            </div>
            <p class="review-text" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
              "<?= $review['text_review'] ?>"
            </p>
            <div class="review-author">
              <?php if (!empty($review['image_1'])) { ?>
                <div data-admc-image="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
                  <img src="<?= $review['image_1'] ?>" alt="<?= $review['input_author_name'] ?>" class="review-avatar" style="object-fit:cover;" />
                </div>
              <?php } else { ?>
                <div class="review-avatar" style="background:<?= $review['bgcolor_avatar'] ?>;"
                     data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>">
                  <?= $review['input_author_initials'] ?>
                </div>
              <?php } ?>
              <div>
                <div class="review-name" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><?= $review['input_author_name'] ?></div>
                <div class="review-role" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><?= $review['input_author_role'] ?></div>
              </div>
            </div>
            <div class="review-source" data-admc-manage="panel_testimonials" data-admc-id="<?= $review['id'] ?>"><i class="fa-solid fa-star"></i> <?= $review['input_source'] ?></div>
          </div>
        <?php } ?>
      </div>

      <div class="reviews-cta">
        <div class="google-rating">
          <div class="google-g">G</div>
          <div>
            <div style="display:flex;align-items:center;gap:6px;">
              <span class="google-score" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_google_score'] ?></span>
              <span style="color:#f59e0b;font-size:0.9rem;">★★★★★</span>
            </div>
            <div class="google-text" data-admc-manage="settings_home_testimonials" data-admc-id="<?= $testimonialsHeader['id'] ?>"><?= $testimonialsHeader['input_google_text'] ?></div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
<?php/*##cbcode_10009c##*/?>


<!-- ═══════════════════════════════════════════════════
     CONTACT / QUOTE (form NOT editable)
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10010o##*/?>
<div data-cbcodesection="cbcode_10010">
  <section id="contact" class="section">
    <div class="container">
      <div class="contact-grid">

        <div>
          <span class="section-label" style="color:var(--teal-light)" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_label'] ?></span>
          <h2 class="section-title" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_title'] ?></h2>
          <p class="section-subtitle" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['text_subtitle'] ?></p>

          <div class="contact-info">
            <div class="contact-item">
              <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
              <div class="contact-item-text">
                <span class="contact-item-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_phone_label'] ?></span>
                <span class="contact-item-value"><?= $site_phone ?? '' ?></span>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
              <div class="contact-item-text">
                <span class="contact-item-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_email_label'] ?></span>
                <span class="contact-item-value"><?= $site_email ?? '' ?></span>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fa-solid fa-clock"></i></div>
              <div class="contact-item-text">
                <span class="contact-item-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_response_label'] ?></span>
                <span class="contact-item-value" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_response_value'] ?></span>
              </div>
            </div>
            <div class="contact-item">
              <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
              <div class="contact-item-text">
                <span class="contact-item-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_coverage_label'] ?></span>
                <span class="contact-item-value" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_coverage_value'] ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="contact-form-wrap">
          <p class="contact-form-title"><i class="fa-solid fa-clipboard-list"></i> Request a Free Quote</p>
          <form class="contact-form" onsubmit="submitForm(event)">
            <div class="form-row">
              <div class="form-group">
                <label>First Name *</label>
                <input type="text" placeholder="Your first name" required />
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" placeholder="Your last name" />
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" placeholder="07xxx xxx xxx" required />
              </div>
              <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="your@email.com" />
              </div>
            </div>
            <div class="form-group">
              <label>Service Required *</label>
              <select required>
                <option value="">Select a service...</option>
                <option>End-of-Tenancy Clean (Fixed Price)</option>
                <option>Regular Domestic Cleaning (Hourly)</option>
                <option>Commercial / Office Cleaning</option>
                <option>One-Off Deep Clean</option>
                <option>Post-Construction / Renovation Clean</option>
                <option>AirBnB / Short-Let Turnover</option>
                <option>Void Period Maintenance Clean</option>
              </select>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Property Size</label>
                <select>
                  <option value="">Select...</option>
                  <option>Studio / Bedsit</option>
                  <option>1 Bedroom</option>
                  <option>2 Bedrooms</option>
                  <option>3 Bedrooms</option>
                  <option>4+ Bedrooms</option>
                  <option>Office / Commercial</option>
                </select>
              </div>
              <div class="form-group">
                <label>Postcode *</label>
                <input type="text" placeholder="e.g. EH1 1AA" required />
              </div>
            </div>
            <div class="form-group">
              <label>Additional Notes</label>
              <textarea rows="3" placeholder="Anything else we should know? (e.g. furnished, oven clean needed, access details...)"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg form-submit">
              Send My Quote Request →
            </button>
          </form>
        </div>

      </div>
    </div>
  </section>
</div>
<?php/*##cbcode_10010c##*/?>


<?php/*##cb1c##*/?>
</div>
</div>
</div>

<?php include("includes/footer.php"); ?>