<?php
require 'config/config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header("Location: auth/auth.php"); exit; }
$id = intval($_GET['id'] ?? 0);
if ($id) {
  $stmt = $mysqli->prepare("DELETE FROM travels WHERE id = ?");
  $stmt->bind_param("i",$id); $stmt->execute();
}
header("Location: travels_list.php"); exit;
?>
