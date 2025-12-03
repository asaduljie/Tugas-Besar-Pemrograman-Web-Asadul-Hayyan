<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$users = $mysqli->query("SELECT * FROM users ORDER BY id DESC");
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Manajemen User</title>
    <link rel="stylesheet" href="public/css/styles.css">

    <style>
        .btn-edit {
            padding: 6px 12px;
            background: #007bff;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            margin-right: 6px;
        }
        .btn-delete {
            padding: 6px 12px;
            background: #dc3545;
            color: white;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-edit:hover { background: #0062cc; }
        .btn-delete:hover { background: #b32b37; }
    </style>
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">

        <h1>Manajemen User</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Terdafar</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php 
                $no = 1; 
                while ($row = $users->fetch_assoc()): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
                        <a href="user_delete.php?id=<?= $row['id'] ?>" 
                           class="btn-delete"
                           onclick="return confirm('Hapus user ini?')">Hapus</a>
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
