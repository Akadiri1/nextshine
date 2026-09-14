<?php
$uri = explode("/",$_SERVER['REQUEST_URI']);



switch ($uri[1]) {

  case "add":
  include APP_PATH."/ajax/add.php";
  die; break;

  case "read":
  include APP_PATH."/ajax/read.php";
  die; break;

  case "put":
  include APP_PATH."/ajax/put.php";
  die; break;

  case "delete":
  include APP_PATH."/ajax/delete.php";
  die; break;

  case "upload2server":
  include APP_PATH."/ajax/upload2server.php";
  die; break;

  case "delete2server":
  include APP_PATH."/ajax/delete2server.php";
  die; break;

  case "change2server":
  include APP_PATH."/ajax/change2server.php";
  die; break;

  case "multiple2server":
  include APP_PATH."/ajax/multiple2server.php";
  die; break;

  case "serialize":
  include APP_PATH."/ajax/serialize.php";
  die; break;

  case "unserialize":
  include APP_PATH."/ajax/unserialize.php";
  die; break;


}
