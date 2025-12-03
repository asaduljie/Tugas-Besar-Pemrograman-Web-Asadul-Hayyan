<?php
$current = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? null;
?>

<div class="sidebar">
  <h3>Menu</h3>

  <ul class="side-menu">

    <li>
      <a href="index.php" class="<?= $current=='index.php'?'active':'' ?>">
        Beranda
      </a>
    </li>

    <li>
      <a href="travels_list.php" class="<?= $current=='travels_list.php'?'active':'' ?>">
        Paket Wisata
      </a>
    </li>

    <li>
      <a href="my_reviews.php" class="<?= $current=='my_reviews.php'?'active':'' ?>">
        Ulasan Saya
      </a>
    </li>

    <?php if ($role === 'user'): ?>
      <li>
        <a href="reservations_user.php"
           class="<?= $current=='reservations_user.php'?'active':'' ?>">
           Reservasi Saya
        </a>
      </li>
    <?php endif; ?>

    <?php if ($role === 'admin'): ?>
      <li>
        <a href="admin_dashboard.php" class="<?= $current=='admin_dashboard.php'?'active':'' ?>">
          Dashboard Admin
        </a>
      </li>

      <li>
        <a href="travel_add.php" class="<?= $current=='travel_add.php'?'active':'' ?>">
          Tambah Paket
        </a>
      </li>

      <li>
        <a href="kelola_paket.php" class="<?= $current=='kelola_paket.php'?'active':'' ?>">
          Kelola Paket
        </a>
      </li>

      <li>
        <a href="admin_reservations.php" class="<?= $current=='admin_reservations.php'?'active':'' ?>">
          Lihat Reservasi
        </a>
      </li>

      <li>
        <a href="admin_users.php" class="<?= $current=='admin_users.php'?'active':'' ?>">
          Manajemen User
        </a>
      </li>

      <li>
        <a href="init_db.php" class="<?= $current=='init_db.php'?'active':'' ?>">
          Inisialisasi DB
        </a>
      </li>
    <?php endif; ?>

  </ul>

  <small>Kontak: info@sulawesi.travel</small>
</div>
