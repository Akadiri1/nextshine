<?php
    

class App {
    protected static $basePath = __DIR__;
    public static $domain;
    protected static $publicPath = __DIR__ . '/www';
    protected static $versionPath = __DIR__ . '/v1';

    public static function setBasePath($path)
    {
        self::$basePath = rtrim($path, DIRECTORY_SEPARATOR);
    }

    public static function getBasePath()
    {
        return self::$basePath;
    }

    public static function basePath($path = '')
    {
        return self::$basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public static function setPublicPath( $path)
    {
        self::$publicPath = rtrim($path, DIRECTORY_SEPARATOR);
    }

    public static function getPublicPath()
    {
        return self::$publicPath;
    }

    public static function publicPath($path = '')
    {
        return self::getPublicPath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public static function versionPath($path = '')
    {
        return self::getVersionPath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }   

    public static function getVersionPath()
    {
        return self::$versionPath;
    }

    public static function setVersionPath($version)
    {
        self::$versionPath = self::basePath() . ($version ? DIRECTORY_SEPARATOR . $version : $version);
    }

    public static function views( $path = '', $args = [ ])
    {
        extract($args);

        require_once self::getVersionPath(). "/views". ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    public static function setDomain ($domain = ''){
        if (empty($domain)) {
            $domain = $_SERVER['HTTP_HOST'];
        }
        self::$domain = $domain;
        // return self::$domain = $domain;
    }
    public static function getUrl()
    {
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? $protocol = "https://" : $protocol = "http://";
        return $protocol . (self::$domain ?? $_SERVER['HTTP_HOST']);
    }
    public static function uri()
    {
        return self::getUrl()."".$_SERVER['REQUEST_URI'];
    }

}

App::setBasePath(__DIR__);

// set version path so you can get the views from the version path
App::setVersionPath('v1');


// Set the base path at initialization

// // Access the base path anywhere
// echo App::basePath()."<br>";          // Outputs /path/to/project
// echo App::basePath('storage')."<br>"; // Outputs /path/to/project/storage
// echo App::basePath('public')."<br>";  // Outputs /path/to/project/public
// echo App::publicPath('public')."<br>";  // Outputs /path/to/project/public
// echo App::versionPath('public')."<br>";  // Outputs /path/to/project/public
?>