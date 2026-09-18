<?php
/**
 * NextShine Beauty: footer and scripts. Expects $beautySite and $beautyHref
 * from the header.
 */
$beautyFooterLinks = array_values(selectContentAsc($conn, "panel_beauty_footer_links", ["visibility" => "show"], "input_order", 10));
$beautyJsVersion   = @filemtime(D_PATH . '/www/assets/js/beauty.js') ?: '1';
?>
  </main>

  <!-- FOOTER -->
  <footer class="beauty-footer">
    <div class="footer-logo">
      <span data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_footer_name'] ?></span>
      <span data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_footer_tagline'] ?></span>
    </div>
    <p class="footer-text">
      <span data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_copyright'] ?></span>
      <?php if ($beautyFooterLinks): ?>
        <br>
        <span data-admc-tb="panel_beauty_footer_links"><?php foreach ($beautyFooterLinks as $i => $link): ?><?= $i ? ' · ' : '' ?><a href="<?= htmlspecialchars($beautyHref($link['input_link'])) ?>" data-admc-manage="panel_beauty_footer_links" data-admc-id="<?= $link['id'] ?>"><?= $link['input_name'] ?></a><?php endforeach; ?></span>
      <?php endif; ?>
    </p>
    <p class="footer-text" data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>">
      <?= $beautySite['input_registration_text'] ?><br>
      Company No. <?= $beautySite['input_company_number'] ?: '[to be added]' ?>
    </p>
  </footer>

  <?= captchaScript() ?>
  <script src="/assets/js/beauty.js?v=<?= $beautyJsVersion ?>" defer></script>
  <script src="/ajax/ajax.js"></script>

  <?php if (isset($_SESSION['admin_id'])): ?>
    <!-- ADMC live editing, driven by the data-admc-* attributes -->
    <script src="https://admc.dev/admc.min.js" charset="utf-8"></script>
  <?php endif; ?>

</body>
</html>
