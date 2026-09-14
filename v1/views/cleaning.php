<?php
/**
 * Cleaning: the former Services and Pricing pages on one page.
 */
$page_title = "Cleaning";
$page_meta  = "Professional cleaning in Edinburgh — end-of-tenancy, regular domestic, commercial, deep cleans, post-construction and Airbnb turnovers. Fixed prices for end-of-tenancy cleans, clear hourly rates for everything else. No hidden fees.";
$hero_title = "Our Cleaning Services";
$hero_text  = "From end-of-tenancy deep cleans to regular domestic, commercial, and Airbnb turnovers — with fixed prices for end-of-tenancy cleans and clear hourly rates for everything else.";
include APP_PATH . "/views/includes/header.php";
?>

<div class="template-page-wrapper">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>

<?php/*##cbcode_21001o##*/?>
<div data-cbcodesection="cbcode_21001">
  <?php include APP_PATH . "/views/includes/partials/page-hero.php"; ?>
</div>
<?php/*##cbcode_21001c##*/?>

<?php/*##cbcode_21002o##*/?>
<div data-cbcodesection="cbcode_21002">
  <?php include APP_PATH . "/views/includes/sections/services.php"; ?>
</div>
<?php/*##cbcode_21002c##*/?>

<?php/*##cbcode_21003o##*/?>
<div data-cbcodesection="cbcode_21003">
  <?php include APP_PATH . "/views/includes/sections/how.php"; ?>
</div>
<?php/*##cbcode_21003c##*/?>

<?php/*##cbcode_21005o##*/?>
<div data-cbcodesection="cbcode_21005">
  <?php include APP_PATH . "/views/includes/sections/pricing.php"; ?>
</div>
<?php/*##cbcode_21005c##*/?>

<?php/*##cbcode_21004o##*/?>
<div data-cbcodesection="cbcode_21004">
  <?php include APP_PATH . "/views/includes/sections/contact.php"; ?>
</div>
<?php/*##cbcode_21004c##*/?>

<?php/*##cb1c##*/?>
</div>
</div>

<?php include APP_PATH . "/views/includes/footer.php"; ?>
