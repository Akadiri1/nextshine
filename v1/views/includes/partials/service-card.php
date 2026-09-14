<?php
/**
 * Service card linking to its detail page.
 *
 * Expects $svc: a panel_services row.
 */
$svcUrl = '/services/' . $svc['hash_id'] . '/' . ($svc['input_slug'] ?: $svc['hash_id']);
?>
<div class="service-card reveal">
  <a href="<?= $svcUrl ?>" class="block">
    <div class="service-card-img"
         style="background: linear-gradient(135deg, <?= htmlspecialchars($svc['bgcolor_card_start']) ?> 0%, <?= htmlspecialchars($svc['bgcolor_card_end']) ?> 100%);"
         data-admc-image="panel_services"
         data-admc-id="<?= $svc['id'] ?>">
      <?php if (!empty($svc['image_1'])): ?>
        <img src="<?= htmlspecialchars($svc['image_1']) ?>" alt="<?= htmlspecialchars($svc['input_title']) ?>">
      <?php else: ?>
        <span><i class="<?= htmlspecialchars($svc['input_icon']) ?>"></i></span>
      <?php endif; ?>

      <?php if (!empty($svc['input_badge'])): ?>
        <span class="service-card-badge" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_badge'] ?></span>
      <?php endif; ?>
    </div>
  </a>

  <div class="p-6">
    <h3 class="service-card-title">
      <a href="<?= $svcUrl ?>" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_title'] ?></a>
    </h3>
    <p class="service-card-desc" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['text_description'] ?></p>
    <div class="flex items-center justify-between">
      <span class="service-price-tag" data-admc-manage="panel_services" data-admc-id="<?= $svc['id'] ?>"><?= $svc['input_price_tag'] ?></span>
      <a class="service-card-link" href="<?= $svcUrl ?>">Learn more →</a>
    </div>
  </div>
</div>
