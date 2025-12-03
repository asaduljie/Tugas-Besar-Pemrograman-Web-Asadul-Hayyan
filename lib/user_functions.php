<?php
function getUserById($mysqli,$id){
  $stmt=$mysqli->prepare("SELECT id,username,role,created_at FROM users WHERE id=?");
  $stmt->bind_param("i",$id); $stmt->execute(); return $stmt->get_result()->fetch_assoc();
}
?>
