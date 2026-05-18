<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ../../index.php'); exit;
}

$pageTitle  = 'Register — FoodBlog';
$flashError = $_SESSION['flash_error'] ?? null;
$errors     = $_SESSION['form_errors'] ?? [];
$old        = $_SESSION['form_data']   ?? [];
unset($_SESSION['flash_error'], $_SESSION['form_errors'], $_SESSION['form_data']);

$depth = 2; $root = str_repeat('../', $depth);
require_once __DIR__ . '/../../view/partials/header_plain.php';
?>

<div class="container" style="max-width:520px; margin-top:3rem; margin-bottom:3rem;">

    <?php if ($flashError): ?>
        <div class="flash error"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="flash error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:.5rem; padding-left:1.2rem;">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="font-size:3rem; line-height:1;">🍽</div>
            <h1 style="font-size:1.8rem; color:#1a1a2e; margin-top:.6rem;">Create Account</h1>
            <p style="color:#888; font-size:.9rem; margin-top:.3rem;">Join FoodBlog today</p>
        </div>

        <form id="registerForm" method="POST" action="../../controllers/authController.php" novalidate>
            <input type="hidden" name="action" value="register">

            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       placeholder="Your full name">
                <div class="field-error" id="nameErr"></div>
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       placeholder="you@example.com">
                <div class="field-error" id="emailErr"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password * <span style="color:#aaa; font-size:.8rem;">(min 8 chars)</span></label>
                    <input type="password" id="password" name="password" placeholder="••••••••">
                    <div class="field-error" id="passErr"></div>
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password *</label>
                    <input type="password" id="confirm" name="confirm" placeholder="••••••••">
                    <div class="field-error" id="confirmErr"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="role">Account Type *</label>
                <select id="role" name="role" onchange="toggleAccessCode(this.value)">
                    <option value="member" <?= ($old['role'] ?? '') === 'member' ? 'selected' : '' ?>>Member</option>
                    <option value="admin"  <?= ($old['role'] ?? '') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                </select>
                <div class="field-error" id="roleErr"></div>
            </div>

            <!-- Admin Access Code — shown only when Admin is selected -->
            <div class="form-group" id="accessCodeGroup"
                 style="display:<?= ($old['role'] ?? '') === 'admin' ? 'block' : 'none' ?>;">
                <label for="admin_access_code">
                    Admin Access Code *
                    <span style="color:#888; font-size:.8rem;">(required for admin accounts)</span>
                </label>
                <input type="password" id="admin_access_code" name="admin_access_code"
                       placeholder="Enter the admin access code" autocomplete="off">
                <div class="field-error" id="codeErr"></div>
                <p style="color:#888; font-size:.8rem; margin-top:.4rem;">
                    🔒 Admin registration is restricted. Contact the system administrator for the access code.
                </p>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%; padding:.8rem; font-size:1rem; border-radius:8px; margin-top:.5rem;">
                🚀 Create Account
            </button>
        </form>

        <div style="text-align:center; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid #f0f0f0;">
            <p style="color:#888; font-size:.88rem;">
                Already have an account?
                <a href="login.php" style="color:#e94560; font-weight:600; text-decoration:none;">Log in here</a>
            </p>
        </div>
    </div>
</div>

<script>
function toggleAccessCode(role) {
    const group = document.getElementById('accessCodeGroup');
    const input = document.getElementById('admin_access_code');
    if (role === 'admin') {
        group.style.display = 'block';
    } else {
        group.style.display = 'none';
        input.value = '';
        document.getElementById('codeErr').textContent = '';
    }
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const pass    = document.getElementById('password').value;
    const confirm = document.getElementById('confirm').value;
    const role    = document.getElementById('role').value;
    const code    = document.getElementById('admin_access_code').value.trim();

    clearErr('nameErr'); clearErr('emailErr'); clearErr('passErr');
    clearErr('confirmErr'); clearErr('codeErr');

    if (name === '')           showErr('nameErr',    'Full name is required.');
    else if (name.length < 2)  showErr('nameErr',    'Name must be at least 2 characters.');

    if (email === '')          showErr('emailErr',   'Email is required.');
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email))
                               showErr('emailErr',   'Enter a valid email address.');

    if (pass === '')           showErr('passErr',    'Password is required.');
    else if (pass.length < 8)  showErr('passErr',    'Password must be at least 8 characters.');

    if (confirm === '')        showErr('confirmErr', 'Please confirm your password.');
    else if (pass !== confirm) showErr('confirmErr', 'Passwords do not match.');

    if (role === 'admin' && code === '') {
        showErr('codeErr', 'Admin access code is required.');
    }

    if (!valid) e.preventDefault();
});

// Live AJAX email check
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value.trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
    let xhttp = new XMLHttpRequest();
    xhttp.open('post', '../../controllers/deleteItem.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.send('email=' + encodeURIComponent(email));
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let result = JSON.parse(this.responseText);
            const err = document.getElementById('emailErr');
            if (!result.available) {
                err.textContent = result.message;
            } else {
                err.textContent = '';
            }
        }
    };
});

// Clear errors on input
['name','email','password','confirm','admin_access_code'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', () => {
        const map = {
            name: 'nameErr', email: 'emailErr', password: 'passErr',
            confirm: 'confirmErr', admin_access_code: 'codeErr'
        };
        document.getElementById(map[id]).textContent = '';
    });
});
</script>

</body>
</html>
