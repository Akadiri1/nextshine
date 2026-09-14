<?php
$howHeader = selectContent($conn, "settings_home_how", ["visibility" => "show"])[0];
$steps     = selectContentAsc($conn, "panel_how_steps", ["visibility" => "show"], "input_order", 10);
?>
<section id="how" class="section bg-white">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-label" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['input_label'] ?></span>
      <h2 class="section-title" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['input_title'] ?></h2>
      <p class="section-subtitle mx-auto" data-admc-manage="settings_home_how" data-admc-id="<?= $howHeader['id'] ?>"><?= $howHeader['text_subtitle'] ?></p>
    </div>

    <!-- steps-line draws the connector behind the numbers on wide screens -->
    <div class="steps-line relative grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4" data-admc-tb="panel_how_steps">
      <?php foreach ($steps as $step): ?>
        <div class="reveal relative z-[1] flex flex-col items-center text-center">
          <div class="step-num" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['input_step_number'] ?></div>
          <h3 class="step-title" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['input_title'] ?></h3>
          <p class="step-desc" data-admc-manage="panel_how_steps" data-admc-id="<?= $step['id'] ?>"><?= $step['text_description'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
