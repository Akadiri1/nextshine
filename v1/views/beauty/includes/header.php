<?php
/**
 * NextShine Beauty: head, top bar, navigation and mobile menu.
 *
 * Beauty is a separate business with its own look. It shares this codebase
 * and database with NextShine Cleaning but none of its styles.
 *
 * Pages may set $page_title before including; it defaults to the meta title
 * from Beauty Site settings.
 */
$beautySite = selectContent($conn, "settings_beauty_site", ["visibility" => "show"])[0];
$beautyNav  = selectContentAsc($conn, "panel_beauty_nav", ["visibility" => "show"], "input_order", 12);

$beautyTitle      = $page_title ?? $beautySite['input_meta_title'];
$beautyCssVersion = @filemtime(D_PATH . '/www/assets/css/beauty.css') ?: '1';

// Menu links (navbar, mobile menu, footer) are stored as typed in the admin:
// site paths such as "/", "/cleaning" and "/beauty", or anchors such as
// "#booking", which point back to the Beauty page when shown on its 404. A
// "{main}" prefix left from when Beauty had its own subdomain is dropped.
// The link to /beauty is marked as the current page.
$beautyOnHome = ($beautyPath ?? '') === '';
$beautyHref = function ($link) use ($beautyOnHome) {
    $link = str_replace('{main}', '', trim($link));
    return (!$beautyOnHome && isset($link[0]) && $link[0] === '#') ? '/beauty' . $link : $link;
};
$beautyActive = function ($link) use ($beautyHref) {
    return rtrim($beautyHref($link), '/') === '/beauty' ? ' class="is-active"' : '';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($beautyTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($beautySite['text_meta_description'] ?? '') ?>">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="<?= htmlspecialchars($beautySite['input_footer_name']) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($beautyTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($beautySite['text_meta_description'] ?? '') ?>">

  <?php include APP_PATH . "/views/includes/partials/favicon.php"; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <!-- Font Awesome: admins pick icons as FA classes in the input_icon columns. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/beauty.css?v=<?= $beautyCssVersion ?>">
</head>
<body>

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="top-bar-left" data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_topbar_text'] ?> <span><?= $beautySite['input_topbar_highlight'] ?></span></div>
  </div>

  <!-- NAVBAR -->
  <nav class="beauty-nav">
    <a href="/" class="nav-logo">
      <span class="logo-group">Next<span>Shine</span> Group</span>
      <span class="logo-sub" data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_logo_sub'] ?></span>
    </a>
    <ul class="nav-links" data-admc-tb="panel_beauty_nav">
      <?php foreach ($beautyNav as $nav): ?>
        <li><a href="<?= htmlspecialchars($beautyHref($nav['input_link'])) ?>"<?= $beautyActive($nav['input_link']) ?> data-admc-manage="panel_beauty_nav" data-admc-id="<?= $nav['id'] ?>"><?= $nav['input_name'] ?></a></li>
      <?php endforeach; ?>
    </ul>
    <a href="<?= htmlspecialchars($beautyHref('#booking')) ?>" class="nav-cta" data-admc-manage="settings_beauty_site" data-admc-id="<?= $beautySite['id'] ?>"><?= $beautySite['input_nav_cta'] ?></a>
    <button type="button" class="hamburger" data-menu-toggle aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- MOBILE MENU -->
  <div class="mobile-menu" id="mobileMenu" data-menu>
    <?php foreach ($beautyNav as $nav): ?>
      <a href="<?= htmlspecialchars($beautyHref($nav['input_link'])) ?>"<?= $beautyActive($nav['input_link']) ?>><?= $nav['input_name'] ?></a>
    <?php endforeach; ?>
    <a href="<?= htmlspecialchars($beautyHref('#booking')) ?>" class="m-cta"><?= $beautySite['input_nav_cta'] ?></a>
  </div>

  <main id="main">
