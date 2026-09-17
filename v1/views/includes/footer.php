<?php
/**
 * Site footer, the floating contact buttons and scripts.
 */
$footerSettings = selectContent($conn, "settings_home_footer", ["visibility" => "show"])[0];
$footerLinks    = selectContentAsc($conn, "panel_footer_links", ["visibility" => "show"], "input_order", 30);
$footerSocials  = selectContentAsc($conn, "panel_footer_socials", ["visibility" => "show"], "input_order", 10);

// Cleaning Services links straight to each service's detail page. Beauty
// Services lists the Beauty page's styles and hair shop, linking to those
// sections of /beauty.
$footerServices       = selectContentAsc($conn, "panel_services", ["visibility" => "show"], "input_order", 20);
$footerBeautyServices = selectContentAsc($conn, "panel_beauty_services", ["visibility" => "show"], "input_order", 20);
$footerBeautyProducts = selectContentAsc($conn, "panel_beauty_products", ["visibility" => "show"], "input_order", 10);
$footerBeautyTitle    = !empty($footerSettings['input_beauty_services_title']) ? $footerSettings['input_beauty_services_title'] : 'Beauty Services';

$footerCompany = array_filter($footerLinks, function ($link) { return $link['input_group'] === 'company'; });
$footerLegal   = array_filter($footerLinks, function ($link) { return $link['input_group'] === 'legal'; });

$footerWhatsappUrl = !empty($site_whatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $site_whatsapp) : '#';
$jsVersion         = @filemtime(D_PATH . '/www/assets/js/app.js') ?: '1';
?>
  </main>

  <!-- FOOTER -->
  <footer class="bg-navy-dark pb-7 pt-14 text-white/[.65]">
    <div class="container">
      <div class="mb-7 grid grid-cols-1 gap-8 border-b border-white/[.08] pb-10 md:grid-cols-2 md:gap-12 lg:grid-cols-[1.6fr_1fr_1fr_0.8fr_1.2fr] lg:gap-8">

        <div>
          <a href="/">
            <?= logo_lockup('dark', 'h-12 w-auto', $site_name) ?>
          </a>
          <p class="footer-brand-desc"
             data-admc-manage="settings_home_footer"
             data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['text_description'] ?>
          </p>
          <div class="flex gap-2.5" data-admc-tb="panel_footer_socials">
            <?php foreach ($footerSocials as $social): ?>
              <a class="social-btn" href="<?= htmlspecialchars($social['input_link']) ?>" aria-label="<?= htmlspecialchars($social['input_label']) ?>"
                 data-admc-manage="panel_footer_socials"
                 data-admc-id="<?= $social['id'] ?>">
                <i class="<?= htmlspecialchars($social['input_icon']) ?>"></i>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_services_title'] ?>
          </h4>
          <ul class="footer-links flex flex-col gap-[9px]" data-admc-tb="panel_services">
            <?php foreach ($footerServices as $svc): ?>
              <li>
                <a href="/services/<?= $svc['hash_id'] ?>/<?= $svc['input_slug'] ?: $svc['hash_id'] ?>"
                   data-admc-manage="panel_services"
                   data-admc-id="<?= $svc['id'] ?>">
                  <?= $svc['input_title'] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerBeautyTitle ?>
          </h4>
          <ul class="footer-links flex flex-col gap-[9px]">
            <?php foreach ($footerBeautyServices as $svc): ?>
              <li>
                <a href="/beauty#services"
                   data-admc-manage="panel_beauty_services"
                   data-admc-id="<?= $svc['id'] ?>">
                  <?= $svc['input_title'] ?>
                </a>
              </li>
            <?php endforeach; ?>
            <?php foreach ($footerBeautyProducts as $product): ?>
              <li>
                <a href="/beauty#hair"
                   data-admc-manage="panel_beauty_products"
                   data-admc-id="<?= $product['id'] ?>">
                  <?= $product['input_title'] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_company_title'] ?>
          </h4>
          <ul class="footer-links flex flex-col gap-[9px]">
            <?php foreach ($footerCompany as $link): ?>
              <li>
                <a href="<?= htmlspecialchars($link['input_link']) ?>"
                   data-admc-manage="panel_footer_links"
                   data-admc-id="<?= $link['id'] ?>">
                  <?= $link['input_name'] ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_contact_title'] ?>
          </h4>
          <ul class="footer-links flex flex-col gap-[9px]">
            <li><a href="tel:<?= htmlspecialchars($site_phone) ?>"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($site_phone) ?></a></li>
            <li><a href="mailto:<?= htmlspecialchars($site_email) ?>"><i class="fa-solid fa-envelope"></i> <?= htmlspecialchars($site_email) ?></a></li>
            <li><i class="fa-brands fa-whatsapp"></i> <?= $footerSettings['input_whatsapp_text'] ?></li>
            <li><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($site_address ?: 'Edinburgh & Surrounding Areas') ?></li>
            <li class="mt-3 !text-[0.78rem] !text-white/40">
              <?= $footerSettings['input_hours_text'] ?>
            </li>
          </ul>
        </div>

      </div>

      <div class="flex flex-col items-center justify-between gap-3.5 text-center text-[0.8rem] md:flex-row md:gap-0 md:text-left">
        <div>
          <p data-admc-manage="settings_home_footer"
             data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_copyright'] ?>
          </p>
          <?php if (!empty($footerSettings['input_registration_number'])): ?>
            <p class="mt-1 text-[0.75rem] text-white/40"
               data-admc-manage="settings_home_footer"
               data-admc-id="<?= $footerSettings['id'] ?>">
              Company Registration Number: <?= $footerSettings['input_registration_number'] ?>
            </p>
          <?php else: ?>
            <p class="mt-1 text-[0.75rem] text-white/40">
              Company Registration Number: <em>— to be provided —</em>
            </p>
          <?php endif; ?>
        </div>
        <div class="footer-legal flex gap-5">
          <?php foreach ($footerLegal as $link): ?>
            <a href="<?= htmlspecialchars($link['input_link']) ?>"
               data-admc-manage="panel_footer_links"
               data-admc-id="<?= $link['id'] ?>">
              <?= $link['input_name'] ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </footer>

  <!-- WhatsApp bubble (always visible) -->
  <a href="<?= $footerWhatsappUrl ?>" target="_blank" rel="noopener" class="whatsapp-bubble" aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span class="whatsapp-bubble-tooltip">Chat with us</span>
  </a>

  <!-- Call / quote buttons (appear once scrolled) -->
  <div class="float-cta" data-float-cta>
    <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="float-btn float-btn-call">
      <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="/contact" class="float-btn float-btn-primary">
      <i class="fa-solid fa-clipboard-list"></i> Get a Quote
    </a>
  </div>

  <script src="/assets/js/app.js?v=<?= $jsVersion ?>" defer></script>
  <script src="/ajax/ajax.js"></script>

  <?php if (isset($_SESSION['admin_id'])): ?>
    <!-- ADMC live editing, driven by the data-admc-* attributes -->
    <script src="https://admc.dev/admc.min.js" charset="utf-8"></script>
  <?php endif; ?>

</body>
</html>
