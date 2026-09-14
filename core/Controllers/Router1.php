<?php

class Router1 extends Controller
{
    private static $routes = []; // Static to store routes from all subclasses

    public function get($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('GET', $path, $handler, $middleware, $subdomain );
    }

    public function post($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('POST', $path, $handler, $middleware, $subdomain );
    }

    public function put($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $subdomain);
    }

    public function delete($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $subdomain);
    }

    private function addRoute($method, $path, $handler, $middleware = null, $subdomain = null)
{
    self::$routes[] = [
        'method' => strtoupper($method),
        'path' => $path,
        'handler' => $handler,
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
                // [$class, $method] = explode('@', $middleware);
                
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


}
