<?php
require '../config/config.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $role     = $_POST['role'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Format email tidak valid!";
    } else {

        $checkUser = $mysqli->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
        $checkUser->bind_param("s", $username);
        $checkUser->execute();
        $userExists = $checkUser->get_result();

        $checkEmail = $mysqli->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $emailExists = $checkEmail->get_result();

        if ($userExists->num_rows > 0) {
            $msg = "Username sudah digunakan!";
        } else if ($emailExists->num_rows > 0) {
            $msg = "Email sudah digunakan!";
        } else {

            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password, role) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);
            $stmt->execute();

            $msg = "Akun berhasil dibuat. Silakan login.";
        }
    }
}
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

            <label>Email
                <input type="email" name="email" required>
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
