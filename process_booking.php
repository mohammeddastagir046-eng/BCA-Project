<?php
require_once 'connect.php';
require_once 'send_email.php';

/*
|--------------------------------------------------------------------------
| Event Booking Processor
|---------------------------------------------------------------------------
| This script handles user event booking submissions.
| It validates inputs, stores booking data, and sends a confirmation email.
|--------------------------------------------------------------------------
*/

// -------------------------------
// 1️⃣ Validate user inputs
// -------------------------------
$name = trim($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$event_id = intval($_POST['event_id'] ?? 0);

if (!$name || !$email || !$event_id) {
    die('Missing required fields.');
}

// -------------------------------
// 2️⃣ Fetch event details
// -------------------------------
$event_stmt = $conn->prepare("
    SELECT title, type, description, start_date, location
    FROM events
    WHERE id = ?
");
$event_stmt->bind_param('i', $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();
$event_stmt->close();

if (!$event) {
    die('Invalid event selected.');
}

// Escape and format event details
$event_name = htmlspecialchars($event['title']);
$event_type = htmlspecialchars($event['type'] ?: 'General');
$event_desc = nl2br(htmlspecialchars($event['description']));
$event_date = date('F j, Y', strtotime($event['start_date']));
$event_location = htmlspecialchars($event['location'] ?: 'Not specified');

// -------------------------------
// 3️⃣ Save booking in database
// -------------------------------
$status = 'Pending';
$stmt = $conn->prepare("
    INSERT INTO event_bookings (event_id, name, email, status, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
$stmt->bind_param('isss', $event_id, $name, $email, $status);
$ok = $stmt->execute();
$stmt->close();

if (!$ok) {
    die('Database error: ' . $conn->error);
}

// -------------------------------
// 4️⃣ Send confirmation email
// -------------------------------
$subject = "Booking Received — $event_name | Bookify";
$body = "
    <p>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
    <p>🎉 Your booking for the event <strong>$event_name</strong> has been received successfully.</p>
    <p><strong>Event Details:</strong></p>
    <ul>
        <li><strong>Type:</strong> $event_type</li>
        <li><strong>Date:</strong> $event_date</li>
        <li><strong>Location:</strong> $event_location</li>
    </ul>
    <p>$event_desc</p>
    <p>Please wait for admin confirmation. You will receive another email once your booking is approved.</p>
    <br>
    <p>Thank you,<br><strong>Bookify Team</strong></p>
";

send_email($email, $subject, $body);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Booking Successful</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <!-- ✅ Booking Success Message -->
  <div class="card shadow-lg p-4 text-center" style="max-width: 420px;">
    <h3 class="text-success mb-3">🎉 Booking Successful!</h3>
    <p class="mb-2">Your booking for <strong><?= $event_name ?></strong> has been successfully submitted.</p>
    <p class="text-muted">Please wait for admin confirmation.</p>
    <hr>
    <a href="index.php" class="btn btn-primary mt-2">Go Back to Home</a>
  </div>

</body>
</html>
