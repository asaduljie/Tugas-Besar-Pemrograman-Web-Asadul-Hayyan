<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Akses ditolak!");
}

$user_id = $_SESSION['user_id'];
$res_id  = intval($_GET['id'] ?? 0);

if (!$res_id) {
    die("ID reservasi tidak valid.");
}

$stmt = $mysqli->prepare("SELECT id FROM reservations WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $res_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Reservasi tidak ditemukan atau bukan milik Anda.");
}

$stmt = $mysqli->prepare("DELETE FROM reservations WHERE id=?");
$stmt->bind_param("i", $res_id);
$stmt->execute();

header("Location: reservations_user.php?canceled=1");
exit;
