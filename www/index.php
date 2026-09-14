<?php
/**
 * NextShine Cleaning - application entry point.
 *
 * Every request is rewritten here (by www/.htaccess, or the root .htaccess when
 * the DocumentRoot is the project root). This file boots the framework, loads
 * the site-wide settings from the database, then hands the request to the
 * routers.
 */

ob_start();
session_start();

# ---------------------------------------------------------------------------
# Paths + framework bootstrap
# ---------------------------------------------------------------------------
define("D_PATH", dirname(dirname(__FILE__)));
define("MAIN_PATH", dirname(dirname(__FILE__)));

require_once D_PATH . "/core/autoload.php";
const APP_PATH = D_PATH . "/v1";

include D_PATH . "/.env/config.php";

require APP_PATH . "/models/model.php";
require APP_PATH . "/controllers/controller.php";
require APP_PATH . "/auth/auth_controller/controller.php";

# ---------------------------------------------------------------------------
# Request context
# ---------------------------------------------------------------------------
$userHTTP    = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
$domain      = $_SERVER['HTTP_HOST'];
$current_uri = $userHTTP . $domain . $_SERVER['REQUEST_URI'];

// NextShine Beauty is a separate site on its own subdomain (BEAUTY_DOMAIN),
// served from this codebase and database.
$beautyDomain  = strtolower((string) getenv('BEAUTY_DOMAIN'));
$requestHost   = strtolower(preg_replace('/:\d+$/', '', $domain));
$isBeautySite  = $beautyDomain !== '' && $requestHost === $beautyDomain;
$mainSiteUrl   = $userHTTP . getenv('APP_DOMAIN');
$beautySiteUrl = $beautyDomain !== '' ? $userHTTP . $beautyDomain : '';

// Identifies this site to the ADMC admin tooling.
if (getenv("ADMC_USERNAME")) {
    setcookie("admc", getenv("ADMC_USERNAME"), time() + 31536000, "/", "", false, false);
}

// Hosts permitted to call the admin CRUD endpoints (v1/ajax) and the quote
// form backend. Managed in the admin as panel_allowed_headers.
$headersName    = array_column(selectContent($conn, 'panel_allowed_headers', ['visibility' => 'show']), 'input_name');
$allowedHeaders = $headersName;

# ---------------------------------------------------------------------------
# Site-wide content
# ---------------------------------------------------------------------------
$websiteInfo  = selectContent($conn, "settings_website_info", ['visibility' => 'show']);
$fetchFavicon = selectContent($conn, "read_favicon", ['visibility' => 'show']);

$site = $websiteInfo[0] ?? [];

$site_name          = $site['input_name'] ?? getenv('APP_NAME');
$site_email         = $site['input_email'] ?? '';
$site_phone         = $site['input_phone_number'] ?? '';
$site_whatsapp      = $site['input_whatsapp_number'] ?? '';
$site_address       = $site['input_address'] ?? '';
$description        = $site['text_description'] ?? '';
$logo_directory     = $site['image_1'] ?? '';
$favicon            = $fetchFavicon[0]['image_1'] ?? '';
$maintenance_status = $site['maintenance_status'] ?? 0;

// Outgoing mail, used by the quote request backend.
$site_email_from             = $site['input_email_from'] ?? '';
$site_email_password         = $site['input_email_password'] ?? '';
$site_email_smtp_host        = $site['input_email_smtp_host'] ?? '';
$site_email_smtp_port        = $site['input_email_smtp_port'] ?? '';
$site_email_smtp_secure_type = $site['input_email_smtp_secure_type'] ?? '';

$metaTitle       = $site_name;
$metaDescription = $description;
$metaImage       = $logo_directory;

// Read by the admin panel's head partials (v1/admin/includes).
$metakeys = $site['input_seo_keywords'] ?? '';
$fbid     = "2213158278782711";

# ---------------------------------------------------------------------------
# Routing - order matters, the first match wins and dies
# ---------------------------------------------------------------------------
// The admin router goes first: its GET /add/<table> screen would otherwise be
// caught by the CRUD router's POST /add endpoint.
include APP_PATH . "/routes/admin_router.php";
include APP_PATH . "/ajax/ajax_router/router.php";
include APP_PATH . "/admc_ext/ext_route/router.php";

if ($isBeautySite) {
    include APP_PATH . "/routes/beauty_router.php";
}

include APP_PATH . "/auth/auth_router/router.php";
include APP_PATH . "/routes/ajax_router.php";
include APP_PATH . "/routes/router.php";
