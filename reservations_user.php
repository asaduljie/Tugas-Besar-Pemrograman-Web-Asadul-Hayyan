<?php
require 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/auth.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? "User";

$sql = "SELECT r.*, t.title, t.location 
        FROM reservations r 
        JOIN travels t ON r.travel_id = t.id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Reservasi Saya</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>

    <?php include 'layouts/navbar.php'; ?>

    <div class="container layout">

        <?php include 'layouts/sidebar.php'; ?>

        <main class="main-content">

            <h1>Reservasi Saya</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="popup-success" id="popupSuccess">
                    <p>Reservasi berhasil dibuat!</p>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById("popupSuccess").style.opacity = 0;
                    }, 2500);
                </script>
            <?php endif; ?>

            <?php if (isset($_GET['canceled'])): ?>
                <div class="popup-success" id="popupCanceled">
                    <p>❌ Reservasi berhasil dibatalkan.</p>
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById("popupCanceled").style.opacity = 0;
                    }, 2500);
                </script>
            <?php endif; ?>

            <?php if ($reservations->num_rows == 0): ?>
                <p class="muted">Anda belum melakukan reservasi.</p>

            <?php else: ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Paket</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Metode</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $reservations->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= $row['created_at'] ?></td>

                                <td>
                                    <span class="status-badge status-<?= strtolower($row['payment_status']) ?>">
                                        <?= ucfirst($row['payment_status']) ?>
                                    </span>
                                </td>

                                <td><?= $row['payment_method'] ?: "-" ?></td>

                                <td>
                                    <?php if ($row['payment_proof']): ?>
                                        <img src="<?= $row['payment_proof'] ?>" class="payment-proof-img">
                                    <?php else: ?>
                                        <span class="muted">Belum upload</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (strtolower($row['payment_status']) === 'pending'): ?>

                                        <a href="payment_upload.php?id=<?= $row['id'] ?>" class="btn-sm">
                                            Upload Pembayaran
                                        </a>

                                        <a href="reservation_cancel.php?id=<?= $row['id'] ?>"
                                            onclick="return confirm('Batalkan reservasi ini?')" class="btn-delete">
                                            Batalkan
                                        </a>

                                    <?php elseif (strtolower($row['payment_status']) === 'menunggu verifikasi'): ?>
                                        <span class="muted">Menunggu verifikasi admin</span>

                                    <?php elseif (strtolower($row['payment_status']) === 'success'): ?>
                                        <span class="muted">Pembayaran Selesai</span>

                                    <?php elseif (strtolower($row['payment_status']) === 'gagal'): ?>
                                        <span class="muted">Pembayaran Ditolak</span>

                                    <?php else: ?>
                                        <span class="muted">Tidak ada aksi</span>
                                    <?php endif; ?>
                                </td>


                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php endif; ?>

        </main>

    </div>

    <?php include 'layouts/footer.php'; ?>

</body>

</html>