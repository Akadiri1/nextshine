<?php
/**
 * Public site routes.
 *
 * Detail URLs (/services/<hash_id>/<slug>) are matched first, then top-level
 * pages. Anything unmatched falls through to the 404 view.
 */

$uri = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Take the site offline from the admin panel without touching code.
if (!empty($maintenance_status) && !isset($_SESSION['admin_id'])) {
    include APP_PATH . "/views/maintenance.php";
    die;
}

# ---------------------------------------------------------------------------
# NextShine Beauty - /beauty and every path under it. A separate business with
# its own design, views and routes (routes/beauty_router.php).
# ---------------------------------------------------------------------------
if (($uri[1] ?? '') === 'beauty') {
    include APP_PATH . "/routes/beauty_router.php";
    die;
}

# ---------------------------------------------------------------------------
# Detail pages - /segment/<id>[/<slug>]
# ---------------------------------------------------------------------------
if (count($uri) > 2 && $uri[2] !== "") {

    switch ($uri[1]) {

        case 'services':
            $serviceId = $uri[2];
            include APP_PATH . "/views/service-details.php";
            die;

        default:
            include APP_PATH . "/views/404.php";
            die;
    }
}

# ---------------------------------------------------------------------------
# Top-level pages
# ---------------------------------------------------------------------------
switch ($uri[1] ?? '') {

    case '':
    case 'home':
        include APP_PATH . "/views/home.php";
        die;

    case 'cleaning':
        include APP_PATH . "/views/cleaning.php";
        die;

    case 'reviews':
        include APP_PATH . "/views/reviews.php";
        die;

    case 'contact':
        include APP_PATH . "/views/contact.php";
        die;

    // Pages retired when Services and Pricing merged into Cleaning, and About
    // Us and Coverage were removed. Permanent redirects keep old links and
    // search results working. (Detail pages under /services/ are unaffected;
    // they are matched above.)
    case 'services':
    case 'pricing':
    case 'about':
    case 'coverage':
        $movedTo = [
            'services' => '/cleaning',
            'pricing'  => '/cleaning#pricing',
            'about'    => '/',
            'coverage' => '/contact#coverage',
        ];
        header("Location: " . $movedTo[$uri[1]], true, 301);
        die;

    default:
        include APP_PATH . "/views/404.php";
        die;
}
