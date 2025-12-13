<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT 
            r.*, 
            u.username,
            t.title AS travel_title
        FROM reservations r
        JOIN users u ON u.id = r.user_id
        JOIN travels t ON t.id = r.travel_id
        ORDER BY r.created_at DESC";

$reservations = $mysqli->query($sql);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Verifikasi Pembayaran</title>
    <link rel="stylesheet" href="public/css/styles.css">

    <style>
        .aksi-col { width: 220px; white-space: nowrap; }
        .btn-approve, .btn-reject {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            color: white;
            margin-right: 6px;
            display: inline-block;
        }
        .btn-approve { background: #28a745; }
        .btn-reject { background: #dc3545; }
        .payment-proof-img { width: 70px; border-radius: 6px; cursor: pointer; }
    </style>
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">

        <h1>Verifikasi Pembayaran</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Paket</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Metode</th>
                    <th>Bukti</th>
                    <th class="aksi-col">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $reservations->fetch_assoc()): ?>
                <?php 
                    $status = strtolower($row['payment_status']);
                ?>
                <tr>

                    <td><?= htmlspecialchars($row['username']) ?></td>

                    <td><?= htmlspecialchars($row['travel_title']) ?></td>

                    <td><?= $row['created_at'] ?></td>

                    <td>
                        <?php if ($status === 'pending'): ?>
                            <span class="status-badge status-pending">Belum Upload</span>

                        <?php elseif ($status === 'waiting'): ?>
                            <span class="status-badge status-pending">Menunggu Verifikasi</span>

                        <?php elseif ($status === 'approved'): ?>
                            <span class="status-badge status-approved">Disetujui</span>

                        <?php else: ?>
                            <span class="status-badge status-rejected">Ditolak</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $row['payment_method'] ?: '-' ?></td>

                    <td>
                        <?php if ($row['payment_proof']): ?>
                            <a href="<?= $row['payment_proof'] ?>" target="_blank">
                                <img src="<?= $row['payment_proof'] ?>" class="payment-proof-img">
                            </a>
                        <?php else: ?>
                            <span class="muted">Belum upload</span>
                        <?php endif; ?>
                    </td>

<td class="aksi-col">
    <?php if ($status === 'waiting'): ?>
        <div class="aksi-buttons">
            <a href="payment_approve.php?id=<?= $row['id'] ?>" class="btn-approve">✔ Setujui</a>
            <a href="payment_reject.php?id=<?= $row['id'] ?>" class="btn-reject">✖ Tolak</a>
        </div>
    <?php else: ?>
        <span class="muted">Tidak ada aksi</span>
    <?php endif; ?>
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
