<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- ✅ Page Title -->
  <title>Bookify</title>

  <!-- ✅ Favicon -->
  <link rel="icon" type="image/jpeg" href="assets/img/logo.JPG">

  <!-- ✅ Bootstrap & Custom CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <!-- ==============================
       NAVIGATION BAR
       ============================== -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <!-- Logo / Brand -->
      <a class="navbar-brand text-primary fw-bold" href="index.php">Bookify</a>

      <!-- Navbar Toggle (Mobile) -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto align-items-lg-center">

          <!-- Common Links -->
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="event_booking.php">Book Event</a></li>
          <li class="nav-item"><a class="nav-link" href="admin/login.php">Admin</a></li>

          <?php if (isset($_SESSION['user_id'])): ?>
            <!-- ✅ Logged-in user dropdown -->
            <li class="nav-item dropdown ms-2">
              <a class="nav-link dropdown-toggle text-primary fw-semibold" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?= htmlspecialchars($_SESSION['user_name']) ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="my_bookings.php">My Bookings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="user_logout.php">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <!-- 🚫 Guest user (not logged in) -->
            <li class="nav-item ms-3">
              <a href="user_login.php" class="btn btn-outline-primary btn-sm me-2">Login</a>
            </li>
            <li class="nav-item">
              <a href="user_register.php" class="btn btn-primary btn-sm">Sign Up</a>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>

  <!-- ==============================
       MAIN CONTENT AREA
       ============================== -->
  <main class="container my-4">
