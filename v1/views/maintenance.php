<?php
/**
 * Shown while maintenance mode is on (settings_website_info.maintenance_status).
 * Deliberately standalone: the router sends visitors here before any page, so
 * it cannot rely on the header or footer.
 */
http_response_code(503);
header('Retry-After: 3600');

require_once APP_PATH . "/views/includes/partials/logo.php";

$maintenanceWhatsapp = !empty($site_whatsapp) ? 'https://wa.me/' . preg_replace('/\D/', '', $site_whatsapp) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>Back shortly | <?= htmlspecialchars($site_name ?? 'NextShine Cleaning') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

<main class="section-dark flex min-h-screen items-center">
  <div class="container py-12 text-center">
    <?= logo_lockup('dark', 'mx-auto h-12 w-auto', $site_name ?? 'NextShine Cleaning') ?>

    <h1 class="page-hero-title mt-10">We'll be back shortly</h1>
    <p class="page-hero-subtitle">
      We're making a few improvements to the site. For quotes and bookings in the
      meantime, call or message us.
    </p>

    <div class="mt-9 flex flex-col items-center justify-center gap-3.5 xs:flex-row">
      <?php if (!empty($site_phone)): ?>
        <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="btn btn-primary btn-lg">
          <i class="fa-solid fa-phone"></i> <?= htmlspecialchars($site_phone) ?>
        </a>
      <?php endif; ?>
      <?php if ($maintenanceWhatsapp): ?>
        <a href="<?= $maintenanceWhatsapp ?>" target="_blank" rel="noopener" class="btn btn-outline btn-lg">
          <i class="fa-brands fa-whatsapp"></i> WhatsApp
        </a>
      <?php endif; ?>
    </div>
  </div>
</main>

</body>
</html>
