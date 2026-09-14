<?php
namespace App;

class Router {
    private $routes = [];

    public function __construct($routes = []) {
        $this->routes = $routes;
    }

    public function addRoute($route, $path) {
        $this->routes[$route] = $path;
    }

    private function prepareUri($uri) {
        // Trim trailing slashes


        if (!empty($_GET)) {
             $explodeQueryString = explode("?",$uri);
             // $query_string = "?".$explodeQueryString[1];
             $uri = $explodeQueryString[0];
             $uriArr = explode("/", $explodeQueryString[0]);

           }else{
             $uriArr = explode("/", $uri);
             $query_string = "";
           }

            $uri = ltrim($uri, '/');

        return [$uri, $uriArr];
    }


    public function handleRequest($uri) {
        $uriData = $this->prepareUri($uri);
        global  $uriArr, $parameteredRoutes;


        $uri = $uriData[0];
        $uriArr = $uriData[1];

        $routPathExist = false;

        foreach ($this->routes as $route => $path) {
            $route = ltrim($route, "/");
            $pattern = $this->getRoutePattern($route);
            // extract($GLOBALS);



            if (preg_match($pattern, $uri, $matches)) {
                $executedRoute = $this->executeRoute($path, $matches);
                $parameteredRoutes = $executedRoute[1];
                // return $executedRoute[0];

                //Start Just Added
                $route_path = $executedRoute[0];
                if (file_exists($route_path)) {
                  $routPathExist = true;
                }
               //End

            }


        }

        if ($routPathExist) {

             return $route_path;
             die;
        }else{
            // page404();
            //   http_response_code(404);
            //   return APP_PATH."/views/404.php";
            //   die;
            false;    
        }

        // http_response_code(404);
        // include APP_PATH."/views/404.php";
        // die;
    }


    private function getRoutePattern($route) {
       $pattern = preg_quote($route, '#');
       $pattern = str_replace(['\{', '\}'], ['{', '}'], $pattern);
       $pattern = str_replace('/', '\/', $pattern);

       // Replace {{{parameter}}} with a capturing group for a single segment
       $pattern = preg_replace('#\{\{\{([^\/]+)\}\}\}#', '(?P<$1>[^/]+)', $pattern);

       // Replace {{parameter}} with a capturing group for a single segment
       $pattern = preg_replace('#\{\{([^\/]+)\}\}#', '(?P<$1>[^/]+)', $pattern);
       $pattern = preg_replace('#\{([^\/]+)\}#', '(?P<$1>[^/]+)', $pattern);

       // Add start and end delimiters to the pattern
       $pattern = '#^' . $pattern . '$#';

       return $pattern;
   }

    private function executeRoute($path, $matches) {
        // Extract named parameters from the matches array
        $parameteredRoutes = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // Replace parameter placeholders with values in the path
        // foreach ($parameteredRoutes as $key => $value) {
        //     $path = str_replace('{' . $key . '}', $value, $path);
        // }

        if (file_exists($path)) {
            return [$path, $parameteredRoutes ?? []];
        } else {
            die($path . " Path does not exist");
        }
    }
}

?>