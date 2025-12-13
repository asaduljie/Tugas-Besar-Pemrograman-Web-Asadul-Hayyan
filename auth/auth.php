<?php
require '../config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login_id = trim($_POST['login_id']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, username, email, password, role FROM users 
                              WHERE username = ? OR email = ? LIMIT 1");
    $stmt->bind_param("ss", $login_id, $login_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();

        if ($password === $u['password']) {

            $_SESSION['user_id']  = $u['id'];
            $_SESSION['username'] = $u['username'];
            $_SESSION['email']    = $u['email'];
            $_SESSION['role']     = $u['role'];

            if ($u['role'] === 'admin') {
                header("Location: ../admin_dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        }
    }

    $error = "ID Login atau password salah!";
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

            <label>Email atau Username
                <input type="text" name="login_id" required>
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
