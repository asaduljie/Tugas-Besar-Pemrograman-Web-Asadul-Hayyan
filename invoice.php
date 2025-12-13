<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$sql = "SELECT r.*, t.title AS travel_title, t.location, t.price
        FROM reservations r
        JOIN travels t ON t.id = r.travel_id
        WHERE r.id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

if (!$invoice) {
    die("Invoice tidak ditemukan.");
}

$invoice_number = "INV-" . str_pad($invoice['id'], 4, "0", STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Invoice <?= $invoice_number ?></title>
<link rel="stylesheet" href="public/css/styles.css">

<style>
body {
    background: #eef6ff;
    font-family: Arial, sans-serif;
}

.invoice-box {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
}

.brand {
    font-size: 32px;
    font-weight: 700;
    color: #007bff;
}
.brand-accent {
    color: #0056ff;
}

h2 { margin-top: 20px; }

.table-info {
    width: 100%;
    margin-top: 20px;
}
.table-info td {
    padding: 8px 0;
}

.print-btn, .back-btn {
    display: inline-block;
    margin-top: 25px;
    padding: 12px 18px;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
}
.print-btn {
    background: #4a77ff;
    color: white;
}
.back-btn {
    background: #555;
    color: white;
}

@media print {
    .print-btn, .back-btn {
        display: none;
    }
}
</style>
</head>

<body>

<div class="invoice-box">

    <div class="brand" style="font-size:32px; margin-bottom:20px; display:inline-block;">
        Sulawesi<span class="brand-accent">Travel</span>
    </div>

    <h2>Invoice Pembayaran</h2>
    <p><b><?= $invoice_number ?></b></p>

    <table class="table-info">
        <tr><td><b>Nama</b></td><td><?= $invoice['name'] ?></td></tr>
        <tr><td><b>Email</b></td><td><?= $invoice['email'] ?></td></tr>
        <tr><td><b>No HP</b></td><td><?= $invoice['phone'] ?></td></tr>
        <tr><td><b>Tanggal Pemesanan</b></td><td><?= $invoice['created_at'] ?></td></tr>
    </table>

    <hr style="margin:25px 0">

    <h3>Detail Pesanan</h3>

    <table class="table-info">
        <tr><td><b>Paket Wisata</b></td><td><?= $invoice['travel_title'] ?></td></tr>
        <tr><td><b>Lokasi</b></td><td><?= $invoice['location'] ?></td></tr>
        <tr>
            <td><b>Harga</b></td>
            <td>Rp <?= number_format($invoice['price'],0,',','.') ?></td>
        </tr>
        <tr><td><b>Status Pembayaran</b></td><td><?= ucfirst($invoice['payment_status']) ?></td></tr>
        <tr><td><b>Metode Pembayaran</b></td><td><?= $invoice['payment_method'] ?></td></tr>
    </table>

    <a href="#" onclick="window.print()" class="print-btn">🖨 Cetak Invoice</a>
    <a href="reservations_user.php" class="print-btn" style="background:#777; margin-left:10px;">← Kembali ke Reservasi Saya</a>
    
</div>

</body>
</html>
