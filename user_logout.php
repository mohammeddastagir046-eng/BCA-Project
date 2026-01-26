<?php
// ---------------------------------------------------------
// User Logout Script
// ---------------------------------------------------------
// This file safely ends the user session and redirects
// back to the home page.
// ---------------------------------------------------------

session_start();     // Start session to clear user data
session_destroy();   // Destroy all session data
header("Location: index.php"); // Redirect to homepage
exit;
?>
