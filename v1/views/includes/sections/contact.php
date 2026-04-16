<?php
$contactHeader = selectContent($conn, "settings_home_contact", ["visibility" => "show"])[0];
?>
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
              <input type="text" name="first_name" placeholder="Your first name" required />
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" name="last_name" placeholder="Your last name" />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Phone Number *</label>
              <input type="tel" name="phone" placeholder="07xxx xxx xxx" required />
            </div>
            <div class="form-group">
              <label>Email Address</label>
              <input type="email" name="email" placeholder="your@email.com" />
            </div>
          </div>
          <div class="form-group">
            <label>Service Required *</label>
            <select name="service" required>
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
              <select name="property_size">
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
              <input type="text" name="postcode" placeholder="e.g. EH1 1AA" required />
            </div>
          </div>
          <div class="form-group">
            <label>Additional Notes</label>
            <textarea name="notes" rows="3" placeholder="Anything else we should know? (e.g. furnished, oven clean needed, access details...)"></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-lg form-submit">
            Send My Quote Request →
          </button>
        </form>
      </div>

    </div>
  </div>
</section>
