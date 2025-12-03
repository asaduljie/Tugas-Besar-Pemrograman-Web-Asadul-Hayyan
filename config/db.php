<?php
// config/db.php - Jalankan sekali untuk membuat database dan tabel

$host = "localhost";
$user = "root";
$pass = "";
$db   = "sulawesi_travel";

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Buat database
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$db` 
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$mysqli->select_db($db);

// Tabel travels
$mysqli->query("
CREATE TABLE IF NOT EXISTS travels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    location VARCHAR(120) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

// Tabel reservations
$mysqli->query("
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    travel_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(40),
    course_registration VARCHAR(255) DEFAULT '',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (travel_id) REFERENCES travels(id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// Tabel users
$mysqli->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
");

$res = $mysqli->query("SELECT COUNT(*) AS c FROM travels")->fetch_assoc();
if ($res['c'] == 0) {
    $mysqli->query("
    INSERT INTO travels (title, location, description, price) VALUES
    ('Pantai Tanjung Bira', 'Bulukumba', 'Pasir putih & laut biru jernih.', 750000),
    ('Tana Toraja 3 Hari', 'Tana Toraja', 'Wisata budaya Toraja.', 1800000),
    ('Wakatobi Diving 4 Hari', 'Wakatobi', 'Spot diving kelas dunia.', 4200000)
    ");
}

$res2 = $mysqli->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc();
if ($res2['c'] == 0) {
    $adminPass = password_hash("admin123", PASSWORD_DEFAULT);
    $userPass  = password_hash("user123", PASSWORD_DEFAULT);

    $mysqli->query("INSERT INTO users (username,password,role) VALUES ('admin','$adminPass','admin')");
    $mysqli->query("INSERT INTO users (username,password,role) VALUES ('user','$userPass','user')");
}

echo "Database & tabel berhasil dibuat!<br>";
echo "Akun: admin/admin123 dan user/user123<br><br>";
echo "Hapus file config/db.php setelah selesai.";
