<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.*, t.title 
        FROM reservations r
        JOIN travels t ON r.travel_id = t.id
        WHERE r.user_id = ? AND r.payment_proof IS NOT NULL
        ORDER BY r.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Pembayaran Saya</title>
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

<?php include 'layouts/sidebar.php'; ?>

<main class="main-content">
    <h1>Riwayat Pembayaran</h1>

    <?php if ($data->num_rows == 0): ?>
        <p class="muted">Belum ada pembayaran.</p>
    <?php else: ?>

    <table class="table">
        <thead>
            <tr>
                <th>Paket</th>
                <th>Tanggal</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Bukti</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= $row['title'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td><?= $row['payment_method'] ?></td>
                <td><?= $row['payment_status'] ?></td>

                <td>
                    <img src="<?= $row['payment_proof'] ?>" class="payment-proof-img">
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php endif; ?>
</main>

</div>

<?php include 'layouts/footer.php'; ?>

</body>
</html>
