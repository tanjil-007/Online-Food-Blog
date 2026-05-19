<?php
session_start();

<<<<<<< HEAD
// Reinstate session from Remember Me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/model/userModel.php';
    $remembered = getUserByRememberToken($_COOKIE['remember_token']);
    if ($remembered) {
        $_SESSION['user_id'] = $remembered['id'];
        $_SESSION['name']    = $remembered['name'];
        $_SESSION['role']    = $remembered['role'];
    }
}

// Redirect already-logged-in users
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: view/admin/dashboard.php');
    } else {
        header('Location: view/browse/restaurants.php');
=======
// If already logged in, redirect by role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
<<<<<<< admin/task2-23-51164-1
        header('Location: view/admin/dashboard.php');
    } else {
        header('Location: view/restaurant/list.php');
=======
        header('Location: views/admin/dashboard.php');
    } else {
        header('Location: views/restaurant/list.php');
>>>>>>> master
>>>>>>> 8eba48aa223268a09e7221a68507f249abc00e7e
    }
    exit;
}

<<<<<<< HEAD
$pageTitle = 'Welcome — FoodBlog';
require_once __DIR__ . '/view/partials/header_plain.php';
?>

<div class="container">
    <!-- Hero Section -->
    <div class="card" style="text-align:center; padding:3.5rem 2rem; background: linear-gradient(135deg,#1a1a2e 0%,#16213e 100%); color:#fff; border-radius:16px; margin-top:2rem;">
        <div style="font-size:4rem; line-height:1; margin-bottom:1rem;">🍽</div>
        <h1 style="font-size:2.5rem; font-weight:800; color:#e94560; margin-bottom:.8rem;">FoodBlog</h1>
        <p style="font-size:1.1rem; color:#ccc; max-width:520px; margin:0 auto 2rem; line-height:1.7;">
            Discover restaurants, explore menus, and share your food experiences. Your ultimate guide to the best dining spots.
        </p>
        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            <a href="view/auth/register.php" class="btn btn-primary" style="font-size:1rem; padding:.75rem 2rem;">🚀 Get Started</a>
            <a href="view/auth/login.php"    class="btn" style="background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.3); font-size:1rem; padding:.75rem 2rem;">🔑 Sign In</a>
        </div>
    </div>

    <!-- Feature Highlights -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:1.2rem; margin-top:2rem;">
        <div class="card" style="text-align:center; padding:2rem;">
            <div style="font-size:2.5rem; margin-bottom:.8rem;">🏪</div>
            <h3 style="color:#1a1a2e; margin-bottom:.5rem;">Browse Restaurants</h3>
            <p style="color:#888; font-size:.9rem; line-height:1.6;">Explore a wide variety of restaurants, discover their menus and backgrounds.</p>
            <a href="view/browse/restaurants.php" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Browse Now →</a>
        </div>
        <div class="card" style="text-align:center; padding:2rem;">
            <div style="font-size:2.5rem; margin-bottom:.8rem;">🍔</div>
            <h3 style="color:#1a1a2e; margin-bottom:.5rem;">Discover Food Items</h3>
            <p style="color:#888; font-size:.9rem; line-height:1.6;">Browse detailed menu items with descriptions, prices, and photos.</p>
            <a href="view/browse/restaurants.php" class="btn btn-secondary btn-sm" style="margin-top:1rem;">Explore Menu →</a>
        </div>
        <div class="card" style="text-align:center; padding:2rem;">
            <div style="font-size:2.5rem; margin-bottom:.8rem;">⭐</div>
            <h3 style="color:#1a1a2e; margin-bottom:.5rem;">Post Reviews</h3>
            <p style="color:#888; font-size:.9rem; line-height:1.6;">Register as a member to post reviews on food items and restaurants.</p>
            <a href="view/auth/register.php" class="btn btn-primary btn-sm" style="margin-top:1rem;">Join Now →</a>
        </div>
    </div>

    <!-- CTA Banner -->
    <div class="card" style="text-align:center; padding:2rem; margin-top:1rem; background:#fff8f0; border:1px solid #fce4ca;">
        <p style="color:#555; font-size:1rem;">
            Already have an account?
            <a href="view/auth/login.php" style="color:#e94560; font-weight:700; text-decoration:none;">Log in here</a>
            &nbsp;|&nbsp; New here?
            <a href="view/auth/register.php" style="color:#e94560; font-weight:700; text-decoration:none;">Create a free account</a>
        </p>
    </div>
</div>

</body>
</html>
=======
$pageTitle = 'Log In — FoodBlog';
<<<<<<< admin/task2-23-51164-1
require_once __DIR__ . '/view/partials/header_plain.php';
=======
require_once __DIR__ . '/views/partials/header_plain.php';
>>>>>>> master

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

<<<<<<< admin/task2-23-51164-1
        <form id="loginForm" method="POST" action="controller/authController.php" novalidate>
=======
        <form id="loginForm" method="POST" action="controllers/authController.php" novalidate>
>>>>>>> master
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
<<<<<<< admin/task2-23-51164-1
            <a href="view/restaurant/list.php" class="btn btn-secondary btn-sm">🏪 Browse Restaurants</a>
=======
            <a href="views/restaurant/list.php" class="btn btn-secondary btn-sm">🏪 Browse Restaurants</a>
>>>>>>> master
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

<<<<<<< admin/task2-23-51164-1
=======
<?php require_once __DIR__ . '/views/partials/footer.php'; ?>
>>>>>>> master
>>>>>>> 8eba48aa223268a09e7221a68507f249abc00e7e
