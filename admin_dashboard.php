<?php
require 'config/config.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: auth/auth.php");
    exit;
}

// --- Statistik ---
$countTravels = $mysqli->query("SELECT COUNT(*) c FROM travels")->fetch_assoc()['c'];
$countRes = $mysqli->query("SELECT COUNT(*) c FROM reservations")->fetch_assoc()['c'];
$countUsers = $mysqli->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];

// --- Grafik: Reservasi per bulan (12 bulan terakhir) ---
$allMonths = [];
for ($i = 11; $i >= 0; $i--) {
    $m = date("Y-m", strtotime("-$i months"));
    $allMonths[$m] = 0;
}

$sql = "SELECT DATE_FORMAT(created_at,'%Y-%m') ym, COUNT(*) c FROM reservations GROUP BY ym";
$res = $mysqli->query($sql);

while ($row = $res->fetch_assoc()) {
    if (isset($allMonths[$row['ym']])) {
        $allMonths[$row['ym']] = intval($row['c']);
    }
}

$months = array_keys($allMonths);
$counts = array_values($allMonths);

// --- Grafik Status Pembayaran ---
$statusData = [
    "approved" => 0,
    "pending" => 0,
    "waiting" => 0,
    "rejected" => 0
];

$stat = $mysqli->query("SELECT payment_status, COUNT(*) c FROM reservations GROUP BY payment_status");

while ($row = $stat->fetch_assoc()) {
    $key = strtolower($row["payment_status"]);
    if (isset($statusData[$key])) {
        $statusData[$key] = intval($row['c']);
    }
}

// --- 4 Paket terbaru ---
$latestTravels = $mysqli->query("SELECT * FROM travels ORDER BY id DESC LIMIT 4");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card h2 { font-size: 38px; margin: 0; }
        .chart-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 35px;
        }
        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
        }
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card-img { width: 100%; height: 140px; object-fit: cover; }
        .card-body { padding: 12px; }
        .card-actions a { margin-right: 10px; }
    </style>
</head>

<body>
<?php include 'layouts/navbar.php'; ?>

<div class="container layout">
<?php include 'layouts/sidebar.php'; ?>

<main class="main-content">

    <h1>Dashboard Admin</h1>

    <!-- Statistik -->
    <div class="dashboard-grid">
        <div class="stat-card">
            <h2><?= $countTravels ?></h2>
            <p>Paket Wisata</p>
        </div>
        <div class="stat-card">
            <h2><?= $countRes ?></h2>
            <p>Total Reservasi</p>
        </div>
        <div class="stat-card">
            <h2><?= $countUsers ?></h2>
            <p>Total User</p>
        </div>
    </div>

    <!-- Grafik Reservasi -->
    <div class="chart-box">
        <h2>Reservasi 12 Bulan Terakhir</h2>
        <canvas id="resChart"></canvas>
    </div>

    <!-- Grafik Status Pembayaran -->
    <div class="chart-box">
        <h2>Status Pembayaran</h2>
        <canvas id="statusChart"></canvas>
    </div>

    <!-- Paket Terbaru -->
    <h2>Paket Terbaru</h2>
    <div class="cards">
        <?php while($row = $latestTravels->fetch_assoc()): ?>
        <div class="card">
            <img src="<?= htmlspecialchars($row['image'] ?: 'public/img/default.jpg') ?>" class="card-img">
            <div class="card-body">
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p class="muted"><?= htmlspecialchars($row['location']) ?></p>
                <div class="card-actions">
                    <a href="travel_edit.php?id=<?= $row['id'] ?>">Edit</a>
                    <a href="travel_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</main>
</div>

<?php include 'layouts/footer.php'; ?>

<script>
// --- Grafik Line ---
new Chart(document.getElementById('resChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Reservasi per bulan',
            data: <?= json_encode($counts) ?>,
            borderWidth: 3,
            borderColor: "#4A90E2",
            backgroundColor: "rgba(74,144,226,0.25)",
            tension: 0.35,
            fill: true
        }]
    }
});

// --- Grafik Status Pembayaran ---
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: ["Disetujui", "Pending", "Menunggu", "Ditolak"],
        datasets: [{
            label: 'Jumlah',
            data: [
                <?= $statusData['approved'] ?>,
                <?= $statusData['pending'] ?>,
                <?= $statusData['waiting'] ?>,
                <?= $statusData['rejected'] ?>
            ],
            backgroundColor: ["#2ecc71","#f1c40f","#3498db","#e74c3c"]
        }]
    }
});
</script>

</body>
</html>
