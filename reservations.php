<?php
require 'config/config.php';
include 'lib/travel_functions.php';

$role = $_SESSION['role'] ?? null;

$travel_id = intval($_GET['travel_id'] ?? 0);

if ($role === 'admin' && !$travel_id) {
    $reservations = $mysqli->query("
        SELECT r.*, t.title 
        FROM reservations r 
        JOIN travels t ON r.travel_id = t.id 
        ORDER BY r.created_at DESC
    ");
}

$travel = null;
if ($travel_id) {
    $travel = getTravelById($mysqli, $travel_id);
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Reservasi</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>
    <?php include 'layouts/navbar.php'; ?>

    <div class="container layout">
        <?php include 'layouts/sidebar.php'; ?>

        <main class="main-content">

            <?php if ($role === 'admin' && !$travel_id): ?>

                <h1>Semua Reservasi</h1>

                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Paket</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telp</th>
                            <th>Kursus</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($row = $reservations->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['title']) ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                <td><?= htmlspecialchars($row['course_registration']) ?></td>
                                <td><?= $row['created_at'] ?></td>

                                <td>
                                    <span class="status-badge status-<?= strtolower($row['payment_status']) ?>">
                                        <?= htmlspecialchars($row['payment_status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($row['payment_proof']): ?>
                                        <a href="<?= htmlspecialchars($row['payment_proof']) ?>" target="_blank" class="action-link">
                                            Lihat
                                        </a>
                                    <?php else: ?>
                                        <span class="muted">Tidak ada</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php elseif ($travel): ?>

                <h1>Reservasi: <?= htmlspecialchars($travel['title']) ?></h1>

                <form action="reservation_store.php" method="post" class="form-card">
                    <input type="hidden" name="travel_id" value="<?= $travel['id'] ?>">

                    <label>Nama
                        <input name="name" required>
                    </label>

                    <label>Email
                        <input name="email" type="email" required>
                    </label>

                    <label>Telepon
                        <input name="phone">
                    </label>

                    <fieldset>
                        <legend>Pendaftaran Kursus (opsional)</legend>

                        <label>
                            <input type="checkbox" id="course_toggle" name="want_course" value="1">
                            Ya, saya ingin mendaftar kursus
                        </label>

                        <div id="course_fields" style="display:none;">
                            <label>Nama Kursus
                                <select name="course_name">
                                    <option value="">--Pilih--</option>
                                    <option>Fotografi Alam</option>
                                    <option>Kursus Diving Pemula</option>
                                    <option>Budaya & Etika Lokal</option>
                                </select>
                            </label>
                        </div>
                    </fieldset>

                    <label>Catatan
                        <textarea name="note"></textarea>
                    </label>

                    <button class="btn" type="submit">Kirim Reservasi</button>
                </form>

            <?php else: ?>

                <p>Paket tidak ditemukan.</p>

            <?php endif; ?>
        </main>
    </div>

    <?php include 'layouts/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let t = document.getElementById('course_toggle');
            if (t) {
                t.addEventListener('change', function () {
                    document.getElementById('course_fields').style.display =
                        this.checked ? 'block' : 'none';
                });
            }
        });
    </script>

</body>
</html>
