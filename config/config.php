<?php
$host = "sql210.infinityfree.com"; 
$user = "if0_40515046";             
$pass = "asadul100204";      
$db   = "if0_40515046_sulawesi_travel";          

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

session_start();
?>
