<?php
$hero = selectContent($conn, "settings_home_hero", ["visibility" => "show"])[0];
$whatsappNumber = $websiteInfo[0]['input_whatsapp_number'] ?? '';
$whatsappUrl = !empty($whatsappNumber) ? 'https://wa.me/' . preg_replace('/\D/', '', $whatsappNumber) : '#';
?>
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
          <a href="<?= $whatsappUrl ?>" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
            <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
          </a>
          <a href="tel:<?= $site_phone ?? '' ?>" class="btn btn-outline btn-lg">
            <i class="fa-solid fa-phone"></i> Call Us Now
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
