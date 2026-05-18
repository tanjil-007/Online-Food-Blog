<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_error'] = 'Please log in to view your profile.';
    header('Location: ../auth/login.php'); exit;
}

require_once __DIR__ . '/../../model/userModel.php';

$user       = getUserById((int)$_SESSION['user_id']);
$pageTitle  = 'My Profile — FoodBlog';
$flash      = $_SESSION['flash']          ?? null;
$flashError = $_SESSION['flash_error']    ?? null;
$errors     = $_SESSION['form_errors']    ?? [];
$errorsPw   = $_SESSION['form_errors_pw'] ?? [];
unset($_SESSION['flash'], $_SESSION['flash_error'], $_SESSION['form_errors'], $_SESSION['form_errors_pw']);

require_once __DIR__ . '/../../view/partials/header.php';
?>

<div class="container" style="max-width:680px;">

    <?php if ($flash): ?>
        <div class="flash success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
        <div class="flash error"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>

    <div class="page-header">
        <h1>👤 My Profile</h1>
        <span style="background:#e94560; color:#fff; padding:.3rem .8rem; border-radius:20px; font-size:.82rem; font-weight:700; text-transform:uppercase;">
            <?= htmlspecialchars($user['role']) ?>
        </span>
    </div>

    <!-- ── Profile Info Card ── -->
    <div class="card">
        <div style="display:flex; align-items:center; gap:1.5rem; margin-bottom:1.8rem; flex-wrap:wrap;">
            <?php if ($user['profile_picture']): ?>
                <img src="../../<?= htmlspecialchars($user['profile_picture']) ?>"
                     alt="Profile Picture" class="avatar">
            <?php else: ?>
                <div class="avatar" style="background:#e94560; display:flex; align-items:center; justify-content:center; font-size:2rem; color:#fff; flex-shrink:0;">
                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <h2 style="font-size:1.4rem; color:#1a1a2e;"><?= htmlspecialchars($user['name']) ?></h2>
                <p style="color:#888; font-size:.9rem;"><?= htmlspecialchars($user['email']) ?></p>
                <p style="color:#aaa; font-size:.82rem; margin-top:.3rem;">
                    Member since <?= date('M d, Y', strtotime($user['created_at'])) ?>
                </p>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="flash error">
                <strong>Fix these errors:</strong>
                <ul style="margin-top:.5rem; padding-left:1.2rem;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h3 style="font-size:1rem; color:#555; margin-bottom:1rem; padding-bottom:.5rem; border-bottom:1px solid #f0f0f0;">
            ✏️ Update Info
        </h3>

        <form id="profileForm" method="POST" enctype="multipart/form-data"
              action="../../controllers/profileController.php" novalidate>
            <input type="hidden" name="action" value="update_profile">

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name"
                           value="<?= htmlspecialchars($user['name']) ?>">
                    <div class="field-error" id="nameErr"></div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email"
                           value="<?= htmlspecialchars($user['email']) ?>">
                    <div class="field-error" id="emailErr"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="profile_picture">
                    Profile Picture
                    <span style="color:#aaa; font-size:.8rem;">(JPEG/PNG, max 2MB)</span>
                </label>
                <input type="file" id="profile_picture" name="profile_picture"
                       accept="image/jpeg,image/png" onchange="previewAvatar(this)">
                <div class="field-error" id="picErr"></div>
                <img id="avatarPreview" src="#" alt="Preview"
                     style="display:none; margin-top:.8rem; width:80px; height:80px; object-fit:cover; border-radius:50%; border:2px solid #e94560;">
            </div>

            <button type="submit" class="btn btn-primary">💾 Save Changes</button>
        </form>
    </div>

    <!-- ── Change Password Card ── -->
    <div class="card">
        <h3 style="font-size:1rem; color:#555; margin-bottom:1rem; padding-bottom:.5rem; border-bottom:1px solid #f0f0f0;">
            🔒 Change Password
        </h3>

        <?php if (!empty($errorsPw)): ?>
            <div class="flash error">
                <ul style="padding-left:1.2rem;">
                    <?php foreach ($errorsPw as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form id="passwordForm" method="POST" action="../../controllers/profileController.php" novalidate>
            <input type="hidden" name="action" value="change_password">

            <div class="form-group">
                <label for="current_password">Current Password *</label>
                <input type="password" id="current_password" name="current_password" placeholder="••••••••">
                <div class="field-error" id="curPassErr"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="new_password">New Password * <span style="color:#aaa; font-size:.8rem;">(min 8)</span></label>
                    <input type="password" id="new_password" name="new_password" placeholder="••••••••">
                    <div class="field-error" id="newPassErr"></div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="••••••••">
                    <div class="field-error" id="confirmPassErr"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-secondary">🔑 Change Password</button>
        </form>
    </div>
</div>

<script>
function previewAvatar(input) {
    const preview = document.getElementById('avatarPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('profileForm').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const name  = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const pic   = document.getElementById('profile_picture').files[0];

    clearErr('nameErr'); clearErr('emailErr'); clearErr('picErr');

    if (name === '')          showErr('nameErr',  'Name is required.');
    else if (name.length < 2) showErr('nameErr',  'Name must be at least 2 characters.');
    if (email === '')         showErr('emailErr', 'Email is required.');
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))
                              showErr('emailErr', 'Enter a valid email address.');
    if (pic) {
        if (!['image/jpeg','image/png'].includes(pic.type))
            showErr('picErr', 'Only JPEG and PNG allowed.');
        else if (pic.size > 2 * 1024 * 1024)
            showErr('picErr', 'Image must be under 2MB.');
    }

    if (!valid) e.preventDefault();
});

document.getElementById('passwordForm').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const cur  = document.getElementById('current_password').value;
    const np   = document.getElementById('new_password').value;
    const conf = document.getElementById('confirm_password').value;

    clearErr('curPassErr'); clearErr('newPassErr'); clearErr('confirmPassErr');

    if (cur === '')           showErr('curPassErr',     'Current password is required.');
    if (np === '')            showErr('newPassErr',     'New password is required.');
    else if (np.length < 8)   showErr('newPassErr',     'Must be at least 8 characters.');
    if (conf === '')          showErr('confirmPassErr', 'Please confirm your new password.');
    else if (np !== conf)     showErr('confirmPassErr', 'Passwords do not match.');

    if (!valid) e.preventDefault();
});

['name','email'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const map = { name:'nameErr', email:'emailErr' };
        document.getElementById(map[id]).textContent = '';
    });
});
['current_password','new_password','confirm_password'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const map = {
            current_password:'curPassErr',
            new_password:'newPassErr',
            confirm_password:'confirmPassErr'
        };
        document.getElementById(map[id]).textContent = '';
    });
});
</script>

</body>
</html>
