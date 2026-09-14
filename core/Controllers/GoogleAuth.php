<?php

/**
 * GoogleAuth Class
 * Handles verification of Google OAuth2 ID tokens
 */
class GoogleAuth
{
    /** @var string Google's public certificate URL */
    private const GOOGLE_CERTS_URL = 'https://www.googleapis.com/oauth2/v1/certs';
    
    /** @var string The Google client ID to verify against */
    private $clientId;
    
    /** @var array Cache for Google certificates */
    private static $certCache = [];
    
    /** @var int Certificate cache duration (5 minutes) */
    private const CERT_CACHE_DURATION = 300;
    
    /** @var int When the cert cache was last updated */
    private static $certCacheTime = 0;

    /**
     * Constructor
     * 
     * @param string $clientId Google Client ID
     */
    public function __construct()
    {
        $this->clientId = GOOGLE_CLIENT_ID;
    }

    /**
     * Verify a Google ID token
     * 
     * @param string $idToken The token to verify
     * @return array The verified token payload
     * @throws Exception If verification fails
     */
    public function verifyIdToken(string $idToken): array
    {
        try {
            // Parse the JWT
            $token = $this->parseJwt($idToken);
            
            // Get Google certificates
            $certs = $this->getGoogleCertificates();
            
            // Verify the token
            if (!$this->verifySignature($token, $certs)) {
                throw new Exception('Invalid token signature');
            }
            
            // Verify token claims
            $this->verifyClaims($token['payload']);
            
            return $token['payload'];
            
        } catch (Exception $e) {
            throw new Exception('Token verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Parse a JWT token into its components
     * 
     * @param string $jwt The JWT token
     * @return array The parsed token parts
     * @throws Exception If the token format is invalid
     */
    private function parseJwt(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new Exception('Invalid token format');
        }

        return [
            'header' => json_decode($this->base64UrlDecode($parts[0]), true),
            'payload' => json_decode($this->base64UrlDecode($parts[1]), true),
            'signature' => $this->base64UrlDecode($parts[2]),
            'signing_input' => $parts[0] . '.' . $parts[1]
        ];
    }

    /**
     * Verify the token signature
     * 
     * @param array $token The parsed token
     * @param array $certificates Available Google certificates
     * @return bool Whether the signature is valid
     * @throws Exception If verification fails
     */
    private function verifySignature(array $token, array $certificates): bool
    {
        if (!isset($token['header']['kid'])) {
            throw new Exception('Token header missing key ID');
        }

        $kid = $token['header']['kid'];
        if (!isset($certificates[$kid])) {
            throw new Exception('Certificate not found for key: ' . $kid);
        }

        $cert = $certificates[$kid];
        
        // Verify the signature
        $verified = openssl_verify(
            $token['signing_input'],
            $token['signature'],
            $cert,
            OPENSSL_ALGO_SHA256
        );

        if ($verified === 1) {
            return true;
        } elseif ($verified === 0) {
            throw new Exception('Invalid signature');
        } else {
            throw new Exception('OpenSSL error: ' . openssl_error_string());
        }
    }

    /**
     * Verify the token claims
     * 
     * @param array $payload The token payload
     * @throws Exception If any claims are invalid
     */
    private function verifyClaims(array $payload): void
    {
        $time = time();

        // Verify required claims
        $requiredClaims = ['iss', 'sub', 'aud', 'exp', 'iat'];
        foreach ($requiredClaims as $claim) {
            if (!isset($payload[$claim])) {
                throw new Exception("Missing required claim: {$claim}");
            }
        }

        // Verify issuer
        $validIssuers = ['accounts.google.com', 'https://accounts.google.com'];
        if (!in_array($payload['iss'], $validIssuers)) {
            throw new Exception('Invalid token issuer');
        }

        // Verify audience
        if ($payload['aud'] !== $this->clientId) {
            throw new Exception('Invalid token audience');
        }

        // Verify expiration
        if ($payload['exp'] < $time) {
            throw new Exception('Token has expired');
        }

        // The nbf value from the payload
        $nbfTime = $payload['nbf'];  // Example: 1737374893

        // Convert both to UTC for comparison

        // Verify not before time if present
        if (isset($payload['nbf']) && ($payload['nbf'] > $time)) {
            throw new Exception('Token not yet valid');
        }

        // Verify issued at time
        if ($payload['iat'] > $time + 300) { // Allow 5 minutes clock skew
            throw new Exception('Token issued in the future');
        }
    }

    /**
     * Fetch and cache Google's public certificates
     * 
     * @return array Array of certificates
     * @throws Exception If certificates cannot be fetched
     */
    private function getGoogleCertificates(): array
    {
        // Check cache
        if (!empty(self::$certCache) && 
            (time() - self::$certCacheTime) < self::CERT_CACHE_DURATION) {
            return self::$certCache;
        }

        // Fetch new certificates
        $response = file_get_contents(self::GOOGLE_CERTS_URL);
        if ($response === false) {
            throw new Exception('Failed to fetch Google certificates');
        }

        $certs = json_decode($response, true);
        if (!is_array($certs)) {
            throw new Exception('Invalid certificate response');
        }

        // Process and cache certificates
        $processed = [];
        foreach ($certs as $kid => $cert) {
            $publicKey = openssl_pkey_get_public($cert);
            if ($publicKey === false) {
                continue; // Skip invalid certificates
            }
            $processed[$kid] = $publicKey;
        }

        if (empty($processed)) {
            throw new Exception('No valid certificates found');
        }

        self::$certCache = $processed;
        self::$certCacheTime = time();

        return $processed;
    }

    /**
     * Base64Url decode a string
     * 
     * @param string $input The string to decode
     * @return string The decoded string
     */
    private function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $input .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Clean up resources
     */
    public function __destruct()
    {
        // Free any cached certificates
        foreach (self::$certCache as $cert) {
            if (is_resource($cert)) {
                openssl_free_key($cert);
            }
        }
        self::$certCache = [];
    }
}