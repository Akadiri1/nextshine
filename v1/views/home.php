<?php
$page_title = "Home";
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
     TRUST STRIP
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10002o##*/?>
<div data-cbcodesection="cbcode_10002">
  <?php include APP_PATH . "/views/includes/sections/trust.php"; ?>
</div>
<?php/*##cbcode_10002c##*/?>


<!-- ═══════════════════════════════════════════════════
     WHY CHOOSE US
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10006o##*/?>
<div data-cbcodesection="cbcode_10006">
  <?php include APP_PATH . "/views/includes/sections/why.php"; ?>
</div>
<?php/*##cbcode_10006c##*/?>


<!-- ═══════════════════════════════════════════════════
     ABOUT
═══════════════════════════════════════════════════ -->
<?php/*##cbcode_10007o##*/?>
<div data-cbcodesection="cbcode_10007">
  <?php include APP_PATH . "/views/includes/sections/about.php"; ?>
</div>
<?php/*##cbcode_10007c##*/?>


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
