<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$user = $_SESSION['user_id'];

$stmt = $mysqli->prepare("DELETE FROM reviews WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user);
$stmt->execute();

header("Location: my_reviews.php");
exit;
?>
