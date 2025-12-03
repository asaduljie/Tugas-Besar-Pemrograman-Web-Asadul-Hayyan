<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login.");
}

$reservation_id = intval($_POST['reservation_id']);
$method         = trim($_POST['payment_method']);

$folder = "uploads/payments/";
if (!is_dir($folder)) mkdir($folder, 0777, true);

$fileName = time() . "_" . basename($_FILES["payment_proof"]["name"]);
$target = $folder . $fileName;

move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target);

$sql = "UPDATE reservations 
        SET payment_method = ?, payment_proof = ?, payment_status = 'Menunggu Verifikasi'
        WHERE id = ? AND user_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssii", $method, $target, $reservation_id, $_SESSION['user_id']);
$stmt->execute();

header("Location: reservations_user.php?uploaded=1");
exit;
?>
