<?php
session_start();
require_once __DIR__ . '/../../models/restaurantModel.php';

$pageTitle   = 'All Restaurants — FoodBlog';
$restaurants = getAllRestaurants();
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <!-- Back to login/home if not admin -->
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="back-bar">
        <a href="../../index.php" class="btn btn-back btn-sm">← Back to Home</a>
    </div>
    <?php elseif ($_SESSION['role'] === 'admin'): ?>
    <div class="back-bar">
        <a href="../admin/dashboard.php" class="btn btn-back btn-sm">← Back to Dashboard</a>
    </div>
    <?php endif; ?>

    <div class="page-header">
        <h1>🏪 All Restaurants</h1>
        <span style="color:#888; font-size:.9rem;"><?= count($restaurants) ?> restaurant(s) listed</span>
    </div>

    <div class="card">
        <h3 style="margin-bottom:1rem; color:#1a1a2e;">Search & Filter</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Restaurant or Food Item</label>
                <input type="text" id="q" placeholder="Search by restaurant or food item" onkeyup="searchData()">
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" id="location" placeholder="Example: Dhaka" onkeyup="searchData()">
            </div>
        </div>
        <div class="form-group">
            <label>Area</label>
            <input type="text" id="area" placeholder="Example: Bashundhara" onkeyup="searchData()">
        </div>
        <button type="button" class="btn btn-primary" onclick="searchData()">Search</button>
    </div>

    <?php if (empty($restaurants)): ?>
        <div class="card" style="text-align:center; padding:3rem;">
            <p style="font-size:1.1rem; color:#888;">No restaurants available yet. Check back soon!</p>
        </div>
    <?php else: ?>
        <div class="restaurant-grid" id="restaurantResult">
            <?php foreach ($restaurants as $r): ?>
            <div class="restaurant-card">
                <h3><?= htmlspecialchars($r['name']) ?></h3>
                <div class="meta">
                    📍 <?= htmlspecialchars($r['location']) ?> &mdash; <?= htmlspecialchars($r['area']) ?>
                </div>
                <p style="color:#555; font-size:.9rem; line-height:1.5; margin-bottom:1rem;">
                    <?= htmlspecialchars(substr($r['short_background'], 0, 120)) ?>...
                </p>
                <a href="detail.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">View Restaurant →</a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="../../assets/js/search.js"></script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
