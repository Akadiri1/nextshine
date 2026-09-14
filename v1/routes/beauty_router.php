<?php
/**
 * NextShine Beauty routes, used only when the request is for BEAUTY_DOMAIN
 * (see www/index.php). Every path ends here; nothing falls through to the
 * cleaning site.
 */

$beautyPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

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
