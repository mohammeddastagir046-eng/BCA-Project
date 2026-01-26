<?php
require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| Email Sending Utility (PHPMailer + Fallback)
|--------------------------------------------------------------------------
| This function handles all outgoing emails in the system.
| - Uses PHPMailer with Gmail SMTP if available.
| - Falls back to PHP's native mail() if PHPMailer isn't installed.
|--------------------------------------------------------------------------
*/

function send_email($to, $subject, $body_html, $body_text = null) {
    // Generate plain-text version if not provided
    $body_text = $body_text ?? strip_tags($body_html);

    // ✅ Use PHPMailer if available
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require __DIR__ . '/vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = MAIL_PORT;

            // Sender and recipient
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);

            // Message details
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body_html;
            $mail->AltBody = $body_text;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    // ⚙️ Fallback: use PHP's built-in mail() function
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";

    return mail($to, $subject, $body_html, $headers);
}
?>
