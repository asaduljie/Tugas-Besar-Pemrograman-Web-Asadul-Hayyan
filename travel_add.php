<?php
require 'config/config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header("Location: auth/auth.php"); exit; }
include 'lib/upload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title']; $location = $_POST['location']; $desc = $_POST['description']; $price = $_POST['price'];
  $imagePath = upload_image('image');
  $stmt = $mysqli->prepare("INSERT INTO travels (title, location, description, price, image) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssds", $title, $location, $desc, $price, $imagePath);
  $stmt->execute();
  header("Location: travels_list.php"); exit;
}
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Tambah Paket</title><link rel="stylesheet" href="public/css/styles.css"></head>
<body>
<?php include 'layouts/navbar.php'; ?>
<div class="container layout">
  <?php include 'layouts/sidebar.php'; ?>
  <main class="main-content">
    <h1>Tambah Paket</h1>
    <form method="post" enctype="multipart/form-data" class="form-card">
      <label>Judul<input name="title" required></label>
      <label>Lokasi<input name="location" required></label>
      <label>Harga (IDR)<input name="price" type="number" required></label>
      <label>Deskripsi<textarea name="description"></textarea></label>
      <label>Gambar<input type="file" name="image" accept="image/*"></label>
      <button class="btn" type="submit">Simpan</button>
    </form>
  </main>
</div>
<?php include 'layouts/footer.php'; ?>
</body>
</html>
