<?php
/**
 * The security check on the public forms (the quote forms and the Beauty
 * booking form). Self-hosted: no third-party service.
 *
 * The visitor slides a handle along a track onto a marked target. The track,
 * with its target, is drawn here; the target's position is encrypted into a
 * JWT that the form carries, signed with a key private to this server. The
 * token also holds when it was issued, when it expires and a one-time id, so
 * verifying needs no database: the position never reaches the browser in
 * readable form, a token works once, and a handle left on the target passes.
 *
 * The key is CAPTCHA_SECRET from .env/config.php where that is set. Otherwise
 * the site makes one on first use and keeps it in .env/captcha-key.php, so a
 * new server needs nothing done to it by hand.
 *
 * Where the server cannot draw images (or has no OpenSSL), the check falls
 * back to a small sum, answered by typing.
 *
 * Only if no key can be made or stored is the check off: nothing is shown and
 * requests are not verified, so the forms keep working regardless.
 */

const CAPTCHA_LIFETIME   = 300;   // seconds a challenge stays valid
const CAPTCHA_MIN_AGE    = 2;     // seconds; anything faster is a machine
const CAPTCHA_ITERATIONS = 12000; // makes guessing a typed answer from the token slow
const CAPTCHA_TRACK_W    = 600;   // the track is drawn this wide, then scaled by CSS
const CAPTCHA_TRACK_H    = 64;
const CAPTCHA_HANDLE     = 64;    // the round handle, in the same units
const CAPTCHA_TOLERANCE  = 18;    // how far from the target still counts as on it

function captchaSecret() {
    static $secret = null;
    if ($secret !== null) {
        return $secret;
    }

    $secret = trim((string) getenv('CAPTCHA_SECRET'));
    if ($secret === '') {
        $secret = captchaStoredSecret();
    }

    return $secret;
}

/**
 * The key kept beside the site's settings, made once on the first visit that
 * needs it. Written as PHP returning a string, so that even on a server whose
 * document root is the project itself the key cannot be fetched as a file.
 * Returns '' if it cannot be made or stored, which leaves the check off.
 */
function captchaStoredSecret() {
    $file = D_PATH . '/.env/captcha-key.php';

    if (is_readable($file)) {
        $stored = @include $file;
        if (is_string($stored) && strlen(trim($stored)) >= 32) {
            return trim($stored);
        }
    }

    if (!function_exists('random_bytes') || !is_dir(dirname($file)) || !is_writable(dirname($file))) {
        return '';
    }

    try {
        $secret = bin2hex(random_bytes(32));
    } catch (Exception $e) {
        return '';
    }

    // Written under a unique name first, then moved into place, so that two
    // visits at once cannot leave a half-written key behind.
    $temp = $file . '.' . bin2hex(random_bytes(4)) . '.tmp';
    if (@file_put_contents($temp, "<?php\n\n// Made by the site on first use. Keep it private; changing it only\n// ends the security checks already on people's screens.\nreturn '" . $secret . "';\n", LOCK_EX) === false) {
        return '';
    }
    @chmod($temp, 0600);

    if (!@rename($temp, $file)) {
        @unlink($temp);
        // Another visit may have written it in the meantime.
        $stored = is_readable($file) ? @include $file : null;

        return is_string($stored) && strlen(trim($stored)) >= 32 ? trim($stored) : '';
    }

    return $secret;
}

function captchaEnabled() {
    return captchaSecret() !== '';
}

/** True when this server can draw the track and keep its target secret. */
function captchaCanDrawPuzzle() {
    return function_exists('imagecreatetruecolor') && function_exists('openssl_encrypt');
}

# ---------------------------------------------------------------------------
# Challenge
# ---------------------------------------------------------------------------

/** The target zone's colour, per division. */
function captchaPalette($palette) {
    $colours = [
        'teal' => [22, 163, 116],
        'gold' => [196, 151, 58],
    ];

    return isset($colours[$palette]) ? $colours[$palette] : $colours['teal'];
}

/**
 * A new challenge: the token to post back, plus either the puzzle (picture,
 * piece and how far the slider can travel) or a question to answer. $palette
 * is 'teal' or 'gold', to match the form it sits in.
 */
function captchaIssue($palette = 'teal') {
    $jti = bin2hex(random_bytes(9));
    $now = time();
    $challenge = ['mode' => 'slider'];
    $claims = [];

    if (captchaCanDrawPuzzle()) {
        // Far enough along the track that nudging the handle cannot pass.
        $targetX = random_int((int) (CAPTCHA_TRACK_W * 0.35), CAPTCHA_TRACK_W - CAPTCHA_HANDLE);
        $track   = captchaTrack($targetX, $palette);

        if ($track !== null) {
            $challenge += [
                'track'     => $track,
                'trackW'    => CAPTCHA_TRACK_W,
                'maxOffset' => CAPTCHA_TRACK_W - CAPTCHA_HANDLE,
            ];
            $claims['pos'] = captchaSeal($targetX);
        }
    }

    if (!isset($claims['pos'])) {
        $a = random_int(2, 9);
        $b = random_int(2, 9);
        $challenge = ['mode' => 'text', 'question' => "What is $a + $b?"];
        $claims['ans'] = captchaAnswerHash((string) ($a + $b), $jti);
    }

    $challenge['token'] = captchaJwtEncode($claims + [
        'jti' => $jti,
        'iat' => $now,
        'exp' => $now + CAPTCHA_LIFETIME,
    ]);

    return $challenge;
}

/**
 * The track with its target zone, as a PNG data URI. The target's position is
 * only in the picture, never in the page, so a script cannot simply read it.
 * Null if the drawing fails.
 */
function captchaTrack($targetX, $palette = 'teal') {
    list($r, $g, $b) = captchaPalette($palette);
    $w    = CAPTCHA_TRACK_W;
    $h    = CAPTCHA_TRACK_H;
    $size = CAPTCHA_HANDLE;

    $img = @imagecreatetruecolor($w, $h);
    if (!$img) {
        return null;
    }
    imagealphablending($img, true);
    imagesavealpha($img, true);
    imagefilledrectangle($img, 0, 0, $w, $h, imagecolorallocate($img, 238, 241, 245));

    $left  = $targetX + 4;
    $right = $targetX + $size - 5;
    $pad   = 8;
    $fill  = imagecolorallocatealpha($img, $r, $g, $b, 110);
    $line  = imagecolorallocate($img, $r, $g, $b);
    $mid   = (int) ($h / 2);
    $round = $h - 2 * $pad;

    // A pale pill with a dashed edge.
    imagefilledrectangle($img, $left + 12, $pad, $right - 12, $h - $pad, $fill);
    imagefilledellipse($img, $left + 12, $mid, $round, $round, $fill);
    imagefilledellipse($img, $right - 12, $mid, $round, $round, $fill);

    for ($x = $left + 8; $x < $right - 8; $x += 13) {
        $to = (int) min($x + 7, $right - 8);
        imagefilledrectangle($img, (int) $x, $pad - 1, $to, $pad + 1, $line);
        imagefilledrectangle($img, (int) $x, $h - $pad - 1, $to, $h - $pad + 1, $line);
    }
    imagesetthickness($img, 2);
    imagearc($img, $left + 12, $mid, $round, $round, 90, 270, $line);
    imagearc($img, $right - 12, $mid, $round, $round, 270, 90, $line);
    imagesetthickness($img, 1);

    ob_start();
    imagepng($img);
    $png = ob_get_clean();
    imagedestroy($img);

    return 'data:image/png;base64,' . base64_encode($png);
}

# ---------------------------------------------------------------------------
# Markup
# ---------------------------------------------------------------------------

/**
 * The check, placed inside a form just above its submit button. $theme is
 * 'light' or 'dark' to suit the form's background, $palette 'teal' or 'gold'
 * for the division. The slider position (or the typed answer) is posted as
 * captcha_answer, with the token in captcha_token.
 */
function captchaWidget($theme = 'dark', $palette = 'teal') {
    if (!captchaEnabled()) {
        return '';
    }

    $GLOBALS['captchaWidgetShown'] = true;

    $c     = captchaIssue($palette);
    $class = $theme === 'light' ? 'captcha captcha-light' : 'captcha';

    $refresh = '<button type="button" class="captcha-refresh" data-captcha-refresh aria-label="Start again" title="Start again">&#8635;</button>';
    $token   = '<input type="hidden" name="captcha_token" value="' . htmlspecialchars($c['token']) . '">';
    // Left empty by people, filled in by many bots.
    $trap    = '<div class="captcha-trap" aria-hidden="true"><label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>';

    if ($c['mode'] === 'text') {
        return '<div class="' . $class . '" data-captcha>' . $token
             . '<div class="captcha-challenge"><span class="captcha-question" data-captcha-question>' . htmlspecialchars($c['question']) . '</span>' . $refresh . '</div>'
             . '<label class="captcha-field"><span class="captcha-label">Your answer</span>'
             . '<input type="text" name="captcha_answer" class="captcha-input" autocomplete="off" spellcheck="false" maxlength="4" required>'
             . '</label>' . $trap . '</div>';
    }

    $arrow = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 12h14M12 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';

    return '<div class="' . $class . '" data-captcha data-board-width="' . $c['trackW'] . '" data-captcha-palette="' . htmlspecialchars($palette) . '">' . $token
         . '<div class="captcha-challenge"><span class="captcha-label" data-captcha-label>Slide to the target</span>'
         . '<span class="captcha-status" data-captcha-status role="status" aria-live="polite"></span>' . $refresh . '</div>'
         . '<div class="captcha-track" data-captcha-track style="background-image:url(' . $c['track'] . ')">'
         . '<input type="range" name="captcha_answer" class="captcha-slider" data-captcha-slider min="0" max="' . $c['maxOffset'] . '" value="0" step="1" aria-label="Slide the handle to the target">'
         . '<span class="captcha-handle" data-captcha-handle aria-hidden="true">' . $arrow . '</span>'
         . '</div>' . $trap . '</div>';
}

/** The script that moves the piece and fetches a fresh puzzle, for the footer. */
function captchaScript() {
    if (empty($GLOBALS['captchaWidgetShown'])) {
        return '';
    }

    return <<<'JS'
<script>
(function () {
  function place(box) {
    var slider = box.querySelector('[data-captcha-slider]');
    var handle = box.querySelector('[data-captcha-handle]');
    if (!slider || !handle) return;
    var width = parseInt(box.getAttribute('data-board-width'), 10) || 600;
    handle.style.left = (slider.value / width * 100) + '%';
  }

  function load(box) {
    var palette = box.getAttribute('data-captcha-palette') || 'teal';
    fetch('/captcha?palette=' + encodeURIComponent(palette), { headers: { 'Accept': 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (!d || !d.token) return;
        var token = box.querySelector('[name="captcha_token"]');
        if (token) token.value = d.token;

        var track = box.querySelector('[data-captcha-track]');
        if (track && d.track) track.style.backgroundImage = 'url(' + d.track + ')';

        var question = box.querySelector('[data-captcha-question]');
        if (question && d.question) question.textContent = d.question;

        var answer = box.querySelector('[name="captcha_answer"]');
        if (answer) {
          if (d.maxOffset) answer.max = d.maxOffset;
          answer.value = answer.type === 'range' ? 0 : '';
          answer.disabled = false;
        }
        box.classList.remove('is-verified');
        var status = box.querySelector('[data-captcha-status]');
        if (status) { status.textContent = ''; status.classList.remove('is-ok'); }
        if (d.trackW) box.setAttribute('data-board-width', d.trackW);
        place(box);
      })
      .catch(function () {});
  }

  function settle(box) {
    var slider = box.querySelector('[data-captcha-slider]');
    var status = box.querySelector('[data-captcha-status]');
    var token  = box.querySelector('[name="captcha_token"]');
    if (!slider || !token || slider.type !== 'range' || Number(slider.value) === 0) return;

    fetch('/captcha-check', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ captcha_token: token.value, captcha_answer: slider.value })
    })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (d && d.ok) {
          // The slider is hidden from here on, but stays in the form so its
          // position is sent; the server checks it again for real.
          box.classList.add('is-verified');
          if (status) { status.textContent = '\u2713 Verified'; status.classList.add('is-ok'); }
        } else {
          box.classList.remove('is-verified');
          slider.value = 0;
          place(box);
          if (status) { status.textContent = 'Not quite \u2014 try again'; status.classList.remove('is-ok'); }
        }
      })
      .catch(function () {});
  }

  // Called by the form scripts after every attempt: a puzzle works only once.
  window.nsCaptchaRefresh = function (scope) {
    var boxes = (scope || document).querySelectorAll('[data-captcha]');
    for (var i = 0; i < boxes.length; i++) load(boxes[i]);
  };

  document.addEventListener('input', function (e) {
    if (e.target && e.target.hasAttribute && e.target.hasAttribute('data-captcha-slider')) {
      var box = e.target.closest('[data-captcha]');
      var status = box.querySelector('[data-captcha-status]');
      if (status) { status.textContent = ''; status.classList.remove('is-ok'); }
      box.classList.remove('is-verified');
      place(box);
    }
  });

  document.addEventListener('change', function (e) {
    if (e.target && e.target.hasAttribute && e.target.hasAttribute('data-captcha-slider')) {
      settle(e.target.closest('[data-captcha]'));
    }
  });

  document.addEventListener('click', function (e) {
    var button = e.target.closest ? e.target.closest('[data-captcha-refresh]') : null;
    if (!button) return;
    e.preventDefault();
    load(button.closest('[data-captcha]'));
  });

  document.addEventListener('DOMContentLoaded', function () {
    var boxes = document.querySelectorAll('[data-captcha]');
    for (var i = 0; i < boxes.length; i++) place(boxes[i]);
  });
})();
</script>
JS;
}

# ---------------------------------------------------------------------------
# Verification
# ---------------------------------------------------------------------------

/**
 * True when the handle is on the target, without spending the token: used by
 * the page to say "Verified" as soon as the handle is let go. Limited to a few
 * tries per challenge, so the position cannot be searched for.
 */
function captchaPeek($token, $answer) {
    if (!captchaEnabled()) {
        return true;
    }

    $payload = captchaJwtDecode((string) $token);
    if ($payload === null || empty($payload['jti']) || empty($payload['exp']) || $payload['exp'] < time()) {
        return false;
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        $tries = isset($_SESSION['captcha_tries']) && is_array($_SESSION['captcha_tries']) ? $_SESSION['captcha_tries'] : [];
        foreach ($tries as $id => $try) {
            if (($try['exp'] ?? 0) < time()) {
                unset($tries[$id]);
            }
        }

        $count = ($tries[$payload['jti']]['n'] ?? 0) + 1;
        $tries[$payload['jti']] = ['n' => $count, 'exp' => $payload['exp']];
        $_SESSION['captcha_tries'] = array_slice($tries, -50, null, true);

        if ($count > 3) {
            return false;
        }
    }

    return captchaMatches($payload, $answer);
}

/**
 * True when the answer matches the token's challenge. Always true while the
 * check is off. $trap is the hidden field that people leave empty.
 */
function captchaVerify($token, $answer = '', $trap = '') {
    if (!captchaEnabled()) {
        return true;
    }

    if (trim((string) $trap) !== '') {
        return false;
    }

    $payload = captchaJwtDecode((string) $token);
    if ($payload === null) {
        return false;
    }

    $now = time();
    if (empty($payload['jti']) || empty($payload['exp']) || empty($payload['iat'])) {
        return false;
    }
    if ($payload['exp'] < $now || $now - $payload['iat'] < CAPTCHA_MIN_AGE) {
        return false;
    }
    if (!captchaSpend($payload['jti'], $payload['exp'])) {
        return false;
    }

    return captchaMatches($payload, $answer);
}

/** The answer against a token's challenge: on the target, or the right sum. */
function captchaMatches(array $payload, $answer) {
    // The handle has to come to rest on the target.
    if (!empty($payload['pos'])) {
        $target = captchaUnseal($payload['pos']);
        if ($target === null || !is_numeric(trim((string) $answer))) {
            return false;
        }

        return abs((int) round((float) $answer) - (int) $target) <= CAPTCHA_TOLERANCE;
    }

    // The typed answer.
    if (empty($payload['ans'])) {
        return false;
    }
    $given = strtoupper(preg_replace('/\s+/', '', (string) $answer));
    if ($given === '') {
        return false;
    }

    return hash_equals($payload['ans'], captchaAnswerHash($given, $payload['jti']));
}

/** Slow on purpose: guessing a typed answer from a stolen token is not worth it. */
function captchaAnswerHash($answer, $jti) {
    return hash_pbkdf2('sha256', strtoupper((string) $answer), $jti . captchaSecret(), CAPTCHA_ITERATIONS, 64);
}

/** Records a challenge id as used; false if it was already spent. */
function captchaSpend($jti, $expires) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return true; // no session to remember it in; the expiry still applies
    }

    $spent = isset($_SESSION['captcha_spent']) && is_array($_SESSION['captcha_spent']) ? $_SESSION['captcha_spent'] : [];

    $now = time();
    foreach ($spent as $id => $until) {
        if ($until < $now) {
            unset($spent[$id]);
        }
    }

    if (isset($spent[$jti])) {
        $_SESSION['captcha_spent'] = $spent;
        return false;
    }

    $spent[$jti] = $expires;
    $_SESSION['captcha_spent'] = array_slice($spent, -50, null, true);

    return true;
}

# ---------------------------------------------------------------------------
# Hiding the gap's position (AES-256-GCM, key from the secret)
# ---------------------------------------------------------------------------

function captchaSeal($value) {
    $iv  = random_bytes(12);
    $tag = '';
    $ct  = openssl_encrypt((string) $value, 'aes-256-gcm', captchaSealKey(), OPENSSL_RAW_DATA, $iv, $tag);

    return $ct === false ? null : captchaBase64Url($iv . $tag . $ct);
}

/** The sealed value, or null if it was tampered with. */
function captchaUnseal($sealed) {
    $raw = captchaBase64UrlDecode((string) $sealed);
    if (strlen($raw) < 29 || !function_exists('openssl_decrypt')) {
        return null;
    }

    $plain = openssl_decrypt(substr($raw, 28), 'aes-256-gcm', captchaSealKey(), OPENSSL_RAW_DATA, substr($raw, 0, 12), substr($raw, 12, 16));

    return $plain === false ? null : $plain;
}

function captchaSealKey() {
    return hash('sha256', 'captcha-position|' . captchaSecret(), true);
}

# ---------------------------------------------------------------------------
# JWT (HS256)
# ---------------------------------------------------------------------------

function captchaJwtEncode(array $payload) {
    $header = captchaBase64Url(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $body   = captchaBase64Url(json_encode($payload));
    $sig    = captchaBase64Url(hash_hmac('sha256', "$header.$body", captchaSecret(), true));

    return "$header.$body.$sig";
}

/** The payload of a correctly signed token, or null. */
function captchaJwtDecode($jwt) {
    $parts = explode('.', trim($jwt));
    if (count($parts) !== 3) {
        return null;
    }

    list($header, $body, $sig) = $parts;
    $expected = captchaBase64Url(hash_hmac('sha256', "$header.$body", captchaSecret(), true));
    if (!hash_equals($expected, $sig)) {
        return null;
    }

    $meta = json_decode(captchaBase64UrlDecode($header), true);
    if (!is_array($meta) || ($meta['alg'] ?? '') !== 'HS256') {
        return null;
    }

    $payload = json_decode(captchaBase64UrlDecode($body), true);

    return is_array($payload) ? $payload : null;
}

function captchaBase64Url($raw) {
    return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
}

function captchaBase64UrlDecode($text) {
    return (string) base64_decode(strtr($text, '-_', '+/'));
}
