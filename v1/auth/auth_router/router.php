<?php
$uri = explode("/",$_SERVER['REQUEST_URI']);

$token = NULL;
if(isset($_GET['token'])){
  $token = $_GET['token'];
}

if (!empty($_GET)) {
$query_string = explode("?",$uri[1])[1];
}else{
$query_string = "";
}

switch ($uri[1]) {
  case "verify?token=$token":
  include APP_PATH."/auth/verify_registration.php";
  die;

  case "forgotPassword":
  include APP_PATH."/auth/forgot_password.php";
  die;

  case "forgotPassword2":
  include APP_PATH."/auth/forgot_password2.php";
  die;

  case "confirmRecovery":
  include APP_PATH."/auth/confirm_recovery.php";
  die;
  case "confirmRecovery":
  include APP_PATH."/auth/confirm_recovery.php";
  die;

  case "login":
  include APP_PATH."/auth/login.php";
  die;
  case "signup":
  include APP_PATH."/auth/signup.php";
  die;
  case "secure":
  include APP_PATH."/auth/secure.php";
  die;
  case "secure?".$query_string:
  include APP_PATH."/auth/secure.php";
  die;
  case "login?".$query_string:
  include APP_PATH."/auth/login.php";
  die;
  case "signup?".$query_string:
  include APP_PATH."/auth/signup.php";
  die;

  case "confirm?token=$token":
  include APP_PATH."/auth/confirm.php";
  die;

  case "changePassword":
  include APP_PATH."/auth/change_password.php";
  die;

}
