<?php
require 'config/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: auth/auth.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id == $_SESSION['user_id']) {
    header("Location: admin_users.php?error=self_delete");
    exit;
}
if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM users WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin_users.php?deleted=1");
exit;
?>
