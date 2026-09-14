<?php
/**
 * Favicon tags, shared by the cleaning site, the beauty site and the
 * maintenance page.
 *
 * An icon uploaded in the admin (Favicon, read_favicon) takes over. Otherwise
 * the NextShine mark in www/ is used: favicon.ico, favicon.png and
 * apple-touch-icon.png. Those files also cover pages without these tags
 * (admin, login), since browsers ask for /favicon.ico on their own.
 */
?>
<?php if (!empty($favicon)): ?>
  <link rel="icon" href="<?= htmlspecialchars($favicon) ?>">
  <link rel="apple-touch-icon" href="<?= htmlspecialchars($favicon) ?>">
<?php else: ?>
  <link rel="icon" href="/favicon.ico" sizes="any">
  <link rel="icon" href="/favicon.png" type="image/png" sizes="192x192">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
<?php endif; ?>
