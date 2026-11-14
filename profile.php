<?php
declare(strict_types=1);
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();
$userId = (int) $_SESSION['user_id'];
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? 'profile');
    if ($action === 'password') {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $passwordStatement = $pdo->prepare('SELECT password FROM users WHERE id_user = ?');
        $passwordStatement->execute([$userId]);
        $storedPassword = (string) ($passwordStatement->fetchColumn() ?: '');
        if (!password_verify($currentPassword, $storedPassword)) {
            $error = 'Password saat ini tidak sesuai.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'Password baru minimal 8 karakter.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Konfirmasi password tidak sama.';
        } else {
            $statement = $pdo->prepare('UPDATE users SET password = ? WHERE id_user = ?');
            $statement->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
            $message = 'Password berhasil diperbarui.';
        }
    } else {
    $name = trim((string) ($_POST['name'] ?? ''));
    $currentPicture = '';
    $pictureStatement = $pdo->prepare('SELECT profile_picture FROM users WHERE id_user = ?');
    $pictureStatement->execute([$userId]);
    $currentPicture = (string) ($pictureStatement->fetchColumn() ?: '');
    $newPicture = $currentPicture;
    $upload = $_FILES['profile_picture'] ?? null;
    if ($upload !== null && $upload['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $fileType = $upload['error'] === UPLOAD_ERR_OK ? (new finfo(FILEINFO_MIME_TYPE))->file((string) $upload['tmp_name']) : '';
        if ($upload['error'] !== UPLOAD_ERR_OK || !isset($allowedTypes[$fileType])) {
            $error = 'Gunakan foto JPG, PNG, atau WEBP yang valid.';
        } elseif ((int) $upload['size'] > 3 * 1024 * 1024) {
            $error = 'Ukuran foto maksimal 3 MB.';
        } else {
            $uploadDirectory = __DIR__ . '/assets/uploads/profile';
            if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
                $error = 'Folder upload foto tidak dapat dibuat.';
            } else {
                $fileName = bin2hex(random_bytes(16)) . '.' . $allowedTypes[$fileType];
                $targetPath = $uploadDirectory . '/' . $fileName;
                if (!move_uploaded_file((string) $upload['tmp_name'], $targetPath)) {
                    $error = 'Foto profile gagal disimpan.';
                } else {
                    $newPicture = 'assets/uploads/profile/' . $fileName;
                }
            }
        }
    }
    if ($error === '' && $name === '') {
        $error = 'Nama tidak boleh kosong.';
    } elseif ($error === '') {
        $statement = $pdo->prepare('UPDATE users SET nama = ?, profile_picture = ? WHERE id_user = ?');
        $statement->execute([$name, $newPicture !== '' ? $newPicture : null, $userId]);
        if ($newPicture !== $currentPicture && $currentPicture !== '' && str_starts_with($currentPicture, 'assets/uploads/profile/')) {
            $oldPath = __DIR__ . '/' . $currentPicture;
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }
        $_SESSION['user_name'] = $name;
        $message = 'Profile berhasil diperbarui.';
    }
    }
}
$statement = $pdo->prepare('SELECT nama,email,profile_picture,created_at FROM users WHERE id_user=?');
$statement->execute([$userId]);
$user = $statement->fetch();
$pageTitle = 'Profile';
$activePage = 'profile';
require __DIR__ . '/includes/app_header.php';
?>
<style>
    .profile-panel { width:100%; }
    .profile-photo-block { display:flex; align-items:center; gap:18px; margin-bottom:22px; }
    .profile-photo { width:88px; height:88px; display:grid; place-items:center; border-radius:50%; color:var(--deep-purple); background:var(--lavender); font-family:"Playfair Display",serif; font-size:32px; object-fit:cover; }
    .profile-photo-preview { display:none; }
    .profile-photo-preview.visible { display:block; }
    .profile-photo-copy { display:grid; gap:7px; }
    .profile-photo-copy strong { font-size:15px; }
    .profile-photo-copy span { color:var(--muted); font-size:12px; }
    .profile-photo-error { color:#a45573 !important; }
    .crop-modal-backdrop { position:fixed; z-index:90; inset:0; display:grid; padding:18px; place-items:center; background:rgba(48,37,65,.42); opacity:0; pointer-events:none; transition:opacity .25s ease; }
    .crop-modal-backdrop.open { opacity:1; pointer-events:auto; }
    .crop-modal { position:relative; width:min(100%,520px); padding:26px; border-radius:22px; background:var(--white); box-shadow:0 25px 70px rgba(48,37,65,.25); }
    .crop-modal h2 { margin-bottom:16px; font-size:25px; }
    .crop-close { position:absolute; top:12px; right:12px; }
    .crop-canvas-wrap { display:grid; width:min(100%,320px); aspect-ratio:1; margin:0 auto 20px; place-items:center; overflow:hidden; border-radius:50%; background:var(--lavender); box-shadow:0 0 0 5px var(--lavender); }
    #crop-canvas { display:block; width:100%; height:100%; }
    .crop-controls { display:grid; gap:12px; margin-bottom:20px; }
    .crop-controls label { display:grid; grid-template-columns:90px 1fr; align-items:center; gap:10px; color:var(--muted); font-size:12px; font-weight:700; }
    .crop-controls input { accent-color:var(--deep-purple); }
    .crop-actions { display:flex; justify-content:flex-end; gap:10px; }
    .crop-secondary { min-height:45px; padding:0 17px; border:0; border-radius:12px; color:var(--deep-purple); background:var(--lavender); cursor:pointer; font:inherit; font-weight:700; }
    .profile-file { display:inline-flex; width:max-content; min-height:38px; align-items:center; padding:0 15px; border-radius:10px; color:var(--white); background:var(--deep-purple); cursor:pointer; font-size:12px; font-weight:700; transition:transform .25s ease, box-shadow .25s ease, background .25s ease; }
    .profile-file:hover, .profile-crop-button:hover { background:var(--purple); box-shadow:0 8px 18px rgba(112,83,156,.2); transform:translateY(-2px); }
    .profile-file input { display:none; }
    .profile-crop-button { display:inline-flex; width:max-content; min-height:38px; align-items:center; padding:0 15px; border:0; border-radius:10px; color:var(--deep-purple); background:var(--lavender); cursor:pointer; font:inherit; font-size:12px; font-weight:700; transition:transform .25s ease, box-shadow .25s ease, background .25s ease; }
    .profile-crop-button[hidden] { display:none; }
    .profile-photo-actions { display:flex; flex-wrap:wrap; align-items:center; gap:9px; }
    .profile-file-name { max-width:280px; overflow:hidden; color:var(--muted) !important; text-overflow:ellipsis; white-space:nowrap; }
    .profile-form { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:16px; max-width:none; }
    .profile-form .profile-member { align-self:center; margin:0; }
    .profile-form .button { justify-self:start; }
    .password-panel { margin-top:20px; }
    .password-form { display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); max-width:none; align-items:end; }
    .password-panel .subcopy { margin-top:7px; }
    .password-field { position:relative; }
    .password-field input { padding-right:42px; }
    .password-toggle { position:absolute; right:10px; bottom:10px; display:grid; width:30px; height:30px; place-items:center; border:0; border-radius:8px; color:var(--muted); background:transparent; cursor:pointer; }
    .password-toggle:hover { color:var(--deep-purple); background:var(--lavender); }
    .password-toggle svg { width:17px; height:17px; }
    .password-form .button { grid-column:1 / -1; justify-self:start; }
    @media (max-width:620px) { .profile-form { grid-template-columns:1fr; } }
</style>
<main class="content">
    <p class="eyebrow">Your account</p>
    <h1>Your <span>profile.</span></h1>
    <p class="subcopy">Keep your personal details up to date.</p>
    <section class="panel profile-panel">
        <div class="profile-photo-block">
            <?php if (!empty($user['profile_picture'])): ?><img class="profile-photo profile-photo-current" src="<?php echo e((string) $user['profile_picture']); ?>" alt="Current profile picture"><?php else: ?><span class="profile-photo profile-photo-current"><?php echo e(strtoupper(substr((string) $user['nama'], 0, 1))); ?></span><?php endif; ?>
            <img class="profile-photo profile-photo-preview" id="profile-photo-preview" alt="Selected profile picture preview">
            <div class="profile-photo-copy"><strong>Profile picture</strong><span>JPG, PNG, or WEBP · maximum 3 MB</span><span class="profile-photo-error" id="profile-photo-error" role="status"></span><div class="profile-photo-actions"><label class="profile-file"><i data-feather="upload"></i>&nbsp; Choose a new photo<input id="profile-picture-input" type="file" name="profile_picture" accept="image/jpeg,image/png,image/webp" form="profile-form"></label><button class="profile-crop-button" id="open-crop" type="button" hidden><i data-feather="crop"></i>&nbsp; Adjust crop</button></div><span class="profile-file-name" id="profile-file-name">No new photo selected</span></div>
        </div>
        <form class="form-grid profile-form" id="profile-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="profile">
            <label>Full name<input name="name" value="<?php echo e((string) $user['nama']); ?>" required maxlength="100"></label>
            <label>Email address<input value="<?php echo e((string) $user['email']); ?>" disabled></label>
            <p class="subcopy profile-member">Member since <?php echo e((string) $user['created_at']); ?></p>
            <button class="button" type="submit">Save changes <i data-feather="check"></i></button>
        </form>
    </section>
    <section class="panel password-panel">
        <h2>Change password</h2>
        <p class="subcopy">Use a password you do not reuse on other websites.</p>
        <form class="form-grid password-form" method="post">
            <input type="hidden" name="action" value="password">
            <label class="password-field">Current password<input name="current_password" type="password" required autocomplete="current-password"><button class="password-toggle" type="button" data-password-target="current_password" aria-label="Show current password"><i data-feather="eye"></i></button></label>
            <label class="password-field">New password<input name="new_password" type="password" required minlength="8" autocomplete="new-password"><button class="password-toggle" type="button" data-password-target="new_password" aria-label="Show new password"><i data-feather="eye"></i></button></label>
            <label class="password-field">Confirm new password<input name="confirm_password" type="password" required minlength="8" autocomplete="new-password"><button class="password-toggle" type="button" data-password-target="confirm_password" aria-label="Show password confirmation"><i data-feather="eye"></i></button></label>
            <button class="button" type="submit">Update password <i data-feather="lock"></i></button>
        </form>
    </section>
</main>
<div class="crop-modal-backdrop" id="crop-modal" role="dialog" aria-modal="true" aria-labelledby="crop-title">
    <section class="crop-modal">
        <button class="menu-toggle crop-close" id="close-crop" type="button" aria-label="Close crop editor"><i data-feather="x"></i></button>
        <h2 id="crop-title">Adjust your photo</h2>
        <div class="crop-canvas-wrap"><canvas id="crop-canvas" width="320" height="320"></canvas></div>
        <div class="crop-controls">
            <label>Zoom<input id="crop-zoom" type="range" min="1" max="3" step="0.01" value="1"></label>
            <label>Horizontal<input id="crop-x" type="range" min="-1" max="1" step="0.01" value="0"></label>
            <label>Vertical<input id="crop-y" type="range" min="-1" max="1" step="0.01" value="0"></label>
        </div>
        <div class="crop-actions"><button class="crop-secondary" id="cancel-crop" type="button">Cancel</button><button class="button" id="apply-crop" type="button">Use this crop <i data-feather="check"></i></button></div>
    </section>
</div>
<?php require __DIR__.'/includes/app_footer.php'; ?>
<script>
    const profilePictureInput = document.getElementById('profile-picture-input');
    const profilePhotoPreview = document.getElementById('profile-photo-preview');
    const currentProfilePhoto = document.querySelector('.profile-photo-current');
    const profilePhotoError = document.getElementById('profile-photo-error');
    const profileFileName = document.getElementById('profile-file-name');
    const cropModal = document.getElementById('crop-modal');
    const cropCanvas = document.getElementById('crop-canvas');
    const cropContext = cropCanvas.getContext('2d');
    const cropImage = new Image();
    const cropZoom = document.getElementById('crop-zoom');
    const cropX = document.getElementById('crop-x');
    const cropY = document.getElementById('crop-y');
    let selectedFile = null;
    let selectedObjectUrl = null;

    const closeCrop = () => {
        cropModal.classList.remove('open');
        document.body.classList.remove('dialog-open');
    };
    const drawCrop = () => {
        if (!cropImage.naturalWidth) return;
        const canvasSize = cropCanvas.width;
        const scale = Math.max(canvasSize / cropImage.naturalWidth, canvasSize / cropImage.naturalHeight) * Number(cropZoom.value);
        const drawnWidth = cropImage.naturalWidth * scale;
        const drawnHeight = cropImage.naturalHeight * scale;
        const maxX = Math.max(0, drawnWidth - canvasSize);
        const maxY = Math.max(0, drawnHeight - canvasSize);
        const offsetX = (canvasSize - drawnWidth) / 2 + Number(cropX.value) * maxX / 2;
        const offsetY = (canvasSize - drawnHeight) / 2 + Number(cropY.value) * maxY / 2;
        cropContext.clearRect(0, 0, canvasSize, canvasSize);
        cropContext.drawImage(cropImage, offsetX, offsetY, drawnWidth, drawnHeight);
    };
    const openCrop = () => {
        if (!selectedFile) return;
        cropZoom.value = '1';
        cropX.value = '0';
        cropY.value = '0';
        cropImage.onload = () => { drawCrop(); cropModal.classList.add('open'); document.body.classList.add('dialog-open'); };
        cropImage.src = selectedObjectUrl;
    };

    profilePictureInput.addEventListener('change', () => {
        const [file] = profilePictureInput.files;
        profilePhotoError.textContent = '';
        if (!file) {
            selectedFile = null;
            document.getElementById('open-crop').hidden = true;
            profileFileName.textContent = 'No new photo selected';
            currentProfilePhoto.style.display = '';
            profilePhotoPreview.removeAttribute('src');
            profilePhotoPreview.classList.remove('visible');
            return;
        }

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 3 * 1024 * 1024) {
            profilePictureInput.value = '';
            selectedFile = null;
            document.getElementById('open-crop').hidden = true;
            currentProfilePhoto.style.display = '';
            profilePhotoPreview.removeAttribute('src');
            profilePhotoPreview.classList.remove('visible');
            profilePhotoError.textContent = 'Pilih foto JPG, PNG, atau WEBP maksimal 3 MB.';
            profileFileName.textContent = 'No new photo selected';
            return;
        }

        selectedFile = file;
        profileFileName.textContent = file.name;
        if (selectedObjectUrl) URL.revokeObjectURL(selectedObjectUrl);
        selectedObjectUrl = URL.createObjectURL(file);
        profilePhotoPreview.src = selectedObjectUrl;
        profilePhotoPreview.classList.add('visible');
        currentProfilePhoto.style.display = 'none';
        document.getElementById('open-crop').hidden = false;
    });
    [cropZoom, cropX, cropY].forEach((control) => control.addEventListener('input', drawCrop));
    document.getElementById('open-crop').addEventListener('click', openCrop);
    document.getElementById('close-crop').addEventListener('click', closeCrop);
    document.getElementById('cancel-crop').addEventListener('click', closeCrop);
    cropModal.addEventListener('click', (event) => { if (event.target === cropModal) closeCrop(); });
    document.getElementById('apply-crop').addEventListener('click', () => {
        cropCanvas.toBlob((blob) => {
            if (!blob) return;
            const croppedFile = new File([blob], selectedFile.name.replace(/\.[^.]+$/, '') + '-cropped.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(croppedFile);
            profilePictureInput.files = dataTransfer.files;
            selectedFile = croppedFile;
            profilePhotoPreview.src = cropCanvas.toDataURL('image/jpeg', .9);
            closeCrop();
        }, 'image/jpeg', .9);
    });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && cropModal.classList.contains('open')) closeCrop(); });
    document.querySelectorAll('.password-toggle').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const input = document.querySelector(`[name="${toggle.dataset.passwordTarget}"]`);
            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            toggle.setAttribute('aria-label', `${visible ? 'Show' : 'Hide'} ${toggle.dataset.passwordTarget.replaceAll('_', ' ')}`);
            toggle.innerHTML = `<i data-feather="${visible ? 'eye' : 'eye-off'}"></i>`;
            feather.replace();
        });
    });
</script>
