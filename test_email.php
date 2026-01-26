<?php
require_once 'send_email.php'; // uses config.php internally

$to = 'your-test-email@example.com'; // put an email you can read (it can be the same Gmail)
$subject = 'Bookify SMTP test';
$body_html = '<p>This is a <strong>test</strong> from Bookify using Gmail SMTP.</p>';
$ok = send_email($to, $subject, $body_html);

if ($ok) {
    echo "✅ Email sent successfully. Check your inbox (and spam).";
} else {
    echo "❌ Email failed to send. Check the server logs and config.php.";
}
