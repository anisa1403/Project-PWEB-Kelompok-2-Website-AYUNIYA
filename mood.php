<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = (int) $_SESSION['user_id'];
$message = '';
$error = '';
$moods = [
    'happy' => ['label' => 'Happy', 'emoji' => '😊', 'color' => 'yellow'],
    'good' => ['label' => 'Good', 'emoji' => '🙂', 'color' => 'green'],
    'okay' => ['label' => 'Okay', 'emoji' => '😐', 'color' => 'lavender'],
    'sad' => ['label' => 'Sad', 'emoji' => '😢', 'color' => 'blue'],
    'stressed' => ['label' => 'Stressed', 'emoji' => '😫', 'color' => 'pink'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'save');
    $moodId = (int) ($_POST['mood_id'] ?? 0);
    $mood = (string) ($_POST['mood'] ?? '');
    $note = trim((string) ($_POST['note'] ?? ''));

    if ($action === 'delete' && $moodId > 0) {
        $statement = $pdo->prepare('DELETE FROM moods WHERE id_mood = ? AND user_id = ?');
        $statement->execute([$moodId, $userId]);
        $message = 'Mood berhasil dihapus.';
    } elseif ($action === 'edit' && $moodId > 0) {
        if (!array_key_exists($mood, $moods)) {
            $error = 'Pilih mood terlebih dahulu.';
        } else {
            $statement = $pdo->prepare('UPDATE moods SET mood = ?, note = ? WHERE id_mood = ? AND user_id = ?');
            $statement->execute([$mood, $note, $moodId, $userId]);
            $message = 'Mood berhasil diperbarui.';
        }
    } elseif (!array_key_exists($mood, $moods)) {
        $error = 'Pilih mood terlebih dahulu.';
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO moods (user_id, mood, note, mood_date) VALUES (?, ?, ?, CURDATE())
             ON DUPLICATE KEY UPDATE mood = VALUES(mood), note = VALUES(note)'
        );
        $statement->execute([$userId, $mood, $note]);
        $message = 'Mood hari ini berhasil disimpan.';
    }
}

$todayStatement = $pdo->prepare('SELECT mood FROM moods WHERE user_id = ? AND mood_date = CURDATE() LIMIT 1');
$todayStatement->execute([$userId]);
$todayMood = (string) ($todayStatement->fetchColumn() ?: '');

$historyStatement = $pdo->prepare(
    'SELECT id_mood, mood, note, mood_date FROM moods WHERE user_id = ? ORDER BY mood_date DESC LIMIT 10'
);
$historyStatement->execute([$userId]);
$history = $historyStatement->fetchAll();

$pageTitle = 'Mood Tracker';
$activePage = 'mood';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .mood-form-layout { display:grid; grid-template-columns:minmax(0, 1.05fr) minmax(280px, .95fr); gap:28px; align-items:start; }
    .mood-form-side { min-width:0; }
    .mood-form-side + .mood-form-side { padding-left:28px; border-left:1px solid var(--line); }
    .mood-choice-grid { display:grid; grid-template-columns:repeat(5, minmax(0, 1fr)); gap:12px; margin:24px 0; }
    .mood-form { max-width:none; }
    .mood-choice { position:relative; cursor:pointer; }
    .mood-choice input { position:absolute; width:1px; height:1px; opacity:0; }
    .mood-choice-card { display:flex; min-height:132px; flex-direction:column; align-items:center; justify-content:center; gap:9px; padding:16px 8px; border:1px solid var(--line); border-radius:17px; color:var(--muted); background:var(--bg); text-align:center; transition:transform .28s ease, border-color .28s ease, box-shadow .28s ease, background .28s ease; }
    .mood-emoji { display:block; font-size:38px; line-height:1; filter:saturate(.9); transition:transform .35s cubic-bezier(.34,1.56,.64,1), filter .28s ease; }
    .mood-choice-card small { font-size:13px; font-weight:700; }
    .mood-choice:hover .mood-choice-card, .mood-choice input:focus-visible + .mood-choice-card { border-color:var(--purple); box-shadow:0 12px 25px rgba(112,83,156,.13); transform:translateY(-5px); }
    .mood-choice:hover .mood-emoji { filter:saturate(1.25); transform:translateY(-6px) rotate(-8deg) scale(1.16); }
    .mood-choice input:checked + .mood-choice-card { border-color:var(--deep-purple); color:var(--deep-purple); background:var(--lavender); box-shadow:0 10px 24px rgba(112,83,156,.16); transform:translateY(-3px); }
    .mood-choice input:checked + .mood-choice-card .mood-emoji { filter:saturate(1.3); transform:scale(1.12); }
    .mood-choice:nth-child(1) input:checked + .mood-choice-card { background:#fff4d6; border-color:#e7c56e; }
    .mood-choice:nth-child(2) input:checked + .mood-choice-card { background:var(--green); border-color:#a9cdb0; }
    .mood-choice:nth-child(4) input:checked + .mood-choice-card { background:#e5f0fa; border-color:#a9c9e6; }
    .mood-choice:nth-child(5) input:checked + .mood-choice-card { background:var(--pink); border-color:#e0aac4; }
    .list-item { position:relative; padding-right:190px; }
    .history-actions { position:absolute; top:50%; right:18px; display:flex; flex-wrap:wrap; gap:8px; justify-content:flex-end; transform:translateY(-50%); }
    .history-action { display:inline-flex; min-height:34px; align-items:center; gap:6px; padding:0 11px; border:1px solid var(--line); border-radius:9px; color:var(--deep-purple); background:var(--white); cursor:pointer; font:inherit; font-size:12px; font-weight:700; transition:background .2s ease, transform .2s ease; }
    .history-action:hover { background:var(--lavender); transform:translateY(-2px); }
    .history-action.delete { color:#a45573; }
    .mood-modal-backdrop { position:fixed; z-index:60; inset:0; display:grid; padding:18px; place-items:center; background:rgba(48,37,65,.4); opacity:0; pointer-events:none; transition:opacity .25s ease; }
    .mood-modal-backdrop.open { opacity:1; pointer-events:auto; }
    .mood-modal { position:relative; width:min(100%,520px); padding:28px; border:1px solid rgba(255,255,255,.8); border-radius:22px; background:var(--white); box-shadow:0 25px 70px rgba(48,37,65,.25); transform:translateY(12px) scale(.98); transition:transform .25s ease; }
    .mood-modal-backdrop.open .mood-modal { transform:translateY(0) scale(1); }
    .mood-modal h2 { margin-bottom:20px; font-size:27px; }
    .mood-modal-close { position:absolute; top:14px; right:14px; display:grid; width:34px; height:34px; place-items:center; border:0; border-radius:50%; color:var(--muted); background:var(--lavender); cursor:pointer; }
    .mood-modal-form { display:grid; gap:16px; }
    .mood-modal-form label { display:grid; gap:7px; font-size:13px; font-weight:700; }
    .mood-modal-form select,.mood-modal-form textarea { width:100%; padding:11px 13px; border:1px solid var(--line); border-radius:11px; color:var(--ink); background:var(--bg); font:inherit; }
    .mood-modal-form textarea { min-height:110px; resize:vertical; }
    body.modal-open { overflow:hidden; }
    @media (max-width:760px) { .mood-form-layout { grid-template-columns:1fr; gap:22px; } .mood-form-side + .mood-form-side { padding-left:0; border-top:1px solid var(--line); border-left:0; padding-top:22px; } .mood-choice-grid { grid-template-columns:repeat(3, minmax(0, 1fr)); } .list-item { padding-right:18px; padding-bottom:70px; } .history-actions { top:auto; right:18px; bottom:17px; transform:none; } }
    @media (max-width:480px) { .mood-choice-grid { grid-template-columns:repeat(2, minmax(0, 1fr)); } .mood-choice-card { min-height:118px; } }
    @media (prefers-reduced-motion:reduce) { .mood-choice-card,.mood-emoji { transition:none; } }
</style>
<main class="content">
    <p class="eyebrow">Awareness</p>
    <h1>How are you feeling <span>today?</span></h1>
    <p class="subcopy">A small check-in helps you understand what you need.</p>
    <section class="panel">
        <form class="form-grid mood-form" method="post">
            <input type="hidden" name="action" value="save">
            <div class="mood-form-layout">
                <fieldset class="mood-form-side" style="border:0;margin:0;padding:0">
                    <legend style="font-size:13px;font-weight:700">Choose your mood</legend>
                    <div class="mood-choice-grid">
                        <?php foreach ($moods as $key => $mood): ?>
                            <label class="mood-choice">
                                <input type="radio" name="mood" value="<?php echo e($key); ?>" <?php echo $todayMood === $key ? 'checked' : ''; ?> required>
                                <span class="mood-choice-card"><span class="mood-emoji" aria-hidden="true"><?php echo $mood['emoji']; ?></span><small><?php echo e($mood['label']); ?></small></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <div class="mood-form-side">
                    <label>Write a little<textarea name="note" placeholder="What made you feel this way?"></textarea></label>
                    <button class="button" type="submit">Save my mood <i data-feather="arrow-right"></i></button>
                </div>
            </div>
        </form>
    </section>
    <section class="panel" style="margin-top:20px">
        <h2>Your mood history</h2>
        <div class="list">
            <?php foreach ($history as $entry): ?>
                <article class="list-item">
                    <h3><?php echo e($moods[$entry['mood']]['emoji'] . ' ' . $moods[$entry['mood']]['label']); ?> · <?php echo e((string) $entry['mood_date']); ?></h3>
                    <p><?php echo e((string) ($entry['note'] ?: 'No note added.')); ?></p>
                    <div class="history-actions">
                        <button class="history-action edit-mood" type="button" data-mood-id="<?php echo (int) $entry['id_mood']; ?>" data-mood="<?php echo e((string) $entry['mood']); ?>" data-note="<?php echo e((string) $entry['note']); ?>"><i data-feather="edit-3"></i> Edit</button>
                        <form method="post" data-confirm="Mood ini akan dihapus dari riwayatmu." data-confirm-title="Hapus mood?" data-confirm-action="Hapus">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="mood_id" value="<?php echo (int) $entry['id_mood']; ?>">
                            <button class="history-action delete" type="submit"><i data-feather="trash-2"></i> Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<div class="mood-modal-backdrop" id="edit-mood-modal" role="dialog" aria-modal="true" aria-labelledby="edit-mood-title">
    <section class="mood-modal">
        <button class="mood-modal-close" id="close-edit-mood" type="button" aria-label="Close edit dialog"><i data-feather="x"></i></button>
        <h2 id="edit-mood-title">Edit your mood</h2>
        <form class="mood-modal-form" method="post">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="mood_id" id="edit-mood-id">
            <label>Mood
                <select name="mood" id="edit-mood-value" required>
                    <?php foreach ($moods as $key => $mood): ?><option value="<?php echo e($key); ?>"><?php echo e($mood['emoji'] . ' ' . $mood['label']); ?></option><?php endforeach; ?>
                </select>
            </label>
            <label>Write a little<textarea name="note" id="edit-mood-note"></textarea></label>
            <button class="button" type="submit">Update mood <i data-feather="check"></i></button>
        </form>
    </section>
</div>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
<script>
    const editMoodModal = document.getElementById("edit-mood-modal");
    const closeEditMood = () => {
        editMoodModal.classList.remove("open");
        document.body.classList.remove("modal-open");
    };
    document.querySelectorAll(".edit-mood").forEach((button) => {
        button.addEventListener("click", () => {
            document.getElementById("edit-mood-id").value = button.dataset.moodId;
            document.getElementById("edit-mood-value").value = button.dataset.mood;
            document.getElementById("edit-mood-note").value = button.dataset.note;
            editMoodModal.classList.add("open");
            document.body.classList.add("modal-open");
        });
    });
    document.getElementById("close-edit-mood").addEventListener("click", closeEditMood);
    editMoodModal.addEventListener("click", (event) => {
        if (event.target === editMoodModal) closeEditMood();
    });
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") closeEditMood();
    });
</script>
