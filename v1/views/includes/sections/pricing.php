<?php
$pricingHeader = selectContent($conn, "settings_home_pricing", ["visibility" => "show"])[0];
$eotPricing    = selectContentAsc($conn, "panel_pricing_eot", ["visibility" => "show"], "input_order", 20);
$hourlyCards   = selectContentAsc($conn, "panel_pricing_hourly", ["visibility" => "show"], "input_order", 10);

// Feature lists, pre-indexed by the parent card's hash_id.
$featuresRaw     = selectContent($conn, "addition_pricing_features", ["visibility" => "show"]);
$indexedFeatures = [];
foreach ($featuresRaw as $f) {
    $indexedFeatures[$f['tb_link']][] = $f;
}
?>
<section id="pricing" class="section bg-off-white" data-tabs>
  <div class="container">
    <div class="section-header text-center">
      <span class="section-label" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['input_label'] ?></span>
      <h2 class="section-title" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['input_title'] ?></h2>
      <p class="section-subtitle mx-auto" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>"><?= $pricingHeader['text_subtitle'] ?></p>
    </div>

    <!-- Tabs -->
    <div class="pricing-tabs" role="tablist">
      <button type="button" class="pricing-tab is-active" role="tab" aria-selected="true" aria-controls="panel-eot" data-tab="eot"
              data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
        <?= $pricingHeader['input_tab_eot'] ?>
      </button>
      <button type="button" class="pricing-tab" role="tab" aria-selected="false" aria-controls="panel-hourly" data-tab="hourly"
              data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
        <?= $pricingHeader['input_tab_hourly'] ?>
      </button>
    </div>

    <!-- End-of-tenancy (fixed price) -->
    <div class="pricing-panel is-active" id="panel-eot" role="tabpanel" data-tab-panel="eot">
      <div class="eot-table">
        <div class="eot-grid eot-table-header">
          <span class="eot-th">Property Size</span>
          <span class="eot-th">Approx. Duration</span>
          <span class="eot-th">Our Fixed Price</span>
          <span class="eot-th hidden md:block">Edinburgh Market Range</span>
        </div>

        <div data-admc-tb="panel_pricing_eot">
          <?php foreach ($eotPricing as $row): ?>
            <div class="eot-grid eot-row">
              <div>
                <div class="eot-property" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_property'] ?></div>
                <div class="eot-detail" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_detail'] ?></div>
              </div>
              <div class="eot-time" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_duration'] ?></div>
              <div class="eot-price" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_price'] ?></div>
              <div class="eot-market hidden md:block" data-admc-manage="panel_pricing_eot" data-admc-id="<?= $row['id'] ?>"><?= $row['input_market'] ?></div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="eot-note" data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
          <i class="fa-solid fa-lightbulb"></i> <?= $pricingHeader['text_eot_note'] ?>
        </div>
      </div>

      <div class="mt-7 flex flex-wrap gap-4">
        <a href="/contact" class="btn btn-primary btn-lg"><i class="fa-solid fa-clipboard-list"></i> Get My EOT Quote</a>
        <p class="self-center text-[0.85rem] text-grey"
           data-admc-manage="settings_home_pricing" data-admc-id="<?= $pricingHeader['id'] ?>">
          <?= $pricingHeader['input_agent_note'] ?>
        </p>
      </div>
    </div>

    <!-- Regular & commercial (hourly) -->
    <div class="pricing-panel" id="panel-hourly" role="tabpanel" data-tab-panel="hourly">
      <div class="grid grid-cols-1 gap-5 md:grid-cols-3" data-admc-tb="panel_pricing_hourly">
        <?php foreach ($hourlyCards as $card):
          $featured = $card['input_featured'] === 'yes';
        ?>
          <div class="hourly-card reveal<?= $featured ? ' featured' : '' ?>">
            <?php if (!empty($card['input_badge'])): ?>
              <div class="hourly-badge" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_badge'] ?></div>
            <?php endif; ?>

            <div<?= !empty($card['input_badge']) ? ' class="mt-7"' : '' ?>>
              <div class="hourly-icon" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><i class="<?= htmlspecialchars($card['input_icon']) ?>"></i></div>
              <h3 class="hourly-title" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_title'] ?></h3>
              <div class="mb-2 mt-5">
                <div class="hourly-price" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><sup>£</sup><?= $card['input_price'] ?></div>
                <div class="hourly-per" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_per'] ?></div>
              </div>
              <p class="hourly-min" data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>"><?= $card['input_minimum'] ?></p>

              <ul class="mb-6 text-left"
                  data-admc-tb="addition_pricing_features"
                  data-admc-tbadd="panel_pricing_hourly"
                  data-admc-tblink="<?= $card['hash_id'] ?>">
                <?php foreach ($indexedFeatures[$card['hash_id']] ?? [] as $feat): ?>
                  <li class="hourly-feature" data-admc-manage="addition_pricing_features" data-admc-id="<?= $feat['id'] ?>"><?= $feat['input_feature'] ?></li>
                <?php endforeach; ?>
              </ul>

              <a href="<?= htmlspecialchars($card['input_cta_url']) ?>" class="btn <?= $featured ? 'btn-primary' : 'btn-navy' ?> w-full justify-center"
                 data-admc-manage="panel_pricing_hourly" data-admc-id="<?= $card['id'] ?>">
                <?= $card['input_cta_text'] ?>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>
