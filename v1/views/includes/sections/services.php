<?php
$servicesHeader = selectContent($conn, "settings_home_services", ["visibility" => "show"])[0];
$services       = selectContentAsc($conn, "panel_services", ["visibility" => "show"], "input_order", 20);
?>
<?php // Less top padding than other sections: the page hero's white curve already separates them. ?>
<section id="services" class="section bg-off-white pt-8 md:pt-10">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-label" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_label'] ?></span>
      <h2 class="section-title" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['input_title'] ?></h2>
      <p class="section-subtitle mx-auto" data-admc-manage="settings_home_services" data-admc-id="<?= $servicesHeader['id'] ?>"><?= $servicesHeader['text_subtitle'] ?></p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" data-admc-tb="panel_services">
      <?php foreach ($services as $svc): ?>
        <?php include APP_PATH . "/views/includes/partials/service-card.php"; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>
