<?php
require 'config/config.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') { header("Location: auth/auth.php"); exit; }
include 'lib/travel_functions.php';
include 'lib/upload.php';
$id = intval($_GET['id'] ?? 0);
if (!$id) { header("Location: travels_list.php"); exit; }
$travel = getTravelById($mysqli, $id);
if (!$travel) { header("Location: travels_list.php"); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title']; $location = $_POST['location']; $desc = $_POST['description']; $price = $_POST['price'];
  $imagePath = $travel['image'];
  $new = upload_image('image');
  if ($new) $imagePath = $new;
  $stmt = $mysqli->prepare("UPDATE travels SET title=?, location=?, description=?, price=?, image=? WHERE id=?");
  $stmt->bind_param("sssisi", $title, $location, $desc, $price, $imagePath, $id);
  $stmt->execute();
  header("Location: travels_list.php"); exit;
}
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><title>Edit Paket</title><link rel="stylesheet" href="public/css/styles.css"></head>
<body>
<?php include 'layouts/navbar.php'; ?>
<div class="container layout">
  <?php include 'layouts/sidebar.php'; ?>
  <main class="main-content">
    <h1>Edit Paket</h1>
    <form method="post" enctype="multipart/form-data" class="form-card">
      <label>Judul<input name="title" value="<?=htmlspecialchars($travel['title'])?>" required></label>
      <label>Lokasi<input name="location" value="<?=htmlspecialchars($travel['location'])?>" required></label>
      <label>Harga (IDR)<input name="price" type="number" value="<?=htmlspecialchars($travel['price'])?>" required></label>
      <label>Deskripsi<textarea name="description"><?=htmlspecialchars($travel['description'])?></textarea></label>
      <label>Gambar sekarang:<br>
        <img src="<?=htmlspecialchars($travel['image'] ?: 'public/img/default.jpg')?>" style="max-width:180px;border-radius:8px">
      </label>
      <label>Ganti Gambar<input type="file" name="image" accept="image/*"></label>
      <button class="btn" type="submit">Update</button>
    </form>
  </main>
</div>
<?php include 'layouts/footer.php'; ?>
</body>
</html>
