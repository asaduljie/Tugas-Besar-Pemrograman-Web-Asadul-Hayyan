<?php
require 'config/config.php';
include 'lib/review_functions.php';   
include 'lib/travel_functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User';

$reviews = getReviewsByUser($mysqli, $user_id);
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Ulasan Saya</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

    <?php include 'layouts/navbar.php'; ?>

    <div class="container layout">

        <?php include 'layouts/sidebar.php'; ?>

        <main class="main-content">

            <h1>Ulasan Saya</h1>

            <?php if ($reviews->num_rows == 0): ?>

                <p class="muted">Anda belum memberikan ulasan pada paket wisata mana pun.</p>

            <?php else: ?>

                <?php while ($r = $reviews->fetch_assoc()): ?>

                    <div class="review-card">

                        <strong>
                            <?= htmlspecialchars(getTravelTitle($mysqli, $r['travel_id'])) ?>
                        </strong>

                        <div class="review-rating">
                            <?= str_repeat('★', intval($r['rating'])) ?>
                            <span class="muted"><?= $r['rating'] ?>/5</span>
                        </div>

                        <p><?= nl2br(htmlspecialchars($r['comment'])) ?></p>

                        <small class="muted">Ditulis pada: <?= htmlspecialchars($r['created_at']) ?></small>

                        <br><br>
                        <div class="review-actions">
                            <a href="edit_review.php?id=<?= $r['id'] ?>" class="btn-review-edit">Edit</a>

                            <a href="review_delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Hapus ulasan ini?')"
                                class="btn-review-delete">
                                Hapus
                            </a>
                        </div>


                    </div>

                <?php endwhile; ?>

            <?php endif; ?>

        </main>

    </div>

    <?php include 'layouts/footer.php'; ?>

</body>

</html>