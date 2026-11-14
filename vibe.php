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

$statement = $pdo->prepare(
    'SELECT id_song, title, artist, album_art, spotify_embed_url, mood_category, vibe_category
     FROM songs
     WHERE mood_category = ? OR mood_category = "all"
     ORDER BY id_song DESC'
);
$statement->execute([$selectedMood]);
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
<main class="content">
    <p class="eyebrow">Your aura</p>
    <h1>Find your vibe, <span>find your sound.</span></h1>
    <p class="subcopy">A soundtrack that follows the feeling you are carrying today.</p>
    <section class="vibe-hero">
        <div><h2><?php echo e($moodLabels[$selectedMood]['label']); ?> mood <?php echo $moodLabels[$selectedMood]['emoji']; ?></h2><p>Choose another mood to refresh your recommendations.</p></div>
        <span class="vibe-hero-emoji"><?php echo $moodLabels[$selectedMood]['emoji']; ?></span>
    </section>
    <div class="filter-row">
        <?php foreach ($moodLabels as $key => $mood): ?><a class="filter-chip <?php echo $key === $selectedMood ? 'active' : ''; ?>" href="vibe.php?mood=<?php echo e($key); ?>"><?php echo e($mood['emoji'] . ' ' . $mood['label']); ?></a><?php endforeach; ?>
    </div>
    <section class="panel" style="margin-top:20px">
        <h2>VibeTunes for you</h2>
        <p class="subcopy">Play a song and its cover will stay visible inside the music card.</p>
        <?php if (!$songs): ?>
            <div class="empty-state">No songs have been added for this mood yet. Add a verified Spotify embed URL and album cover to the <code>songs</code> table.</div>
        <?php else: ?>
            <div class="media-grid">
                <?php foreach ($songs as $song): ?>
                    <article class="media-card">
                        <?php if ((string) $song['album_art'] !== ''): ?><img class="media-cover" src="<?php echo e((string) $song['album_art']); ?>" alt="Cover <?php echo e((string) $song['title']); ?>"><?php else: ?><div class="media-cover" aria-label="Album cover placeholder"></div><?php endif; ?>
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
