<?php
session_start();

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
    }
    exit;
}

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
