<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
requireLogin();
$pageTitle = $pageTitle ?? 'AYUNIYA';
$activePage = $activePage ?? '';
$userName = e((string) $_SESSION['user_name']);
$profilePicture = '';
if (isset($pdo)) {
    $profileStatement = $pdo->prepare('SELECT profile_picture FROM users WHERE id_user = ? LIMIT 1');
    $profileStatement->execute([(int) $_SESSION['user_id']]);
    $profilePicture = (string) ($profileStatement->fetchColumn() ?: '');
}
$avatarInitial = strtoupper(substr((string) $_SESSION['user_name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title><?php echo e($pageTitle); ?> — AYUNIYA</title><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="assets/css/app.css"><script src="https://unpkg.com/feather-icons"></script></head>
<body><div class="app" id="app-shell"><aside class="sidebar open" id="sidebar"><div class="sidebar-head"><a class="brand" href="dashboard.php"><span class="brand-mark">A</span> AYUNIYA</a><button class="menu-toggle sidebar-toggle" id="sidebar-toggle" type="button" aria-label="Close menu" aria-expanded="true"><i data-feather="menu"></i></button></div><p class="side-label">My universe</p>
<a class="side-link <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>" href="dashboard.php"><span><i data-feather="home"></i></span> Dashboard</a><a class="side-link <?php echo $activePage === 'mood' ? 'active' : ''; ?>" href="mood.php"><span><i data-feather="smile"></i></span> Mood Tracker</a><a class="side-link <?php echo $activePage === 'journal' ? 'active' : ''; ?>" href="journal.php"><span><i data-feather="edit-3"></i></span> Daily Journal</a><a class="side-link <?php echo $activePage === 'goals' ? 'active' : ''; ?>" href="goals.php"><span><i data-feather="target"></i></span> Daily Goals</a><a class="side-link <?php echo $activePage === 'vibe' ? 'active' : ''; ?>" href="vibe.php"><span><i data-feather="music"></i></span> Personal Vibe</a><a class="side-link <?php echo $activePage === 'recap' ? 'active' : ''; ?>" href="recap.php"><span><i data-feather="pie-chart"></i></span> Personal Recap</a><div class="side-bottom"><p class="side-label">Account</p><a class="side-link <?php echo $activePage === 'profile' ? 'active' : ''; ?>" href="profile.php"><span><i data-feather="user"></i></span> Profile</a><a class="side-link" href="logout.php"><span><i data-feather="log-out"></i></span> Logout</a></div></aside><div class="main"><header class="topbar"><span class="date"><?php echo e(date('l, d F Y')); ?></span><a class="profile profile-link" href="profile.php" aria-label="Open profile"><span><?php echo $userName; ?></span><?php if ($profilePicture !== ''): ?><img class="avatar" src="<?php echo e($profilePicture); ?>" alt="Profile picture"><?php else: ?><span class="avatar"><?php echo e($avatarInitial); ?></span><?php endif; ?></a><a class="topbar-home" href="dashboard.php" aria-label="Go to dashboard"><i data-feather="home"></i></a><button class="menu-toggle topbar-toggle" id="menu-toggle" type="button" aria-label="Open menu" aria-expanded="false"><i data-feather="menu"></i></button></header>
