<?php
$page_title = "Reviews";
$page_meta  = "Customer reviews for NextShine Cleaning — reliable, professional cleaning services in Edinburgh. Read what our clients say.";
$hero_title = "What Our Clients Say";
$hero_text  = "Real feedback from Edinburgh landlords, letting agents, and businesses that trust NextShine Cleaning.";
include APP_PATH . "/views/includes/header.php";
?>

<div class="template-page-wrapper">
<div data-cbsection="cb1">
<?php/*##cb1o##*/?>

<?php/*##cbcode_25001o##*/?>
<div data-cbcodesection="cbcode_25001">
  <?php include APP_PATH . "/views/includes/partials/page-hero.php"; ?>
</div>
<?php/*##cbcode_25001c##*/?>

<?php/*##cbcode_25002o##*/?>
<div data-cbcodesection="cbcode_25002">
  <?php include APP_PATH . "/views/includes/sections/testimonials.php"; ?>
</div>
<?php/*##cbcode_25002c##*/?>

<?php/*##cbcode_25003o##*/?>
<div data-cbcodesection="cbcode_25003">
  <?php include APP_PATH . "/views/includes/sections/contact.php"; ?>
</div>
<?php/*##cbcode_25003c##*/?>

<?php/*##cb1c##*/?>
</div>
</div>

<?php include APP_PATH . "/views/includes/footer.php"; ?>
