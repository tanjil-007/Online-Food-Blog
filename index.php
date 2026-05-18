<?php
session_start();

// If already logged in, redirect by role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: views/admin/dashboard.php');
    } else {
        header('Location: views/restaurant/list.php');
    }
    exit;
}

$pageTitle = 'Log In — FoodBlog';
require_once __DIR__ . '/views/partials/header_plain.php';

$flashError = $_SESSION['flash_error'] ?? null;
$flashOk    = $_SESSION['flash']       ?? null;
$savedEmail = $_SESSION['form_data']['email'] ?? '';
unset($_SESSION['flash_error'], $_SESSION['flash'], $_SESSION['form_data']);
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
            <p style="color:#888; font-size:.9rem; margin-top:.3rem;">Admin Log In</p>
        </div>

        <form id="loginForm" method="POST" action="controllers/authController.php" novalidate>
            <input type="hidden" name="action" value="login">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                       value="<?= htmlspecialchars($savedEmail) ?>">
                <div class="field-error" id="emailErr"></div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" >
                <div class="field-error" id="passErr"></div>
            </div>

            

            <button type="submit" class="btn btn-primary"
                    style="width:100%; padding:.8rem; font-size:1rem; margin-top:.5rem; border-radius:8px;">
                 Log In
            </button>
        </form>

        <div style="text-align:center; margin-top:1.5rem; padding-top:1.2rem; border-top:1px solid #f0f0f0;">
            <p style="color:#aaa; font-size:.85rem; margin-bottom:.5rem;">Not an admin?</p>
            <a href="views/restaurant/list.php" class="btn btn-secondary btn-sm">🏪 Browse Restaurants</a>
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
['email','password'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const map = {email:'emailErr', password:'passErr'};
        document.getElementById(map[id]).textContent = '';
    });
});
</script>

<?php require_once __DIR__ . '/views/partials/footer.php'; ?>
