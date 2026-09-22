<?php
/**
 * POST /captcha-check — is the handle on the target?
 *
 * Answers the page so it can say "Verified" as soon as the handle is let go.
 * This does not spend the token: the form still has to pass the full check
 * when it is sent. A few tries per challenge are allowed, no more.
 */

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    die;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data)) {
    $data = [];
}

echo json_encode(['ok' => captchaPeek($data['captcha_token'] ?? '', $data['captcha_answer'] ?? '')]);
