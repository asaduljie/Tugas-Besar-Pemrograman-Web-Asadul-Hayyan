<?php
require '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();

        if ($password === $u['password']) {

            $_SESSION['user_id'] = $u['id'];
            $_SESSION['username'] = $u['username'];
            $_SESSION['role'] = $u['role'];

            if ($u['role'] === 'admin') {
                header("Location: ../admin_dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        }
    }

    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Masuk</h2>

        <?php if ($error): ?>
            <div class="error-box"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">

            <label>Username
                <input type="text" name="username" required>
            </label>

            <label>Password
                <input type="password" name="password" required>
            </label>

            <button class="btn" type="submit">Masuk</button>

            <p class="login-links">
                Belum punya akun? <a href="register.php">Daftar</a>
            </p>
        </form>
    </div>
</div>

</body>
</html>
