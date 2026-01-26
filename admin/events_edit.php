<?php
session_start();

// ✅ Allow only logged-in admins to access this page
if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../connect.php';

// ✅ Get event ID from query string
$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Event ID missing');

// ✅ Fetch existing event details
$res = $conn->query("SELECT * FROM events WHERE id = $id");
$event = $res->fetch_assoc();

// ✅ Handle form submission (update event)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $location = $_POST['location'];
    $start_date = $_POST['start_date'];
    $description = $_POST['description'];
    $trailer_link = trim($_POST['trailer_link'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image_path = $event['image']; // keep old image if not replaced

    // ✅ Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . '/../assets/uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'assets/uploads/' . $filename;
            }
        }
    }

    // ✅ Update event in the database
    $stmt = $conn->prepare('
        UPDATE events 
        SET title=?, type=?, location=?, start_date=?, description=?, image=?, trailer_link=?, is_active=? 
        WHERE id=?
    ');
    $stmt->bind_param('sssssssii', $title, $type, $location, $start_date, $description, $image_path, $trailer_link, $is_active, $id);
    $stmt->execute();

    // ✅ Redirect back to dashboard after saving
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- ✅ Page title and favicon -->
<title>Edit Event — Bookify Admin</title>
<link rel="icon" type="image/jpeg" href="../assets/img/logo.JPG">

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-4">
  <a href="dashboard.php" class="btn btn-link">&larr; Back</a>

  <div class="card p-3 shadow-sm border-0">
    <h5 class="mb-3">Edit Event</h5>

    <form method="post" enctype="multipart/form-data">
      <!-- Event Title -->
      <div class="mb-2">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" required value="<?= htmlspecialchars($event['title']) ?>">
      </div>

      <!-- Event Type -->
      <div class="mb-2">
        <label class="form-label">Type of Movie</label>
        <input name="type" class="form-control" placeholder="Action, Comedy, Drama..." value="<?= htmlspecialchars($event['type']) ?>">
      </div>

      <!-- Location -->
      <div class="mb-2">
        <label class="form-label">Location</label>
        <input name="location" class="form-control" value="<?= htmlspecialchars($event['location']) ?>">
      </div>

      <!-- Date -->
      <div class="mb-2">
        <label class="form-label">Date</label>
        <input name="start_date" type="date" class="form-control" required value="<?= htmlspecialchars($event['start_date']) ?>">
      </div>

      <!-- Description -->
      <div class="mb-2">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
      </div>

      <!-- YouTube Trailer -->
      <div class="mb-2">
        <label class="form-label">YouTube Trailer Link (optional)</label>
        <input name="trailer_link" type="url" class="form-control" placeholder="https://www.youtube.com/watch?v=example" value="<?= htmlspecialchars($event['trailer_link']) ?>">
      </div>

      <!-- Image Upload -->
      <div class="mb-2">
        <label class="form-label">Event Image</label>
        <?php if ($event['image']): ?>
          <div class="mb-2">
            <img src="../<?= htmlspecialchars($event['image']) ?>" alt="Event Image" width="150" class="rounded shadow-sm">
          </div>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*" class="form-control">
      </div>

      <!-- Active Checkbox -->
      <div class="form-check mb-3">
        <input type="checkbox" name="is_active" class="form-check-input" id="active" <?= $event['is_active'] ? 'checked' : '' ?>>
        <label class="form-check-label" for="active">Active</label>
      </div>

      <button class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>
</body>
</html>
