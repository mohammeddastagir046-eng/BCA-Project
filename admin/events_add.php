<?php
session_start();

// ✅ Restrict access to logged-in admins only
if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../connect.php';

// ------------------------------------------------
// Handle form submission to add a new event
// ------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title        = $_POST['title'];
    $type         = $_POST['type'];
    $location     = $_POST['location'];
    $start_date   = $_POST['start_date'];
    $description  = $_POST['description'];
    $trailer_link = trim($_POST['trailer_link'] ?? '');
    $is_active    = isset($_POST['is_active']) ? 1 : 0;

    // ----------------------------
    // Handle image upload (optional)
    // ----------------------------
    $image_path = null;

    if (!empty($_FILES['image']['name'])) {
        $target_dir = __DIR__ . '/../assets/uploads/';
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $target_file = $target_dir . $filename;

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        // Only allow specific file types
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'assets/uploads/' . $filename;
            }
        }
    }

    // ----------------------------
    // Insert new event into database
    // ----------------------------
    $stmt = $conn->prepare('
        INSERT INTO events 
        (title, type, location, start_date, description, image, trailer_link, is_active) 
        VALUES (?,?,?,?,?,?,?,?)
    ');
    $stmt->bind_param('sssssssi', $title, $type, $location, $start_date, $description, $image_path, $trailer_link, $is_active);
    $stmt->execute();

    header('Location: dashboard.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Add Event — Admin | Bookify</title>

<!-- ✅ Favicon -->
<link rel="icon" type="image/jpeg" href="../assets/img/logo.JPG">

<!-- ✅ Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container py-4">
  <a href="dashboard.php" class="btn btn-link">&larr; Back</a>

  <div class="card p-3 shadow-sm border-0">
    <h5 class="mb-3">Add New Event</h5>

    <!-- Event Creation Form -->
    <form method="post" enctype="multipart/form-data">

      <!-- Event Title -->
      <div class="mb-2">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" placeholder="Enter event title" required>
      </div>

      <!-- Movie Type -->
      <div class="mb-2">
        <label class="form-label">Type of Movie</label>
        <input name="type" class="form-control" placeholder="Action, Comedy, Drama...">
      </div>

      <!-- Location -->
      <div class="mb-2">
        <label class="form-label">Location</label>
        <input name="location" class="form-control" placeholder="Location of event or cinema">
      </div>

      <!-- Start Date -->
      <div class="mb-2">
        <label class="form-label">Start Date</label>
        <input name="start_date" type="date" class="form-control" required>
      </div>

      <!-- Description -->
      <div class="mb-2">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" placeholder="Short description about the event"></textarea>
      </div>

      <!-- Optional YouTube Trailer -->
      <div class="mb-2">
        <label class="form-label">YouTube Trailer Link (optional)</label>
        <input name="trailer_link" type="url" class="form-control" placeholder="https://www.youtube.com/watch?v=example">
      </div>

      <!-- Event Image -->
      <div class="mb-2">
        <label class="form-label">Event Image</label>
        <input type="file" name="image" accept="image/*" class="form-control">
      </div>

      <!-- Active Checkbox -->
      <div class="form-check mb-3">
        <input type="checkbox" name="is_active" class="form-check-input" id="active">
        <label class="form-check-label" for="active">Active</label>
      </div>

      <button class="btn btn-primary w-100">Create Event</button>
    </form>
  </div>
</div>
</body>
</html>
