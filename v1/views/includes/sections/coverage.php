<?php
$coverageHeader = selectContent($conn, "settings_home_coverage", ["visibility" => "show"])[0];
$coverageAreas  = selectContentAsc($conn, "panel_coverage_areas", ["visibility" => "show"], "input_order", 50);

$cityAreas        = array_filter($coverageAreas, function ($area) { return $area['input_group'] === 'city'; });
$surroundingAreas = array_filter($coverageAreas, function ($area) { return $area['input_group'] !== 'city'; });
?>
<section id="coverage" class="section bg-off-white">
  <div class="container">
    <div class="grid grid-cols-1 items-center gap-[60px] md:grid-cols-2">

      <div class="flex flex-col gap-6">
        <span class="section-label" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>"><?= $coverageHeader['input_label'] ?></span>
        <h2 class="section-title" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>"><?= $coverageHeader['input_title'] ?></h2>
        <p class="mb-6 text-[0.95rem] leading-[1.7] text-grey"
           data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
          <?= $coverageHeader['text_description'] ?>
        </p>

        <div>
          <p class="coverage-group-label" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
            <?= $coverageHeader['input_city_label'] ?>
          </p>
          <div class="mt-4 flex flex-wrap gap-2.5" data-admc-tb="panel_coverage_areas">
            <?php foreach ($cityAreas as $area): ?>
              <span class="area-tag primary" data-admc-manage="panel_coverage_areas" data-admc-id="<?= $area['id'] ?>"><?= $area['input_name'] ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mt-5">
          <p class="coverage-group-label" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
            <?= $coverageHeader['input_surrounding_label'] ?>
          </p>
          <div class="mt-4 flex flex-wrap gap-2.5">
            <?php foreach ($surroundingAreas as $area): ?>
              <span class="area-tag" data-admc-manage="panel_coverage_areas" data-admc-id="<?= $area['id'] ?>"><?= $area['input_name'] ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mt-7">
          <p class="text-[0.88rem] text-grey" data-admc-manage="settings_home_coverage" data-admc-id="<?= $coverageHeader['id'] ?>">
            <i class="fa-solid fa-location-dot"></i> <?= $coverageHeader['input_footer_note'] ?>
          </p>
        </div>
      </div>

      <div class="coverage-map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d72033.62044253644!2d-3.2590591!3d55.9532927!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2suk!4v1712800000000"
          width="100%"
          height="100%"
          allowfullscreen
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="NextShine Cleaning Coverage — Edinburgh &amp; Surrounding Areas">
        </iframe>
      </div>

    </div>
  </div>
</section>
