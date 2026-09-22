<?php
/**
 * GET /captcha — a fresh security-check challenge, as JSON.
 *
 * Used by the refresh button and after every form attempt, since a challenge
 * works only once. The answer is never sent here: only the image (or the
 * question) and the signed token that carries its hash.
 */

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

if (!captchaEnabled()) {
    echo json_encode(['failed' => 'The security check is switched off.']);
    die;
}

echo json_encode(captchaIssue(isset($_GET['palette']) ? $_GET['palette'] : 'teal'));
