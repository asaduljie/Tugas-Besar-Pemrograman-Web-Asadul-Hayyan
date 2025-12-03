<?php
$mysqli = new mysqli("localhost", "root", "", "sulawesi_travel");
if ($mysqli->connect_errno) {
    die("Gagal koneksi DB: " . $mysqli->connect_error);
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
