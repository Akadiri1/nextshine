<?php

ob_start();
// session_start();


$is404 = true;

$allowedHeaders = ["192.168.33.64"];

$dummy = "dummy.png";
$fbid = "2213158278782711";
session_start();
// $_SESSION['admin_id'] = true;
// die("Critical Maintenance in progress");
#Define App Path
define("D_PATH", dirname(dirname(__FILE__)));
const APP_PATH = D_PATH . "/v1";
#load database
#load Controllers(functions)

include D_PATH . "/.env/config.php";

$mkp_init = false;


if (getenv("MKP_INIT")) {
  $mkp_init = getenv("MKP_INIT");
  if ($mkp_init === "true") {
    $mkp_init = true;
  } else {
    $mkp_init = false;
  }

  if ($mkp_init) {
    if (in_array($_SERVER['REQUEST_METHOD'], ["GET", "POST", "PATCH", "PUT", "DELETE", "OPTIONS"])) {
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Credentials: true");
      header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
      header('Access-Control-Allow-Headers: Origin, Content-Type, Access-Control-Allow-Origin');

      if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        die;
      }
    }
  }
}




if (getenv("ADMC_USERNAME")) {
  $admc_username = getenv("ADMC_USERNAME");
  setcookie("admc", "", time() - 3600, "/", null, false, false);
  setcookie("admc", "", time() - 3600, null, null, false, false);
  setcookie("admc", $admc_username, time() + 31536000, "/", null, false, false);
}

// $_SESSION['admin_id'] = "aaa";

#load routes
require APP_PATH . "/models/model.php";
require APP_PATH . "/controllers/controller.php";
require APP_PATH . "/auth/auth_controller/controller.php";
#load routes
// require APP_PATH."/routes/router.php";

// $_SESSION['admin_id']=true;

$headers = selectContent($conn, 'panel_allowed_headers', ['visibility' => 'show']);

foreach ($headers as $key => $value) {
  $headersName[] = $value['input_name'];
}

$websiteInfo = selectContent($conn, "settings_website_info", ['visibility' => 'show']);
$websiteStyle = selectContent($conn, "website_status", ['visibility' => 'show']);
$fetchFavicon = selectContent($conn, "read_favicon", ['visibility' => 'show']);


// $_SESSION['color'] = "green";

$pages = selectContent($conn, "panel_pages", ['visibility' => 'show']);
$pagesArray = array_column($pages,  'label', 'input_link');
$urlToPage = array_column($pages, 'input_link', 'label');
$urlToPage = array_map(function ($value) {
    return ltrim($value, "/");
}, $urlToPage);

$urlToFile = [];

foreach ($pages as $page) {
    $urlToFile[ltrim($page['input_link'], "/")] = $page['file_path'];
}



$site_name = $websiteInfo[0]['input_name'];
$site_email = $websiteInfo[0]['input_email'];
$site_email_from = $websiteInfo[0]['input_email_from'];
$site_email_smtp_host = $websiteInfo[0]['input_email_smtp_host'];
$site_email_smtp_secure_type = $websiteInfo[0]['input_email_smtp_secure_type'];
$site_email_smtp_port = $websiteInfo[0]['input_email_smtp_port'];
$site_email_password = $websiteInfo[0]['input_email_password'];
$site_phone = $websiteInfo[0]['input_phone_number'];
$site_address = $websiteInfo[0]['input_address'];
$fbLink = $websiteInfo[0]['input_facebook'];
$igLink = $websiteInfo[0]['input_instagram'];
$linkedinLink = $websiteInfo[0]['input_linkedin'];
$twitterLink = $websiteInfo[0]['input_twitter'];
$pinterestLink = $websiteInfo[0]['input_pinterest'];
$day_opened_closed = $websiteInfo[0]['input_day'];
$time_opened_closed = $websiteInfo[0]['input_time'];
$description = $websiteInfo[0]['text_description'];
$logo_directory = $websiteInfo[0]['image_1'];
$domain = $_SERVER['HTTP_HOST'];
$metakeys = $websiteInfo[0]['input_seo_keywords'];
$metaImage = $logo_directory;
$site_icon = $logo_directory;
$metaDescription = $description;
$logo_width = $websiteInfo[0]['input_image_width'];

/* 




*/

if ($mkp_init && $websiteStyle[0]['status'] !== "live" && $websiteStyle[0]['color'] != "") {
  $style_color = $websiteStyle[0]['color'];
}

if ($websiteStyle[0]['status'] === "live") {
  if (count($websiteStyle) > 0 && $websiteStyle[0]['color'] != "") {
    $style_color = $websiteStyle[0]['color'];
    $secondary_color = $websiteStyle[0]['secondary_color'];
  } else {
    // die(count($websiteStyle[0]['color']));
    // unset($style_color);
  }
}

if ($websiteStyle[0]['status'] === "demo") {
  if (isset($_SESSION['color'])) {
    $style_color = $_SESSION['color'];
    $secondary_color = $websiteStyle[0]['secondary_color'];
  }
  // else{
  //   $style_color = $websiteStyle[0]['color'];
  //   $secondary_color = $websiteStyle[0]['secondary_color'];
  // }
}



if ($websiteStyle[0]['status'] === "demo") {
  if (isset($_SESSION['image_select'])) {
    $logo_directory = $_SESSION['image_select'];
  } else {
    //Add the path to your project screenshot
    $metaImage = "/seo-image.png";
  }
}





// if($maintenance_status == 1 && isset($_SESSION['active']) && $url == "/preview"){
//   header("location:/home");
//   exit;
// }



include APP_PATH . "/routes/router.php";
include APP_PATH . "/demo/demo_router/router.php";
include APP_PATH . "/ajax/ajax_router/router.php";
include APP_PATH . "/auth/auth_router/router.php";
include APP_PATH . "/routes/ajax_router.php";
include APP_PATH . "/routes/admin_router.php";
include APP_PATH . "/admc_ext/ext_route/router.php";

if ($is404 == true) {
  include APP_PATH . "/views/404.php";
}
