<?php
$request_headers = getallheaders();
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    http_response_code(405);
    die;
}

if (!in_array($request_headers['Host'], $headersName)) {
    http_response_code(403);
    die;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['first_name']) || empty($data['phone'])) {
    echo json_encode(['failed' => 'Name and phone are required.']);
    die;
}

$first_name = htmlspecialchars($data['first_name'] ?? '');
$last_name = htmlspecialchars($data['last_name'] ?? '');
$phone = htmlspecialchars($data['phone'] ?? '');
$email = htmlspecialchars($data['email'] ?? '');
$service = htmlspecialchars($data['service'] ?? '');
$property_size = htmlspecialchars($data['property_size'] ?? '');
$postcode = htmlspecialchars($data['postcode'] ?? '');
$notes = htmlspecialchars($data['notes'] ?? '');
$full_name = trim("$first_name $last_name");

$result = [];

try {
    require APP_PATH . '/phpm/PHPMailerAutoload.php';

    // Email to customer (confirmation)
    if (!empty($email)) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $site_email_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $site_email_from;
        $mail->Password = $site_email_password;
        $mail->SMTPSecure = $site_email_smtp_secure_type;
        $mail->Port = $site_email_smtp_port;
        $mail->setFrom($site_email, $site_name);
        $mail->addAddress($email, $full_name);
        $mail->addReplyTo($site_email, $site_name);
        $mail->isHTML(true);
        $mail->Subject = "Quote Request Received - $site_name";
        $mail->Body = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <div style='background: #1A335C; padding: 24px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 22px;'>$site_name</h1>
            </div>
            <div style='padding: 24px; background: #ffffff;'>
                <p style='color: #333; font-size: 15px;'>Dear $first_name,</p>
                <p style='color: #555; line-height: 1.7;'>Thank you for your quote request. We have received your details and will get back to you within 3 hours during business hours (Mon–Sat 7am–7pm).</p>
                <p style='color: #555; line-height: 1.7;'>If your request is urgent, please call us directly at <strong>$site_phone</strong>.</p>
            </div>
            <div style='padding: 16px 24px; background: #f8f8f8; font-size: 13px; color: #888; text-align: center;'>
                $site_name · Edinburgh & Surrounding Areas
            </div>
        </div>";
        $mail->AltBody = "Dear $first_name, thank you for your quote request. We'll be in touch within 3 hours.";
        $mail->send();
    }

    // Email to admin (full details)
    $mail2admin = new PHPMailer;
    $mail2admin->isSMTP();
    $mail2admin->Host = $site_email_smtp_host;
    $mail2admin->SMTPAuth = true;
    $mail2admin->Username = $site_email_from;
    $mail2admin->Password = $site_email_password;
    $mail2admin->SMTPSecure = $site_email_smtp_secure_type;
    $mail2admin->Port = $site_email_smtp_port;
    $mail2admin->setFrom($site_email, $site_name);
    $mail2admin->addAddress($site_email);
    if (!empty($email)) {
        $mail2admin->addReplyTo($email, $full_name);
    }
    $mail2admin->isHTML(true);
    $mail2admin->Subject = "New Quote Request - $service - $full_name";
    $mail2admin->Body = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <h2 style='color: #1A335C; border-bottom: 2px solid #00878A; padding-bottom: 10px;'>New Quote Request</h2>
        <table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";

    $fields = [
        'Name' => $full_name,
        'Phone' => "<a href='tel:$phone'>$phone</a>",
        'Email' => !empty($email) ? "<a href='mailto:$email'>$email</a>" : '—',
        'Service Required' => $service ?: '—',
        'Property Size' => $property_size ?: '—',
        'Postcode' => $postcode ?: '—',
    ];

    foreach ($fields as $label => $value) {
        $mail2admin->Body .= "<tr>
            <td style='padding: 10px; font-weight: bold; color: #333; border-bottom: 1px solid #eee; width: 40%;'>$label</td>
            <td style='padding: 10px; color: #555; border-bottom: 1px solid #eee;'>$value</td>
        </tr>";
    }

    $mail2admin->Body .= "</table>";

    if (!empty($notes)) {
        $mail2admin->Body .= "
        <div style='margin-top: 20px; padding: 15px; background-color: #f9f9f9; border-left: 3px solid #00878A;'>
            <h4 style='margin: 0 0 10px; color: #333;'>Additional Notes</h4>
            <p style='color: #555; line-height: 1.6; margin: 0;'>" . nl2br($notes) . "</p>
        </div>";
    }

    $mail2admin->Body .= "</div>";
    $mail2admin->AltBody = "New Quote Request from $full_name - $phone - $service";

    if ($mail2admin->send()) {
        $result['success'] = "Quote request sent successfully! We'll be in touch shortly.";
    } else {
        $result['failed'] = "Sorry, an error occurred. Please try again or call us directly.";
    }

} catch (Exception $e) {
    $result['failed'] = "Sorry, an error occurred. Please try again or call us directly.";
}

echo json_encode($result);
