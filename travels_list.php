<?php
require 'config/config.php';
include 'lib/travel_functions.php';

$role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? null;

$q = $_GET['q'] ?? '';
$loc = $_GET['location'] ?? '';
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';
$sort = $_GET['sort'] ?? 'newest';

$travels = searchTravels($mysqli, $q, $loc, $min, $max, $sort);
$locations = getAllLocations($mysqli);
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Daftar Paket Wisata</title>
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

  <?php include 'layouts/navbar.php'; ?>

  <div class="container layout">

    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">

      <h1>Daftar Paket</h1>

      <form method="get" class="filter-container">
        <div class="filter-grid">

          <input type="text" name="q" placeholder="Cari judul atau lokasi..." value="<?= htmlspecialchars($q) ?>">

          <select name="location">
            <option value="">Semua Lokasi</option>

            <?php foreach ($locations as $l): ?>
              <option value="<?= htmlspecialchars($l['location']) ?>" <?= ($l['location'] == $loc ? 'selected' : '') ?>>
                <?= htmlspecialchars($l['location']) ?>
              </option>
            <?php endforeach; ?>
          </select>

          <input type="number" name="min" placeholder="Min harga" value="<?= htmlspecialchars($min) ?>">
          <input type="number" name="max" placeholder="Max harga" value="<?= htmlspecialchars($max) ?>">

          <select name="sort">
            <option value="newest" <?= $sort == 'newest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $sort == 'oldest' ? 'selected' : '' ?>>Terlama</option>
            <option value="low" <?= $sort == 'low' ? 'selected' : '' ?>>Harga Terendah</option>
            <option value="high" <?= $sort == 'high' ? 'selected' : '' ?>>Harga Tertinggi</option>
          </select>

        </div>

        <div class="filter-footer">
          <div class="filter-left">
            <button class="btn" type="submit">Filter</button>
            <a href="travels_list.php" class="btn-reset">Reset</a>
          </div>

          <?php if ($role === 'admin'): ?>
            <a href="travel_add.php" class="add-btn">Tambah Paket Baru</a>
          <?php endif; ?>
        </div>

      </form>

      <div class="cards">

        <?php while ($row = $travels->fetch_assoc()): ?>
          <div class="card">

            <img src="<?= htmlspecialchars($row['image'] ?: 'public/img/default.jpg') ?>" class="card-img">

            <div class="card-body">

              <h3><?= htmlspecialchars($row['title']) ?></h3>
              <p class="muted">
                <?= htmlspecialchars($row['location']) ?> •
                Rp <?= number_format($row['price'], 0, ',', '.') ?>
              </p>

              <div class="card-buttons">

                <?php if (!$user_id): ?>

                  <a class="btn-card" href="auth/auth.php">Login untuk Reservasi</a>

                <?php elseif ($role === 'user'): ?>

                  <a class="btn-card" href="reservations.php?travel_id=<?= $row['id'] ?>">Reservasi</a>
                <?php endif; ?>

                <a class="btn-outline" href="travel_detail.php?id=<?= $row['id'] ?>">
                  Lihat detail
                </a>

                <?php if ($role === 'admin'): ?>
                  <a href="travel_edit.php?id=<?= $row['id'] ?>" class="action-btn btn-edit">Edit</a>

                  <a href="travel_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus paket ini?')"
                    class="action-btn btn-delete">Hapus</a>

                <?php endif; ?>

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