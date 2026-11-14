<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$userId = (int) $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'create');
    $journalId = (int) ($_POST['journal_id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $content = trim((string) ($_POST['content'] ?? ''));

    if ($action === 'delete' && $journalId > 0) {
        $statement = $pdo->prepare('DELETE FROM journals WHERE id_journal = ? AND user_id = ?');
        $statement->execute([$journalId, $userId]);
        $message = 'Journal berhasil dihapus.';
    } elseif (($action === 'create' || $action === 'edit') && ($title === '' || $content === '')) {
        $error = 'Judul dan isi journal wajib diisi.';
    } elseif ($action === 'edit' && $journalId > 0) {
        $statement = $pdo->prepare('UPDATE journals SET title = ?, content = ? WHERE id_journal = ? AND user_id = ?');
        $statement->execute([$title, $content, $journalId, $userId]);
        $message = 'Journal berhasil diperbarui.';
    } elseif ($action === 'create') {
        $statement = $pdo->prepare('INSERT INTO journals (user_id, title, content) VALUES (?, ?, ?)');
        $statement->execute([$userId, $title, $content]);
        $message = 'Journal berhasil disimpan.';
    }
}

$statement = $pdo->prepare('SELECT id_journal, title, content, created_at, updated_at FROM journals WHERE user_id = ? ORDER BY updated_at DESC');
$statement->execute([$userId]);
$journals = $statement->fetchAll();

$pageTitle = 'Daily Journal';
$activePage = 'journal';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .crud-actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px;margin-top:14px}
    .crud-action{display:inline-flex;min-height:34px;align-items:center;gap:6px;padding:0 11px;border:1px solid var(--line);border-radius:9px;color:var(--deep-purple);background:var(--white);cursor:pointer;font:inherit;font-size:12px;font-weight:700;transition:background .2s ease,transform .2s ease}
    .crud-action:hover{background:var(--lavender);transform:translateY(-2px)}
    .crud-action.delete{color:#a45573}
    .crud-item{display:flex;flex-direction:column}
    .crud-item .crud-actions{margin-top:auto;padding-top:14px}
</style>
<main class="content">
    <p class="eyebrow">Notes</p>
    <h1>Your thoughts, <span>your stories.</span></h1>
    <p class="subcopy">Keep a gentle record of the moments that matter.</p>
    <div class="page-grid">
    <section class="panel">
        <h2>New journal</h2>
        <form class="form-grid" method="post" style="margin-top:20px">
            <input type="hidden" name="action" value="create">
            <label>Journal title<input name="title" required maxlength="200" placeholder="What is on your mind?"></label>
            <label>Journal content<textarea name="content" required placeholder="Write freely..."></textarea></label>
            <button class="button" type="submit">Save journal <i data-feather="arrow-right"></i></button>
        </form>
    </section>
    <section class="panel">
        <h2>My journal</h2>
        <div class="list">
            <?php if (!$journals): ?><p class="subcopy">Your journal is waiting for its first page.</p><?php endif; ?>
            <?php foreach ($journals as $journal): ?>
                <article class="list-item crud-item">
                    <h3><?php echo e((string) $journal['title']); ?></h3>
                    <p><?php echo nl2br(e((string) $journal['content'])); ?></p>
                    <div class="crud-actions">
                        <button class="crud-action edit-journal" type="button" data-id="<?php echo (int) $journal['id_journal']; ?>" data-title="<?php echo e((string) $journal['title']); ?>" data-content="<?php echo e((string) $journal['content']); ?>"><i data-feather="edit-3"></i> Edit</button>
                        <form method="post" data-confirm="Journal ini akan dihapus permanen." data-confirm-title="Hapus journal?" data-confirm-action="Hapus">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="journal_id" value="<?php echo (int) $journal['id_journal']; ?>">
                            <button class="crud-action delete" type="submit"><i data-feather="trash-2"></i> Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    </div>
</main>
<div class="alert-dialog-backdrop" id="journal-modal" role="dialog" aria-modal="true" aria-labelledby="journal-modal-title">
    <section class="alert-dialog">
        <button class="menu-toggle" id="close-journal-modal" type="button" aria-label="Close edit journal"><i data-feather="x"></i></button>
        <h2 id="journal-modal-title">Edit journal</h2>
        <form class="form-grid" method="post">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="journal_id" id="journal-id">
            <label>Journal title<input name="title" id="journal-title" required maxlength="200"></label>
            <label>Journal content<textarea name="content" id="journal-content" required></textarea></label>
            <button class="button" type="submit">Update journal <i data-feather="check"></i></button>
        </form>
    </section>
</div>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
<script>
const journalModal=document.getElementById("journal-modal");
const closeJournalModal=()=>{journalModal.classList.remove("open");document.body.classList.remove("dialog-open");};
document.querySelectorAll(".edit-journal").forEach((button)=>button.addEventListener("click",()=>{
    document.getElementById("journal-id").value=button.dataset.id;
    document.getElementById("journal-title").value=button.dataset.title;
    document.getElementById("journal-content").value=button.dataset.content;
    journalModal.classList.add("open");document.body.classList.add("dialog-open");
}));
document.getElementById("close-journal-modal").addEventListener("click",closeJournalModal);
journalModal.addEventListener("click",(event)=>{if(event.target===journalModal)closeJournalModal();});
document.addEventListener("keydown",(event)=>{if(event.key==="Escape")closeJournalModal();});
</script>
