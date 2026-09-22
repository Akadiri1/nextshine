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

// Security check (controllers/captcha.php): the typed code against the signed
// token it came with, plus the hidden field people leave empty. Skipped while
// CAPTCHA_SECRET is not set.
if (!captchaVerify($data['captcha_token'] ?? '', $data['captcha_answer'] ?? '', $data['website'] ?? '')) {
    echo json_encode(['failed' => 'The security check did not pass, or it has expired. Please try the new one.']);
    die;
}

$beautySite   = selectContent($conn, "settings_beauty_site", ["visibility" => "show"])[0] ?? [];
$businessName = !empty($beautySite['input_footer_name']) ? $beautySite['input_footer_name'] : 'NextShine Beauty';
$recipient    = !empty($beautySite['input_email']) ? $beautySite['input_email'] : $site_email;

$first_name = htmlspecialchars($data['first_name'] ?? '');
$last_name  = htmlspecialchars($data['last_name'] ?? '');
$phone      = htmlspecialchars($data['phone'] ?? '');
$email      = htmlspecialchars($data['email'] ?? '');
$service    = htmlspecialchars($data['service'] ?? '');
$notes      = htmlspecialchars($data['notes'] ?? '');
$full_name  = trim("$first_name $last_name");
$whatsapp   = 'https://wa.me/' . preg_replace('/\D/', '', $data['phone'] ?? '');

$result = [];

try {
    require APP_PATH . '/phpm/PHPMailerAutoload.php';

    // Confirmation to the customer, when they gave an email address. Their
    // details are repeated back so they have a record of the request.
    if ($email !== '') {
        $summary = '';
        foreach ([
            'Name'             => $full_name,
            'Phone / WhatsApp' => $phone,
            'Service'          => $service,
            'Preferred dates & notes' => $notes !== '' ? nl2br($notes) : '',
        ] as $label => $value) {
            if ($value === '') {
                continue;
            }
            $summary .= "<tr>
                <td style='padding: 8px 10px; color: #9A9A9A; border-bottom: 1px solid #F0E8D8; width: 45%;'>$label</td>
                <td style='padding: 8px 10px; color: #1C1C1C; border-bottom: 1px solid #F0E8D8;'>$value</td>
            </tr>";
        }

        $confirm = new PHPMailer;
        $confirm->isSMTP();
        $confirm->Host = $site_email_smtp_host;
        $confirm->SMTPAuth = true;
        $confirm->Username = $site_email_from;
        $confirm->Password = $site_email_password;
        $confirm->SMTPSecure = $site_email_smtp_secure_type;
        $confirm->Port = $site_email_smtp_port;
        $confirm->CharSet = 'UTF-8';
        $confirm->setFrom($site_email, $businessName);
        $confirm->addAddress($email, $full_name);
        $confirm->addReplyTo($site_email, $businessName);
        $confirm->isHTML(true);
        $confirm->Subject = "Booking Request Received - $businessName";
        $confirm->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #4A1028; padding: 24px; text-align: center;'>
                <h1 style='color: #E8C06A; margin: 0; font-size: 22px;'>$businessName</h1>
            </div>
            <div style='padding: 24px; background: #ffffff;'>
                <p style='color: #333; font-size: 15px;'>Dear $first_name,</p>
                <p style='color: #555; line-height: 1.7;'>Thank you for your booking request. We have received your details and will be in touch within 24 hours by WhatsApp or phone to confirm availability and pricing.</p>
                <p style='color: #333; font-size: 14px; font-weight: bold; margin: 24px 0 8px;'>What you sent us</p>
                <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>$summary</table>
            </div>
            <div style='padding: 16px 24px; background: #FAF6EF; font-size: 13px; color: #888; text-align: center;'>
                $businessName · Edinburgh, Scotland
            </div>
        </div>";
        $confirm->AltBody = "Dear $first_name, thank you for your booking request. We will be in touch within 24 hours by WhatsApp or phone.\n\n"
            . "What you sent us:\nName: $full_name\nPhone / WhatsApp: $phone\nService: $service\n"
            . ($notes !== '' ? "Preferred dates & notes: $notes\n" : '');

        if (!$confirm->send()) {
            error_log('Booking confirmation email failed: ' . $confirm->ErrorInfo);
        }
    }

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
    if ($email !== '') {
        $mail->addReplyTo($email, $full_name);
    }
    $mail->isHTML(true);
    $mail->Subject = "New Booking Request - $service - $full_name";

    $mail->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <h2 style='color: #4A1028; border-bottom: 2px solid #C4973A; padding-bottom: 10px;'>New Booking Request</h2>
        <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";

    $fields = [
        'Name'              => $full_name,
        'Phone / WhatsApp'  => "<a href='tel:$phone'>$phone</a> · <a href='$whatsapp'>WhatsApp</a>",
        'Email'             => $email !== '' ? "<a href='mailto:$email'>$email</a>" : '—',
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
        // Logged so a mail problem (wrong host, certificate, login) can be
        // identified from the server's PHP log; the visitor sees a plain message.
        error_log('Booking request email failed: ' . $mail->ErrorInfo);
        $result['failed'] = "Sorry, something went wrong. Please message us on WhatsApp instead.";
    }

} catch (Exception $e) {
    error_log('Booking request email failed: ' . $e->getMessage());
    $result['failed'] = "Sorry, something went wrong. Please message us on WhatsApp instead.";
}

echo json_encode($result);
