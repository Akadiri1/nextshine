<?php

class JWTHandler
{
    private $secretKey;
    private $algorithm;

    public function __construct($secretKey = APP_SECRET_KEY, $algorithm = 'HS256')
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }


    /**
     * Set the secret key dynamically.
     *
     * @param string $secretKey The secret key to use.
     * @return void
     */
    public function setSecretKey(string $secretKey): void
    {
        $this->secretKey = $secretKey;
    }


    /**
     * Set the algorithm dynamically.
     *
     * @param string $algorithm The algorithm to use (e.g., HS256, HS384, HS512).
     * @return void
     * @throws Exception If the algorithm is unsupported.
     */
    public function setAlgorithm(string $algorithm): void
    {
        $supportedAlgorithms = ['HS256', 'HS384', 'HS512'];
        if (!in_array($algorithm, $supportedAlgorithms, true)) {
            throw new Exception("Unsupported algorithm: $algorithm");
        }
        $this->algorithm = $algorithm;
    }


    /**
     * Generate a JWT.
     *
     * @param array $payload The data to include in the token.
     * @param int $expiry Expiration time in seconds.
     * @return string
     */
    public function generateToken(array $payload, int $expiry = 3600): string
    {
        $header = $this->base64UrlEncode(json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]));
        if (!isset($payload['iat'])) {
            $payload['iat'] = time();
        }
        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + $expiry;
        }
        $payload = $this->base64UrlEncode(json_encode($payload));

        $signature = $this->generateSignature("$header.$payload");
        return "$header.$payload.$signature";
    }

    /**
     * Decode a JWT and validate its signature and expiration.
     *
     * @param string $token The JWT.
     * @return array The decoded payload.
     * @throws Exception If the token is invalid or expired.
     */
    public function decodeToken(string $token): array
    {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new Exception('Invalid token structure.');
        }

        list($header, $payload, $signature) = $parts;

        // Verify signature
        $validSignature = $this->generateSignature("$header.$payload");
        if (!hash_equals($validSignature, $signature)) {
            throw new Exception('Invalid token signature.');
        }

        // Decode payload
        $payload = json_decode($this->base64UrlDecode($payload), true);
        if (!$payload) {
            throw new Exception('Invalid payload encoding.');
        }

        // Validate expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new Exception('Token has expired.');
        }

        return $payload;
    }

    /**
     * Validate a JWT.
     *
     * @param string $token The JWT to validate.
     * @return bool True if the token is valid, false otherwise.
     */
    public function validateToken(string $token)
    {
        try {
            // $this->decodeToken($token);
            return $this->decodeToken($token);
            // return true; // Token is valid
        } catch (Exception $e) {
            return false; // Token is invalid
        }
    }

    /**
     * Generate the HMAC signature for the token.
     *
     * @param string $data The data to sign.
     * @return string The signature.
     */
    private function generateSignature(string $data): string
    {
        return $this->base64UrlEncode(hash_hmac($this->getAlgorithm(), $data, $this->secretKey, true));
    }

    /**
     * Map algorithm name to hash_hmac function.
     *
     * @return string
     */
    private function getAlgorithm(): string
    {
        $algorithms = [
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512',
        ];

        if (!isset($algorithms[$this->algorithm])) {
            throw new Exception("Unsupported algorithm: {$this->algorithm}");
        }

        return $algorithms[$this->algorithm];
    }

    /**
     * Encode data in Base64 URL format.
     *
     * @param string $data The data to encode.
     * @return string
     */
    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Decode data from Base64 URL format.
     *
     * @param string $data The encoded data.
     * @return string
     */
    private function base64UrlDecode(string $data): string
    {
        return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
    }
}
