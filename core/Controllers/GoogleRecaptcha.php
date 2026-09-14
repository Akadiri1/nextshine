<?php

class GoogleRecaptcha
{
    private static $instance = null;

    private $recaptchaId;
    private $recaptchaApiKey;
    private $recaptchaApiUrl;

    // Private constructor prevents direct instantiation
    private function __construct()
    {
        $this->recaptchaId = getenv('GOOGLE_RECAPTCHA_ID');
        $this->recaptchaApiKey = getenv('GOOGLE_RECAPTCHA_API_KEY');
        $this->recaptchaApiUrl = getenv('GOOGLE_RECAPTCHA_API_URL') . $this->recaptchaApiKey;
    }

    // Singleton accessor
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new GoogleRecaptcha();
        }
        return self::$instance;
    }

    public function getRecaptchaId()
    {
        return $this->recaptchaId;
    }

    public function getRecaptchaApiKey()
    {
        return $this->recaptchaApiKey;
    }

    public function getRecaptchaApiUrl()
    {
        return $this->recaptchaApiUrl;
    }

    public function headerJSScript()
    {
        echo '<script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>';

        // Uncomment this if you want to use recaptcha with render key
        // echo '<script src="https://www.google.com/recaptcha/enterprise.js?render=' . $this->recaptchaId . '" async defer></script>';
    }

    public function renderRecaptchaElement($ACTION = 'submit')
    {
        $element = '<div style="margin-left: 10px !important;" class="g-recaptcha" data-sitekey="{RECAPTCHA_SITE_KEY}" data-action="{ACTION}"></div>';
        $element = str_replace('{RECAPTCHA_SITE_KEY}', $this->recaptchaId, $element);
        $element = str_replace('{ACTION}', $ACTION, $element);

        echo $element;
    }

    public function verify($token, $action)
    {
        $payload = [
            'event' => [
                'token' => $token,
                'expectedAction' => $action,
                'siteKey' => $this->recaptchaId,
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->recaptchaApiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['riskAnalysis']['score'])) {
            return $result['riskAnalysis']['score'] >= 0.5;
        }

        return false;
    }
}


/* Usage Example:

$recaptcha = GoogleRecaptcha::getInstance();
$recaptcha->headerJSScript();
$recaptcha->renderRecaptchaElement('submit');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['g-recaptcha-response'];
    $action = 'submit';

    if ($recaptcha->verify($token, $action)) {
        // Proceed with form submission
    } else {
        // Handle verification failure
    }
}

*/