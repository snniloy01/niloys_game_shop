<?php
// Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Small helper for safe output 
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}
?>

<!-- Navbar (partial) -->
<nav class="navbar navbar-expand-lg" style="background-color:#0f1923;">
  <div class="container">
    <a class="navbar-brand" href="index.php" style="color:#ff4655;font-weight:bold;">Niloy's game Shop</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(93deg) brightness(103%) contrast(103%);"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php" style="color:#ffffff;">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php" style="color:#ffffff;">About</a></li>

        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
          <li class="nav-item"><a class="nav-link" href="add_game.php" style="color:#ffffff;">Add Game</a></li>
          <li class="nav-item"><a class="nav-link" href="add_post.php" style="color:#ffffff;">Add Post</a></li>
          <li class="nav-item"><a class="nav-link" href="admin_dashboard.php" style="color:#ffffff;">Dashboard</a></li>
        <?php endif; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
          <span class="username-badge" style="
              background:rgba(255,255,255,0.08);
              border-radius:999px;
              padding:6px 12px;
              display:inline-flex;
              align-items:center;
              gap:8px;
              font-weight:600;
              color:#ffffff;">
            <i class="bi bi-person-circle" style="font-size:1rem;"></i>
            <?php echo e($_SESSION['username'] ?? 'Admin'); ?>
          </span>
          <a class="btn btn-sm btn-outline-light" href="logout.php">Logout</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
