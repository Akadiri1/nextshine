<?php
    
    
if (!defined('D_PATH')) {
    define("D_PATH", dirname(dirname(__FILE__)));
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); // Start the session if not already started
}
    
require_once D_PATH."/core/compat.php";  // PHP 7.x polyfills, before anything uses them
require_once D_PATH."/App.php";



function loadEnvironmentVariables($filePath) {
    if (!file_exists($filePath)) {
        throw new Exception("Config file not found: $filePath");
    }

    // Read the content of the file
    $fileContent = file_get_contents($filePath);

    // Match all `putenv` lines
    preg_match_all('/putenv\([\'"](.+?)=(.+?)[\'"]\);/', $fileContent, $matches);



    if (empty($matches[1]) || empty($matches[2])) {
        throw new Exception("No environment variables found in the config file.");
    }

    // Extract keys and values
    $keys = $matches[1];
    $values = $matches[2];

    // Define constants
    foreach ($keys as $index => $key) {
        $value = $values[$index];

        // Convert boolean-like strings to actual boolean values
        if (strtolower($value) === 'true') {
            $value = true;
        } elseif (strtolower($value) === 'false') {
            $value = false;
        }

        // Define the constant
        if (!defined($key)) {
            define($key, $value);
        }
    }
}
    
function AutoLoader($className) {

    $filePath = D_PATH . '/core/Controllers/' . $className . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}

function AppVersionAutoLoader($className) {
    // Convert namespace separators to directory separators
    $className = str_replace('\\', '/', $className);

    // Construct full file path
    $filePath = App::basePath() . '/' . $className . '.php';

    // Check if the file exists before requiring it
    if (file_exists($filePath)) {
        require_once $filePath;
    }
}



if (!file_exists(App::basePath() . '/.env/config.php')) {
    // config.php is deliberately git-ignored, so a fresh deploy will not have
    // one. Point the operator at the template rather than failing blankly.
    http_response_code(503);
    die("Configuration missing. Copy .env/config.example.php to .env/config.php and set the values for this server.");
}

loadEnvironmentVariables(App::basePath() . '/.env/config.php');
if(!defined('APP_DOMAIN')) {
    die("Please define APP_DOMAIN in your .env/config.php file");
}
App::setDomain(APP_DOMAIN ?? 'localhost');


// Register the autoloader
spl_autoload_register('AutoLoader');
spl_autoload_register('AppVersionAutoLoader');
// new Functions();






?>