<?php
// print_r(App::publicPath(). $_SERVER['REQUEST_URI']);

// require_once D_PATH."/core/core_shell.php";
require_once __DIR__ . '/autoload.php';

// // Automatically load all routers
Router::load(App::basePath() . '/routes');

if (!PRODUCTION_MODE) {
    Router::get('/migrate', function($req, $res) {
        require_once D_PATH . '/core/migration.php';
    });
    Router::get('/seed', function($req, $res) {
        require_once D_PATH . '/core/seed.php';
    });
}

Router::run();
?>