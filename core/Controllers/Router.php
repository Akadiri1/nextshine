<?php

class Router {
    private static $routes = [];
    private static $middlewareStack = [];
    private static $groupStack = [];
    
    public static function __callStatic($method, $args = []) {
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'any'];
        if (in_array(strtolower($method), $methods)) {

            return self::addRoute(strtoupper($method), ...$args);
        }
        throw new BadMethodCallException("Method $method does not exist");
    }

    public static function use($middleware) {
        if (empty(self::$groupStack)) {
            self::$middlewareStack[] = [
                'path' => '/',
                'middleware' => $middleware
            ];
        } else {
            $currentGroup = end(self::$groupStack);
            $currentGroup['middleware'][] = $middleware;
            self::$groupStack[count(self::$groupStack)-1] = $currentGroup;
        }
        return new static();
    }
    
    public static function group($attributes, $callback) {
        $group = [
            'prefix' => $attributes['prefix'] ?? '',
            'middleware' => $attributes['middleware'] ?? [],
            'subdomain' => $attributes['subdomain'] ?? null
        ];
        
        array_push(self::$groupStack, $group);
        $callback();
        array_pop(self::$groupStack);
    }
    
    public static function subdomain($subdomain, $callback) {
        self::group(['subdomain' => $subdomain], $callback);
    }
    
    public static function prefix($prefix, $callback) {
        self::group(['prefix' => $prefix], $callback);
    }
    
    public static function load($path) {
        //  enable to check through nested folders
        if (is_dir($path)) {
            foreach (glob($path . '/*') as $file) {
                if (is_dir($file)) {
                    self::load($file);
                } elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                    require $file;
                }
            }
        } else {
            require $path;
        }
        // Load all PHP files in the specified directory

    }

    
    
    private static function addRoute($method, $path, $handler, $routeMiddleware = null) {
        $prefix = '';
        $middleware = [];
        $subdomain = null;
        
        foreach (self::$groupStack as $group) {
            if ($group['prefix']) {
                $prefix = $prefix ? $prefix . '/' . $group['prefix'] : $group['prefix'];
            }
            if ($group['middleware']) {
                $middleware = array_merge($middleware, $group['middleware']);
            }
            if ($group['subdomain'] && $subdomain === null) {
                $subdomain = $group['subdomain'];
            }
        }
        
        if ($routeMiddleware) {
            $middleware = array_merge($middleware, (array)$routeMiddleware);
        }
        
        $fullPath = $prefix ? '/' . $prefix . '/' . ltrim($path, '/') : $path;
        $fullPath = preg_replace('/\/+/', '/', $fullPath);
        $fullPath = rtrim($fullPath, '/');

        // var_dump($fullPath);
        
        self::$routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'handler' => $handler,
            'middleware' => $middleware,
            'subdomain' => $subdomain
        ];
        
        return new static();
    }
    
    public static function run() {
        $request = new Request();
        $response = new Response();
        $currentSubdomain = self::getCurrentSubdomain();
        
        // Execute global middleware
        foreach (self::$middlewareStack as $middleware) {
            if (self::executeMiddleware($middleware['middleware'], $request, $response) === false) {
                return;
            }
        }

        // If in subdomain context
        if ($currentSubdomain !== null) {
            // Execute subdomain group middleware
            $subdomainGroup = self::findSubdomainGroup($currentSubdomain);
            if ($subdomainGroup) {
                foreach ($subdomainGroup['middleware'] as $middleware) {
                    if (self::executeMiddleware($middleware, $request, $response) === false) {
                        return;
                    }
                }
            }
            
            // Only match routes for this subdomain
            foreach (self::$routes as $route) {
                if ($route['subdomain'] === $currentSubdomain && 
                ($route['method'] === $request->method || $route['method'] === 'ANY')) {
                        // $response->json([$route, $request->uri]);
                    
                    $params = self::matchRoute($route['path'], $request->uri);
                    if ($params !== false) {
                        $request->params = $params;
                        
                        foreach ($route['middleware'] as $middleware) {
                            if (self::executeMiddleware($middleware, $request, $response) === false) {
                                return;
                            }
                        }
                        
                        self::executeHandler($route['handler'], $request, $response);
                        return;
                    }
                }
            }
            
            // No matching route in subdomain
            $response->status(404)->send("Cannot {$request->method} {$request->uri}");
            return;
        }

        // Handle non-subdomain requests
        foreach (self::$routes as $route) {
            if ($route['subdomain'] === null && 
                ($route['method'] === $request->method || $route['method'] === 'ANY')) {
                
                $params = self::matchRoute($route['path'], $request->uri);
                if ($params !== false) {
                    $request->params = $params;
                    
                    foreach ($route['middleware'] as $middleware) {
                        if (self::executeMiddleware($middleware, $request, $response) === false) {
                            return;
                        }
                    }
                    
                    self::executeHandler($route['handler'], $request, $response);
                    return;
                }
            }
        }

        // 404 Not Found
        $response->status(404)->send("Cannot {$request->method} {$request->uri}");

    }
    
    private static function findSubdomainGroup($subdomain) {
        foreach (self::$routes as $route) {
            if ($route['subdomain'] === $subdomain) {
                foreach (self::$groupStack as $group) {
                    if ($group['subdomain'] === $subdomain) {
                        return $group;
                    }
                }
            }
        }
        return null;
    }
    
    private static function getCurrentSubdomain() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (!defined('APP_DOMAIN')) return null;
        
        $baseDomain = APP_DOMAIN;
        if (strpos($host, $baseDomain) !== false) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            return $subdomain !== $host ? $subdomain : null;
        }
        return null;
    }
    
    private static function matchRoute($routePath, $requestUri) {
        /* Format URI begin */
        // Remove query string from request URI
        $requestUri = strtok($requestUri, '?');
        // Remove leading slashes from both paths
        $routePath = ltrim($routePath, '/');
        $requestUri = ltrim($requestUri, '/');
        // Escape special characters in the route path
        $routePath = preg_quote($routePath, '#');
        // Replace placeholders with regex patterns
        $routePath = preg_replace('/\{([a-z]+)\}/', '([^/]+)', $routePath);
        $routePath = preg_replace('/\{([a-z]+)\?\}/', '([^/]*)?', $routePath);

        /* Format URI ends */




        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^/]+)', $routePath);
        $pattern = preg_replace('/\{([a-z]+)\?\}/', '(?P<\1>[^/]*)?', $pattern);
        $pattern = '#^' . $pattern . '$#i';
        
        if (preg_match($pattern, $requestUri, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        return false;
    }
    
    private static function executeMiddleware($middleware, $request, $response) {
        $next = function() { return true; };
        
        if (is_callable($middleware)) {
            return $middleware($request, $response, $next);
        } elseif (is_string($middleware)) {
            if (strpos($middleware, '@') !== false) {
                list($class, $method) = explode('@', $middleware);
                if (class_exists($class)) {
                    $instance = new $class();
                    if (method_exists($instance, $method)) {
                        return $instance->$method($request, $response, $next);
                    }
                }
            } elseif (class_exists($middleware)) {
                $instance = new $middleware();
                if (method_exists($instance, '__invoke')) {
                    return $instance($request, $response, $next);
                }
            }
        }
        return true;
    }
    
    private static function executeHandler($handler, $request, $response) {
        if (is_callable($handler)) {
            $handler($request, $response);
            return;
        } elseif (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                list($class, $method) = explode('@', $handler);
                if (class_exists($class)) {
                    $instance = new $class();
                    if (method_exists($instance, $method)) {
                        $instance->$method($request, $response);
                        return;
                    }
                }
            } elseif (class_exists($handler)) {
                $instance = new $handler();
                if (method_exists($instance, '__invoke')) {
                    $instance($request, $response);
                    return;
                }
            }
        }
        $response->status(500)->send('Invalid handler');
    }
}