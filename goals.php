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
    $goalId = (int) ($_POST['goal_id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $deadline = trim((string) ($_POST['deadline'] ?? ''));
    $deadline = $deadline !== '' ? $deadline : null;

    if ($action === 'delete' && $goalId > 0) {
        $statement = $pdo->prepare('DELETE FROM goals WHERE id_goal = ? AND user_id = ?');
        $statement->execute([$goalId, $userId]);
        $message = 'Goal berhasil dihapus.';
    } elseif ($action === 'toggle' && $goalId > 0) {
        $statement = $pdo->prepare("UPDATE goals SET status = IF(status = 'completed', 'pending', 'completed') WHERE id_goal = ? AND user_id = ?");
        $statement->execute([$goalId, $userId]);
        $message = 'Status goal berhasil diperbarui.';
    } elseif (($action === 'create' || $action === 'edit') && $title === '') {
        $error = 'Judul goal wajib diisi.';
    } elseif ($action === 'edit' && $goalId > 0) {
        $statement = $pdo->prepare('UPDATE goals SET title = ?, description = ?, deadline = ? WHERE id_goal = ? AND user_id = ?');
        $statement->execute([$title, $description, $deadline, $goalId, $userId]);
        $message = 'Goal berhasil diperbarui.';
    } elseif ($action === 'create') {
        $statement = $pdo->prepare('INSERT INTO goals (user_id, title, description, deadline) VALUES (?, ?, ?, ?)');
        $statement->execute([$userId, $title, $description, $deadline]);
        $message = 'Goal berhasil ditambahkan.';
    }
}

$statement = $pdo->prepare('SELECT id_goal, title, description, deadline, status FROM goals WHERE user_id = ? ORDER BY status ASC, created_at DESC');
$statement->execute([$userId]);
$goals = $statement->fetchAll();

$pageTitle = 'Daily Goals';
$activePage = 'goals';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .crud-actions{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:8px;margin-top:14px}
    .crud-action{display:inline-flex;min-height:34px;align-items:center;gap:6px;padding:0 11px;border:1px solid var(--line);border-radius:9px;color:var(--deep-purple);background:var(--white);cursor:pointer;font:inherit;font-size:12px;font-weight:700;transition:background .2s ease,transform .2s ease}
    .crud-action:hover{background:var(--lavender);transform:translateY(-2px)}
    .crud-action.delete{color:#a45573}.crud-item{display:flex;flex-direction:column}.crud-item .crud-actions{margin-top:auto;padding-top:14px}.goal-complete h3{text-decoration:line-through;opacity:.65}.goal-status{display:inline-flex;margin-top:10px;padding:4px 9px;border-radius:20px;color:var(--deep-purple);background:var(--lavender);font-size:11px;font-weight:700}
</style>
<main class="content">
    <p class="eyebrow">Intentions</p>
    <h1>Small steps, <span>big growth.</span></h1>
    <p class="subcopy">Turn your intentions into simple goals you can celebrate.</p>
    <div class="page-grid">
    <section class="panel">
        <h2>Add a goal</h2>
        <form class="form-grid" method="post" style="margin-top:20px">
            <input type="hidden" name="action" value="create">
            <label>Goal title<input name="title" required maxlength="200" placeholder="What would you like to do?"></label>
            <label>Description<textarea name="description" placeholder="A little detail..."></textarea></label>
            <label>Deadline<input name="deadline" type="date"></label>
            <button class="button" type="submit">Save goal <i data-feather="arrow-right"></i></button>
        </form>
    </section>
    <section class="panel">
        <h2>My goals</h2>
        <div class="list">
            <?php if (!$goals): ?><p class="subcopy">Set one small intention to begin.</p><?php endif; ?>
            <?php foreach ($goals as $goal): ?>
                <article class="list-item crud-item <?php echo $goal['status'] === 'completed' ? 'goal-complete' : ''; ?>">
                    <h3><?php echo e((string) $goal['title']); ?></h3>
                    <p><?php echo e((string) ($goal['description'] ?: 'No description')); ?> · <?php echo e((string) ($goal['deadline'] ?: 'No deadline')); ?></p>
                    <span class="goal-status"><?php echo $goal['status'] === 'completed' ? 'Completed' : 'In progress'; ?></span>
                    <div class="crud-actions">
                        <form method="post">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="goal_id" value="<?php echo (int) $goal['id_goal']; ?>">
                            <button class="crud-action" type="submit"><i data-feather="check-circle"></i> <?php echo $goal['status'] === 'completed' ? 'Undo' : 'Complete'; ?></button>
                        </form>
                        <button class="crud-action edit-goal" type="button" data-id="<?php echo (int) $goal['id_goal']; ?>" data-title="<?php echo e((string) $goal['title']); ?>" data-description="<?php echo e((string) $goal['description']); ?>" data-deadline="<?php echo e((string) $goal['deadline']); ?>"><i data-feather="edit-3"></i> Edit</button>
                        <form method="post" data-confirm="Goal ini akan dihapus permanen." data-confirm-title="Hapus goal?" data-confirm-action="Hapus">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="goal_id" value="<?php echo (int) $goal['id_goal']; ?>">
                            <button class="crud-action delete" type="submit"><i data-feather="trash-2"></i> Delete</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    </div>
</main>
<div class="alert-dialog-backdrop" id="goal-modal" role="dialog" aria-modal="true" aria-labelledby="goal-modal-title">
    <section class="alert-dialog">
        <button class="menu-toggle" id="close-goal-modal" type="button" aria-label="Close edit goal"><i data-feather="x"></i></button>
        <h2 id="goal-modal-title">Edit goal</h2>
        <form class="form-grid" method="post">
            <input type="hidden" name="action" value="edit"><input type="hidden" name="goal_id" id="goal-id">
            <label>Goal title<input name="title" id="goal-title" required maxlength="200"></label>
            <label>Description<textarea name="description" id="goal-description"></textarea></label>
            <label>Deadline<input name="deadline" id="goal-deadline" type="date"></label>
            <button class="button" type="submit">Update goal <i data-feather="check"></i></button>
        </form>
    </section>
</div>
<?php require __DIR__ . '/includes/app_footer.php'; ?>
<script>
const goalModal=document.getElementById("goal-modal");
const closeGoalModal=()=>{goalModal.classList.remove("open");document.body.classList.remove("dialog-open");};
document.querySelectorAll(".edit-goal").forEach((button)=>button.addEventListener("click",()=>{
    document.getElementById("goal-id").value=button.dataset.id;
    document.getElementById("goal-title").value=button.dataset.title;
    document.getElementById("goal-description").value=button.dataset.description;
    document.getElementById("goal-deadline").value=button.dataset.deadline;
    goalModal.classList.add("open");document.body.classList.add("dialog-open");
}));
document.getElementById("close-goal-modal").addEventListener("click",closeGoalModal);
goalModal.addEventListener("click",(event)=>{if(event.target===goalModal)closeGoalModal();});
document.addEventListener("keydown",(event)=>{if(event.key==="Escape")closeGoalModal();});
</script>
