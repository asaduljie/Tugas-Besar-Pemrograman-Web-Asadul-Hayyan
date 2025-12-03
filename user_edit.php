<?php
require 'config/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan");
}

$user_id = intval($_GET['id']);

// Ambil data user
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="public/css/styles.css">

    <style>
        .form-box {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            width: 450px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .form-box label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }
        .form-box input, .form-box select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .btn-save {
            padding: 10px 16px;
            background: #007bff;
            color: white;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
        }
        .btn-save:hover {
            background: #0056b3;
        }
        .btn-back {
            padding: 10px 16px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-left: 10px;
        }
        .btn-back:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>

<?php include 'layouts/navbar.php'; ?>

<div class="container layout">

    <?php include 'layouts/sidebar.php'; ?>

    <main class="main-content">

        <h1>Edit User</h1>

        <div class="form-box">

            <form action="user_edit_save.php" method="POST">

                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <label>Username</label>
                <input type="text" name="username" 
                       required 
                       value="<?= htmlspecialchars($user['username']) ?>">

                <label>Role</label>
                <select name="role" required>
                    <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
                    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
                </select>

                <button type="submit" class="btn-save">Simpan</button>
                <a href="admin_users.php" class="btn-back">Kembali</a>
            </form>

        </div>

    </main>

</div>

<?php include 'layouts/footer.php'; ?>

</body>
</html>
