<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: ../../index.php'); exit;
}

$pageTitle  = 'Log In — FoodBlog';
$flashError = $_SESSION['flash_error'] ?? null;
$flashOk    = $_SESSION['flash']       ?? null;
$savedEmail = $_SESSION['form_data']['email'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash'], $_SESSION['form_data']);

// Load header from view/auth/ → depth 2
$depth = 2; $root = str_repeat('../', $depth);
require_once __DIR__ . '/../../view/partials/header_plain.php';
?>

<div class="container" style="max-width:460px; margin-top:4rem; margin-bottom:4rem;">

    <?php if ($flashError): ?>
        <div class="flash error"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>
    <?php if ($flashOk): ?>
        <div class="flash success"><?= htmlspecialchars($flashOk) ?></div>
    <?php endif; ?>

    <div class="card">
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="font-size:3rem; line-height:1;">🍽</div>
            <h1 style="font-size:1.8rem; color:#1a1a2e; margin-top:.6rem;">FoodBlog</h1>
            <p style="color:#888; font-size:.9rem; margin-top:.3rem;">Sign in to your account</p>
        </div>

        <form id="loginForm" method="POST" action="../../controllers/authController.php" novalidate>
            <input type="hidden" name="action" value="login">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($savedEmail) ?>"
                       placeholder="you@example.com">
                <div class="field-error" id="emailErr"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••">
                <div class="field-error" id="passErr"></div>
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.2rem;">
                <label style="display:flex; align-items:center; gap:.4rem; font-size:.88rem; color:#555; cursor:pointer;">
                    <input type="checkbox" name="remember_me" value="1" style="width:auto;"> Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary"
                    style="width:100%; padding:.8rem; font-size:1rem; border-radius:8px;">
                🔑 Log In
            </button>
        </form>

        <div style="text-align:center; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid #f0f0f0;">
            <p style="color:#888; font-size:.88rem;">
                Don't have an account?
                <a href="register.php" style="color:#e94560; font-weight:600; text-decoration:none;">Register here</a>
            </p>
            <p style="margin-top:.6rem;">
                <a href="../../view/browse/restaurants.php" class="btn btn-secondary btn-sm">🏪 Browse as Visitor</a>
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const email = document.getElementById('email').value.trim();
    const pass  = document.getElementById('password').value;

    clearErr('emailErr'); clearErr('passErr');

    if (email === '') {
        showErr('emailErr', 'Email is required.');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('emailErr', 'Enter a valid email address.');
    }
    if (pass === '') {
        showErr('passErr', 'Password is required.');
    }

    if (!valid) e.preventDefault();
});

['email', 'password'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const map = { email: 'emailErr', password: 'passErr' };
        document.getElementById(map[id]).textContent = '';
    });
});
</script>

</body>
</html>
