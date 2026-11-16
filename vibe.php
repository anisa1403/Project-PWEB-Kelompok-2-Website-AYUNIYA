<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = (int) $_SESSION['user_id'];
$moodLabels = [
    'happy' => ['label' => 'Happy', 'emoji' => '😊'],
    'good' => ['label' => 'Good', 'emoji' => '🙂'],
    'okay' => ['label' => 'Okay', 'emoji' => '😐'],
    'sad' => ['label' => 'Sad', 'emoji' => '😢'],
    'stressed' => ['label' => 'Stressed', 'emoji' => '😫'],
];
$selectedMood = (string) ($_GET['mood'] ?? '');
if (!array_key_exists($selectedMood, $moodLabels)) {
    $statement = $pdo->prepare('SELECT mood FROM moods WHERE user_id = ? ORDER BY mood_date DESC LIMIT 1');
    $statement->execute([$userId]);
    $selectedMood = (string) ($statement->fetchColumn() ?: 'happy');
}

// The four feeling cards refine the existing song list. No changes to database tables.
$vibeOptions = [
    'chill' => ['label' => 'Chill', 'icon' => 'headphones', 'subtitle' => 'Slow & peaceful', 'categories' => ['chill', 'calm', 'relax']],
    'focus' => ['label' => 'Focus', 'icon' => 'monitor', 'subtitle' => 'Find your flow', 'categories' => ['focus', 'study', 'concentration']],
    'healing' => ['label' => 'Healing', 'icon' => 'heart', 'subtitle' => 'Breathe & restore', 'categories' => ['healing', 'soft', 'slow']],
    'energetic' => ['label' => 'Energetic', 'icon' => 'zap', 'subtitle' => 'A little spark', 'categories' => ['energetic', 'energy', 'upbeat']],
];
$selectedVibe = (string) ($_GET['vibe'] ?? '');
if ($selectedVibe !== '' && !array_key_exists($selectedVibe, $vibeOptions)) {
    $selectedVibe = '';
}
$query = 'SELECT id_song, title, artist, album_art, spotify_embed_url, mood_category, vibe_category
          FROM songs WHERE (mood_category = ? OR mood_category = "all")';
$params = [$selectedMood];
if ($selectedVibe !== '') {
    $categories = $vibeOptions[$selectedVibe]['categories'];
    $query .= ' AND LOWER(vibe_category) IN (' . implode(',', array_fill(0, count($categories), '?')) . ')';
    $params = array_merge($params, $categories);
}
$query .= ' ORDER BY id_song DESC';
$statement = $pdo->prepare($query);
$statement->execute($params);
$songs = $statement->fetchAll();

$pageTitle = 'Personal Vibe + VibeTunes';
$activePage = 'vibe';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .vibe-hero{display:flex;align-items:center;justify-content:space-between;gap:24px;padding:26px 28px;border:1px solid var(--line);border-radius:20px;background:linear-gradient(120deg,#fff,var(--pink),var(--lavender))}
    .vibe-hero-emoji{display:grid;width:70px;height:70px;place-items:center;border-radius:22px;background:rgba(255,255,255,.7);font-size:42px}
    .vibe-hero h2{font-size:28px}.vibe-hero p{margin:7px 0 0;color:var(--muted);font-size:13px}
    @media(max-width:480px){.vibe-hero{align-items:flex-start;padding:20px}.vibe-hero-emoji{width:54px;height:54px;font-size:31px}}
</style>
<main class="content vibe-page">
    <p class="eyebrow">Your aura • Your melody</p>
    <h1>Find your vibe, <span>find your sound.</span></h1>
    <p class="subcopy">A little soundtrack for every feeling. Pick a mood, then explore what fits your energy.</p>
    <section class="vibe-hero" aria-label="Current mood">
        <div><p class="eyebrow">Today's feeling ✧</p><h2><?php echo e($moodLabels[$selectedMood]['label']); ?> mood <?php echo $moodLabels[$selectedMood]['emoji']; ?></h2><p>Your playlist follows the mood you choose below.</p></div>
        <span class="vibe-hero-emoji" aria-hidden="true"><?php echo $moodLabels[$selectedMood]['emoji']; ?></span>
    </section>
    <div class="filter-row" aria-label="Choose a mood">
        <?php foreach ($moodLabels as $key => $mood): ?>
            <a class="filter-chip <?php echo $key === $selectedMood ? 'active' : ''; ?>" href="vibe.php?mood=<?php echo e($key); ?><?php echo $selectedVibe !== '' ? '&amp;vibe=' . e($selectedVibe) : ''; ?>" <?php echo $key === $selectedMood ? 'aria-current="true"' : ''; ?>><?php echo e($mood['emoji'] . ' ' . $mood['label']); ?></a>
        <?php endforeach; ?>
    </div>
    <div class="vibe-pick-heading"><h2>Choose today's vibe ♡</h2><span>Optional music filter</span></div>
    <div class="vibe-picker" aria-label="Choose a vibe">
        <?php foreach ($vibeOptions as $key => $vibe): ?>
          <a class="vibe-option <?php echo $key === $selectedVibe ? 'selected' : ''; ?>" href="vibe.php?mood=<?php echo e($selectedMood); ?><?php echo $key === $selectedVibe ? '' : '&amp;vibe=' . e($key); ?>" <?php echo $key === $selectedVibe ? 'aria-current="true"' : ''; ?>><i data-feather="<?php echo e($vibe['icon']); ?>"></i><strong><?php echo e($vibe['label']); ?></strong><small><?php echo e($vibe['subtitle']); ?></small></a>
        <?php endforeach; ?>
    </div>
    <section class="panel vibe-music-panel">
        <div class="vibe-playlist-title"><h2>VibeTunes for you</h2></div>
        <p class="subcopy"><?php echo $selectedVibe === '' ? 'Playlist by mood' : e($vibeOptions[$selectedVibe]['label']) . ' playlist'; ?> • Music appears here when songs have been added in the database.</p>
        <?php if (!$songs): ?>
            <div class="empty-state"><div style="font-size:32px;margin-bottom:8px">♫ ✧</div><strong>No tracks in this selection yet.</strong><br>The playlist needs songs with valid Spotify embed URLs in the <code>songs</code> table.<?php if ($selectedVibe !== ''): ?> <a style="color:#79509d;text-decoration:underline" href="vibe.php?mood=<?php echo e($selectedMood); ?>">See all songs for this mood</a>.<?php endif; ?></div>
        <?php else: ?>
            <div class="media-grid">
                <?php foreach ($songs as $song): ?>
                    <article class="media-card">
                        <?php if ((string) $song['album_art'] !== ''): ?><img class="media-cover" src="<?php echo e((string) $song['album_art']); ?>" alt="Cover <?php echo e((string) $song['title']); ?>" loading="lazy"><?php else: ?><div class="media-cover" role="img" aria-label="Illustrated music cover"></div><?php endif; ?>
                        <div class="media-body">
                            <h3><?php echo e((string) $song['title']); ?></h3>
                            <p><?php echo e((string) $song['artist']); ?> · <?php echo e((string) $song['vibe_category']); ?></p>
                            <?php if ((string) $song['spotify_embed_url'] !== ''): ?><iframe src="<?php echo e((string) $song['spotify_embed_url']); ?>" title="Spotify player <?php echo e((string) $song['title']); ?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe><?php else: ?><p class="subcopy">Spotify player belum tersedia untuk lagu ini.</p><?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
