<?php

class Router extends Controller
{
    private static $routes = []; // Static to store routes from all subclasses
    private static $middlewareStack = []; // Stack for global middleware
    private $prefix = ''; // Route prefix for nested routers
    private $localMiddleware = []; // Middleware specific to this router instance

    /**
     * Create a new Router instance with optional prefix
     * 
     * @param string $prefix Optional prefix for all routes in this router
     */
    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * Register a GET route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function get($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('GET', $path, $handler, $middleware, $subdomain);
        return $this;
    }

    /**
     * Register a POST route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function post($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('POST', $path, $handler, $middleware, $subdomain);
        return $this;
    }

    /**
     * Register a PUT route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function put($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $subdomain);
        return $this;
    }

    /**
     * Register a DELETE route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function delete($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $subdomain);
        return $this;
    }

    /**
     * Register a PATCH route
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function patch($path, $handler, $middleware = null, $subdomain = null)
    {
        $this->addRoute('PATCH', $path, $handler, $middleware, $subdomain);
        return $this;
    }

    /**
     * Register a route that responds to multiple HTTP methods
     * 
     * @param array $methods Array of HTTP methods
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function match($methods, $path, $handler, $middleware = null, $subdomain = null)
    {
        foreach ($methods as $method) {
            $this->addRoute(strtoupper($method), $path, $handler, $middleware, $subdomain);
        }
        return $this;
    }

    /**
     * Register a route that responds to all HTTP methods
     * 
     * @param string $path Route path
     * @param mixed $handler Handler function or class@method string
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     * @return $this
     */
    public function all($path, $handler, $middleware = null, $subdomain = null)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'];
        foreach ($methods as $method) {
            $this->addRoute($method, $path, $handler, $middleware, $subdomain);
        }
        return $this;
    }

    /**
     * Express-like use method for middleware or mounting sub-routers
     * 
     * @param string|callable $pathOrMiddleware Path or middleware function
     * @param mixed|null $middlewareOrRouter Middleware function or Router instance
     * @return $this
     */
    public function use($pathOrMiddleware, $middlewareOrRouter = null)
    {
        // Case 1: use($middleware) - Global middleware
        if ($middlewareOrRouter === null && (is_callable($pathOrMiddleware) || is_string($pathOrMiddleware))) {
            $this->localMiddleware[] = $pathOrMiddleware;
            return $this;
        }

        $path = '/';
        $middleware = null;

        // Case 2: use($path, $middleware) - Path-specific middleware
        if (is_string($pathOrMiddleware) && (is_callable($middlewareOrRouter) || is_string($middlewareOrRouter))) {
            $path = $pathOrMiddleware;
            $middleware = $middlewareOrRouter;
        }

        // Case 3: use($middleware, $router) - Apply middleware to router
        if ((is_callable($pathOrMiddleware) || is_string($pathOrMiddleware)) && $middlewareOrRouter instanceof Router) {
            $middleware = $pathOrMiddleware;
            $router = $middlewareOrRouter;
            
            // Apply the middleware to all routes in the router
            foreach ($router->getRoutes() as $route) {
                $existingMiddleware = $route['middleware'] ?? null;
                $newMiddleware = $existingMiddleware ? 
                    (is_array($existingMiddleware) ? array_merge([$middleware], $existingMiddleware) : [$middleware, $existingMiddleware]) : 
                    $middleware;
                
                $route['middleware'] = $newMiddleware;
                self::$routes[] = $route;
            }
            
            return $this;
        }

        // Case 4: use($path, $router) - Mount a sub-router
        if (is_string($pathOrMiddleware) && $middlewareOrRouter instanceof Router) {
            $path = $pathOrMiddleware;
            $router = $middlewareOrRouter;
            
            // Apply this router's middleware to the sub-router
            foreach ($this->localMiddleware as $localMw) {
                $router->use($localMw);
            }
            
            // Add routes from the sub-router with the path prefix
            foreach ($router->getRoutes() as $route) {
                $route['path'] = $this->normalizePath($path) . ltrim($route['path'], '/');
                $route['path'] = $this->normalizePath($route['path']);
                self::$routes[] = $route;
            }
            
            return $this;
        }

        // Case 5: use($path, $middleware) - Regular middleware
        if ($middleware !== null) {
            self::$middlewareStack[] = [
                'path' => $this->normalizePath($this->prefix . $path),
                'middleware' => $middleware
            ];
        }

        return $this;
    }

    /**
     * Create a router group with shared attributes
     * 
     * @param array $attributes Group attributes (prefix, middleware, subdomain)
     * @param callable $callback Function to define routes
     * @return $this
     */
    public function group(array $attributes, callable $callback)
    {
        $prefix = $attributes['prefix'] ?? '';
        $middleware = $attributes['middleware'] ?? null;
        $subdomain = $attributes['subdomain'] ?? null;
        
        // Create a new router with the combined prefix
        $groupRouter = new Router($this->prefix . $prefix);
        
        // Apply middleware
        if ($middleware) {
            $groupRouter->use($middleware);
        }
        
        // Define routes on the group router
        $callback($groupRouter);
        
        // Apply subdomain to all routes
        if ($subdomain) {
            foreach ($groupRouter->getRoutes() as &$route) {
                $route['subdomain'] = $subdomain;
            }
        }
        
        // Mount the group router
        $this->use('/', $groupRouter);
        
        return $this;
    }

    /**
     * Create a subdomain group
     * 
     * @param string $subdomain Subdomain name
     * @param callable $callback Function to define routes
     * @return $this
     */
    public function subdomain($subdomain, callable $callback)
    {
        return $this->group(['subdomain' => $subdomain], $callback);
    }

    /**
     * Add a single route to the routes collection
     * 
     * @param string $method HTTP method
     * @param string $path Route path
     * @param mixed $handlers Handler function(s) or class@method string(s)
     * @param mixed $middleware Optional middleware
     * @param string $subdomain Optional subdomain restriction
     */
    private function addRoute($method, $path, $handlers, $middleware = null, $subdomain = null)
    {
        if (!is_array($handlers)) {
            $handlers = [$handlers]; // Convert single handler to an array
        }
        
        // Apply local middleware
        if (!empty($this->localMiddleware)) {
            if ($middleware) {
                if (is_array($middleware)) {
                    $middleware = array_merge($this->localMiddleware, $middleware);
                } else {
                    $middleware = array_merge($this->localMiddleware, [$middleware]);
                }
            } else {
                $middleware = $this->localMiddleware;
            }
        }

        self::$routes[] = [
            'method' => strtoupper($method),
            'path' => $this->normalizePath($this->prefix . $path),
            'handler' => $handlers,
            'middleware' => $middleware,
            'subdomain' => $subdomain
        ];
    }

    /**
     * Run the router and dispatch the request to the appropriate handler
     */
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestSubdomain = self::getSubdomain();

        // Execute global middleware
        foreach (self::$middlewareStack as $middleware) {
            if (strpos($requestUri, $middleware['path']) === 0 || $middleware['path'] === '/') {
                $middlewareResult = $this->executeMiddleware($middleware['middleware']);
                if ($middlewareResult === false) {
                    return; // Stop further execution if middleware fails
                }
            }
        }

        // Sort routes to ensure more specific routes are matched first
        usort(self::$routes, function($a, $b) {
            // Routes with subdomain restrictions should be checked first
            if ($a['subdomain'] && !$b['subdomain']) return -1;
            if (!$a['subdomain'] && $b['subdomain']) return 1;
            
            // Longer paths (more specific) should be checked first
            return strlen($b['path']) - strlen($a['path']);
        });

        foreach (self::$routes as $route) {
            if ($route['method'] === $requestMethod) {
                $params = self::matchRoute($route['path'], $requestUri);

                // Check if the route matches the subdomain (if specified)
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

                    self::executeHandler($route['handler'], $params);
                    return;
                }
            }
        }

        // Default 404 response
        $res = new Response();
        $res->status(404)->send("Cannot $requestMethod $requestUri");
    }

    /**
     * Match a route pattern against the request URI
     * 
     * @param string $routePath Route pattern
     * @param string $requestUri Actual request URI
     * @return array|false Parameters if matched, false otherwise
     */
    private function matchRoute($routePath, $requestUri)
    {
        // Replace route parameters with regex patterns
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $routePath);
        
        // Handle optional parameters
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\?\}/', '(?P<\1>[^/]*)?', $pattern);
        
        // Finalize the pattern
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return false;
    }

    /**
     * Execute middleware function or class
     * 
     * @param mixed $middleware Middleware to execute
     * @return bool True to continue, False to halt execution
     */
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

        $req = new Request(); 
        $res = new Response();
        $next = function() {
            return true; // Continue to next middleware
        };

        if (is_callable($middleware)) {
            return $middleware($req, $res, $next) !== false;
        } elseif (is_string($middleware)) {
            // Check for "Class@Method" format
            if (strpos($middleware, '@') !== false) {
                list($class, $method) = explode('@', $middleware, 2);
                if (class_exists($class)) {
                    $middlewareInstance = new $class();
                    if (method_exists($middlewareInstance, $method)) {
                        return $middlewareInstance->$method($req, $res, $next) !== false;
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
                    return $middlewareInstance($req, $res, $next) !== false;
                }
            }
        }

        return true; // Allow the request to proceed if no middleware is matched
    }

    /**
     * Execute route handler with parameters
     * 
     * @param array $handlers Handler functions or class@method strings
     * @param array $params Route parameters
     */
    private function executeHandler($handlers, $params = [])
    {
        $req = new Request($params); // Create a Request object with route parameters
        $res = new Response();       // Create a Response object

        $index = 0;
        $next = function() use (&$index, $handlers, $req, $res, &$next) {
            $index++;
            if (isset($handlers[$index])) {
                $handler = $handlers[$index];
                self::processHandler($handler, $req, $res, $next);
            }
        };

        if (isset($handlers[$index])) {
            $handler = $handlers[$index];
            self::processHandler($handler, $req, $res, $next);
        }
    }

    /**
     * Process a single handler (function, class@method, or file)
     * 
     * @param mixed $handler Handler to process
     * @param Request $req Request object
     * @param Response $res Response object
     * @param callable $next Next function for middleware chaining
     */
    private function processHandler($handler, $req, $res, $next)
    {
        if (is_callable($handler)) {
            $handler($req, $res, $next);
        } elseif (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                list($class, $method) = explode('@', $handler, 2);

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

    /**
     * Get the current subdomain from the request
     * 
     * @return string|null Subdomain or null if no subdomain
     */
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

    /**
     * Normalize path to ensure it has leading slash and no trailing slash
     * 
     * @param string $path Path to normalize
     * @return string Normalized path
     */
    private function normalizePath($path)
    {
        // Ensure path starts with a slash
        if (substr($path, 0, 1) !== '/') {
            $path = '/' . $path;
        }
        
        // Remove trailing slash unless it's the root path
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        
        return $path;
    }

    /**
     * Get all registered routes
     * 
     * @return array Routes array
     */
    public function getRoutes()
    {
        return self::$routes;
    }

    /**
     * Load router files from a directory
     * 
     * @param string $directory Directory containing router files
     */
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
}