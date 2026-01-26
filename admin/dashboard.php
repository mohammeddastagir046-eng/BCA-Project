<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../connect.php';
require_once __DIR__ . '/../send_email.php';

// --------------------------------------------------
// Handle Accept / Reject actions for event bookings
// --------------------------------------------------
if (isset($_GET['action'], $_GET['booking'])) {
    $bid = (int)$_GET['booking'];
    $action = $_GET['action'] === 'accept' ? 'Accepted' : 'Rejected';

    // Update booking status in database
    $stmt = $conn->prepare('UPDATE event_bookings SET status=? WHERE id=?');
    $stmt->bind_param('si', $action, $bid);
    $stmt->execute();

    // Send email notification to user
    $r = $conn->query("SELECT name, email FROM event_bookings WHERE id=$bid");
    if ($r && $row = $r->fetch_assoc()) {
        send_email(
            $row['email'],
            "Your booking #$bid: $action",
            "<p>Hi " . htmlspecialchars($row['name']) . ",<br>Your booking has been <strong>$action</strong>.</p>"
        );
    }

    header('Location: dashboard.php');
    exit;
}

// ----------------------------------------------
// Fetch events and bookings for dashboard tables
// ----------------------------------------------
$events = $conn->query('SELECT * FROM events ORDER BY start_date DESC');
$bookings = $conn->query("
    SELECT eb.*, e.title AS event_title 
    FROM event_bookings eb 
    LEFT JOIN events e ON eb.event_id = e.id 
    ORDER BY eb.created_at DESC
");
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<!-- ✅ Page title and favicon -->
<title>Admin Dashboard — Bookify</title>
<link rel="icon" type="image/jpeg" href="../assets/img/logo.JPG">

<!-- ✅ Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<!-- Admin Navigation -->
<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="#">Admin — Bookify</a>
    <div>
      <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['admin_user']) ?></span>
      <a href="logout.php" class="btn btn-outline-secondary btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <a href="events_add.php" class="btn btn-primary w-100 mb-2">+ Add Event</a>
        <a href="dashboard.php" class="btn btn-outline-secondary w-100">Events</a>
        <a href="dashboard.php#bookings" class="btn btn-outline-secondary w-100 mt-2">Bookings</a>
      </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="col-md-9">

      <!-- Events Table -->
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="mb-3">Events</h5>
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Active</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($events && $events->num_rows > 0): ?>
                <?php while ($e = $events->fetch_assoc()): ?>
                  <tr>
                    <td><?= $e['id'] ?></td>
                    <td><?= htmlspecialchars($e['title']) ?></td>
                    <td><?= htmlspecialchars($e['start_date']) ?></td>
                    <td><?= $e['is_active'] ? 'Yes' : 'No' ?></td>
                    <td>
                      <a href="events_edit.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                      <a href="events_delete.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-danger confirm-delete">Delete</a>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">No events found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Bookings Table -->
      <div class="card shadow-sm" id="bookings">
        <div class="card-body">
          <h5 class="mb-3">Bookings</h5>
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
              <?php if ($bookings && $bookings->num_rows > 0): ?>
                <?php while ($r = $bookings->fetch_assoc()): ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['event_title']) ?></td>
                    <td><?= htmlspecialchars($r['status']) ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td>
                      <?php if ($r['status'] === 'Pending'): ?>
                        <a href="?booking=<?= $r['id'] ?>&action=accept" class="btn btn-sm btn-success">Accept</a>
                        <a href="?booking=<?= $r['id'] ?>&action=reject" class="btn btn-sm btn-danger">Reject</a>
                      <?php else: ?>
                        <span class="text-muted small">No actions</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center text-muted">No bookings found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
