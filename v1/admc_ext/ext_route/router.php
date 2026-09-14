<?php
$uri = explode("/",$_SERVER['REQUEST_URI']);

if (!empty($_GET)) {
$query_string = explode("?",$_SERVER['REQUEST_URI'])[1];
}else{
$query_string = "";
}
if(count(explode("?",$uri[1])) > 1){
$uri[1] = explode("?",$uri[1])[0]."?".$query_string;
}
// var_dump($uri); die;

switch ($uri[1]) {
  case "mck_ext?".$query_string:
  include APP_PATH."/admc_ext/mck_ext.php";
    die();
  break;




}
