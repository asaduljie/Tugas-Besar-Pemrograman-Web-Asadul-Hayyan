<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$sql = "SELECT r.*, t.title AS travel_title, t.location
        FROM reservations r
        JOIN travels t ON t.id = r.travel_id
        WHERE r.id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

if (!$ticket) {
    die("Tiket tidak ditemukan.");
}

if (strtolower($ticket['payment_status']) !== "approved") {
    die("❌ Tiket hanya tersedia jika pembayaran sudah disetujui admin.");
}

$ticket_number = "TCK-" . str_pad($ticket['id'], 4, "0", STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tiket <?= $ticket_number ?></title>
<link rel="stylesheet" href="public/css/styles.css">

<style>
body {
    background: #eef6ff;
    font-family: Arial, sans-serif;
}

.ticket-box {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    background: white;
    border-radius: 14px;
    box-shadow: 0 0 12px rgba(0,0,0,0.15);
    text-align: center;
}

.brand {
    font-size: 30px;
    font-weight: 700;
    color: #007bff;
}
.brand-accent {
    color: #0056ff;
}

.qr {
    width: 150px;
    margin: 25px auto;
}

.print-btn {
    margin-top: 25px;
    padding: 12px 18px;
    font-weight: bold;
    background: #2ecc71;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}

.back-btn {
    margin-top: 15px;
    padding: 12px 18px;
    background: #555;
    color: white;
    border-radius: 8px;
    text-decoration: none;
}

@media print {
    .print-btn, .back-btn {
        display: none;
    }
}
</style>

</head>

<body>

<div class="ticket-box">

    <div class="brand">
        Sulawesi<span class="brand-accent">Travel</span>
    </div>

    <h2>Tiket Elektronik</h2>
    <p><b><?= $ticket_number ?></b></p>

    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $ticket_number ?>" class="qr">

    <p><b>Nama:</b> <?= $ticket['name'] ?></p>
    <p><b>Paket:</b> <?= $ticket['travel_title'] ?></p>
    <p><b>Lokasi:</b> <?= $ticket['location'] ?></p>
    <p><b>Tanggal Pemesanan:</b> <?= $ticket['created_at'] ?></p>
    <p><b>Status:</b> <?= ucfirst($ticket['payment_status']) ?></p>

   <a href="#" onclick="window.print()" class="print-btn">🎫 Cetak Tiket</a>
   <a href="reservations_user.php" class="print-btn" style="background:#777; margin-left:10px;">← Kembali ke Reservasi Saya</a>


</div>

</body>
</html>
