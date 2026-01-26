<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../connect.php';
require_once __DIR__ . '/../send_email.php';

// ----------------------------------------------
// Handle booking status updates (Accept / Reject)
// ----------------------------------------------
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'] === 'accept' ? 'Accepted' : 'Rejected';

    // Update booking status in database
    $stmt = $conn->prepare('UPDATE event_bookings SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $action, $id);
    $stmt->execute();

    // Fetch user details for email notification
    $r = $conn->query("SELECT name, email FROM event_bookings WHERE id = $id");
    if ($r && $row = $r->fetch_assoc()) {
        send_email(
            $row['email'],
            "Booking #$id - $action",
            "<p>Hi " . htmlspecialchars($row['name']) . ", your booking has been <strong>$action</strong>.</p>"
        );
    }

    header('Location: bookings.php');
    exit;
}

// -----------------------------------
// Fetch all bookings with event title
// -----------------------------------
$rows = $conn->query("
    SELECT eb.*, e.title AS event_title
    FROM event_bookings eb
    LEFT JOIN events e ON eb.event_id = e.id
    ORDER BY eb.created_at DESC
");
?>

<!doctype html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bookings - Admin | Bookify</title>

<!-- ✅ Favicon -->
<link rel="icon" type="image/jpeg" href="../assets/img/logo.JPG">

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-4">
  <a href="dashboard.php" class="btn btn-link">&larr; Back</a>
  <h4>Bookings</h4>

  <div class="card p-3 mt-3 shadow-sm border-0">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Event</th>
          <th>Status</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php while ($r = $rows->fetch_assoc()): ?>
          <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['event_title']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= $r['created_at'] ?></td>
            <td>
              <?php if ($r['status'] === 'Pending'): ?>
                <a href="?id=<?= $r['id'] ?>&action=accept" class="btn btn-sm btn-success">Accept</a>
                <a href="?id=<?= $r['id'] ?>&action=reject" class="btn btn-sm btn-danger">Reject</a>
              <?php else: ?>
                <span class="text-muted small">No actions</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
