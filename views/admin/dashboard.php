<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_redirect.php'); exit;
}
require_once __DIR__ . '/../../models/restaurantModel.php';
require_once __DIR__ . '/../../models/menuItemModel.php';

$pageTitle       = 'Admin Dashboard — FoodBlog';
$restaurantCount = getRestaurantCount();
$menuItemCount   = getMenuItemCount();
$reviewCount     = getTotalReviewCount();
$foodExpCount    = getTotalFoodExpCount();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <?php if ($flash): ?>
        <div class="flash success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="page-header">
        <h1>📊 Admin Dashboard</h1>
        <span style="color:#888; font-size:.9rem;">Welcome back, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>!</span>
    </div>

    <!-- Live stats -->
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

    <div class="card">
        <h2 style="margin-bottom:1rem; color:#1a1a2e;">⚡ Quick Actions</h2>
        <div style="display:flex; gap:1rem; flex-wrap:wrap;">
            <a href="restaurants.php" class="btn btn-primary">🏪 Manage Restaurants</a>
            <a href="restaurant_form.php" class="btn btn-secondary">＋ Add New Restaurant</a>
            <a href="../restaurant/list.php" class="btn btn-back">🌐 Public View</a>
        </div>
    </div>
</div>

<script>
// AJAX refresh stats every 30s
function refreshStats() {
    fetch('../../api/dashboard_stats.php')
        .then(r => r.json())
        .then(d => {
            document.getElementById('stat-restaurants').textContent = d.restaurants ?? '–';
            document.getElementById('stat-menu').textContent        = d.menu_items   ?? '–';
            document.getElementById('stat-reviews').textContent     = d.reviews      ?? '–';
            document.getElementById('stat-posts').textContent       = d.food_exp_posts ?? '–';
        })
        .catch(() => {});
}
setInterval(refreshStats, 30000);
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
