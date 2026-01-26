<?php
require_once 'connect.php';
include 'header.php';
?>

<!-- ==============================
     HERO SECTION
     ============================== -->
<div class="card card-hero mb-4">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h1>Welcome to Bookify</h1>
      <p class="text-muted">One Tap Endless Experience.</p>
    </div>
    <div class="col-md-4 text-md-end">
      <a href="event_booking.php" class="btn btn-primary">Book Event</a>
    </div>
  </div>
</div>

<!-- ==============================
     RECOMMENDED EVENTS SECTION
     ============================== -->
<div class="mb-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h3 class="mb-0">Recommended Events</h3>
    <a href="event_booking.php" class="text-decoration-none text-primary">See All ›</a>
  </div>
  <p class="text-muted">Discover and book trending events</p>
</div>

<!-- ==============================
     EVENTS GRID
     ============================== -->
<div class="row g-4">
<?php
$res = $conn->query("SELECT * FROM events WHERE is_active=1 ORDER BY start_date ASC");

if ($res && $res->num_rows > 0):
  while ($e = $res->fetch_assoc()):
?>
  <div class="col-6 col-sm-4 col-md-3 col-lg-2">
    <div class="movie-card text-center shadow-sm">
      
      <?php if (!empty($e['trailer_link'])): ?>
        <!-- Clickable poster (opens trailer) -->
        <a href="<?= htmlspecialchars($e['trailer_link']) ?>" target="_blank" rel="noopener noreferrer">
          <img src="<?= htmlspecialchars($e['image'] ?: 'assets/img/event-placeholder.jpg') ?>"
               class="movie-poster"
               alt="<?= htmlspecialchars($e['title']) ?>">
        </a>
      <?php else: ?>
        <!-- Static poster (no trailer) -->
        <img src="<?= htmlspecialchars($e['image'] ?: 'assets/img/event-placeholder.jpg') ?>"
             class="movie-poster"
             alt="<?= htmlspecialchars($e['title']) ?>">
      <?php endif; ?>

      <div class="movie-info mt-2">
        <h6 class="movie-title mb-1"><?= htmlspecialchars($e['title']) ?></h6>
        <?php if (!empty($e['type'])): ?>
          <p class="text-muted small mb-1"><?= htmlspecialchars($e['type']) ?></p>
        <?php endif; ?>
        <p class="text-muted small"><?= date('M j, Y', strtotime($e['start_date'])) ?></p>
        <a href="event_booking.php?event_id=<?= $e['id'] ?>" class="btn btn-sm btn-primary mt-2">Book</a>
      </div>
    </div>
  </div>
<?php
  endwhile;
else:
?>
  <div class="col-12">
    <div class="alert alert-info text-center">No events available at the moment.</div>
  </div>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<!-- ==============================
     PAGE STYLES
     ============================== -->
<style>
/* 🎬 Poster-style event cards */
.movie-card {
  border-radius: 12px;
  overflow: hidden;
  background-color: #fff;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.movie-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* 🖼️ Poster image */
.movie-poster {
  width: 100%;
  height: 300px;
  object-fit: cover;
  border-radius: 12px;
}

/* 🎞️ Event info below image */
.movie-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #222;
}
.movie-info {
  padding: 8px;
}
.movie-info p {
  line-height: 1.3;
  margin-bottom: 4px;
}
.btn-sm {
  font-size: 0.8rem;
  padding: 3px 10px;
}

/* 📱 Responsive adjustments */
@media (max-width: 576px) {
  .movie-poster { height: 240px; }
  .movie-title { font-size: 0.85rem; }
}
</style>
