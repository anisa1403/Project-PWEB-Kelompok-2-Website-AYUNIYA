<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/admin_auth.php';

if (!empty($_SESSION['admin_authenticated'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username !== ADMIN_USERNAME || !hash_equals(ADMIN_PASSWORD, $password)) {
        $error = 'Username atau password admin tidak sesuai.';
    } else {
        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_username'] = ADMIN_USERNAME;
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
    <title>Admin Login — AYUNIYA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/auth.css?v=20261116-9">
    <link rel="stylesheet" href="../assets/css/auth-storybook.css?v=20261116-9">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-auth-page">
    <div class="auth-shell">
        <aside class="auth-intro">
            <a class="brand" href="../index.php"><span class="brand-mark">☾</span> AYUNIYA</a>
            <div class="intro-copy"><p class="admin-kicker">Private control room</p><h1>Welcome to the <span>admin space.</span></h1><p>Manage the AYUNIYA community and its soundtrack from one calm corner.</p></div>
            <p class="intro-quote">“Keep the universe gently in order.”</p>
        </aside>
        <main class="auth-main">
            <section class="auth-card" aria-labelledby="admin-login-title">
                <div class="auth-heading"><p class="admin-kicker">AYUNIYA ADMIN</p><h2 id="admin-login-title">Sign in</h2><p>Use your administrator credentials to continue.</p></div>
                <?php if ($error !== ''): ?><p class="auth-error"><?php echo e($error); ?></p><?php endif; ?>
                <form method="post" action="login.php">
                    <div class="form-group"><label for="username">Username</label><input id="username" name="username" type="text" autocomplete="username" required></div>
                    <div class="form-group password-field"><label for="password">Password</label><div><input id="password" name="password" type="password" autocomplete="current-password" required><button class="password-toggle" type="button" aria-label="Show password"><i data-feather="eye"></i></button></div></div>
                    <button class="button" type="submit">Enter admin space <span aria-hidden="true">→</span></button>
                </form>
                <a class="back-link" href="../index.php">← Back to AYUNIYA</a>
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