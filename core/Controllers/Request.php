<?php
class Request
{
    public $params;
    public $query;
    public $body;
    public $cookies;
    public $headers;
    public $method;
    public $uri;
    public $client_ip;

    public function __construct($params = [])
    {
        $this->params = $params;
        $this->query = $_GET ?? [];
        $this->cookies = $_COOKIE ?? [];
        $this->body = $this->parseBody();
        $this->headers = $this->getAllHeaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->url_encode_uri = urlencode($_SERVER['REQUEST_URI']);

        $this->client_ip = $this->getClientIp();
        $this->subdomain = $this->subdomain();

    }

    private function parseBody()
    {
        $contentType = $this->getContentType();
        if ($contentType === 'application/json') {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        }
        return $_POST ?? [];
    }

    private function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', strtolower(substr($key, 5)));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    public function getQueryParam($name, $default = null)
    {
        return $this->query[$name] ?? $default;
    }

    public function getBodyParam($name, $default = null)
    {
        return $this->body[$name] ?? $default;
    }

    public function hasQueryParam($name)
    {
        return isset($this->query[$name]);
    }

    public function hasBodyParam($name)
    {
        return isset($this->body[$name]);
    }

    public function getHeader($name, $default = null)
    {
        return $this->headers[$name] ?? $default;
    }

    public function getCookie($name, $default = null)
    {
        return $this->cookies[$name] ?? $default;
    }

    public function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    public function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'] ?? '';
    }

    public function isJson()
    {
        return $this->getContentType() === 'application/json';
    }

    public function isSecure()
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    }

    
    public function file($name)
    {
        if (isset($_FILES[$name])) {
            return new FileUpload($_FILES[$name]);
        }
        return null;
    }

    public function files($name)
    {
        if (!isset($_FILES[$name])) {
            return [];
        }

        $files = $_FILES[$name];
        $fileObjects = [];

        // Check if it's a single file or multiple files
        if (is_array($files['name'])) {
            // Multiple files
            foreach ($files['name'] as $index => $fileName) {
                $fileObjects[] = new FileUpload([
                    'name' => $fileName,
                    'type' => $files['type'][$index],
                    'tmp_name' => $files['tmp_name'][$index],
                    'error' => $files['error'][$index],
                    'size' => $files['size'][$index],
                ]);
            }
        } else {
            // Single file
            $fileObjects[] = new FileUpload($files);
        }

        return $fileObjects;
    }

    public function hasFile($name)
    {
        return isset($_FILES[$name]);
    }


    public function getBearerToken()
    {
        $authHeader = $this->getHeader('authorization', '');
        if ($authHeader === '') {
            $authHeader = $this->getHeader('Authorization', '');
        }
        if (strpos($authHeader, 'Bearer ') === 0) {
            return substr($authHeader, 7);
        }
        return null;
    }

    public function getBasicAuth()
    {
        if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
            return ['user' => $_SERVER['PHP_AUTH_USER'], 'password' => $_SERVER['PHP_AUTH_PW']];
        }
        return null;
    }

    /**
     * CSRF Protection: Validate CSRF token.
     * @throws Exception if token is invalid or missing.
     */
    public function checkCSRF()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); // Start the session if not already started
        }

        $tokenFromRequest = $this->getBodyParam('_csrf') ?? $this->getQueryParam('_csrf') ?? null;
        $tokenFromSession = $_SESSION['_csrf_token'] ?? null;

        if (!$tokenFromRequest || !$tokenFromSession || !hash_equals($tokenFromSession, $tokenFromRequest)) {
            return false;
            throw new Exception('Invalid or missing CSRF token');
        }else{
            return true;
        }
    }

    /**
     * Generate a CSRF token and store it in the session.
     */
    public static function generateCSRFToken()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); // Start the session if not already started
        }

        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure CSRF token
        }

        return $_SESSION['_csrf_token'];
    }

    public static function redirect($url, $statusCode = 302){
        header('Location: '.$url, true, $statusCode);
        exit();
    }

    public static function back(){
        header('Location: '.$_SERVER['HTTP_REFERER']);
        exit();
    }

    public static function isAjax(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public static function isPost(){
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function isGet(){
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function isPut(){
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }   

    public static function isDelete(){
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    public static function isPatch(){
        return $_SERVER['REQUEST_METHOD'] === 'PATCH';
    }

    public static function isOptions(){
        return $_SERVER['REQUEST_METHOD'] === 'OPTIONS';
    }

    public static function isHead(){
        return $_SERVER['REQUEST_METHOD'] === 'HEAD';
    }


    public static function getMethod(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getUri(){
        return $_SERVER['REQUEST_URI'];
    }

    public function cors($config = []) {
        // Default configuration
        $defaults = [
            'allowedOrigins' => ['*'], // Allow all origins by default
            'allowedMethods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
            'allowedHeaders' => ['Content-Type', 'Authorization', 'X-Requested-With'],
            'allowCredentials' => true,
        ];

        // Merge user-provided config with defaults
        $settings = array_merge($defaults, $config);

        // Ensure values are arrays
        $settings['allowedOrigins'] = is_array($settings['allowedOrigins']) ? $settings['allowedOrigins'] : [$settings['allowedOrigins']];
        $settings['allowedMethods'] = is_array($settings['allowedMethods']) ? $settings['allowedMethods'] : [$settings['allowedMethods']];
        $settings['allowedHeaders'] = is_array($settings['allowedHeaders']) ? $settings['allowedHeaders'] : [$settings['allowedHeaders']];

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';

        if (in_array('*', $settings['allowedOrigins']) || in_array($origin, $settings['allowedOrigins'])) {
            header('Access-Control-Allow-Origin: ' . (in_array('*', $settings['allowedOrigins']) ? '*' : $origin));
            header('Access-Control-Allow-Methods: ' . implode(', ', $settings['allowedMethods']));
            header('Access-Control-Allow-Headers: ' . implode(', ', $settings['allowedHeaders']));

            if ($settings['allowCredentials']) {
                header('Access-Control-Allow-Credentials: true');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            // Preflight requests can terminate here
            header('HTTP/1.1 204 No Content');
            exit();
        }
    }

    
    
    
    
    public function validateParams(array $keysToCheck, ?array $array = null){
        if($array === null){
            $array = $this->body;
        }
        $remains = array_diff($keysToCheck, array_keys($array));
        if(empty($remains)){
            return true;
        }else{
            return false;
        }
    }

    public function validateEmptyParams(array $keysToCheck, ?array $array = null){
        if($array === null){
            $array = $this->body;
        }
        $errorArray = [];
        foreach($keysToCheck as $key){
            if(empty($array[$key])){
                $errorArray[] = $key;
            }
        }
        if(empty($errorArray)){
            return true;
        }else{
            return false;
        }
    }

    public function getEmptyParams(array $keysToCheck, ?array $array = null){
        if($array === null){
            $array = $this->body;
        }
        $errorArray = [];
        foreach($keysToCheck as $key){
            if(empty($array[$key])){
                $errorArray[] = $key;
            }
        }
        return $errorArray;

    }

    public function getMissingParams(array $keysToCheck, ?array $array = null){
        if($array === null){
            $array = $this->body;
        }
        return array_values(array_diff($keysToCheck, array_keys($array)));
    }

    public function getMissingAndEmptyParams(array $keysToCheck, ?array $array = null){
        if($array === null){
            $array = $this->body;
        }
        $errorArray = [];
        foreach($keysToCheck as $key){
            if(empty($array[$key])){
                $errorArray[] = $key;
            }
        }
        return array_values(array_diff($keysToCheck, array_keys($array)));
    }


    public function subdomain(){
        if (!defined('APP_DOMAIN')) return null;
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];

        $subdomain = explode(APP_DOMAIN, $host);;
        if (count($subdomain) > 1) {
            $subdomain = $subdomain[0];
        } else {
            $subdomain = null;
        }
        return $subdomain;
    }
    

}
