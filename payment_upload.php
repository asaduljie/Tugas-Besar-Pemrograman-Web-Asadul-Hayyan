<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$reservation_id = intval($_GET['id'] ?? 0);

$sql = "SELECT r.*, t.title 
        FROM reservations r
        JOIN travels t ON r.travel_id = t.id
        WHERE r.id = ? AND r.user_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $reservation_id, $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    die("Reservasi tidak ditemukan.");
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Upload Pembayaran</title>
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">
  <?php include 'layouts/sidebar.php'; ?>

  <main class="main-content">
    <h1>Upload Bukti Pembayaran</h1>

    <p>Paket: <strong><?= $res['title'] ?></strong></p>

    <form action="payment_process.php" method="post" enctype="multipart/form-data" class="form-card">

        <input type="hidden" name="reservation_id" value="<?= $reservation_id ?>">

        <label>Metode Pembayaran</label>
        <select name="payment_method" required>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="QRIS">QRIS</option>
        </select>

        <label>Bukti Pembayaran</label>
        <input type="file" name="payment_proof" required accept="image/*">

        <button class="btn" type="submit">Upload</button>
    </form>

  </main>
</div>

<?php include 'layouts/footer.php'; ?>
</body>
</html>
