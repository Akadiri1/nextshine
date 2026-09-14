<?php

/**
 * FacebookAuth Class
 * Handles verification of Facebook OAuth2 access tokens and user data retrieval
 */
class FacebookAuth
{
    /** @var string Facebook Graph API version */
    private const API_VERSION = 'v19.0';
    
    /** @var string Facebook Graph API base URL */
    private const API_BASE_URL = 'https://graph.facebook.com/';
    
    /** @var string App ID from Facebook Developer Console */
    private $appId;
    
    /** @var string App Secret from Facebook Developer Console */
    private $appSecret;
    
    /** @var array Default fields to request from Facebook */
    private const DEFAULT_FIELDS = [
        'id',
        'email',
        'name',
        'first_name',
        'last_name',
        'picture.width(200).height(200)',
        'birthday',
        'gender'
    ];

    /**
     * Constructor
     * 
     * @param string $appId Facebook App ID
     * @param string $appSecret Facebook App Secret
     * @throws InvalidArgumentException if credentials are empty
     */
    public function __construct(string $appId, string $appSecret)
    {
        if (empty($appId) || empty($appSecret)) {
            throw new InvalidArgumentException('Facebook App ID and App Secret cannot be empty');
        }
        
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * Verify a Facebook access token and get user data
     * 
     * @param string $accessToken The access token to verify
     * @param array $fields Optional array of specific fields to request
     * @return array User data if token is valid
     * @throws Exception If token is invalid or verification fails
     */
    public function verifyTokenAndGetUser(string $accessToken, array $fields = []): array
    {
        try {
            // First verify the token
            $this->verifyAccessToken($accessToken);
            
            // If verification passed, get user data
            return $this->getUserData($accessToken, $fields);
            
        } catch (Exception $e) {
            throw new Exception('Facebook authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Verify a Facebook access token
     * 
     * @param string $accessToken The access token to verify
     * @return bool True if token is valid
     * @throws Exception If token is invalid
     */
    public function verifyAccessToken(string $accessToken): bool
    {
        // Construct debug token URL
        $url = self::API_BASE_URL . self::API_VERSION . '/debug_token?' . http_build_query([
            'input_token' => $accessToken,
            'access_token' => $this->appId . '|' . $this->appSecret
        ]);

        try {
            $response = $this->makeRequest($url);
            
            if (!isset($response['data'])) {
                throw new Exception('Invalid response from Facebook');
            }

            $data = $response['data'];

            // Verify the token
            if (!isset($data['is_valid']) || $data['is_valid'] !== true) {
                throw new Exception('Invalid token');
            }

            // Verify app ID
            if (!isset($data['app_id']) || $data['app_id'] !== $this->appId) {
                throw new Exception('Token was not issued for this app');
            }

            // Check if token is expired
            if (isset($data['expires_at']) && $data['expires_at'] < time()) {
                throw new Exception('Token has expired');
            }

            return true;

        } catch (Exception $e) {
            throw new Exception('Token verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Get user data from Facebook
     * 
     * @param string $accessToken Valid access token
     * @param array $fields Optional specific fields to request
     * @return array User data
     * @throws Exception If data cannot be retrieved
     */
    public function getUserData(string $accessToken, array $fields = []): array
    {
        // Use default fields if none specified
        $requestFields = !empty($fields) ? $fields : self::DEFAULT_FIELDS;
        
        // Construct user data URL
        $url = self::API_BASE_URL . self::API_VERSION . '/me?' . http_build_query([
            'fields' => implode(',', $requestFields),
            'access_token' => $accessToken
        ]);

        try {
            $userData = $this->makeRequest($url);
            
            // Verify we got a user ID at minimum
            if (!isset($userData['id'])) {
                throw new Exception('Could not retrieve user ID');
            }

            return $userData;

        } catch (Exception $e) {
            throw new Exception('Failed to get user data: ' . $e->getMessage());
        }
    }

    /**
     * Make an HTTP request to Facebook API
     * 
     * @param string $url The URL to request
     * @return array Decoded JSON response
     * @throws Exception If request fails
     */
    private function makeRequest(string $url): array
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HEADER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception('Request failed: ' . $error);
        }
        
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('Facebook API error with status code: ' . $httpCode);
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from Facebook');
        }

        if (isset($data['error'])) {
            throw new Exception('Facebook API error: ' . ($data['error']['message'] ?? 'Unknown error'));
        }

        return $data;
    }

    /**
     * Generate a Facebook Login URL
     * 
     * @param string $redirectUri Where to redirect after login
     * @param array $permissions Permissions to request
     * @return string Login URL
     */
    public function getLoginUrl(string $redirectUri, array $permissions = ['email']): string
    {
        $params = [
            'client_id' => $this->appId,
            'redirect_uri' => $redirectUri,
            'state' => $this->generateState(),
            'scope' => implode(',', $permissions)
        ];

        return 'https://www.facebook.com/' . self::API_VERSION . '/dialog/oauth?' . 
               http_build_query($params);
    }

    /**
     * Generate a random state parameter for OAuth
     * 
     * @return string Random state string
     */
    private function generateState(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Exchange authorization code for access token
     * 
     * @param string $code Authorization code from Facebook
     * @param string $redirectUri Same redirect URI used in getLoginUrl
     * @return array Token data including access_token
     * @throws Exception If exchange fails
     */
    public function getAccessToken(string $code, string $redirectUri): array
    {
        $url = self::API_BASE_URL . self::API_VERSION . '/oauth/access_token?' . http_build_query([
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri' => $redirectUri,
            'code' => $code
        ]);

        try {
            return $this->makeRequest($url);
        } catch (Exception $e) {
            throw new Exception('Failed to get access token: ' . $e->getMessage());
        }
    }
}