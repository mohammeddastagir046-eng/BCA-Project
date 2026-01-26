<?php
/**
 * ============================================
 *  CONFIGURATION FILE — Bookify Project
 * ============================================
 *  Contains database and email (SMTP) settings.
 *  Make sure to update credentials before deploying.
 */

// ---------- DATABASE SETTINGS ----------
define('DB_HOST', 'localhost');   // Database host (usually 'localhost')
define('DB_USER', 'root');        // MySQL username
define('DB_PASS', '');            // MySQL password (leave blank for XAMPP)
define('DB_NAME', 'bookify');     // Database name


// ---------- EMAIL (SMTP - GMAIL) SETTINGS ----------
define('MAIL_HOST', 'smtp.gmail.com');              // SMTP server
define('MAIL_USER', 'bookifyy2025@gmail.com');      // Gmail address
define('MAIL_PASS', 'rkxu vfsg xiow gpgm');         // Gmail app password
define('MAIL_PORT', 465);                           // SMTP port for SSL
define('MAIL_FROM', 'no-reply@bookify.local');      // Default 'From' email
define('MAIL_FROM_NAME', 'Bookify');                // Sender name
?>
