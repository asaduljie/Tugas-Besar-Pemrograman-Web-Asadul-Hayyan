<?php 
require 'config/config.php';
include 'lib/travel_functions.php';

$user_id  = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null;
$role     = $_SESSION['role'] ?? null;

$travels = getLimitedTravels($mysqli, 6);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Sulawesi Travel</title>
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="hero">
  <div class="hero-inner">
    <h1>Jelajahi Keindahan Pulau Sulawesi</h1>
    <p>Destinasi tropis, budaya unik, dan pengalaman laut yang tak terlupakan.</p>

    <a class="cta" href="travels_list.php">Jelajahi Paket</a>
  </div>
</div>


<div class="container layout">

  <?php include 'layouts/sidebar.php'; ?>

  <main class="main-content">
    
    <h2>Paket Pilihan</h2>

    <div class="cards">

      <?php while($row = $travels->fetch_assoc()): ?>
      
        <div class="card">

          <img src="<?= htmlspecialchars($row['image'] ?: 'public/img/default.jpg') ?>" 
               class="card-img" alt="">

          <div class="card-body">
            
            <h3><?= htmlspecialchars($row['title']) ?></h3>

            <p class="muted">
              <?= htmlspecialchars($row['location']) ?> • 
              Rp <?= number_format($row['price'],0,',','.') ?>
            </p>

            <p><?= nl2br(htmlspecialchars(substr($row['description'], 0, 120))) ?>...</p>

            <div class="card-buttons">

              <?php if (!$user_id): ?>
                  <a class="btn-card" href="auth/auth.php">Login untuk Reservasi</a>
              <?php else: ?>
                  <a class="btn-card" href="reservations.php?travel_id=<?= $row['id'] ?>">Reservasi</a>
              <?php endif; ?>

              <a class="btn-outline" 
                 href="travel_detail.php?id=<?= $row['id'] ?>">
                 Lihat detail
              </a>

            </div>

          </div>
        </div>

      <?php endwhile; ?>

    </div>
    
  </main>

</div>

<?php include 'layouts/footer.php'; ?>

<script src="public/js/script.js" defer></script>

</body>
</html>
