<?php
require_once __DIR__ . '/../core/compat.php';  // PHP 7.x polyfills

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    // This points to the project/src directory
    $base_dir = __DIR__ . '/src/'; 
    require_once __DIR__ . '/src/helpers.php';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});