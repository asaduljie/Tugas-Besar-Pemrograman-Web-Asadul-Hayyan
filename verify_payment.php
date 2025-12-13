<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

$id = intval($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['approve', 'reject'])) {
    die("Permintaan tidak valid.");
}

$status = ($action === "approve") ? "Approved" : "Rejected";

$stmt = $mysqli->prepare("UPDATE reservations SET payment_status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

if ($action === "approve") {
    header("Location: admin_reservations.php?verified=1");
} else {
    header("Location: admin_reservations.php?rejected=1");
}
exit;
?>
