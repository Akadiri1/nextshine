<?php
/**
 * Brand logo.
 *
 * The NextShine Group artwork is served as supplied, in two variants:
 *
 *   logo-on-dark.png   light wordmark, for the hero and footer
 *   logo-on-light.png  navy wordmark, for the solid navigation bar
 *
 * Usage:
 *   <?= logo_lockup('dark') ?>                       on a dark background
 *   <?= logo_lockup('light', 'nav-logo-img') ?>      on a light background
 *
 * The images are sized by height; leave the width automatic.
 */

if (!function_exists('logo_lockup')) {
    function logo_lockup(string $background = 'dark', string $class = 'h-11 w-auto', string $alt = 'NextShine Cleaning'): string
    {
        $src = $background === 'light'
            ? '/assets/images/logo-on-light.png'
            : '/assets/images/logo-on-dark.png';

        return '<img src="' . $src . '"'
             . ' alt="' . htmlspecialchars($alt, ENT_QUOTES) . '"'
             . ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"'
             . ' decoding="async">';
    }
}
