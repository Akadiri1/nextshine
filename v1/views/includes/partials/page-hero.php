<?php
/**
 * Inner-page hero: navy-to-teal band with a curved lower edge.
 *
 * Set before including:
 *   $hero_title
 *   $hero_text   (optional)
 */
$hero_title = $hero_title ?? ($page_title ?? '');
$hero_text  = $hero_text  ?? '';
?>
<section class="page-hero">
  <div class="container">
    <h1 class="page-hero-title"><?= htmlspecialchars($hero_title) ?></h1>
    <?php if ($hero_text !== ''): ?>
      <p class="page-hero-subtitle"><?= htmlspecialchars($hero_text) ?></p>
    <?php endif; ?>
  </div>
</section>
