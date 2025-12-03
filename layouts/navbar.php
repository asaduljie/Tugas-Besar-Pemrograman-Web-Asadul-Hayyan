<?php
$role = $_SESSION['role'] ?? null;
$username = $_SESSION['username'] ?? null;
?>

<nav class="nav">
    <a href="index.php" class="brand">Sulawesi<span class="brand-accent">Travel</span></a>

    <div class="nav-right">

        <?php if (!$role): ?>
            <a class="nav-link" href="auth/auth.php">Login</a>
            <a class="nav-link" href="auth/register.php">Register</a>

        <?php else: ?>
            <img src="public/img/avatar.png" alt="avatar" class="nav-avatar-img">
            <span class="nav-username"><?= htmlspecialchars($username) ?></span>
            <a class="nav-link" href="logout.php">Logout</a>

        <?php endif; ?>

    </div>
</nav>
