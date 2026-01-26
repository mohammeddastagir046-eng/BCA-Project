<?php
// ---------------------------------------------------------
// User Registration Page
// ---------------------------------------------------------
// This script handles new user registration, hashing
// passwords securely and storing user data in the database.
// ---------------------------------------------------------

require_once 'connect.php';
session_start();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">✅ Registration successful! You can now <a href="user_login.php">login</a>.</div>';
    } else {
        $message = '<div class="alert alert-danger">❌ Email already exists.</div>';
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>User Registration</title>
<link rel="icon" type="image/jpeg" href="assets/img/logo.JPG">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-5">
    <div class="card mx-auto shadow" style="max-width:400px;">
      <div class="card-body">
        <h4 class="mb-3">Create Account</h4>

        <?= $message ?>

        <form method="post">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Register</button>
          <p class="text-center mt-3">
            Already have an account? <a href="user_login.php">Login</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
