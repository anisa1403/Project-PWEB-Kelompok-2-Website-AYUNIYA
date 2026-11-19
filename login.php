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
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = $pdo->prepare('SELECT id_user, nama, password FROM users WHERE email = ? LIMIT 1');
    $statement->execute([$email]);
    $user = $statement->fetch();

    if (!$user || !password_verify($password, (string) $user['password'])) {
        $error = 'Email atau password yang kamu masukkan salah.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id_user'];
        $_SESSION['user_name'] = (string) $user['nama'];
        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in — AYUNIYA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="assets/css/auth.css?v=20261116-9"><link rel="stylesheet" href="assets/css/auth-storybook.css?v=20261116-9">
</head>
<body>
    <div class="auth-shell">
        <aside class="auth-intro">
            <a class="brand" href="index.php"><span class="brand-mark">☾</span> AYUNIYA</a>
            <div class="intro-copy"><h1>Welcome back to <span>your space.</span></h1><p>Take a breath, check in with yourself, and continue your journey at your own pace.</p></div>
            <p class="intro-quote">“You can always begin again, gently.”</p>
        </aside>
        <main class="auth-main">
            <section class="auth-card" aria-labelledby="login-title">
                <div class="auth-heading"><h2 id="login-title">Welcome back</h2><p>Log in to return to your personal universe.</p></div>
                <?php if ($error !== ''): ?><p class="auth-error"><?php echo e($error); ?></p><?php endif; ?>
                <form method="post" action="login.php">
                    <div class="form-group"><label for="email">Email address</label><input id="email" name="email" type="email" autocomplete="email" required></div>
                    <div class="form-group password-field"><label for="password">Password</label><div><input id="password" name="password" type="password" autocomplete="current-password" required><button class="password-toggle" type="button" aria-label="Show password"><i data-feather="eye"></i></button></div></div>
                    <button class="button" type="submit">Log in <span aria-hidden="true">→</span></button>
                </form>
                <p class="auth-switch">New to AYUNIYA? <a href="register.php">Start your journey</a></p>
                <a class="back-link" href="index.php">← Back to home</a>
            </section>
        </main>
    </div>
    <script src="https://unpkg.com/feather-icons"></script><script>
        feather.replace();
        document.querySelectorAll(".password-toggle").forEach((toggle) => toggle.addEventListener("click", () => {
            const input = toggle.parentElement.querySelector("input"), visible = input.type === "text";
            input.type = visible ? "password" : "text";
            toggle.setAttribute("aria-label", visible ? "Show password" : "Hide password");
            toggle.innerHTML = `<i data-feather="${visible ? "eye" : "eye-off"}"></i>`;
            feather.replace();
        }));
    </script>
</body>
</html>
