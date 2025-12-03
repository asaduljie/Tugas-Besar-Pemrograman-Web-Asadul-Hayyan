<?php
require 'config/config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header("Location: auth/auth.php"); exit; }

$res = $mysqli->query("SELECT r.*, t.title FROM reservations r JOIN travels t ON r.travel_id=t.id ORDER BY r.created_at DESC");
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Kelola Pembayaran</title><link rel="stylesheet" href="public/css/styles.css"></head><body>
<?php include 'layouts/navbar.php'; include 'layouts/sidebar.php'; ?>
<div class="container layout"><main class="main-content">
  <h1>Kelola Pembayaran</h1>
  <table class="table">
    <thead><tr><th>#</th><th>Paket</th><th>Nama</th><th>Email</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php while($r = $res->fetch_assoc()): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['title']) ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['payment_status']) ?></td>
        <td><?php if($r['payment_proof']): ?><a href="<?= htmlspecialchars($r['payment_proof']) ?>" target="_blank">Lihat</a><?php endif; ?></td>
        <td>
          <a href="process_payment.php?res_id=<?= $r['id'] ?>&action=approve">Set Lunas</a> |
          <a href="process_payment.php?res_id=<?= $r['id'] ?>&action=reject">Tolak</a>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
</main></div></body></html>
