<?php
/**
 * ============================================
 *  DATABASE CONNECTION — Bookify Project
 * ============================================
 *  Establishes a secure connection to the MySQL
 *  database using credentials from config.php
 */

require_once __DIR__ . '/config.php'; // Load database constants

// Create MySQL connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Stop execution if connection fails
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Ensure proper character encoding
$conn->set_charset('utf8mb4');
?>
