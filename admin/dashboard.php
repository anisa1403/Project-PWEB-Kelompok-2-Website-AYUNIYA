<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_auth.php';
requireAdmin();

if (!defined('ADMIN_PAGE_SECTION')) {
    $userTotal = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $songTotal = (int) $pdo->query('SELECT COUNT(*) FROM songs')->fetchColumn();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard — AYUNIYA</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/admin.css?v=20261116-9">
    </head>
    <body class="admin-dashboard">
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <a class="admin-brand" href="../index.php"><span class="brand-mark">☾</span><span>AYUNIYA<small>Admin space</small></span></a>
                <p class="admin-label">Manage universe</p>
                <nav class="admin-nav">
                    <a class="active" href="dashboard.php">⌂ <span>Overview</span></a>
                    <a href="users.php">♧ <span>Users</span></a>
                    <a href="songs.php">♫ <span>Songs</span></a>
                </nav>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar"><div><p class="admin-kicker">Good to see you, admin</p><h1>Your universe at a glance.</h1></div><div class="admin-topbar-actions"><a class="admin-home-link" href="../index.php">View website ↗</a><form method="get" action="logout.php" data-confirm="Are you sure you want to log out from admin?" data-confirm-title="Leave admin space?" data-confirm-action="Log out"><button class="admin-logout-button" type="submit">Log out <span>→</span></button></form></div></header>
                <section class="admin-stats overview-stats">
                    <article><span>Registered users</span><strong><?php echo $userTotal; ?></strong><a class="overview-link" href="users.php">Manage users →</a></article>
                    <article><span>Music library</span><strong><?php echo $songTotal; ?></strong><a class="overview-link" href="songs.php">Manage songs →</a></article>
                    <article><span>Admin access</span><strong>Active</strong><small>Private control room</small></article>
                </section>
                <section class="overview-welcome">
                    <div class="welcome-copy">
                        <p class="admin-kicker">Admin control room</p>
                        <h2>Keep your AYUNIYA space in tune.</h2>
                        <p>Manage your community and music library from one calm, focused workspace.</p>
                    </div>
                    <div class="overview-actions">
                        <a class="overview-action" href="users.php">
                            <span class="action-number">01</span>
                            <span><strong>Manage users</strong><small>View, add, or update accounts</small></span>
                            <span class="action-arrow">→</span>
                        </a>
                        <a class="overview-action" href="songs.php">
                            <span class="action-number">02</span>
                            <span><strong>Manage songs</strong><small>Curate the music library</small></span>
                            <span class="action-arrow">→</span>
                        </a>
                    </div>
                </section>
            </main>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$error = '';
$message = '';
$section = ADMIN_PAGE_SECTION;
$pageFile = $section === 'songs' ? 'songs.php' : 'users.php';
$search = trim((string) ($_GET['search'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$moodCategories = ['happy' => 'Happy', 'good' => 'Good', 'okay' => 'Okay', 'sad' => 'Sad', 'stressed' => 'Stressed'];
$vibeCategories = [
    'chill' => 'Chill',
    'focus' => 'Focus',
    'healing' => 'Healing',
    'energetic' => 'Energetic',
];
$editUser = null;
$editSong = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = (string) ($_POST['action'] ?? '');
        if ($action === 'create_user' || $action === 'update_user') {
            $id = (int) ($_POST['id_user'] ?? 0);
            $name = trim((string) ($_POST['nama'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('Nama dan email harus diisi dengan benar.');
            }
            if ($action === 'create_user') {
                if (strlen($password) < 8) {
                    throw new RuntimeException('Password user minimal 8 karakter.');
                }
                $statement = $pdo->prepare('INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
                $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
                $message = 'User berhasil ditambahkan.';
            } else {
                if ($id < 1) {
                    throw new RuntimeException('User yang akan diubah tidak valid.');
                }
                if ($password !== '' && strlen($password) < 8) {
                    throw new RuntimeException('Password user minimal 8 karakter.');
                }
                if ($password === '') {
                    $statement = $pdo->prepare('UPDATE users SET nama = ?, email = ? WHERE id_user = ?');
                    $statement->execute([$name, $email, $id]);
                } else {
                    $statement = $pdo->prepare('UPDATE users SET nama = ?, email = ?, password = ? WHERE id_user = ?');
                    $statement->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $id]);
                }
                $message = 'User berhasil diperbarui.';
            }
            $section = 'users';
        } elseif ($action === 'delete_user') {
            $id = (int) ($_POST['id_user'] ?? 0);
            if ($id < 1) {
                throw new RuntimeException('User yang akan dihapus tidak valid.');
            }
            $statement = $pdo->prepare('DELETE FROM users WHERE id_user = ?');
            $statement->execute([$id]);
            $message = 'User berhasil dihapus.';
            $section = 'users';
        } elseif ($action === 'create_song' || $action === 'update_song') {
            $id = (int) ($_POST['id_song'] ?? 0);
            $title = trim((string) ($_POST['title'] ?? ''));
            $artist = trim((string) ($_POST['artist'] ?? ''));
            $spotifyUrl = trim((string) ($_POST['spotify_embed_url'] ?? ''));
            $moodCategory = trim((string) ($_POST['mood_category'] ?? ''));
            $vibeCategory = trim((string) ($_POST['vibe_category'] ?? ''));
            if ($title === '' || $artist === '' || !array_key_exists($moodCategory, $moodCategories) || !array_key_exists($vibeCategory, $vibeCategories)) {
                throw new RuntimeException('Judul, artis, mood, dan vibe wajib diisi.');
            }
            if ($action === 'create_song') {
                $statement = $pdo->prepare('INSERT INTO songs (title, artist, spotify_embed_url, mood_category, vibe_category) VALUES (?, ?, ?, ?, ?)');
                $statement->execute([$title, $artist, $spotifyUrl ?: null, $moodCategory, $vibeCategory]);
                $message = 'Lagu berhasil ditambahkan.';
            } else {
                if ($id < 1) {
                    throw new RuntimeException('Lagu yang akan diubah tidak valid.');
                }
                $statement = $pdo->prepare('UPDATE songs SET title = ?, artist = ?, spotify_embed_url = ?, mood_category = ?, vibe_category = ? WHERE id_song = ?');
                $statement->execute([$title, $artist, $spotifyUrl ?: null, $moodCategory, $vibeCategory, $id]);
                $message = 'Lagu berhasil diperbarui.';
            }
            $section = 'songs';
        } elseif ($action === 'delete_song') {
            $id = (int) ($_POST['id_song'] ?? 0);
            if ($id < 1) {
                throw new RuntimeException('Lagu yang akan dihapus tidak valid.');
            }
            $statement = $pdo->prepare('DELETE FROM songs WHERE id_song = ?');
            $statement->execute([$id]);
            $message = 'Lagu berhasil dihapus.';
            $section = 'songs';
        }
    }

    if (isset($_GET['edit_user'])) {
        $statement = $pdo->prepare('SELECT id_user, nama, email FROM users WHERE id_user = ? LIMIT 1');
        $statement->execute([(int) $_GET['edit_user']]);
        $editUser = $statement->fetch() ?: null;
        $section = 'users';
    }
    if (isset($_GET['edit_song'])) {
        $statement = $pdo->prepare('SELECT id_song, title, artist, spotify_embed_url, mood_category, vibe_category FROM songs WHERE id_song = ? LIMIT 1');
        $statement->execute([(int) $_GET['edit_song']]);
        $editSong = $statement->fetch() ?: null;
        $section = 'songs';
    }

} catch (PDOException $exception) {
    $error = $exception->getCode() === '23000' ? 'Data tidak dapat disimpan karena email mungkin sudah digunakan.' : 'Terjadi kesalahan saat mengakses database.';
} catch (RuntimeException $exception) {
    $error = $exception->getMessage();
}

if ($section === 'users') {
    $userSearch = $search;
    $songSearch = '';
} else {
    $userSearch = '';
    $songSearch = $search;
}

$userWhere = '';
$userParams = [];
if ($userSearch !== '') {
    $userWhere = 'WHERE CAST(id_user AS CHAR) LIKE ? OR nama LIKE ? OR email LIKE ? OR created_at LIKE ?';
    $userTerm = '%' . $userSearch . '%';
    $userParams = [$userTerm, $userTerm, $userTerm, $userTerm];
}
$userCountStatement = $pdo->prepare('SELECT COUNT(*) FROM users ' . $userWhere);
$userCountStatement->execute($userParams);
$filteredUserCount = (int) $userCountStatement->fetchColumn();
$userTotal = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$userTotalPages = max(1, (int) ceil($filteredUserCount / $perPage));

$songWhere = '';
$songParams = [];
if ($songSearch !== '') {
    $songWhere = 'WHERE CAST(id_song AS CHAR) LIKE ? OR title LIKE ? OR artist LIKE ? OR spotify_embed_url LIKE ? OR mood_category LIKE ? OR vibe_category LIKE ?';
    $songTerm = '%' . $songSearch . '%';
    $songParams = [$songTerm, $songTerm, $songTerm, $songTerm, $songTerm, $songTerm];
}
$songCountStatement = $pdo->prepare('SELECT COUNT(*) FROM songs ' . $songWhere);
$songCountStatement->execute($songParams);
$filteredSongCount = (int) $songCountStatement->fetchColumn();
$songTotal = (int) $pdo->query('SELECT COUNT(*) FROM songs')->fetchColumn();
$songTotalPages = max(1, (int) ceil($filteredSongCount / $perPage));

if ($section === 'users') {
    $page = min($page, $userTotalPages);
    $offset = ($page - 1) * $perPage;
    $statement = $pdo->prepare('SELECT id_user, nama, email, created_at FROM users ' . $userWhere . ' ORDER BY id_user DESC LIMIT ' . $perPage . ' OFFSET ' . $offset);
    $statement->execute($userParams);
    $users = $statement->fetchAll();
    $songs = [];
} else {
    $page = min($page, $songTotalPages);
    $offset = ($page - 1) * $perPage;
    $statement = $pdo->prepare('SELECT id_song, title, artist, spotify_embed_url, mood_category, vibe_category FROM songs ' . $songWhere . ' ORDER BY id_song DESC LIMIT ' . $perPage . ' OFFSET ' . $offset);
    $statement->execute($songParams);
    $songs = $statement->fetchAll();
    $users = [];
}

$userForm = $editUser ?: ['id_user' => '', 'nama' => '', 'email' => ''];
$songForm = $editSong ?: ['id_song' => '', 'title' => '', 'artist' => '', 'spotify_embed_url' => '', 'mood_category' => 'happy', 'vibe_category' => 'chill'];
$searchQuery = $search !== '' ? '&amp;search=' . rawurlencode($search) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — AYUNIYA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css?v=20261116-9">
</head>
<body class="admin-dashboard">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="admin-brand" href="../index.php"><span class="brand-mark">☾</span><span>AYUNIYA<small>Admin space</small></span></a>
            <p class="admin-label">Manage universe</p>
            <nav class="admin-nav">
                <a href="dashboard.php">⌂ <span>Overview</span></a>
                <a class="<?php echo $section === 'users' ? 'active' : ''; ?>" href="users.php">♧ <span>Users</span></a>
                <a class="<?php echo $section === 'songs' ? 'active' : ''; ?>" href="songs.php">♫ <span>Songs</span></a>
            </nav>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar"><div><p class="admin-kicker">Good to see you, admin</p><h1>Keep AYUNIYA in tune.</h1></div><div class="admin-topbar-actions"><a class="admin-home-link" href="../index.php">View website ↗</a><form method="get" action="logout.php" data-confirm="Are you sure you want to log out from admin?" data-confirm-title="Leave admin space?" data-confirm-action="Log out"><button class="admin-logout-button" type="submit">Log out <span>→</span></button></form></div></header>
            <?php if ($error !== ''): ?><div class="admin-alert error"><span class="admin-alert-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M12 3 2.8 20h18.4L12 3Z"></path><path class="icon-cutout" d="M12 9v5"></path><circle class="icon-cutout" cx="12" cy="17.5" r=".75"></circle></svg></span><span><?php echo e($error); ?></span></div><?php elseif ($message !== ''): ?><div class="admin-alert success"><span class="admin-alert-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><circle cx="12" cy="12" r="9"></circle><path class="icon-cutout" d="m8 12 2.5 2.5L16 9"></path></svg></span><span><?php echo e($message); ?></span></div><?php endif; ?>
            <?php if ($section === 'users'): ?>
                <section class="admin-panel">
                    <div class="panel-heading"><div><p class="admin-kicker">People in the universe</p><h2><?php echo $editUser ? 'Edit user' : 'Add a user'; ?></h2></div><a class="soft-link" href="users.php">Clear form</a></div>
                    <form class="admin-form" method="post" action="users.php">
                        <input type="hidden" name="action" value="<?php echo $editUser ? 'update_user' : 'create_user'; ?>">
                        <input type="hidden" name="id_user" value="<?php echo e((string) $userForm['id_user']); ?>">
                        <label>Name<input name="nama" type="text" value="<?php echo e((string) $userForm['nama']); ?>" required></label>
                        <label>Email<input name="email" type="email" value="<?php echo e((string) $userForm['email']); ?>" required></label>
                        <label class="admin-password-field">Password <small><?php echo $editUser ? '(leave blank to keep current)' : '(minimum 8 characters)'; ?></small><span><input name="password" type="password" minlength="8" <?php echo $editUser ? '' : 'required'; ?>><button class="admin-password-toggle" type="button" aria-label="Show password"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg></button></span></label>
                        <button class="admin-button" type="submit"><?php echo $editUser ? 'Save changes' : 'Add user'; ?> →</button>
                    </form>
                </section>
                <section class="admin-panel">
                    <div class="panel-heading"><div><p class="admin-kicker">User management</p><h2>All users</h2></div></div>
                    <form class="table-tools" method="get" action="users.php"><label for="user-search">Search all user columns</label><div><input id="user-search" name="search" type="search" value="<?php echo e($search); ?>" placeholder="Name, email, ID, date..."><button class="search-button" type="submit">Search</button><?php if ($search !== ''): ?><a class="clear-search" href="users.php">Clear</a><?php endif; ?></div></form>
                    <div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Joined</th><th>Actions</th></tr></thead><tbody>
                    <?php foreach ($users as $user): ?><tr><td><strong><?php echo e((string) $user['nama']); ?></strong></td><td><?php echo e((string) $user['email']); ?></td><td><?php echo e(date('d M Y', strtotime((string) $user['created_at']))); ?></td><td class="actions"><a class="action-edit" href="users.php?edit_user=<?php echo (int) $user['id_user']; ?>">Edit</a><form method="post" action="users.php" data-confirm="Are you sure you want to delete this user?" data-confirm-title="Delete this user?" data-confirm-action="Delete"><input type="hidden" name="action" value="delete_user"><input type="hidden" name="id_user" value="<?php echo (int) $user['id_user']; ?>"><button class="action-delete" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
                    <?php if (!$users): ?><tr><td colspan="4" class="empty-row">Belum ada user.</td></tr><?php endif; ?></tbody></table></div>
                    <div class="pagination"><span>Page <?php echo $page; ?> of <?php echo $userTotalPages; ?> · <?php echo $filteredUserCount; ?> result(s)</span><div><?php if ($page > 1): ?><a href="users.php?page=<?php echo $page - 1; ?><?php echo $searchQuery; ?>">← Previous</a><?php endif; ?><?php if ($page < $userTotalPages): ?><a href="users.php?page=<?php echo $page + 1; ?><?php echo $searchQuery; ?>">Next →</a><?php endif; ?></div></div>
                </section>
            <?php else: ?>
                <section class="admin-panel">
                    <div class="panel-heading"><div><p class="admin-kicker">Song management</p><h2><?php echo $editSong ? 'Edit song' : 'Add a song'; ?></h2></div><a class="soft-link" href="songs.php">Clear form</a></div>
                    <form class="admin-form song-form" method="post" action="songs.php">
                        <input type="hidden" name="action" value="<?php echo $editSong ? 'update_song' : 'create_song'; ?>"><input type="hidden" name="id_song" value="<?php echo e((string) $songForm['id_song']); ?>">
                        <label>Title<input name="title" type="text" value="<?php echo e((string) $songForm['title']); ?>" required></label><label>Artist<input name="artist" type="text" value="<?php echo e((string) $songForm['artist']); ?>" required></label>
                        <label>Spotify track URL<input name="spotify_embed_url" type="url" value="<?php echo e((string) $songForm['spotify_embed_url']); ?>"></label>
                        <label>Mood category<select name="mood_category" required><?php foreach ($moodCategories as $value => $label): ?><option value="<?php echo e($value); ?>" <?php echo (string) $songForm['mood_category'] === $value ? 'selected' : ''; ?>><?php echo e($label); ?></option><?php endforeach; ?></select></label><label>Vibe category<select name="vibe_category" required><?php foreach ($vibeCategories as $value => $label): ?><option value="<?php echo e($value); ?>" <?php echo (string) $songForm['vibe_category'] === $value ? 'selected' : ''; ?>><?php echo e($label); ?></option><?php endforeach; ?></select></label>
                        <button class="admin-button" type="submit"><?php echo $editSong ? 'Save changes' : 'Add song'; ?> →</button>
                    </form>
                </section>
                <section class="admin-panel">
                    <div class="panel-heading"><div><p class="admin-kicker">Your soundtrack</p><h2>All songs</h2></div></div>
                    <form class="table-tools" method="get" action="songs.php"><label for="song-search">Search all song columns</label><div><input id="song-search" name="search" type="search" value="<?php echo e($search); ?>" placeholder="Title, artist, mood, vibe..."><button class="search-button" type="submit">Search</button><?php if ($search !== ''): ?><a class="clear-search" href="songs.php">Clear</a><?php endif; ?></div></form>
                    <div class="table-wrap"><table><thead><tr><th>Song</th><th>Categories</th><th>Spotify</th><th>Actions</th></tr></thead><tbody>
                    <?php foreach ($songs as $song): ?><tr><td><strong><?php echo e((string) $song['title']); ?></strong><small><?php echo e((string) $song['artist']); ?></small></td><td><?php echo e((string) $song['mood_category']); ?> · <?php echo e((string) $song['vibe_category']); ?></td><td><?php echo (string) $song['spotify_embed_url'] !== '' ? 'Connected' : 'Missing'; ?></td><td class="actions"><a class="action-edit" href="songs.php?edit_song=<?php echo (int) $song['id_song']; ?>">Edit</a><form method="post" action="songs.php" data-confirm="Are you sure you want to delete this song?" data-confirm-title="Delete this song?" data-confirm-action="Delete"><input type="hidden" name="action" value="delete_song"><input type="hidden" name="id_song" value="<?php echo (int) $song['id_song']; ?>"><button class="action-delete" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
                    <?php if (!$songs): ?><tr><td colspan="4" class="empty-row">Belum ada lagu.</td></tr><?php endif; ?></tbody></table></div>
                    <div class="pagination"><span>Page <?php echo $page; ?> of <?php echo $songTotalPages; ?> · <?php echo $filteredSongCount; ?> result(s)</span><div><?php if ($page > 1): ?><a href="songs.php?page=<?php echo $page - 1; ?><?php echo $searchQuery; ?>">← Previous</a><?php endif; ?><?php if ($page < $songTotalPages): ?><a href="songs.php?page=<?php echo $page + 1; ?><?php echo $searchQuery; ?>">Next →</a><?php endif; ?></div></div>
                </section>
            <?php endif; ?>
        </main>
    </div>
    <div class="admin-confirm-backdrop" id="admin-confirm" role="dialog" aria-modal="true" aria-labelledby="admin-confirm-title" aria-hidden="true">
        <section class="admin-confirm-dialog">
            <div class="admin-confirm-icon" id="admin-confirm-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M12 3 19 6v5c0 4.5-2.9 8.3-7 10-4.1-1.7-7-5.5-7-10V6l7-3Z"></path><path class="icon-cutout" d="M9.5 12 11 13.5l3.5-3.5"></path></svg></div>
            <h2 id="admin-confirm-title">Leave admin space?</h2>
            <p id="admin-confirm-message">Are you sure you want to log out?</p>
            <div class="admin-confirm-actions"><button class="admin-confirm-cancel" id="admin-confirm-cancel" type="button">Stay here</button><button class="admin-confirm-continue" id="admin-confirm-continue" type="button">Log out</button></div>
        </section>
    </div>
    <script>
        const adminConfirm = document.getElementById("admin-confirm");
        const adminConfirmMessage = document.getElementById("admin-confirm-message");
        const adminConfirmTitle = document.getElementById("admin-confirm-title");
        const adminConfirmIcon = document.getElementById("admin-confirm-icon");
        const adminConfirmCancel = document.getElementById("admin-confirm-cancel");
        const adminConfirmContinue = document.getElementById("admin-confirm-continue");
        document.querySelectorAll(".admin-password-toggle").forEach((toggle) => toggle.addEventListener("click", () => {
            const input = toggle.parentElement.querySelector("input");
            const visible = input.type === "text";
            input.type = visible ? "password" : "text";
            toggle.setAttribute("aria-label", visible ? "Show password" : "Hide password");
            toggle.innerHTML = visible
                ? '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path><circle cx="12" cy="12" r="2.5"></circle></svg>'
                : '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 3 18 18"></path><path d="M10.6 6.2A10.7 10.7 0 0 1 12 6c6.5 0 10 6 10 6a18.3 18.3 0 0 1-3.1 3.8M6.1 6.1C3.5 7.7 2 12 2 12s3.5 6 10 6c1.1 0 2.1-.2 3-.5"></path></svg>';
        }));
        const confirmIcons = {
            default: '<svg viewBox="0 0 24 24" focusable="false"><path d="M12 3 19 6v5c0 4.5-2.9 8.3-7 10-4.1-1.7-7-5.5-7-10V6l7-3Z"></path><path class="icon-cutout" d="M9.5 12 11 13.5l3.5-3.5"></path></svg>',
            warning: '<svg viewBox="0 0 24 24" focusable="false"><path d="M12 3 2.8 20h18.4L12 3Z"></path><path class="icon-cutout" d="M12 9v5"></path><circle class="icon-cutout" cx="12" cy="17.5" r=".75"></circle></svg>'
        };
        let adminPendingForm = null;
        const closeAdminConfirm = () => {
            adminConfirm.classList.remove("open");
            adminConfirm.setAttribute("aria-hidden", "true");
            document.body.classList.remove("admin-dialog-open");
            adminPendingForm = null;
        };
        document.querySelectorAll("form[data-confirm]").forEach((form) => {
            form.addEventListener("submit", (event) => {
                if (form.dataset.confirmed === "true") {
                    form.dataset.confirmed = "";
                    return;
                }
                event.preventDefault();
                adminPendingForm = form;
                adminConfirmTitle.textContent = form.dataset.confirmTitle || "Are you sure?";
                adminConfirmMessage.textContent = form.dataset.confirm;
                adminConfirmIcon.innerHTML = form.dataset.confirmAction === "Delete" ? confirmIcons.warning : confirmIcons.default;
                adminConfirmIcon.classList.toggle("warning", form.dataset.confirmAction === "Delete");
                adminConfirmContinue.textContent = form.dataset.confirmAction || "Continue";
                adminConfirm.classList.add("open");
                adminConfirm.setAttribute("aria-hidden", "false");
                document.body.classList.add("admin-dialog-open");
                adminConfirmCancel.focus();
            });
        });
        adminConfirmCancel.addEventListener("click", closeAdminConfirm);
        adminConfirmContinue.addEventListener("click", () => {
            if (!adminPendingForm) return;
            const form = adminPendingForm;
            form.dataset.confirmed = "true";
            closeAdminConfirm();
            form.submit();
        });
        adminConfirm.addEventListener("click", (event) => {
            if (event.target === adminConfirm) closeAdminConfirm();
        });
        document.addEventListener("keydown", (event) => {
            if (event.key === "Escape" && adminConfirm.classList.contains("open")) closeAdminConfirm();
        });
    </script>
</body>
</html>
