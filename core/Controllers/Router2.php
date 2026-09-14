<?php

class Router extends Controller
{
    private static $routes = []; // Static to store routes from all subclasses
    private static $middlewareStack = []; // Stack for global middleware

    public function get($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('GET', $path, $handler, $middleware, $subdomain);
    }

    public function post($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('POST', $path, $handler, $middleware, $subdomain);
    }

    public function put($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $subdomain);
    }

    public function delete($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $subdomain);
    }

    public function use($path, $middlewareOrRouter)
    {
        if (is_callable($middlewareOrRouter) || is_string($middlewareOrRouter)) {
            // If it's a middleware function or class, add it to the middleware stack
            self::$middlewareStack[] = [
                'path' => $path,
                'middleware' => $middlewareOrRouter
            ];
        } elseif ($middlewareOrRouter instanceof Router) {
            // If it's a sub-router, mount it at the specified path
            foreach ($middlewareOrRouter->getRoutes() as $route) {
                $route['path'] = $path . $route['path'];
                self::$routes[] = $route;
            }
        }
    }

    private function addRoute($method, $path, $handlers, $middleware = null, $subdomain = null)
{
    if (!is_array($handlers)) {
        $handlers = [$handlers]; // Convert single handler to an array
    }

    self::$routes[] = [
        'method' => strtoupper($method),
        'path' => $path,
        'handler' => $handlers,
        'middleware' => $middleware,
        'subdomain' => $subdomain
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
        $requestSubdomain = $this->getSubdomain();

        // Execute global middleware
        foreach (self::$middlewareStack as $middleware) {
            if (strpos($requestUri, $middleware['path']) === 0) {
                $middlewareResult = $this->executeMiddleware($middleware['middleware']);
                if ($middlewareResult === false) {
                    return; // Stop further execution if middleware fails
                }
            }
        }

        foreach (self::$routes as $route) {
            if ($route['method'] === $requestMethod) {
                $params = $this->matchRoute($route['path'], $requestUri);

                // Check if the route matches the subdomain
                if ($route['subdomain'] && $route['subdomain'] !== $requestSubdomain) {
                    continue;
                }

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
        $res->status(404)->send("Cannot $requestMethod $requestUri");
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
        if (is_array($middleware)) {
            // Loop through middleware array and execute each one
            foreach ($middleware as $mw) {
                if (!$this->executeMiddleware($mw)) {
                    return false; // Stop further execution if any middleware fails
                }
            }
            return true; // All middleware passed
        }

        if (is_callable($middleware)) {
            return $middleware();
        } elseif (is_string($middleware)) {
            // Check for "Class@Method" format
            if (strpos($middleware, '@') !== false) {
                $expMiddleware = explode('@', $middleware);
                $class = $expMiddleware[0];
                $method = $expMiddleware[1];
                if (class_exists($class)) {
                    $middlewareInstance = new $class();
                    if (method_exists($middlewareInstance, $method)) {
                        return $middlewareInstance->$method();
                    } else {
                        echo "Middleware method $method does not exist in $class";
                    }
                } else {
                    echo "Middleware class $class does not exist";
                }
            } elseif (class_exists($middleware)) {
                // If it's just a class, call its `__invoke` method
                $middlewareInstance = new $middleware();
                if (method_exists($middlewareInstance, '__invoke')) {
                    return $middlewareInstance();
                }
            }
        }

        return true; // Allow the request to proceed if no middleware is matched
    }

    private function executeHandler($handlers, $params = [])
{
    $req = new Request($params); // Create a Request object with route parameters
    $res = new Response();       // Create a Response object

    $index = 0;
    $next = function() use (&$index, $handlers, $req, $res, &$next) {
        $index++;
        if (isset($handlers[$index])) {
            $handler = $handlers[$index];
            if (is_callable($handler)) {
                $handler($req, $res, $next);
            } elseif (is_string($handler)) {
                if (strpos($handler, '@') !== false) {
                    $parts = explode('@', $handler);
                    $class = array_shift($parts);
                    $method = array_shift($parts);

                    if (class_exists($class)) {
                        $instance = new $class();

                        if (method_exists($instance, $method)) {
                            $instance->$method($req, $res, $next);
                        } else {
                            echo "Method $method does not exist in class $class";
                        }
                    } else {
                        echo "Class $class does not exist";
                    }
                } elseif (class_exists($handler)) {
                    $instance = new $handler();
                    if (method_exists($instance, '__invoke')) {
                        $instance($req, $res, $next);
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
    };

    if (isset($handlers[$index])) {
        $handler = $handlers[$index];
        if (is_callable($handler)) {
            $handler($req, $res, $next);
        } elseif (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                $parts = explode('@', $handler);
                $class = array_shift($parts);
                $method = array_shift($parts);

                if (class_exists($class)) {
                    $instance = new $class();

                    if (method_exists($instance, $method)) {
                        $instance->$method($req, $res, $next);
                    } else {
                        echo "Method $method does not exist in class $class";
                    }
                } else {
                    echo "Class $class does not exist";
                }
            } elseif (class_exists($handler)) {
                $instance = new $handler();
                if (method_exists($instance, '__invoke')) {
                    $instance($req, $res, $next);
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

    private function getSubdomain()
    {
        $host = $_SERVER['HTTP_HOST'] ?? null;
        if (!$host) {
            return null; // No host found
        }

        if (!defined('APP_DOMAIN')) {
            return null; // No base domain set
        }

        $baseDomain = APP_DOMAIN;

        // Remove the base domain from the host
        if (substr($host, -strlen($baseDomain)) === $baseDomain) {
            $subdomain = substr($host, 0, -strlen($baseDomain) - 1); // Get the subdomain
            return $subdomain ?: null; // Return null if no subdomain
        }

        return null; // Host doesn't match the base domain
    }

    public function getRoutes()
    {
        return self::$routes;
    }
}

// //Usage
// // Create a new Router instance
// $router = new Router();

// // Define routes
// $router->get('/', function($req, $res) {
//     $res->send('Hello, World!');
// });

// //call route with class
// $router->get('/about', 'AboutController@index');