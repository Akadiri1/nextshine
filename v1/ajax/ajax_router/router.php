<?php
$uri = explode("/",$_SERVER['REQUEST_URI']);



switch ($uri[1]) {

  case "add":
  include APP_PATH."/ajax/add.php";
  break;
  case "read":
  include APP_PATH."/ajax/read.php";
  break;
  case "put":
  include APP_PATH."/ajax/put.php";
  break;
  case "delete":
  include APP_PATH."/ajax/delete.php";
  break;
  case "upload2server":
  include APP_PATH."/ajax/upload2server.php";
  break;
  case "delete2server":
  include APP_PATH."/ajax/delete2server.php";
  break;
  case "change2server":
  include APP_PATH."/ajax/change2server.php";
  break;
  case "multiple2server":
  include APP_PATH."/ajax/multiple2server.php";
  break;
  case "serialize":
  include APP_PATH."/ajax/serialize.php";
  break;
  case "unserialize":
  include APP_PATH."/ajax/unserialize.php";
  break;

}
