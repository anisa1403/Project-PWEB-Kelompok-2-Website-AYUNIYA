<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = (int) $_SESSION['user_id'];
$today = date('Y-m-d');
$hour = (int) date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : ($hour < 21 ? 'Good evening' : 'Good night'));
$moodLabels = ['happy' => 'Happy 😊', 'good' => 'Good 🙂', 'okay' => 'Okay 😐', 'sad' => 'Sad 😢', 'stressed' => 'Stressed 😫'];

$statement = $pdo->prepare('SELECT mood FROM moods WHERE user_id = ? AND mood_date = ? LIMIT 1');
$statement->execute([$userId, $today]);
$todayMood = $statement->fetch();
$statement = $pdo->prepare('SELECT COUNT(*) FROM journals WHERE user_id = ?');
$statement->execute([$userId]);
$journalCount = (int) $statement->fetchColumn();
$statement = $pdo->prepare("SELECT COUNT(*) AS total, SUM(status = 'completed') AS completed FROM goals WHERE user_id = ?");
$statement->execute([$userId]);
$goalSummary = $statement->fetch() ?: ['total' => 0, 'completed' => 0];
$totalGoals = (int) $goalSummary['total'];
$completedGoals = (int) ($goalSummary['completed'] ?? 0);
$goalProgress = $totalGoals > 0 ? (int) round(($completedGoals / $totalGoals) * 100) : 0;
$currentMood = $todayMood ? ($moodLabels[$todayMood['mood']] ?? ucfirst((string) $todayMood['mood'])) : 'Belum check in';

$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .dashboard-welcome { position:relative; isolation:isolate; display:flex; align-items:end; justify-content:space-between; gap:25px; overflow:hidden; padding:38px; border:1px solid rgba(255,255,255,.8); border-radius:28px; background:linear-gradient(125deg,#fff 8%,#f0e8fc 62%,#f8e6f1); box-shadow:0 18px 45px rgba(112,83,156,.12); }
    .dashboard-welcome::before,.dashboard-welcome::after { position:absolute; z-index:-1; border-radius:50%; content:""; }
    .dashboard-welcome::before { width:210px; height:210px; right:22%; bottom:-145px; background:rgba(150,119,197,.2); }
    .dashboard-welcome::after { width:110px; height:110px; right:5%; top:-55px; background:rgba(248,230,241,.8); }
    .daily-note { max-width:260px; color:var(--deep-purple); font-family:"Playfair Display",serif; font-size:19px; }
    .dashboard-section-title { display:flex; align-items:end; justify-content:space-between; gap:20px; margin:38px 0 17px; }
    .dashboard-section-title h2 { font-size:28px; }
    .dashboard-section-title a,.quick-link { color:var(--deep-purple); font-size:12px; font-weight:700; }
    .dashboard-cards { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:15px; }
    .dashboard-card { position:relative; overflow:hidden; min-height:185px; padding:21px; border:1px solid var(--line); border-radius:20px; background:var(--white); box-shadow:0 8px 25px rgba(112,83,156,.05); transition:transform .28s ease,box-shadow .28s ease,border-color .28s ease; }
    .dashboard-card::after { position:absolute; right:-24px; bottom:-32px; width:100px; height:100px; border-radius:50%; background:rgba(234,224,250,.5); content:""; transition:transform .35s ease; }
    .dashboard-card:hover { border-color:#d6c6ee; box-shadow:0 15px 32px rgba(112,83,156,.15); transform:translateY(-6px); }
    .dashboard-card:hover::after { transform:scale(1.45); }
    .dashboard-icon { position:relative; z-index:1; display:grid; width:42px; height:42px; margin-bottom:18px; place-items:center; border-radius:13px; color:var(--deep-purple); background:var(--lavender); font-size:20px; transition:transform .3s ease; }
    .dashboard-icon svg { width:20px; height:20px; stroke-width:2; }
    .dashboard-card:hover .dashboard-icon { transform:rotate(-8deg) scale(1.08); }
    .dashboard-card:nth-child(2) .dashboard-icon { background:var(--pink); }
    .dashboard-card:nth-child(3) .dashboard-icon { background:var(--green); }
    .dashboard-card:nth-child(4) .dashboard-icon { background:#fff1d2; }
    .dashboard-card h3,.dashboard-card p,.dashboard-card .quick-link { position:relative; z-index:1; }
    .dashboard-card h3 { font-family:"DM Sans",sans-serif; font-size:16px; }
    .dashboard-card p { margin:6px 0 16px; color:var(--muted); font-size:13px; }
    .mood-value { color:var(--deep-purple)!important; font-weight:700; }
    .dashboard-lower { display:grid; grid-template-columns:1.1fr .9fr; gap:15px; margin-top:15px; }
    .dashboard-panel { padding:24px; border:1px solid var(--line); border-radius:18px; background:var(--white); transition:transform .28s ease,box-shadow .28s ease; }
    .dashboard-panel:hover { box-shadow:0 15px 32px rgba(112,83,156,.12); transform:translateY(-3px); }
    .dashboard-panel h2 { font-size:25px; }
    .mood-row { display:flex; align-items:center; justify-content:space-between; gap:15px; margin-top:22px; padding:15px; border-radius:14px; background:var(--bg); }
    .stats { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-top:20px; }
    .stat { padding:14px; border-radius:13px; background:var(--bg); }
    .stat strong { display:block; font-size:23px; }
    .stat small { color:var(--muted); font-size:11px; }
    .progress { height:8px; margin-top:18px; overflow:hidden; border-radius:8px; background:var(--lavender); }
    .progress span { display:block; width:<?php echo $goalProgress; ?>%; height:100%; border-radius:inherit; background:var(--purple); transition:width .8s ease; }
    @media(max-width:1000px){.dashboard-cards{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(max-width:760px){.dashboard-welcome,.dashboard-lower{display:grid;grid-template-columns:1fr}.daily-note{max-width:none}.dashboard-cards{grid-template-columns:1fr}}
    @media(max-width:480px){.dashboard-welcome{padding:23px}.dashboard-section-title{align-items:flex-start;flex-direction:column}.stats{grid-template-columns:1fr}}
</style>
<main class="content">
    <section class="dashboard-welcome">
        <div><p class="eyebrow">Your personal universe</p><h1><?php echo $greeting; ?>, <span><?php echo e((string) $_SESSION['user_name']); ?>.</span></h1><p class="subcopy">Welcome back. Make a little space for what matters to you today.</p></div>
        <p class="daily-note">“You do not have to have it all figured out at once.”</p>
    </section>
    <div class="dashboard-section-title"><h2>Today at a glance</h2><a href="mood.php">Check in today →</a></div>
    <section class="dashboard-cards">
        <a class="dashboard-card" href="mood.php"><div class="dashboard-icon"><i data-feather="smile"></i></div><h3>Today's mood</h3><p class="mood-value"><?php echo e($currentMood); ?></p><span class="quick-link">Open tracker →</span></a>
        <a class="dashboard-card" href="journal.php"><div class="dashboard-icon"><i data-feather="edit-3"></i></div><h3>My journal</h3><p><?php echo $journalCount; ?> journal<?php echo $journalCount === 1 ? '' : 's'; ?> written</p><span class="quick-link">Open journal →</span></a>
        <a class="dashboard-card" href="goals.php"><div class="dashboard-icon"><i data-feather="target"></i></div><h3>My goals</h3><p><?php echo $completedGoals; ?> of <?php echo $totalGoals; ?> completed</p><span class="quick-link">View goals →</span></a>
        <a class="dashboard-card" href="vibe.php"><div class="dashboard-icon"><i data-feather="music"></i></div><h3>Your aura</h3><p>Find your sound for today</p><span class="quick-link">Discover vibe →</span></a>
    </section>
    <section class="dashboard-lower">
        <div class="dashboard-panel"><h2>Today's mood</h2><p class="subcopy">A small check-in can help you understand what you need.</p><div class="mood-row"><span>Your current mood</span><strong class="mood-value"><?php echo e($currentMood); ?></strong></div><a class="quick-link" href="mood.php">Update my mood →</a></div>
        <div class="dashboard-panel"><h2>Goal progress</h2><div class="stats"><div class="stat"><strong><?php echo $totalGoals; ?></strong><small>Total goals</small></div><div class="stat"><strong><?php echo $completedGoals; ?></strong><small>Completed</small></div><div class="stat"><strong><?php echo $goalProgress; ?>%</strong><small>Progress</small></div></div><div class="progress"><span></span></div><a class="quick-link" href="goals.php">Manage goals →</a></div>
    </section>
</main>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
