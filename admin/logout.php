<?php
// Start session and immediately destroy it to log out admin
session_start();
session_unset();
session_destroy();

// Redirect to admin login page after logout
header('Location: login.php');
exit;
?>
