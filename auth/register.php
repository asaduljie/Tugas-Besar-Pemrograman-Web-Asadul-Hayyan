<?php
require '../config/config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password']; 
    $role     = $_POST['role'];

    $check = $mysqli->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
    $check->bind_param("s", $username);
    $check->execute();
    $exists = $check->get_result();

    if ($exists->num_rows > 0) {
        $msg = "Username sudah digunakan!";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO users (username,password,role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $password, $role);
        $stmt->execute();

        $msg = "Akun berhasil dibuat. Silakan login.";
    }
}
?>

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Daftar</h2>

        <?php if ($msg): ?>
            <div class="info-box"><?= $msg ?></div>
        <?php endif; ?>

        <form method="POST">

            <label>Username
                <input type="text" name="username" required>
            </label>

            <label>Password
                <input type="password" name="password" required>
            </label>

            <label>Daftar Sebagai
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </label>

            <button class="btn" type="submit">Daftar</button>

            <p class="login-links">
                Sudah punya akun? <a href="auth.php">Masuk</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
