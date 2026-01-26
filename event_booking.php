<?php
require_once 'connect.php';
session_start(); // Start session to manage user login status
include 'header.php';

/* ==========================================================
   FETCH ACTIVE EVENTS
   ----------------------------------------------------------
   Retrieves all active events from the database, ordered
   by start date in ascending order.
   ========================================================== */
$events = [];
$res = $conn->query("SELECT * FROM events WHERE is_active = 1 ORDER BY start_date ASC");
if ($res) {
  while ($r = $res->fetch_assoc()) {
    $events[] = $r;
  }
}

/* ==========================================================
   PREFILL USER DATA FOR BOOKING FORM
   ----------------------------------------------------------
   If the user is logged in, prefill their name (and email,
   if stored in session). Also preselect event if passed
   via query string (?event_id=).
   ========================================================== */
$pref = [
  'name' => $_SESSION['user_name'] ?? '',
  'email' => '', // Optional: add if you store user email in session
  'event_id' => $_GET['event_id'] ?? ''
];
?>

<div class="card p-4 my-4 shadow-sm">
  <h4 class="mb-3">Book an Event</h4>

  <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- 🚫 User not logged in -->
    <div class="alert alert-warning">
      You must 
      <a href="user_login.php?redirect=event_booking.php<?= isset($_GET['event_id']) ? '?event_id=' . $_GET['event_id'] : '' ?>">
        login
      </a> 
      before booking an event.
    </div>

    <p>You can still browse events freely, but please log in or sign up to book.</p>

    <a href="user_login.php" class="btn btn-primary">Login</a>
    <a href="user_register.php" class="btn btn-outline-secondary">Sign Up</a>

  <?php else: ?>
    <!-- ✅ User logged in -->
    <form action="process_booking.php" method="post" class="mt-3">
      
      <!-- Full name -->
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input name="name" class="form-control" required value="<?= htmlspecialchars($pref['name']) ?>">
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required value="<?= htmlspecialchars($pref['email']) ?>">
      </div>

      <!-- Select event -->
      <div class="mb-3">
        <label class="form-label">Select Event</label>
        <select name="event_id" class="form-select" required>
          <option value="">Choose...</option>
          <?php foreach ($events as $ev): ?>
            <option value="<?= $ev['id'] ?>" <?= $pref['event_id'] == $ev['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($ev['title']) ?> — <?= date('M j, Y', strtotime($ev['start_date'])) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Submit button -->
      <button class="btn btn-primary">Submit Booking</button>
    </form>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
