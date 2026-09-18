<?php
/**
 * POST /beauty/booking-request
 *
 * Receives the appointment form (JSON, fields named as in
 * www/assets/js/beauty.js) and emails it to NextShine Beauty. The form has no
 * email field, so there is no customer confirmation; the business replies by
 * WhatsApp or phone. Responds with {success} or {failed}.
 */

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die;
}

// Accept requests from an allowed host, or from a page on this same host.
$currentHost = $_SERVER['HTTP_HOST'] ?? '';
$headersList = $headersName ?? [];
if (!empty($headersList) && !in_array($currentHost, $headersList)) {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if (empty($referer) || strpos($referer, $currentHost) === false) {
        http_response_code(403);
        echo json_encode(['failed' => 'Unauthorized request.']);
        die;
    }
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

if (empty($data['first_name']) || empty($data['phone']) || empty($data['service'])) {
    echo json_encode(['failed' => 'Please add your name, phone number and the service you want.']);
    die;
}

// Cloudflare Turnstile security check (controllers/captcha.php). Skipped while
// the Turnstile keys are not set.
if (!captchaVerify($data['captcha_token'] ?? '')) {
    echo json_encode(['failed' => 'Please complete the security check and try again.']);
    die;
}

$beautySite   = selectContent($conn, "settings_beauty_site", ["visibility" => "show"])[0] ?? [];
$businessName = !empty($beautySite['input_footer_name']) ? $beautySite['input_footer_name'] : 'NextShine Beauty';
$recipient    = !empty($beautySite['input_email']) ? $beautySite['input_email'] : $site_email;

$first_name = htmlspecialchars($data['first_name'] ?? '');
$last_name  = htmlspecialchars($data['last_name'] ?? '');
$phone      = htmlspecialchars($data['phone'] ?? '');
$service    = htmlspecialchars($data['service'] ?? '');
$notes      = htmlspecialchars($data['notes'] ?? '');
$full_name  = trim("$first_name $last_name");
$whatsapp   = 'https://wa.me/' . preg_replace('/\D/', '', $data['phone'] ?? '');

$result = [];

try {
    require APP_PATH . '/phpm/PHPMailerAutoload.php';

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = $site_email_smtp_host;
    $mail->SMTPAuth = true;
    $mail->Username = $site_email_from;
    $mail->Password = $site_email_password;
    $mail->SMTPSecure = $site_email_smtp_secure_type;
    $mail->Port = $site_email_smtp_port;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($site_email, $businessName);
    $mail->addAddress($recipient);
    $mail->isHTML(true);
    $mail->Subject = "New Booking Request - $service - $full_name";

    $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <h2 style='color: #4A1028; border-bottom: 2px solid #C4973A; padding-bottom: 10px;'>New Booking Request</h2>
        <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";

    $fields = [
        'Name'              => $full_name,
        'Phone / WhatsApp'  => "<a href='tel:$phone'>$phone</a> · <a href='$whatsapp'>WhatsApp</a>",
        'Service'           => $service,
    ];
    foreach ($fields as $label => $value) {
        $mail->Body .= "<tr>
            <td style='padding: 10px; font-weight: bold; color: #333; border-bottom: 1px solid #eee; width: 40%;'>$label</td>
            <td style='padding: 10px; color: #555; border-bottom: 1px solid #eee;'>$value</td>
        </tr>";
    }
    $mail->Body .= "</table>";

    if (!empty($notes)) {
        $mail->Body .= "
        <div style='margin-top: 20px; padding: 15px; background-color: #FAF6EF; border-left: 3px solid #C4973A;'>
            <h4 style='margin: 0 0 10px; color: #333;'>Preferred Dates &amp; Notes</h4>
            <p style='color: #555; line-height: 1.6; margin: 0;'>" . nl2br($notes) . "</p>
        </div>";
    }

    $mail->Body .= "</div>";
    $mail->AltBody = "New booking request from $full_name - $phone - $service";

    if ($mail->send()) {
        $result['success'] = "Booking request sent.";
    } else {
        $result['failed'] = "Sorry, something went wrong. Please message us on WhatsApp instead.";
    }

} catch (Exception $e) {
    $result['failed'] = "Sorry, something went wrong. Please message us on WhatsApp instead.";
}

echo json_encode($result);
