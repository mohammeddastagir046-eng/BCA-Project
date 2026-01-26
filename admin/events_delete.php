<?php
session_start();

// ✅ Ensure only logged-in admins can delete events
if (!isset($_SESSION['admin_user'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../connect.php';

// ✅ Get event ID from query parameter
$id = (int)($_GET['id'] ?? 0);

// ✅ Delete event if valid ID is provided
if ($id) {
    $stmt = $conn->prepare('DELETE FROM events WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// ✅ Redirect back to dashboard after deletion
header('Location: dashboard.php');
exit;
?>
