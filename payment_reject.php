<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

// Hanya boleh menolak jika status saat ini adalah "waiting" atau "pending"
$check = $mysqli->prepare("
    SELECT payment_status FROM reservations WHERE id = ?
");
$check->bind_param("i", $id);
$check->execute();
$res = $check->get_result()->fetch_assoc();

if (!$res) {
    header("Location: admin_reservations.php?error=notfound");
    exit;
}

$current = strtolower($res['payment_status']);

if ($current === "waiting" || $current === "pending") {

    $stmt = $mysqli->prepare("
        UPDATE reservations 
        SET payment_status = 'rejected'
        WHERE id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: admin_reservations.php?rejected=1");
    exit;
}

// Jika status bukan salah satu yg valid untuk ditolak
header("Location: admin_reservations.php?error=invalidstatus");
exit;
