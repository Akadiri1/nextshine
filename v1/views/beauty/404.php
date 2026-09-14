<?php
http_response_code(404);

$page_title = "Page Not Found | NextShine Beauty";
include APP_PATH . "/views/beauty/includes/header.php";
?>

<section class="hero">
  <div class="hero-pattern"></div>
  <div class="hero-content">
    <span class="hero-eyebrow">Page not found</span>
    <h1 class="hero-title">This page<br><em>isn't here.</em></h1>
    <p class="hero-text">The link may be out of date or the page may have moved. Everything we offer is on our main page.</p>
    <div class="flex flex-col gap-4 xs:flex-row xs:flex-wrap">
      <a href="/" class="btn-primary">Back to NextShine Beauty</a>
      <a href="/#booking" class="btn-outline">Book an Appointment</a>
    </div>
  </div>
</section>

<?php include APP_PATH . "/views/beauty/includes/footer.php"; ?>
