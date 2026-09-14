<?php
   class WAF
   {
       private $logFile;
       private $logDir;
       private $log;
       private $customRules = [];
       private $limitCount = 100; // Default limit count for rate limiting 
       private $limitTime = 60; // Default limit time for rate limiting
       private $enabledModules = [
           'sql_injection' => true,
           'xss' => true,
           'rate_limiting' => true,
           'csrf_protection' => true,
           'geo_blocking' => false,
           'security_headers' => true,
       ];
       private $geoBlockCountries = []; // ISO 3166-1 alpha-2 codes, e.g., ['CN', 'RU']
   
       public function __construct($logDir = null)
       {
        
        $this->logDir = $logDir;

        if ($this->logDir === null) {
            $this->logDir = "/tmp/{project_folder_name}/logs/waf/"; // Default log file path}"

            $projectFolderName = basename(dirname(dirname(__DIR__)));
            $this->logDir = str_replace('{project_folder_name}', $projectFolderName, $this->logDir);
        }
        
        // Format logDir to ensure it can be used as a folder
        $this->logDir = rtrim($this->logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        // Ensure the directory for the log file exists
        $this->logFile = $this->logDir . 'waf.log';
        $this->log = new Logger($this->logDir);

       }

       public function setLimitCounter($limit = 100){
              $this->limitCount = $limit; // Set the limit count for rate limiting
       }

       public function setLimitTime($time = 60){
              $this->limitTime = $time; // Set the limit time for rate limiting
       }    
       
       
        /**
        * Enable or disable specific protection modules.
        */
       public function enableModule($module, $status = true)
       {
           $this->enabledModules[$module] = $status;
       }
   
       /**
        * Add custom rules for filtering.
        */
       public function addCustomRule($pattern, $message = 'Custom rule triggered')
       {
           $this->customRules[] = ['pattern' => $pattern, 'message' => $message];
       }
   
       /**
        * Run the WAF.
        */
       public function run()
       {
           if ($this->enabledModules['security_headers']) {
               $this->applySecurityHeaders();
           }
           if ($this->enabledModules['sql_injection']) {
               $this->checkSQLInjection();
           }
           if ($this->enabledModules['xss']) {
               $this->checkXSS();
           }
           if ($this->enabledModules['csrf_protection']) {
               $this->checkCSRF();
           }
           if ($this->enabledModules['geo_blocking']) {
               $this->checkGeoBlocking();
           }
           if ($this->enabledModules['rate_limiting']) {
               $this->checkRateLimiting();
           }
           $this->applyCustomRules();
       }
   
       /**
        * Apply security headers.
        */
       private function applySecurityHeaders()
       {
           header('X-Content-Type-Options: nosniff');
           header('X-Frame-Options: DENY');
           header('X-XSS-Protection: 1; mode=block');
           header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
           header('Content-Security-Policy: default-src \'self\';');
       }
   
       /**
        * Check for SQL Injection attempts.
        */
        private function checkSQLInjection()
        {
            // Advanced SQL keywords and suspicious patterns
            $sqlKeywords = [
                'UNION', 'SELECT', 'FROM', 'DROP', 'WHERE', 'INSERT', 'UPDATE', 'DELETE',
                'TABLE', '--', '#', ';', 'OR', 'AND', 'LIKE', 'CHAR', 'CONCAT', 'BENCHMARK', 'SLEEP'
            ];
            
            foreach ($_GET + $_POST as $key => $value) {
                $value = $this->normalizeSQLInput($value);
        
                // Check for SQL keywords
                foreach ($sqlKeywords as $keyword) {
                    if (stripos($value, $keyword) !== false) {
                        $this->logAndBlock("SQL Injection keyword detected: $keyword in $key", 'WARNING');
                    }
                }
        
                // Check for SQL-like patterns
                if ($this->containsSQLPattern($value)) {
                    $this->logAndBlock("SQL Injection pattern detected in $key: $value", 'WARNING');
                }
            }
        }
        
        /**
         * Normalize the input to detect obfuscated attacks.
         */
        private function normalizeSQLInput($input)
        {
            if (is_array($input)) {
                // Recursively normalize arrays
                return implode(' ', array_map([$this, 'normalizeInput'], $input));
            }
        
            // Decode URL-encoded input
            $input = urldecode($input);
        
            // Remove backticks and other SQL obfuscation
            $input = str_replace(['`', '"', "'", '\\'], '', $input);
        
            // Convert to lowercase for easier matching
            return strtolower(trim($input));
        }
        
        /**
         * Detects common SQL injection patterns.
         */
        private function containsSQLPattern($value)
        {
            // Regex patterns for SQL injection detection
            $patterns = [
                '/(\b(select|union|insert|update|delete|drop|alter)\b.*?\bfrom\b)/i',  // Basic SQL keywords in context
                '/(\b(or|and)\b.*?=)/i',                                             // Logical operators in conditions
                '/(--|#|\/\*)/',                                                     // Comment operators
                '/\b(sleep|benchmark|load_file|outfile|dumpfile)\b/i',               // Dangerous functions
                '/\b(char|concat|substr|ascii|hex|unhex)\b/i'                        // Encoding/obfuscation functions
            ];
        
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    return true;
                }
            }
        
            return false;
        }
        
   
       /**
        * Check for XSS attempts.
        */
        private function checkXSS()
        {
            // Common XSS patterns and vectors
            $xssPatterns = [
                '/(<script.*?>.*?<\/script>)/i',                   // Script tags
                '/(<.*?on\w+=[\'"]?.*?[\'"]?)/i',                  // Inline event handlers (onload, onclick, etc.)
                '/(javascript:)/i',                                // JavaScript URIs
                '/(<iframe.*?>.*?<\/iframe>)/i',                   // Iframes
                '/(data:text\/html;base64,)/i',                    // Base64-encoded payloads
                '/(<.*?style=.*?expression\(.*?\))/i',             // CSS expressions
                '/(vbscript:)/i',                                  // VBScript
                '/(document\.|window\.|eval\(|setTimeout\()/i',    // Dangerous JavaScript functions
            ];
        
            foreach ($_GET + $_POST as $key => $value) {
                $value = $this->normalizeXSSInput($value);
        
                // Check for XSS patterns
                foreach ($xssPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->logAndBlock("XSS attempt detected in $key: $value", 'WARNING');
                    }
                }
            }
        }
        
        /**
         * Normalize the input to detect obfuscated attacks.
         */
        private function normalizeXSSInput($input)
        {
            if (is_array($input)) {
                // Recursively normalize arrays
                return implode(' ', array_map([$this, 'normalizeInput'], $input));
            }
        
            // Decode URL-encoded input
            $input = urldecode($input);
        
            // Decode HTML entities (e.g., `&lt;script&gt;` -> `<script>`)
            $input = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
            // Strip null bytes and excessive whitespace
            $input = str_replace("\0", '', $input);
            $input = preg_replace('/\s+/', ' ', $input);
        
            // Convert to lowercase for case-insensitive matching
            return strtolower(trim($input));
        }
        
   
       /**
        * Apply custom rules.
        */
       private function applyCustomRules()
       {
           foreach ($this->customRules as $rule) {
               foreach ($_GET + $_POST as $key => $value) {
                   if (is_array($value)) {
                       $value = implode(' ', $value);
                   }
                   if (preg_match($rule['pattern'], $value)) {
                       $this->logAndBlock($rule['message'] . " in $key: $value", 'INFO');
                   }
               }
           }
       }
   
       /**
        * Rate Limiting to prevent brute force attacks.
        */
       /*private*/ function checkRateLimiting()
       {
           $ip = $_SERVER['REMOTE_ADDR'];
           $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        //    $rateLimitFile = sys_get_temp_dir() . "/waf_rate_limit_$ip";
            $logDir = dirname($this->logFile);
              if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            
           $rateLimitFile = $this->logDir."/waf_rate_limit_$ip";
        //    var_dump($rateLimitFile);

   
           if (file_exists($rateLimitFile)) {
               $data = json_decode(file_get_contents($rateLimitFile), true);
               if ($data['count'] >= $this->limitCount && (time() - $data['timestamp']) < $this->limitTime) {
                   $this->logAndBlock("Rate limit exceeded for IP $ip (User-Agent: $userAgent)", 'ERROR');
               } elseif ((time() - $data['timestamp']) >= $this->limitTime) {
                   $data = ['count' => 1, 'timestamp' => time()];
               } else {
                   $data['count']++;
               }
           } else {
               $data = ['count' => 1, 'timestamp' => time()];
           }
   
           file_put_contents($rateLimitFile, json_encode($data));
       }
   
       /**
        * Check for CSRF token validity.
        */
       private function checkCSRF()
       {
            $Request = new Request();
            return $Request->checkCSRF();
       }
   
       /**
        * Geo-blocking based on IP address.
        */
       private function checkGeoBlocking()
       {
           $ip = $_SERVER['REMOTE_ADDR'];
           $country = $this->getCountryByIP($ip);
           if (in_array($country, $this->geoBlockCountries)) {
               $this->logAndBlock("Access blocked for country $country (IP: $ip)", 'ERROR');
           }
       }
   
       /**
        * Get country code by IP address.
        */
       private function getCountryByIP($ip)
       {
           // Example using a free geolocation API (replace with a better solution in production)
           $response = @file_get_contents("https://ipapi.co/$ip/country/");
           return $response ?: 'unknown';
       }
   
       /**
        * Log and block the request.
        */
       private function logAndBlock($message, $level = 'INFO')
       {
           $this->log($message, $level);
           
            //   $Response->status(403)->json(["code"=>403, "message"=>'Access Denied']);
            //   exit;
           header('HTTP/1.1 403 Forbidden');
           echo 'Access Denied';
           exit;
       }
   
       /**
        * Log events to the specified log file.
        */
        
        private function log($message)
        {
            $Logger = new Logger($this->logDir);
            $Logger->log($message);
        }
   }
   

?>