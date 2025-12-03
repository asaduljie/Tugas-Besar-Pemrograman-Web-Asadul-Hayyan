<?php
require 'config/config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: auth/auth.php");
  exit;
}

include 'lib/travel_functions.php';

$countTravels = $mysqli->query("SELECT COUNT(*) c FROM travels")->fetch_assoc()['c'];
$countRes = $mysqli->query("SELECT COUNT(*) c FROM reservations")->fetch_assoc()['c'];

$res = $mysqli->query("
  SELECT DATE_FORMAT(created_at,'%Y-%m') ym, COUNT(*) c 
  FROM reservations 
  GROUP BY ym 
  ORDER BY ym DESC 
  LIMIT 12
");

$months = []; 
$counts = [];

while($row = $res->fetch_assoc()) { 
  $months[] = $row['ym']; 
  $counts[] = intval($row['c']); 
}

$months = array_reverse($months); 
$counts = array_reverse($counts);
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="public/css/styles.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

  <?php include 'layouts/sidebar.php'; ?>

  <main class="main-content">
    <h1>Dashboard Admin</h1>

    <div class="grid-stats">
      <div class="stat-box box">
        <h3><?= $countTravels ?></h3>
        <p>Paket Wisata</p>
      </div>

      <div class="stat-box box">
        <h3><?= $countRes ?></h3>
        <p>Reservasi</p>
      </div>
    </div>

    <h2>Reservasi (12 bulan terakhir)</h2>
    <canvas id="resChart"></canvas>

    <script>
      const ctx = document.getElementById('resChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: <?= json_encode($months) ?>,
          datasets: [{
            label: 'Reservasi per bulan',
            data: <?= json_encode($counts) ?>,
            fill: true,
            borderWidth: 2,
            tension: 0.3
          }]
        },
        options: { responsive: true }
      });
    </script>

    <h2>Terakhir Ditambahkan</h2>

    <div class="cards">
      <?php 
      $res2 = getLimitedTravels($mysqli,4);
      while ($row = $res2->fetch_assoc()): 
      ?>
        <div class="card">
          <img src="<?= htmlspecialchars($row['image'] ?: 'public/img/default.jpg') ?>" class="card-img">
          <div class="card-body">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p class="muted"><?= htmlspecialchars($row['location']) ?></p>
            <div class="card-actions">
              <a href="travel_edit.php?id=<?= $row['id'] ?>">Edit</a>
              <a href="travel_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  </main>
</div>

<?php include 'layouts/footer.php'; ?>
</body>
</html>
