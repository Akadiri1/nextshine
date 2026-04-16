<?php
$footerSettings = selectContent($conn, "settings_home_footer", ["visibility" => "show"])[0];
$footerLinksAll = selectContentAsc($conn, "panel_footer_links", ["visibility" => "show"], "input_order", 30);
$footerSocials = selectContentAsc($conn, "panel_footer_socials", ["visibility" => "show"], "input_order", 10);

$footerServices = [];
$footerCompany = [];
$footerLegal = [];
foreach ($footerLinksAll as $fl) {
    switch ($fl['input_group']) {
        case 'services': $footerServices[] = $fl; break;
        case 'company':  $footerCompany[] = $fl; break;
        case 'legal':    $footerLegal[] = $fl; break;
    }
}
?>

  <!-- FOOTER -->
  <footer>
    <div class="container">
      <div class="footer-top">

        <div>
          <a href="/" class="footer-logo">
            <img src="/uploads/nsg-logo-on-dark-bg.png" alt="<?= $site_name ?? 'NextShine Cleaning' ?>" style="height:48px;width:auto;" />
          </a>
          <p class="footer-brand-desc"
             data-admc-manage="settings_home_footer"
             data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['text_description'] ?>
          </p>
          <div class="footer-socials" data-admc-tb="panel_footer_socials">
            <?php foreach ($footerSocials as $social) { ?>
              <a class="social-btn" href="<?= $social['input_link'] ?>" aria-label="<?= $social['input_label'] ?>"
                 data-admc-manage="panel_footer_socials"
                 data-admc-id="<?= $social['id'] ?>">
                <i class="<?= $social['input_icon'] ?>"></i>
              </a>
            <?php } ?>
          </div>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_services_title'] ?>
          </h4>
          <ul class="footer-links" data-admc-tb="panel_footer_links">
            <?php foreach ($footerServices as $link) { ?>
              <li>
                <a href="<?= $link['input_link'] ?>"
                   data-admc-manage="panel_footer_links"
                   data-admc-id="<?= $link['id'] ?>">
                  <?= $link['input_name'] ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_company_title'] ?>
          </h4>
          <ul class="footer-links">
            <?php foreach ($footerCompany as $link) { ?>
              <li>
                <a href="<?= $link['input_link'] ?>"
                   data-admc-manage="panel_footer_links"
                   data-admc-id="<?= $link['id'] ?>">
                  <?= $link['input_name'] ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title"
              data-admc-manage="settings_home_footer"
              data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_contact_title'] ?>
          </h4>
          <ul class="footer-links">
            <li><i class="fa-solid fa-phone"></i> <?= $site_phone ?? '' ?></li>
            <li><i class="fa-solid fa-envelope"></i> <?= $site_email ?? '' ?></li>
            <li><i class="fa-brands fa-whatsapp"></i> <?= $footerSettings['input_whatsapp_text'] ?></li>
            <li><i class="fa-solid fa-location-dot"></i> <?= $site_address ?? 'Edinburgh & Surrounding Areas' ?></li>
            <li style="margin-top:12px;color:rgba(255,255,255,0.4);font-size:0.78rem;">
              <?= $footerSettings['input_hours_text'] ?>
            </li>
          </ul>
        </div>

      </div>

      <div class="footer-bottom">
        <div>
          <p data-admc-manage="settings_home_footer"
             data-admc-id="<?= $footerSettings['id'] ?>">
            <?= $footerSettings['input_copyright'] ?>
          </p>
          <?php if (!empty($footerSettings['input_registration_number'] ?? '')) { ?>
            <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;"
               data-admc-manage="settings_home_footer"
               data-admc-id="<?= $footerSettings['id'] ?>">
              Company Registration Number: <?= $footerSettings['input_registration_number'] ?>
            </p>
          <?php } else { ?>
            <p style="font-size:0.75rem;color:rgba(255,255,255,0.4);margin-top:4px;">
              Company Registration Number: <em>— to be provided —</em>
            </p>
          <?php } ?>
        </div>
        <div class="footer-legal">
          <?php foreach ($footerLegal as $link) { ?>
            <a href="<?= $link['input_link'] ?>"
               data-admc-manage="panel_footer_links"
               data-admc-id="<?= $link['id'] ?>">
              <?= $link['input_name'] ?>
            </a>
          <?php } ?>
        </div>
      </div>
    </div>
  </footer>

  <!-- FLOATING CTA BUTTONS -->
  <?php
    $fWhatsapp = $websiteInfo[0]['input_whatsapp_number'] ?? '';
    $fWhatsappUrl = !empty($fWhatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $fWhatsapp) : '#';
  ?>
  <!-- Persistent WhatsApp bubble (always visible) -->
  <a href="<?= $fWhatsappUrl ?>" target="_blank" rel="noopener" class="whatsapp-bubble" aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
    <span class="whatsapp-bubble-tooltip">Chat with us</span>
  </a>

  <div class="float-cta" id="floatCta" style="opacity:0;transition:opacity 0.4s;">
    <a href="tel:<?= $site_phone ?? '' ?>" class="float-btn float-btn-call">
      <i class="fa-solid fa-phone"></i> Call
    </a>
    <a href="/contact" class="float-btn float-btn-primary">
      <i class="fa-solid fa-clipboard-list"></i> Get a Quote
    </a>
  </div>

  <!-- JAVASCRIPT -->
  <script>
    // Sticky nav
    const navbar  = document.getElementById('navbar');
    const floatCta = document.getElementById('floatCta');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 60) {
        navbar.classList.add('scrolled');
        floatCta.style.opacity = '1';
      } else {
        navbar.classList.remove('scrolled');
        floatCta.style.opacity = '0';
      }
    });

    // Mobile menu
    function openMenu()  { document.getElementById('mobileMenu').classList.add('open'); document.body.style.overflow='hidden'; }
    function closeMenu() { document.getElementById('mobileMenu').classList.remove('open'); document.body.style.overflow=''; }

    // Pricing tabs
    function switchTab(id, btn) {
      document.querySelectorAll('.pricing-panel').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.pricing-tab').forEach(t => t.classList.remove('active'));
      document.getElementById('panel-' + id).classList.add('active');
      btn.classList.add('active');
    }

    // Contact form submission — uses name attributes for reliable field reading
    function submitForm(e) {
      e.preventDefault();
      var form = e.target;
      var btn = form.querySelector('.form-submit');
      var originalText = btn.textContent;
      btn.textContent = 'Sending...';
      btn.disabled = true;

      // Read by name attribute (robust across all form layouts)
      function getVal(name) {
        var el = form.querySelector('[name="' + name + '"]');
        return el ? el.value : '';
      }

      var data = {
        first_name: getVal('first_name'),
        last_name: getVal('last_name'),
        phone: getVal('phone'),
        email: getVal('email'),
        service: getVal('service'),
        property_size: getVal('property_size'),
        postcode: getVal('postcode'),
        notes: getVal('notes')
      };

      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/quote-request', true);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onload = function() {
        try {
          var res = JSON.parse(xhr.responseText);
          if (res.success) {
            btn.textContent = '\u2713 Request Sent! We\'ll be in touch shortly.';
            btn.style.background = '#16a34a';
            form.reset();
          } else {
            btn.textContent = '\u2717 ' + (res.failed || 'Error \u2014 please try again.');
            btn.style.background = '#dc2626';
            setTimeout(function() { btn.textContent = originalText; btn.style.background = ''; btn.disabled = false; }, 4000);
          }
        } catch(parseErr) {
          btn.textContent = '\u2717 Server error \u2014 please call us directly.';
          btn.style.background = '#dc2626';
          setTimeout(function() { btn.textContent = originalText; btn.style.background = ''; btn.disabled = false; }, 4000);
        }
      };
      xhr.onerror = function() {
        btn.textContent = '\u2717 Network error \u2014 please call us directly.';
        btn.style.background = '#dc2626';
        setTimeout(function() { btn.textContent = originalText; btn.style.background = ''; btn.disabled = false; }, 4000);
      };
      xhr.send(JSON.stringify(data));
    }

    // Hero quick quote form
    document.querySelector('.quote-form')?.addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      const btn = form.querySelector('button[type="submit"]');
      const originalText = btn.textContent;
      btn.textContent = 'Sending...';
      btn.disabled = true;

      const inputs = form.querySelectorAll('input, select');
      const data = {
        first_name: inputs[0]?.value || '',
        phone:      inputs[1]?.value || '',
        service:    inputs[2]?.value || '',
        property_size: inputs[3]?.value || '',
        postcode:   inputs[4]?.value || ''
      };

      fetch('/quote-request', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          btn.textContent = '✓ Sent! We\'ll call you shortly.';
          btn.style.background = '#16a34a';
          form.reset();
        } else {
          btn.textContent = '✗ ' + (res.failed || 'Error');
          btn.style.background = '#dc2626';
          setTimeout(() => { btn.textContent = originalText; btn.style.background = ''; btn.disabled = false; }, 4000);
        }
      })
      .catch(() => {
        btn.textContent = '✗ Network error';
        btn.style.background = '#dc2626';
        setTimeout(() => { btn.textContent = originalText; btn.style.background = ''; btn.disabled = false; }, 4000);
      });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => {
        const href = a.getAttribute('href');
        if (href === '#') return;
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          const offset = 80;
          window.scrollTo({ top: target.offsetTop - offset, behavior: 'smooth' });
          closeMenu();
        }
      });
    });

    // Intersection observer fade-in
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.service-card, .why-card, .review-card, .step-item, .hourly-card').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(24px)';
      el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      observer.observe(el);
    });
  </script>

<script src="/ajax/ajax.js"></script>
<!-- <?php
if ($websiteStyle[0]['status'] === "demo") {
?>
    <script src="https://themarketplace.website/mkp_assets/plugin/script.js?ver=<?php echo time(); ?>"></script>
<?php } ?> -->

<?php if (isset($_SESSION['admin_id'])) { ?>
    <script src="https://admc.dev/admc.min.js" charset="utf-8"></script>
<?php } ?>

</body>
</html>