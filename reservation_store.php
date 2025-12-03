<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk melakukan reservasi.");
}

$user_id    = $_SESSION['user_id'];
$travel_id  = intval($_POST['travel_id']);
$name       = trim($_POST['name']);
$email      = trim($_POST['email']);
$phone      = trim($_POST['phone']);
$course     = trim($_POST['course_registration']);
$note       = trim($_POST['note']);
$created_at = date("Y-m-d H:i:s");

$sql = "INSERT INTO reservations 
        (travel_id, user_id, name, email, phone, course_registration, note, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("SQL ERROR: " . $mysqli->error);
}

$stmt->bind_param(
    "iissssss",
    $travel_id,
    $user_id,
    $name,
    $email,
    $phone,
    $course,
    $note,
    $created_at
);

$stmt->execute();
$stmt->close();

header("Location: reservations_user.php?success=1");
exit;


?>
