<?php
http_response_code(404);

$page_title = "Page Not Found";
$page_meta  = "The page you are looking for could not be found.";
$hero_title = "Sorry, we can't find that page";
$hero_text  = "The link may be out of date, or the page may have moved.";

include APP_PATH . "/views/includes/header.php";
include APP_PATH . "/views/includes/partials/page-hero.php";
?>

<section class="section">
  <div class="container text-center">
    <p class="font-display text-[clamp(4rem,14vw,8rem)] font-extrabold leading-none text-navy">404</p>
    <p class="section-subtitle mx-auto mt-4">These will get you back on track.</p>

    <div class="mt-9 flex flex-col items-center justify-center gap-3.5 xs:flex-row xs:flex-wrap">
      <a href="/" class="btn btn-primary btn-lg"><i class="fa-solid fa-house"></i> Back to Home</a>
      <a href="/cleaning" class="btn btn-navy btn-lg">Our Services</a>
      <a href="/contact" class="btn btn-outline-navy btn-lg">Contact Us</a>
    </div>
  </div>
</section>

<?php include APP_PATH . "/views/includes/footer.php"; ?>
