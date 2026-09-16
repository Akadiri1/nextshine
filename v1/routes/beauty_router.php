<?php
/**
 * NextShine Beauty routes: /beauty and every path under it, handed over by
 * routes/router.php. Beauty is a separate business with its own design, so
 * nothing here falls through to the cleaning site.
 *
 * $beautyPath is the part after /beauty ('' for the Beauty page itself); the
 * beauty header uses it to tell the page from its 404.
 */

$beautyPath = rtrim((string) substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), strlen('/beauty')), '/');

switch ($beautyPath) {

    case '':
        include APP_PATH . "/views/beauty/home.php";
        die;

    // Appointment request form
    case '/booking-request':
        include APP_PATH . "/views/beauty/booking-request-mail-backend.php";
        die;

    default:
        include APP_PATH . "/views/beauty/404.php";
        die;
}
