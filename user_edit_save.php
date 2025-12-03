<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$id = intval($_POST['id']);
$username = trim($_POST['username']);
$role = trim($_POST['role']);

if ($id == $_SESSION['user_id'] && $role != 'admin') {
    die("Tidak bisa mengubah role admin untuk diri sendiri!");
}

$stmt = $mysqli->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
$stmt->bind_param("ssi", $username, $role, $id);
$stmt->execute();

header("Location: admin_users.php?updated=1");
exit;
?>
