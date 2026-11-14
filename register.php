<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Nama dan email harus diisi dengan benar.';
    } elseif (strlen($password) < 8) {
        $error = 'Password harus memiliki minimal 8 karakter.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $check = $pdo->prepare('SELECT id_user FROM users WHERE email = ? LIMIT 1');
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'Email tersebut sudah terdaftar. Silakan login.';
        } else {
            $insert = $pdo->prepare('INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
            $insert->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account — AYUNIYA</title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-intro">
            <a class="brand" href="index.php"><span class="brand-mark">A</span> AYUNIYA</a>
            <div class="intro-copy"><h1>Make room for <span>your story.</span></h1><p>Create a calm corner for your feelings, memories, intentions, and everyday growth.</p></div>
            <p class="intro-quote">“Small rituals can make meaningful change.”</p>
        </aside>
        <main class="auth-main">
            <section class="auth-card" aria-labelledby="register-title">
                <div class="auth-heading"><h2 id="register-title">Create your space</h2><p>A gentle place to begin your next chapter.</p></div>
                <?php if ($error !== ''): ?><p class="auth-error"><?php echo e($error); ?></p><?php endif; ?>
                <form method="post" action="register.php">
                    <div class="form-group"><label for="name">Full name</label><input id="name" name="name" type="text" autocomplete="name" required></div>
                    <div class="form-group"><label for="email">Email address</label><input id="email" name="email" type="email" autocomplete="email" required></div>
                    <div class="form-group"><label for="password">Password</label><input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required></div>
                    <div class="form-group"><label for="confirm_password">Confirm password</label><input id="confirm_password" name="confirm_password" type="password" minlength="8" autocomplete="new-password" required></div>
                    <button class="button" type="submit">Create account <span aria-hidden="true">→</span></button>
                </form>
                <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
                <a class="back-link" href="index.php">← Back to home</a>
            </section>
        </main>
    </div>
</body>
</html>
