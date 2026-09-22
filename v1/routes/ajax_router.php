<?php
/**
 * Page-level AJAX endpoints.
 *
 * The generic CRUD endpoints (/add, /read, /put, /delete, /upload2server, ...)
 * are handled earlier by v1/ajax/ajax_router/router.php. This file is for
 * endpoints specific to NextShine's pages.
 */

$uri = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

switch ($uri[1]) {

    // Quote forms: contact section and the service detail sidebar.
    case 'quote-request':
        include APP_PATH . "/views/quote-request-mail-backend.php";
        die;

    // A fresh security-check challenge for any of the forms.
    case 'captcha':
        include APP_PATH . "/views/captcha-endpoint.php";
        die;

    // Is the handle on the target? Answers the page, without spending the token.
    case 'captcha-check':
        include APP_PATH . "/views/captcha-check.php";
        die;
}
