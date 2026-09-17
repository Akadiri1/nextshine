<?php
$contactHeader = selectContent($conn, "settings_home_contact", ["visibility" => "show"])[0];

// Dropdown options are admin-editable selection_ tables.
$formServiceOptions  = selectContentAsc($conn, "selection_form_services", ["visibility" => "show"], "input_order", 50);
$formPropertyOptions = selectContentAsc($conn, "selection_form_property_sizes", ["visibility" => "show"], "input_order", 50);

// Services with an input_group ("Cleaning Services", "Beauty Services") are
// listed under that heading, in the order each group's first option appears.
$formServiceGroups = [];
foreach ($formServiceOptions as $opt) {
    $formServiceGroups[$opt['input_group'] ?? ''][] = $opt;
}

$contactItems = [
    ['icon' => 'fa-solid fa-phone',        'label' => 'input_phone_label',    'value' => htmlspecialchars($site_phone), 'href' => 'tel:' . htmlspecialchars($site_phone)],
    ['icon' => 'fa-solid fa-envelope',     'label' => 'input_email_label',    'value' => htmlspecialchars($site_email), 'href' => 'mailto:' . htmlspecialchars($site_email)],
    ['icon' => 'fa-solid fa-clock',        'label' => 'input_response_label', 'value' => 'input_response_value',       'href' => null],
    ['icon' => 'fa-solid fa-location-dot', 'label' => 'input_coverage_label', 'value' => 'input_coverage_value',       'href' => null],
];
?>
<section id="contact" class="section section-dark">
  <div class="container">
    <div class="grid grid-cols-1 items-start gap-[72px] md:grid-cols-[1fr_1.2fr]">

      <div>
        <span class="section-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['input_title'] ?></h2>
        <p class="section-subtitle" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader['text_subtitle'] ?></p>

        <div class="mt-8 flex flex-col gap-5">
          <?php foreach ($contactItems as $item): ?>
            <div class="flex items-center gap-3.5">
              <div class="contact-icon"><i class="<?= $item['icon'] ?>"></i></div>
              <div class="flex flex-col">
                <span class="contact-item-label" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader[$item['label']] ?></span>
                <?php if ($item['href']): ?>
                  <a href="<?= $item['href'] ?>" class="contact-item-value"><?= $item['value'] ?></a>
                <?php else: ?>
                  <span class="contact-item-value" data-admc-manage="settings_home_contact" data-admc-id="<?= $contactHeader['id'] ?>"><?= $contactHeader[$item['value']] ?></span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="contact-form-wrap">
        <p class="contact-form-title"><i class="fa-solid fa-clipboard-list"></i> Request a Free Quote</p>

        <form class="flex flex-col gap-4" data-quote-form>
          <div class="grid grid-cols-2 gap-3.5">
            <label class="field">
              <span class="field-label">First Name *</span>
              <input type="text" name="first_name" class="field-control" placeholder="Your first name" required>
            </label>
            <label class="field">
              <span class="field-label">Last Name</span>
              <input type="text" name="last_name" class="field-control" placeholder="Your last name">
            </label>
          </div>

          <div class="grid grid-cols-2 gap-3.5">
            <label class="field">
              <span class="field-label">Phone Number *</span>
              <input type="tel" name="phone" class="field-control" placeholder="07xxx xxx xxx" required>
            </label>
            <label class="field">
              <span class="field-label">Email Address</span>
              <input type="email" name="email" class="field-control" placeholder="your@email.com">
            </label>
          </div>

          <label class="field">
            <span class="field-label">Service Required *</span>
            <select name="service" class="field-control" required>
              <option value="">Select a service...</option>
              <?php foreach ($formServiceGroups as $group => $options): ?>
                <?php if ($group !== ''): ?><optgroup label="<?= htmlspecialchars($group) ?>"><?php endif; ?>
                <?php foreach ($options as $opt): ?>
                  <option value="<?= htmlspecialchars($opt['input_name']) ?>"><?= htmlspecialchars($opt['input_name']) ?></option>
                <?php endforeach; ?>
                <?php if ($group !== ''): ?></optgroup><?php endif; ?>
              <?php endforeach; ?>
            </select>
          </label>

          <div class="grid grid-cols-2 gap-3.5">
            <label class="field">
              <span class="field-label">Property Size</span>
              <select name="property_size" class="field-control">
                <option value="">Select (if applicable)...</option>
                <?php foreach ($formPropertyOptions as $opt): ?>
                  <option value="<?= htmlspecialchars($opt['input_name']) ?>"><?= htmlspecialchars($opt['input_name']) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
            <label class="field">
              <span class="field-label">Postcode *</span>
              <input type="text" name="postcode" class="field-control" placeholder="e.g. EH1 1AA" required>
            </label>
          </div>

          <label class="field">
            <span class="field-label">Additional Notes</span>
            <textarea name="notes" rows="3" class="field-control" placeholder="Details about your property, preferred dates, style preferences, or any questions..."></textarea>
          </label>

          <button type="submit" class="btn btn-primary btn-lg mt-1 w-full justify-center">
            Send My Quote Request →
          </button>
        </form>
      </div>

    </div>
  </div>
</section>
