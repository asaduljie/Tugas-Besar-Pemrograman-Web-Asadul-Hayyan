<?php
require 'config/config.php';
include 'lib/travel_functions.php';
include 'lib/review_functions.php';

$role     = $_SESSION['role'] ?? null;
$user_id  = $_SESSION['user_id'] ?? null;

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header("Location: travels_list.php");
    exit;
}

$travel = getTravelById($mysqli, $id);
if (!$travel) {
    echo "Paket tidak ditemukan.";
    exit;
}

$reviews   = getReviewsByTravel($mysqli, $id);
$avgRating = getAverageRating($mysqli, $id);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($travel['title']) ?></title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

    <?php if ($role): ?>
        <?php include 'layouts/sidebar.php'; ?>
    <?php endif; ?>

    <main class="main-content">

        <h1><?= htmlspecialchars($travel['title']) ?></h1>
        <p class="muted">
            <?= htmlspecialchars($travel['location']) ?> • 
            Rp <?= number_format($travel['price'], 0, ',', '.') ?>
        </p>

        <div class="card">
            <img src="<?= htmlspecialchars($travel['image'] ?: 'public/img/default.jpg') ?>" 
                 class="card-img">

            <div class="card-body">
                <h3>Deskripsi</h3>
                <p><?= nl2br(htmlspecialchars($travel['description'])) ?></p>
            </div>
        </div>

        <h3>Rating & Ulasan (<?= $avgRating ? round($avgRating,2) . " / 5" : "Belum ada" ?>)</h3>

        <?php if ($reviews->num_rows > 0): ?>
            <?php while ($r = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <strong><?= htmlspecialchars($r['username'] ?? "User") ?></strong>
                    <div class="review-rating">
                        <?= str_repeat("★", intval($r['rating'])) ?>
                        <?= intval($r['rating']) ?>/5
                    </div>
                    <p><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                    <small class="muted"><?= $r['created_at'] ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Belum ada ulasan.</p>
        <?php endif; ?>

        <?php if ($user_id): ?>
            <h3>Berikan Ulasan</h3>

            <form action="add_review.php" method="post" class="form-card">
                <input type="hidden" name="travel_id" value="<?= $travel['id'] ?>">

                <label>Rating
                    <select name="rating" required>
                        <option value="">--Pilih--</option>
                        <option>5</option>
                        <option>4</option>
                        <option>3</option>
                        <option>2</option>
                        <option>1</option>
                    </select>
                </label>

                <label>Ulasan
                    <textarea name="comment"></textarea>
                </label>

                <button class="btn" type="submit">Kirim Ulasan</button>
            </form>

        <?php else: ?>
            <p>Silakan <a href="auth/auth.php">login</a> untuk memberikan ulasan.</p>
        <?php endif; ?>

        <h3>Reservasi</h3>

        <?php if (!$user_id): ?>
            <a class="btn" href="auth/auth.php">Login untuk Reservasi</a>

        <?php else: ?>
            <form action="reservation_store.php" method="post" class="form-card">
                <input type="hidden" name="travel_id" value="<?= $travel['id'] ?>">

                <label>Nama
                    <input type="text" name="name" required>
                </label>

                <label>Email
                    <input type="email" name="email" required>
                </label>

                <label>No. Telepon
                    <input type="text" name="phone" required>
                </label>

                <label>Catatan (opsional)
                    <textarea name="note"></textarea>
                </label>

                <button class="btn" type="submit">Kirim Reservasi</button>
            </form>
        <?php endif; ?>

    </main>
</div>

<?php include 'layouts/footer.php'; ?>
</body>
</html>
