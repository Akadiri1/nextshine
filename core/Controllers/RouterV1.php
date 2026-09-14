<?php

class Router
{
    private static $routes = []; // Static to store routes from all subclasses

    public function get($path, $handler, $middleware = null)
    {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    public function post($path, $handler, $middleware = null)
    {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    public function put($path, $handler, $middleware = null)
    {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }

    public function delete($path, $handler, $middleware = null)
    {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }

    private function addRoute($method, $path, $handler, $middleware = null)
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public static function loadRouters($directory)
    {
        foreach (glob("$directory/*.php") as $file) {
            require_once $file; // Include all router files
        }

        // Use reflection to automatically instantiate all subclasses of Router
        foreach (get_declared_classes() as $class) {
            if (is_subclass_of($class, self::class)) {
                new $class(); // Automatically instantiate
            }
        }
    }

    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach (self::$routes as $route) {
            if ($route['method'] === $requestMethod) {
                $params = $this->matchRoute($route['path'], $requestUri);

                if ($params !== false) {
                    if ($route['middleware']) {
                        $middlewareResult = $this->executeMiddleware($route['middleware']);
                        if ($middlewareResult === false) {
                            return;
                        }
                    }

                    $this->executeHandler($route['handler'], $params);
                    return;
                }
            }
        }

        // Default 404 response
        $res = new Response();
        $res->status(404)->json(['message' => 'Route not found']);
    }

    private function matchRoute($routePath, $requestUri)
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return false;
    }

    private function executeMiddleware($middleware)
    {
        if (is_callable($middleware)) {
            return $middleware();
        } elseif (is_string($middleware) && class_exists($middleware)) {
            $middlewareInstance = new $middleware();
            return $middlewareInstance->handle();
        }

        return true;
    }

//     private function executeHandler($handler, $params = [])
//     {
//         $req = new Request($params);
//         $res = new Response();

//         if (is_callable($handler)) {
//             call_user_func($handler, $req, $res);
//         } elseif (is_string($handler) && file_exists($handler)) {
//             include $handler;
//         } else {
//             echo "Invalid handler.";
//         }
//     }

    private function executeHandler($handler, $params = [])
{
    $req = new Request($params); // Create a Request object with route parameters
    $res = new Response();       // Create a Response object

    if (is_callable($handler)) {
        call_user_func($handler, $req, $res);
    } elseif (is_string($handler)) {
        if (strpos($handler, '@') !== false) {
            $parts = explode('@', $handler);
            $class = array_shift($parts);
            $method = array_shift($parts);

            if (class_exists($class)) {
                $instance = new $class();

                if (method_exists($instance, $method)) {
                    call_user_func([$instance, $method], $req, $res);
                } else {
                    echo "Method $method does not exist in class $class";
                }
            } else {
                echo "Class $class does not exist";
            }
        } elseif (class_exists($handler)) {
            $instance = new $handler();
            if (method_exists($instance, '__invoke')) {
                call_user_func($instance, $req, $res);
            } else {
                echo "Class $handler does not have an __invoke method";
            }
        } elseif (file_exists($handler)) {
            include $handler;
        } else {
            echo "Invalid handler type or file not found";
        }
    } else {
        echo "Invalid handler type";
    }
}

}

// class Router
// {
//     private $routes = [];

//     public function get($path, $handler, $middleware = null)
//     {
//         $this->addRoute('GET', $path, $handler, $middleware);
//     }

//     public function post($path, $handler, $middleware = null)
//     {
//         $this->addRoute('POST', $path, $handler, $middleware);
//     }

//     public function put($path, $handler, $middleware = null)
//     {
//         $this->addRoute('PUT', $path, $handler, $middleware);
//     }

//     public function delete($path, $handler, $middleware = null)
//     {
//         $this->addRoute('DELETE', $path, $handler, $middleware);
//     }

//     private function addRoute($method, $path, $handler, $middleware = null)
//     {
//         $this->routes[] = [
//             'method' => strtoupper($method),
//             'path' => $path,
//             'handler' => $handler,
//             'middleware' => $middleware
//         ];
//     }

//     // Dispatch the request to this router
//     public function dispatch($requestMethod, $requestUri)
//     {
//         foreach ($this->routes as $route) {
//             if ($route['method'] === $requestMethod) {
//                 $params = $this->matchRoute($route['path'], $requestUri);

//                 if ($params !== false) {
//                     // Execute middleware if defined
//                     if ($route['middleware']) {
//                         $middlewareResult = $this->executeMiddleware($route['middleware']);
//                         if ($middlewareResult === false) {
//                             return true; // Middleware blocked the request
//                         }
//                     }

//                     // Execute the handler
//                     $this->executeHandler($route['handler'], $params);
//                     return true; // Route matched and handled
//                 }
//             }
//         }

//         return false; // No route matched
//     }

//     private function matchRoute($routePath, $requestUri)
//     {
//         $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $routePath);
//         $pattern = '#^' . $pattern . '$#';

//         if (preg_match($pattern, $requestUri, $matches)) {
//             return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named parameters
//         }

//         return false;
//     }

//     private function executeMiddleware($middleware)
//     {
//         if (is_callable($middleware)) {
//             return $middleware();
//         } elseif (is_string($middleware) && class_exists($middleware)) {
//             $middlewareInstance = new $middleware();
//             return $middlewareInstance->handle();
//         }

//         return true;
//     }

//     private function executeHandler($handler, $params = [])
//     {
//         $req = new Request($params);
//         $res = new Response();

//         if (is_callable($handler)) {
//             call_user_func($handler, $req, $res);
//         } elseif (is_string($handler)) {
//             if (strpos($handler, '@') !== false) {
//                 $parts = explode('@', $handler);
//                 $class = array_shift($parts);
//                 $method = array_shift($parts);

//                 if (class_exists($class)) {
//                     $instance = new $class();

//                     if (method_exists($instance, $method)) {
//                         call_user_func([$instance, $method], $req, $res);
//                     } else {
//                         echo "Method $method does not exist in class $class";
//                     }
//                 } else {
//                     echo "Class $class does not exist";
//                 }
//             } elseif (class_exists($handler)) {
//                 $instance = new $handler();
//                 if (method_exists($instance, '__invoke')) {
//                     call_user_func($instance, $req, $res);
//                 } else {
//                     echo "Class $handler does not have an __invoke method";
//                 }
//             } elseif (file_exists($handler)) {
//                 include $handler;
//             } else {
//                 echo "Invalid handler type or file not found";
//             }
//         } else {
//             echo "Invalid handler type";
//         }
//     }
// }


// class Router
// {
//     private $routes = [];

//     public function get($path, $handler, $middleware = null)
//     {
//         $this->addRoute('GET', $path, $handler, $middleware);
//     }

//     public function post($path, $handler, $middleware = null)
//     {
//         $this->addRoute('POST', $path, $handler, $middleware);
//     }

//     public function put($path, $handler, $middleware = null)
//     {
//         $this->addRoute('PUT', $path, $handler, $middleware);
//     }

//     public function delete($path, $handler, $middleware = null)
//     {
//         $this->addRoute('DELETE', $path, $handler, $middleware);
//     }

//     private function addRoute($method, $path, $handler, $middleware = null)
//     {
//         $this->routes[] = [
//             'method' => strtoupper($method),
//             'path' => $path,
//             'handler' => $handler,
//             'middleware' => $middleware
//         ];
//     }

//     public function run()
//     {
//         $requestMethod = $_SERVER['REQUEST_METHOD'];
//         $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//         foreach ($this->routes as $route) {
//             if ($route['method'] === $requestMethod) {
//                 $params = $this->matchRoute($route['path'], $requestUri);

//                 if ($params !== false) {
//                     // Execute middleware if defined
//                     if ($route['middleware']) {
//                         $middlewareResult = $this->executeMiddleware($route['middleware']);
//                         if ($middlewareResult === false) {
//                             return;
//                         }
//                     }

//                     // Execute the handler, which may be a class, method chain, or function
//                     $this->executeHandler($route['handler'], $params);
//                     return;
//                 }
//             }
//         }

        
//     $req = new Request(); // Create a Request object with route parameters
//     $res = new Response();       // Create a Response object
//         // // If no route matched, return 404
//         // http_response_code(404);
//         // echo json_encode(['message' => 'Route not found']);
//         $res->status(404)->json(['message' => 'Route not found']);
//     }

//     private function matchRoute($routePath, $requestUri)
//     {
//         $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $routePath);
//         $pattern = '#^' . $pattern . '$#';

//         if (preg_match($pattern, $requestUri, $matches)) {
//             return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Only keep named parameters
//         }

//         return false;
//     }

//     private function executeMiddleware($middleware)
//     {
//         if (is_callable($middleware)) {
//             return $middleware();
//         } elseif (is_string($middleware) && class_exists($middleware)) {
//             $middlewareInstance = new $middleware();
//             return $middlewareInstance->handle();
//         }

//         return true;
//     }

//     // private function executeHandler($handler, $params = [])
//     // {
//     //     if (is_callable($handler)) {
//     //         // If the handler is a callable function
//     //         call_user_func_array($handler, $params);
//     //     } elseif (is_string($handler)) {
//     //         // Check if handler is a class with method chaining (Class@method1@method2@...)
//     //         if (strpos($handler, '@') !== false) {
//     //             $parts = explode('@', $handler);
//     //             $class = array_shift($parts);

//     //             if (class_exists($class)) {
//     //                 $instance = new $class();

//     //                 // Call each method in the chain on the same instance
//     //                 foreach ($parts as $method) {
//     //                     if (method_exists($instance, $method)) {
//     //                         $instance = call_user_func([$instance, $method]);
//     //                     } else {
//     //                         echo "Method $method does not exist in class $class";
//     //                         return;
//     //                     }
//     //                 }
//     //             } else {
//     //                 echo "Class $class does not exist";
//     //             }
//     //         } elseif (class_exists($handler)) {
//     //             // If handler is a class, instantiate and call __invoke if available
//     //             $instance = new $handler();
//     //             if (method_exists($instance, '__invoke')) {
//     //                 call_user_func_array($instance, $params);
//     //             } else {
//     //                 echo "Class $handler does not have an __invoke method";
//     //             }
//     //         } elseif (file_exists($handler)) {
//     //             // If the handler is a file path, include it
//     //             include $handler;
//     //         } else {
//     //             echo "Invalid handler type or file not found";
//     //         }
//     //     } else {
//     //         echo "Invalid handler type";
//     //     }
//     // }

    
 
//     private function executeHandler($handler, $params = [])
// {
//     $req = new Request($params); // Create a Request object with route parameters
//     $res = new Response();       // Create a Response object

//     if (is_callable($handler)) {
//         call_user_func($handler, $req, $res);
//     } elseif (is_string($handler)) {
//         if (strpos($handler, '@') !== false) {
//             $parts = explode('@', $handler);
//             $class = array_shift($parts);
//             $method = array_shift($parts);

//             if (class_exists($class)) {
//                 $instance = new $class();

//                 if (method_exists($instance, $method)) {
//                     call_user_func([$instance, $method], $req, $res);
//                 } else {
//                     echo "Method $method does not exist in class $class";
//                 }
//             } else {
//                 echo "Class $class does not exist";
//             }
//         } elseif (class_exists($handler)) {
//             $instance = new $handler();
//             if (method_exists($instance, '__invoke')) {
//                 call_user_func($instance, $req, $res);
//             } else {
//                 echo "Class $handler does not have an __invoke method";
//             }
//         } elseif (file_exists($handler)) {
//             include $handler;
//         } else {
//             echo "Invalid handler type or file not found";
//         }
//     } else {
//         echo "Invalid handler type";
//     }
// }




// }
