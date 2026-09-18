<?php
/**
 * Cloudflare Turnstile: the security check on the public forms (the quote
 * forms and the Beauty booking form).
 *
 * Keys come from .env/config.php (TURNSTILE_SITE_KEY, TURNSTILE_SECRET_KEY),
 * never the database, which the admin CRUD endpoints can read. With either
 * key empty the check is off: no widget is shown and requests are not
 * verified, so the forms keep working on a server that has no keys yet.
 */

function captchaSiteKey() {
    return trim((string) getenv('TURNSTILE_SITE_KEY'));
}

function captchaEnabled() {
    return captchaSiteKey() !== '' && trim((string) getenv('TURNSTILE_SECRET_KEY')) !== '';
}

/**
 * The widget, placed inside a form just above its submit button. $theme is
 * 'light' or 'dark' to suit the form's background. Its single-use token is
 * posted as captcha_token.
 */
function captchaWidget($theme = 'auto') {
    if (!captchaEnabled()) {
        return '';
    }

    $GLOBALS['captchaWidgetShown'] = true;

    return '<div class="cf-turnstile" data-sitekey="' . htmlspecialchars(captchaSiteKey()) . '"'
         . ' data-theme="' . htmlspecialchars($theme) . '" data-size="flexible"'
         . ' data-response-field-name="captcha_token"></div>';
}

/**
 * The Turnstile script, for the footer. Only output on pages that showed a
 * widget.
 */
function captchaScript() {
    if (empty($GLOBALS['captchaWidgetShown'])) {
        return '';
    }

    return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
}

/**
 * Checks a widget token with Cloudflare. Always true while the check is off.
 * Fails closed: if Cloudflare cannot be reached, the request is refused.
 */
function captchaVerify($token) {
    if (!captchaEnabled()) {
        return true;
    }

    $token = trim((string) $token);
    if ($token === '' || strlen($token) > 2048) {
        return false;
    }

    $url    = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $fields = http_build_query([
        'secret'   => trim((string) getenv('TURNSTILE_SECRET_KEY')),
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $error = '';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $fields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            $error = curl_error($ch);
        }
        unset($ch); // releases the handle (curl_close() is deprecated from PHP 8.5)
    } else {
        $body = @file_get_contents($url, false, stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $fields,
            'timeout' => 10,
        ]]));
    }

    $result = is_string($body) ? json_decode($body, true) : null;
    if (!is_array($result)) {
        // Cloudflare could not be reached or sent something unexpected, e.g.
        // PHP has no CA bundle (curl.cainfo). Logged for whoever runs the
        // server; the visitor is asked to try again.
        error_log('Turnstile: could not verify with Cloudflare (' . ($error ?: 'unexpected reply') . ')');
        return false;
    }

    return !empty($result['success']);
}
