<?php
/**
 * Home: "Two Divisions. One Standard." A card for each NextShine division
 * (panel_home_divisions) with its services (addition_home_division_services).
 * The Beauty card keeps NextShine Beauty's wine and gold.
 */
$divisionsHeader = selectContent($conn, "settings_home_divisions", ["visibility" => "show"])[0] ?? null;
$divisions       = selectContentAsc($conn, "panel_home_divisions", ["visibility" => "show"], "input_order", 6);

// Each division's services, pre-indexed by the division's hash_id.
$divisionServices = [];
foreach (selectContentAsc($conn, "addition_home_division_services", ["visibility" => "show"], "input_order", 100) as $item) {
    $divisionServices[$item['tb_link']][] = $item;
}

if (!$divisionsHeader) {
    return;
}
?>
<section id="services" class="section">
  <div class="container">
    <div class="section-header mx-auto max-w-[680px] text-center">
      <span class="section-label" data-admc-manage="settings_home_divisions" data-admc-id="<?= $divisionsHeader['id'] ?>"><?= $divisionsHeader['input_label'] ?></span>
      <h2 class="section-title" data-admc-manage="settings_home_divisions" data-admc-id="<?= $divisionsHeader['id'] ?>"><?= $divisionsHeader['input_title'] ?></h2>
      <p class="section-subtitle mx-auto" data-admc-manage="settings_home_divisions" data-admc-id="<?= $divisionsHeader['id'] ?>"><?= $divisionsHeader['text_subtitle'] ?></p>
    </div>

    <div class="grid grid-cols-1 gap-7 md:grid-cols-2" data-admc-tb="panel_home_divisions">
      <?php foreach ($divisions as $division): ?>
        <article class="division-card<?= $division['input_theme'] === 'beauty' ? ' division-card-beauty' : '' ?>">
          <div class="division-card-header">
            <span class="division-icon" data-admc-manage="panel_home_divisions" data-admc-id="<?= $division['id'] ?>"><i class="<?= htmlspecialchars($division['input_icon']) ?>" aria-hidden="true"></i></span>
            <h3 class="division-title" data-admc-manage="panel_home_divisions" data-admc-id="<?= $division['id'] ?>"><?= $division['input_title'] ?></h3>
            <p class="division-desc" data-admc-manage="panel_home_divisions" data-admc-id="<?= $division['id'] ?>"><?= $division['text_description'] ?></p>
          </div>
          <div class="division-card-body">
            <ul class="division-services"
                data-admc-tb="addition_home_division_services"
                data-admc-tbadd="panel_home_divisions"
                data-admc-tblink="<?= $division['hash_id'] ?>">
              <?php foreach ($divisionServices[$division['hash_id']] ?? [] as $item): ?>
                <li data-admc-manage="addition_home_division_services" data-admc-id="<?= $item['id'] ?>"><?= $item['input_name'] ?></li>
              <?php endforeach; ?>
            </ul>
            <a href="<?= htmlspecialchars($division['input_link']) ?>" class="btn division-cta" data-admc-manage="panel_home_divisions" data-admc-id="<?= $division['id'] ?>"><?= $division['input_link_text'] ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
