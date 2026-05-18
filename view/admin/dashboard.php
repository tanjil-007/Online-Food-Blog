<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['flash_error'] = 'Admin access required.';
    header('Location: ../auth/login.php'); exit;
}

require_once __DIR__ . '/../../model/restaurantModel.php';

$pageTitle = 'Admin Dashboard — FoodBlog';
$flash     = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Count helpers — reuse models if included, else fallback
function getRestaurantCount() {
    require_once __DIR__ . '/../../model/db.php';
    $con    = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM restaurants");
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}
function getMenuItemCount() {
    require_once __DIR__ . '/../../model/db.php';
    $con    = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM menu_items");
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}
function getTotalReviewCount() {
    require_once __DIR__ . '/../../model/db.php';
    $con    = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM reviews");
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}
function getTotalFoodExpCount() {
    require_once __DIR__ . '/../../model/db.php';
    $con    = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM food_experience_posts");
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}

$restaurantCount = getRestaurantCount();
$menuItemCount   = getMenuItemCount();
$reviewCount     = getTotalReviewCount();
$foodExpCount    = getTotalFoodExpCount();

require_once __DIR__ . '/../../view/partials/header.php';
?>

<div class="container">

    <?php if ($flash): ?>
        <div class="flash success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="page-header">
        <h1>📊 Admin Dashboard</h1>
        <span style="color:#888; font-size:.9rem;">Welcome back, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>!</span>
    </div>

    <!-- Stats -->
    <div class="stats-grid" id="statsGrid">
        <div class="stat-card">
            <div class="stat-number" id="stat-restaurants"><?= $restaurantCount ?></div>
            <div class="stat-label">🏪 Total Restaurants</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="stat-menu"><?= $menuItemCount ?></div>
            <div class="stat-label">🍔 Total Menu Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="stat-reviews"><?= $reviewCount ?></div>
            <div class="stat-label">⭐ Total Reviews</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="stat-posts"><?= $foodExpCount ?></div>
            <div class="stat-label">📝 Food Exp. Posts</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h2 style="margin-bottom:1rem; color:#1a1a2e;">⚡ Quick Actions</h2>
        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
            <a href="../browse/restaurants.php" class="btn btn-primary">🏪 Browse Restaurants</a>
            <a href="../profile/profile.php"    class="btn btn-secondary">👤 My Profile</a>
        </div>
        <p style="margin-top:1rem; color:#aaa; font-size:.85rem;">
            💡 Use Task 2 for full restaurant &amp; menu management (CRUD admin panel).
        </p>
    </div>
</div>

<script>
// AJAX refresh stats every 30s
function refreshStats() {
    let xhttp = new XMLHttpRequest();
    xhttp.open('get', '../../controllers/deleteItem.php', true);
    xhttp.send();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let d = JSON.parse(this.responseText);
            document.getElementById('stat-restaurants').textContent = d.restaurants    ?? '–';
            document.getElementById('stat-menu').textContent        = d.menu_items     ?? '–';
            document.getElementById('stat-reviews').textContent     = d.reviews        ?? '–';
            document.getElementById('stat-posts').textContent       = d.food_exp_posts ?? '–';
        }
    };
}
setInterval(refreshStats, 30000);
</script>

</body>
</html>
