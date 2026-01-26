
<?php
// ---------------------------------------------------------
// Utility Script: Generate a Secure Password Hash
// ---------------------------------------------------------
// Use this file to generate a bcrypt hash for any password.
// Example: Use when adding new admins or resetting passwords.
// ---------------------------------------------------------

// Change the password below to generate its hash
$password = '040604';

// Output the hashed password
echo password_hash($password, PASSWORD_DEFAULT);
#echo password_hash('awez040604', PASSWORD_DEFAULT);
#e#cho password_hash('040604', PASSWORD_DEFAULT);
?>