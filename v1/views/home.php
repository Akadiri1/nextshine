<?php
/**
 * Home: the NextShine Group page, following the client's sample index.html.
 * Cleaning and Beauty side by side: the hero, the two divisions, then the
 * quote form. The page title and description come from the hero settings.
 */
$homeHero        = selectContent($conn, "settings_home_hero", ["visibility" => "show"])[0] ?? [];
$page_title      = "Home";
$page_meta_title = $homeHero['input_meta_title'] ?? '';
if (!empty($homeHero['text_meta_description'])) {
    $page_meta = $homeHero['text_meta_description'];
}
include APP_PATH . "/views/includes/header.php";
?>

<div class="template-home-wrapper">
<div class="page-content-home-page">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>


<!-- ═══════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10001o##*/?>
<div data-cbcodesection="cbcode_10001">
  <?php include APP_PATH . "/views/includes/sections/hero.php"; ?>
</div>
<?php/*##cbcode_10001c##*/?>


<!-- ═══════════════════════════════════════════════════
     OUR SERVICES: THE TWO DIVISIONS
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10012o##*/?>
<div data-cbcodesection="cbcode_10012">
  <?php include APP_PATH . "/views/includes/sections/divisions.php"; ?>
</div>
<?php/*##cbcode_10012c##*/?>


<!-- ═══════════════════════════════════════════════════
     CONTACT / QUOTE (form NOT editable)
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10010o##*/?>
<div data-cbcodesection="cbcode_10010">
  <?php include APP_PATH . "/views/includes/sections/contact.php"; ?>
</div>
<?php/*##cbcode_10010c##*/?>


<?php/*##cb1c##*/?>
</div>
</div>
</div>

<?php include APP_PATH . "/views/includes/footer.php"; ?>
