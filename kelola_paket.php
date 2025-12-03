<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM travels ORDER BY created_at DESC";
$travels = $mysqli->query($sql);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Paket Wisata</title>
    <link rel="stylesheet" href="public/css/styles.css">

    <style>
        .aksi-col {
            width: 180px;
            white-space: nowrap;
        }
        .btn-edit {
            background: #007bff;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 6px;
        }
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
        }
        .img-thumb {
            width: 80px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">

        <h1>Kelola Paket Wisata</h1>

        <a href="travel_add.php" class="btn">+ Tambah Paket Baru</a>
        <br><br>

        <table class="table">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Lokasi</th>
                    <th>Harga</th>
                    <th class="aksi-col">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $travels->fetch_assoc()): ?>
                <tr>

                    <td>
                        <img src="<?= $row['image'] ?: 'public/img/default.jpg' ?>" class="img-thumb">
                    </td>

                    <td><?= htmlspecialchars($row['title']) ?></td>

                    <td><?= htmlspecialchars($row['location']) ?></td>

                    <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>

                    <td>
                        <a href="travel_edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>

                        <a href="travel_delete.php?id=<?= $row['id'] ?>"
                           onclick="return confirm('Hapus paket ini?')"
                           class="btn-delete">
                           Hapus
                        </a>
                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </main>

</div>

<?php include 'layouts/footer.php'; ?>
</body>
</html>
