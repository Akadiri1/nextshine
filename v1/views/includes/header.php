<?php
/**
 * Site head + navigation.
 *
 * Pages set these before including:
 *   $page_title       shown after the site name in the <title>
 *   $page_meta        meta description (optional)
 *   $page_meta_title  replaces the whole <title> (optional)
 */

require_once APP_PATH . "/views/includes/partials/logo.php";

$navItems = selectContentAsc($conn, "panel_home_nav", ["visibility" => "show"], "input_order", 10);

// The Beauty button beside "Get a Quote" (Home Nav Button in the admin).
$navButton = selectContent($conn, "settings_home_nav_button", ["visibility" => "show"])[0] ?? null;

$page_title      = $page_title ?? 'Home';
$webpage_title   = !empty($page_meta_title) ? $page_meta_title : "{$site_name} | {$page_title}";
$metaDescription = $page_meta ?? $metaDescription ?? '';

// Nav links stored as bare anchors ("#pricing") are made root-relative so
// they still work from inner pages.
$navHref = function ($link) {
    return (isset($link[0]) && $link[0] === '#') ? '/' . $link : $link;
};

$cssVersion = @filemtime(D_PATH . '/www/assets/css/app.css') ?: '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($webpage_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= htmlspecialchars($site_name) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($webpage_title) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">

  <?php if (!empty($favicon)): ?>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($favicon) ?>">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($favicon) ?>">
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome: admins pick icons as FA classes in the input_icon columns. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <script>document.documentElement.classList.add('js');</script>
  <link rel="stylesheet" href="/assets/css/app.css?v=<?= $cssVersion ?>">
  <?php include APP_PATH . "/views/includes/theme.php"; ?>
</head>
<body>

  <!-- NAVIGATION -->
  <nav id="navbar" class="site-nav" data-nav>
    <div class="container">
      <?php // Tablets: logo, links and buttons evenly spaced. From 1025px the links sit
            // in the middle of the page; a side too wide for its half pushes them over,
            // always leaving at least the column gap. ?>
      <div class="flex items-center justify-between lg:grid lg:grid-cols-[minmax(max-content,1fr)_auto_minmax(max-content,1fr)] lg:gap-x-6">

        <a href="/" class="flex shrink-0 items-center gap-2.5 lg:justify-self-start" aria-label="<?= htmlspecialchars($site_name) ?>">
          <?= logo_lockup('dark', 'nav-logo-img nav-logo-img-dark', $site_name) ?>
          <?= logo_lockup('light', 'nav-logo-img nav-logo-img-light', $site_name) ?>
        </a>

        <ul class="hidden shrink items-center gap-4 md:flex lg:gap-6" data-admc-tb="panel_home_nav">
          <li><a href="/" class="nav-link">Home</a></li>
          <?php foreach ($navItems as $nav): ?>
            <li>
              <a href="<?= $navHref($nav['input_link']) ?>" class="nav-link"
                 data-admc-manage="panel_home_nav"
                 data-admc-id="<?= $nav['id'] ?>">
                <?= $nav['input_name'] ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="hidden shrink-0 items-center gap-3 md:flex lg:justify-self-end">
          <?php // On tablets only the phone icon shows, leaving room for the links. ?>
          <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="nav-phone" aria-label="Call <?= htmlspecialchars($site_phone) ?>"><i class="fa-solid fa-phone"></i> <span class="hidden lg:inline"><?= htmlspecialchars($site_phone) ?></span></a>
          <?php if ($navButton): ?>
            <a href="<?= htmlspecialchars($navButton['input_link']) ?>" class="btn nav-beauty"
               data-admc-manage="settings_home_nav_button"
               data-admc-id="<?= $navButton['id'] ?>">
              <?php if (!empty($navButton['input_icon'])): ?><i class="<?= htmlspecialchars($navButton['input_icon']) ?>" aria-hidden="true"></i><?php endif; ?>
              <?= $navButton['input_text'] ?>
            </a>
          <?php endif; ?>
          <a href="/contact" class="btn btn-primary">Get a Quote</a>
        </div>

        <button type="button" class="hamburger flex cursor-pointer flex-col gap-[5px] p-1 md:hidden"
                data-menu-open aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
          <span></span><span></span><span></span>
        </button>

      </div>
    </div>
  </nav>

  <!-- MOBILE MENU (below 769px) -->
  <div class="mobile-menu" id="mobileMenu" data-menu>
    <button type="button" class="mobile-close" data-menu-close aria-label="Close menu">✕</button>
    <a href="/"><i class="fa-solid fa-house"></i> Home</a>
    <?php foreach ($navItems as $nav): ?>
      <a href="<?= $navHref($nav['input_link']) ?>"><?= $nav['input_name'] ?></a>
    <?php endforeach; ?>
    <div class="mobile-menu-divider"></div>
    <a href="/contact" class="btn btn-primary btn-lg">Get a Free Quote</a>
    <?php if ($navButton): ?>
      <a href="<?= htmlspecialchars($navButton['input_link']) ?>" class="btn btn-outline btn-lg mobile-beauty">
        <?php if (!empty($navButton['input_icon'])): ?><i class="<?= htmlspecialchars($navButton['input_icon']) ?>" aria-hidden="true"></i><?php endif; ?>
        <?= $navButton['input_text'] ?>
      </a>
    <?php endif; ?>
    <a href="tel:<?= htmlspecialchars($site_phone) ?>" class="mobile-menu-phone"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($site_phone) ?></a>
  </div>

  <main id="main">
