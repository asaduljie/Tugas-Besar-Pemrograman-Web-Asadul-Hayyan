<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $mysqli->prepare("
    UPDATE reservations 
    SET payment_status = 'approved'
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin_reservations.php?approved=1");
exit;
