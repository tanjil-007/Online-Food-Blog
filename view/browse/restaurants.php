<?php
session_start();

// Reinstate Remember Me session
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../../model/userModel.php';
    $remembered = getUserByRememberToken($_COOKIE['remember_token']);
    if ($remembered) {
        $_SESSION['user_id'] = $remembered['id'];
        $_SESSION['name']    = $remembered['name'];
        $_SESSION['role']    = $remembered['role'];
    }
}

require_once __DIR__ . '/../../model/restaurantModel.php';

$pageTitle   = 'All Restaurants — FoodBlog';
$restaurants = getAllRestaurants();
require_once __DIR__ . '/../../view/partials/header.php';
?>

<div class="container">

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

    <?php if (empty($restaurants)): ?>
        <div class="card" style="text-align:center; padding:3rem;">
            <p style="font-size:1.1rem; color:#888;">No restaurants available yet. Check back soon!</p>
        </div>
    <?php else: ?>
        <div class="restaurant-grid">
            <?php foreach ($restaurants as $r): ?>
            <div class="restaurant-card">
                <h3><?= htmlspecialchars($r['name']) ?></h3>
                <div class="meta">
                    📍 <?= htmlspecialchars($r['location']) ?> &mdash; <?= htmlspecialchars($r['area']) ?>
                </div>
                <p style="color:#555; font-size:.9rem; line-height:1.5; margin-bottom:1rem;">
                    <?= htmlspecialchars(substr($r['short_background'], 0, 120)) ?>...
                </p>
                <a href="restaurant_detail.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">View Restaurant →</a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
