<?php
// Route static files directly
// if (file_exists(__DIR__ . $_SERVER['REQUEST_URI'])) {
//     return false;
// }

// Route everything else to index.php
// require __DIR__'/../www/index.php';
require_once  App::basePath().'www/index.php';
// echo (App::basePath());
