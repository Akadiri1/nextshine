<?php
$navItems = selectContentAsc($conn, "panel_home_nav", ["visibility" => "show"], "input_order", 10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($site_name ?? 'NextShine Cleaning', ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($page_title ?? 'Home', ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription ?? '', ENT_QUOTES, 'UTF-8') ?>" />
  <?php $faviconUrl = (!empty($fetchFavicon) && !empty($fetchFavicon[0]['image_1'])) ? $fetchFavicon[0]['image_1'] : ''; ?>
  <?php if (!empty($faviconUrl)) { ?>
    <link rel="icon" type="image/png" href="<?= $faviconUrl ?>">
    <link rel="apple-touch-icon" href="<?= $faviconUrl ?>">
  <?php } ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    <?php include("style.php"); ?>
  </style>
</head>
<body>

  <!-- NAVIGATION -->
  <nav id="navbar">
    <div class="container">
      <div class="nav-inner">
        <a href="/" class="nav-logo">
          <?php if (!empty($logo_directory)) { ?>
            <img src="<?= $logo_directory ?>" alt="<?= $site_name ?>" style="height:38px;" />
          <?php } else { ?>
            <div class="nav-logo-icon">NS</div>
          <?php } ?>
          <div class="nav-logo-text">
            <span class="nav-logo-name"><?= $site_name ?? 'NextShine Cleaning' ?></span>
            <span class="nav-logo-tag">A Division of NextShine Group Ltd</span>
          </div>
        </a>

        <ul class="nav-links" data-admc-tb="panel_home_nav">
          <li><a href="/">Home</a></li>
          <?php foreach ($navItems as $nav) {
            $navHref = $nav['input_link'];
            if (str_starts_with($navHref, '#')) { $navHref = '/' . $navHref; }
          ?>
            <li>
              <a href="<?= $navHref ?>"
                 data-admc-manage="panel_home_nav"
                 data-admc-id="<?= $nav['id'] ?>">
                <?= $nav['input_name'] ?>
              </a>
            </li>
          <?php } ?>
        </ul>

        <div class="nav-cta">
          <span class="nav-phone"><i class="fa-solid fa-phone"></i> <?= $site_phone ?? '' ?></span>
          <a href="/#contact" class="btn btn-primary">Get a Quote</a>
        </div>

        <div class="hamburger" onclick="openMenu()">
          <span></span><span></span><span></span>
        </div>
      </div>
    </div>
  </nav>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu">
    <button class="mobile-close" onclick="closeMenu()">✕</button>
    <a href="/" onclick="closeMenu()"><i class="fa-solid fa-house"></i> Home</a>
    <?php foreach ($navItems as $nav) {
      $mobileHref = $nav['input_link'];
      if (str_starts_with($mobileHref, '#')) { $mobileHref = '/' . $mobileHref; }
    ?>
      <a href="<?= $mobileHref ?>" onclick="closeMenu()"><?= $nav['input_name'] ?></a>
    <?php } ?>
    <div class="mobile-menu-divider"></div>
    <a href="/#contact" onclick="closeMenu()" class="btn btn-primary btn-lg">Get a Free Quote</a>
    <a href="tel:<?= $site_phone ?? '' ?>" class="mobile-menu-phone"><i class="fa-solid fa-phone"></i> <?= $site_phone ?? '' ?></a>
  </div>