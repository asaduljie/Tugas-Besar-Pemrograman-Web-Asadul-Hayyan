<?php
require 'config/config.php';
include 'lib/review_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$review_id = intval($_GET['id']);
$review = getReviewById($mysqli, $review_id);

if (!$review || $review['user_id'] != $_SESSION['user_id']) {
    echo "Akses ditolak.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    updateReview($mysqli, $review_id, $rating, $comment);

    header("Location: my_reviews.php?updated=1");
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Ulasan</title>
  <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">
<?php include 'layouts/sidebar.php'; ?>

<main class="main-content">

  <h2>Edit Ulasan</h2>

  <form method="post" class="form-card">

    <label>Rating
      <select name="rating" required>
        <option value="5" <?= $review['rating']==5?'selected':'' ?>>5</option>
        <option value="4" <?= $review['rating']==4?'selected':'' ?>>4</option>
        <option value="3" <?= $review['rating']==3?'selected':'' ?>>3</option>
        <option value="2" <?= $review['rating']==2?'selected':'' ?>>2</option>
        <option value="1" <?= $review['rating']==1?'selected':'' ?>>1</option>
      </select>
    </label>

    <label>Komentar
      <textarea name="comment" required><?= htmlspecialchars($review['comment']) ?></textarea>
    </label>

    <button class="btn" type="submit">Simpan Perubahan</button>

  </form>

</main>
</div>

<?php include 'layouts/footer.php'; ?>

</body>
</html>
