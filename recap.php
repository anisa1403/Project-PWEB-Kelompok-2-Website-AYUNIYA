<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';require_once __DIR__ . '/includes/auth.php';requireLogin();$userId=(int)$_SESSION['user_id'];$s=$pdo->prepare('SELECT COUNT(*) FROM moods WHERE user_id=?');$s->execute([$userId]);$moods=(int)$s->fetchColumn();$s=$pdo->prepare('SELECT COUNT(*) FROM journals WHERE user_id=?');$s->execute([$userId]);$journals=(int)$s->fetchColumn();$s=$pdo->prepare("SELECT COUNT(*) FROM goals WHERE user_id=? AND status='completed'");$s->execute([$userId]);$completed=(int)$s->fetchColumn();$pageTitle='Personal Recap';$activePage='recap';require __DIR__.'/includes/app_header.php';?>
<main class="content recap-page">
    <p class="eyebrow">Your little universe ✦</p>
    <h1>Look how far <span>you've come.</span></h1>
    <p class="subcopy">Every check-in, journal page, and completed intention is part of your story.</p>
    <section class="recap-cover">
      <div><p class="eyebrow">Your personal recap</p><h2>Your days, your story ♡</h2><p>Small moments matter more than you think.</p></div>
      <span aria-hidden="true" class="recap-cover-spark">✦ ☾</span>
    </section>
    <div class="recap-tiles">
      <a href="mood.php" class="recap-tile"><span>☼</span><strong>Mood</strong><b><?php echo $moods; ?></b><small>Check-ins</small></a>
      <a href="journal.php" class="recap-tile"><span>✎</span><strong>Journal</strong><b><?php echo $journals; ?></b><small>Entries</small></a>
      <a href="goals.php" class="recap-tile"><span>✧</span><strong>Goals</strong><b><?php echo $completed; ?></b><small>Completed</small></a>
      <a href="vibe.php" class="recap-tile"><span>♫</span><strong>Vibe</strong><b>♡</b><small>Find your sound</small></a>
    </div>
    <section class="panel" style="margin-top:22px"><h2>A gentle reminder</h2><p class="subcopy">You don't need a perfect day to make meaningful progress. Keep noticing what matters, writing what feels important, and celebrating small steps.</p><a class="button" href="dashboard.php">Back to your universe <i data-feather="arrow-right"></i></a></section>
</main>
<?php require __DIR__.'/includes/app_footer.php';?>
