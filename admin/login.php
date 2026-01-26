<?php
// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Include database connection
require_once __DIR__ . '/../connect.php';

$error = '';

// ✅ Redirect if already logged in
if (isset($_SESSION['admin_user'])) {
    header('Location: dashboard.php');
    exit;
}

// ✅ Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch admin record
    $stmt = $conn->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();

    // Verify credentials
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['admin_user'] = $row['username'];
            header('Location: dashboard.php');
            exit;
        }
    }

    // Invalid credentials
    $error = 'Invalid credentials';
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login - Bookify</title>

  <!-- ✅ Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Favicon -->
  <link rel="icon" type="image/jpeg" href="../assets/img/logo.JPG">
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h4 class="mb-3 text-center">Admin Login</h4>

            <!-- 🚫 Error Message -->
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- ✅ Login Form -->
            <form method="post">
              <div class="mb-3">
                <input name="username" class="form-control" placeholder="Username" required>
              </div>
              <div class="mb-3">
                <input name="password" type="password" class="form-control" placeholder="Password" required>
              </div>
              <button class="btn btn-primary w-100">Login</button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
