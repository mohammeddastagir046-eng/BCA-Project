<?php
require_once 'connect.php';
session_start();

$message = '';

/*
|--------------------------------------------------------------------------
| Handle User Login
|--------------------------------------------------------------------------
| This section verifies user credentials:
| - Fetch user by email
| - Verify password using password_verify()
| - Store user session on success
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // ✅ Verify password hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to home after login
            header("Location: index.php");
            exit;
        } else {
            $message = '<div class="alert alert-danger">Invalid password.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">No user found with that email.</div>';
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Login</title>

<!-- ✅ Favicon -->
<link rel="icon" type="image/jpeg" href="assets/img/logo.JPG">

<!-- ✅ Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container py-5">
  <div class="card mx-auto shadow" style="max-width:400px;">
    <div class="card-body">
      <h4 class="mb-3 text-center">Login</h4>

      <?= $message ?>

      <form method="post">
        <!-- Email -->
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>

        <p class="text-center mt-3">
          Don’t have an account?
          <a href="user_register.php">Sign up</a>
        </p>
      </form>
    </div>
  </div>
</div>
</body>
</html>
