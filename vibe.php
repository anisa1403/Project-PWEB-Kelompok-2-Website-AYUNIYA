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
<<<<<<< HEAD
$query = 'SELECT id_song, title, artist, album_art, spotify_embed_url, mood_category, vibe_category
          FROM songs WHERE (mood_category = ? OR mood_category = "all")';
$params = [$selectedMood];
if ($selectedVibe !== '') {
    $categories = $vibeOptions[$selectedVibe]['categories'];
    $query .= ' AND LOWER(vibe_category) IN (' . implode(',', array_fill(0, count($categories), '?')) . ')';
    $params = array_merge($params, $categories);
}
$query .= ' ORDER BY id_song DESC';
=======

function spotifyEmbedUrl(string $url): ?string
{
    $parts = parse_url(trim($url));
    if (($parts['scheme'] ?? '') !== 'https' || ($parts['host'] ?? '') !== 'open.spotify.com') {
        return null;
    }

    $path = trim((string) ($parts['path'] ?? ''), '/');
    if (preg_match('~(?:^|/)track/([A-Za-z0-9]+)$~', $path, $matches) !== 1) {
        return null;
    }

    return 'https://open.spotify.com/embed/track/' . $matches[1];
}

$perPage = 10;
$page = max(1, (int) ($_GET['page'] ?? 1));
$search = trim((string) ($_GET['q'] ?? ''));
$whereQuery = ' FROM songs WHERE (mood_category = ? OR mood_category = "all")';
$params = [$selectedMood];
if ($selectedVibe !== '') {
    $categories = $vibeOptions[$selectedVibe]['categories'];
    $whereQuery .= ' AND LOWER(vibe_category) IN (' . implode(',', array_fill(0, count($categories), '?')) . ')';
    $params = array_merge($params, $categories);
}
if ($search !== '') {
    $whereQuery .= ' AND (title LIKE ? OR spotify_embed_url LIKE ? OR mood_category LIKE ? OR vibe_category LIKE ?)';
    $searchValue = '%' . $search . '%';
    $params = array_merge($params, [$searchValue, $searchValue, $searchValue, $searchValue]);
}
$countStatement = $pdo->prepare('SELECT COUNT(*)' . $whereQuery);
$countStatement->execute($params);
$totalSongs = (int) $countStatement->fetchColumn();
$totalPages = max(1, (int) ceil($totalSongs / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$query = 'SELECT id_song, title, spotify_embed_url, mood_category, vibe_category' . $whereQuery . ' ORDER BY id_song DESC LIMIT ' . $perPage . ' OFFSET ' . $offset;
>>>>>>> a3bd38c (perbaikan alur dan penambahan halaman admin)
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
<<<<<<< HEAD
=======
        <form class="list-search" method="get">
            <input type="hidden" name="mood" value="<?php echo e($selectedMood); ?>">
            <?php if ($selectedVibe !== ''): ?><input type="hidden" name="vibe" value="<?php echo e($selectedVibe); ?>"><?php endif; ?>
            <label for="song-search">Cari lagu</label>
            <div><input id="song-search" name="q" value="<?php echo e($search); ?>" placeholder="Cari judul, kategori, atau URL Spotify"><button type="submit">Cari</button></div>
        </form>
>>>>>>> a3bd38c (perbaikan alur dan penambahan halaman admin)
        <?php if (!$songs): ?>
            <div class="empty-state"><div style="font-size:32px;margin-bottom:8px">♫ ✧</div><strong>No tracks in this selection yet.</strong><br>The playlist needs songs with valid Spotify embed URLs in the <code>songs</code> table.<?php if ($selectedVibe !== ''): ?> <a style="color:#79509d;text-decoration:underline" href="vibe.php?mood=<?php echo e($selectedMood); ?>">See all songs for this mood</a>.<?php endif; ?></div>
        <?php else: ?>
            <div class="media-grid">
                <?php foreach ($songs as $song): ?>
                    <article class="media-card">
<<<<<<< HEAD
                        <?php if ((string) $song['album_art'] !== ''): ?><img class="media-cover" src="<?php echo e((string) $song['album_art']); ?>" alt="Cover <?php echo e((string) $song['title']); ?>" loading="lazy"><?php else: ?><div class="media-cover" role="img" aria-label="Illustrated music cover"></div><?php endif; ?>
=======
>>>>>>> a3bd38c (perbaikan alur dan penambahan halaman admin)
                        <div class="media-body">
                            <?php $spotifyUrl = spotifyEmbedUrl((string) ($song['spotify_embed_url'] ?? '')); ?>
                            <?php if ($spotifyUrl !== null): ?><iframe class="spotify-player" src="<?php echo e($spotifyUrl); ?>" title="Spotify player <?php echo e((string) $song['title']); ?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"></iframe><?php else: ?><p class="subcopy">Spotify player belum tersedia untuk lagu ini.</p><?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <span>Halaman <?php echo $page; ?> dari <?php echo $totalPages; ?> · <?php echo $totalSongs; ?> lagu</span>
                <div>
                    <?php if ($page > 1): ?><a href="vibe.php?mood=<?php echo e($selectedMood); ?><?php echo $selectedVibe !== '' ? '&amp;vibe=' . e($selectedVibe) : ''; ?>&amp;q=<?php echo urlencode($search); ?>&amp;page=<?php echo $page - 1; ?>">← Sebelumnya</a><?php endif; ?>
                    <?php if ($page < $totalPages): ?><a href="vibe.php?mood=<?php echo e($selectedMood); ?><?php echo $selectedVibe !== '' ? '&amp;vibe=' . e($selectedVibe) : ''; ?>&amp;q=<?php echo urlencode($search); ?>&amp;page=<?php echo $page + 1; ?>">Selanjutnya →</a><?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
