<?php
    
class RouterManager
{
    private $routers = [];

    // Add a router instance to the manager
    public function addRouter($router)
    {
        $this->routers[] = $router;
    }

    // Run all routers in sequence
    public function run()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routers as $router) {
            if ($router->dispatch($requestMethod, $requestUri)) {
                return; // Stop processing if a route matches
            }
        }

        // If no router matched, return 404
        $res = new Response();
        $res->status(404)->json(['message' => 'Route not found']);
    }
}
