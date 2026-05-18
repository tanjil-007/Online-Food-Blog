<?php
session_start();
require_once __DIR__ . '/../../models/restaurantModel.php';
require_once __DIR__ . '/../../models/menuItemModel.php';
require_once __DIR__ . '/../../models/restaurantReviewModel.php';

$id         = (int)($_GET['id'] ?? 0);
$restaurant = getRestaurantById($id);
if (!$restaurant) { header('Location: list.php'); exit; }

$items     = getMenuItemsByRestaurant($id);
$restaurantReviews = getRestaurantReviews($id);
$pageTitle = htmlspecialchars($restaurant['name']) . ' — FoodBlog';
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <!-- Back button -->
    <div class="back-bar">
        <a href="list.php" class="btn btn-back btn-sm">← Back to Restaurants</a>
    </div>

    <div class="breadcrumb">
        <a href="list.php">Restaurants</a> &rsaquo; <?= htmlspecialchars($restaurant['name']) ?>
    </div>

    <!-- Restaurant info card -->
    <div class="card" style="margin-bottom:2rem;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-size:2rem; color:#1a1a2e; margin-bottom:.4rem;">
                    <?= htmlspecialchars($restaurant['name']) ?>
                </h1>
                <p style="color:#e94560; font-weight:600; font-size:1rem;">
                    📍 <?= htmlspecialchars($restaurant['location']) ?> &mdash; <?= htmlspecialchars($restaurant['area']) ?>
                </p>
            </div>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div style="display:flex; gap:.7rem; flex-wrap:wrap;">
                    <a href="../admin/restaurant_form.php?id=<?= $id ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                    <a href="../admin/menu_items.php?restaurant_id=<?= $id ?>" class="btn btn-primary btn-sm">🍔 Manage Menu</a>
                </div>
            <?php endif; ?>
        </div>

        <hr style="margin:1.2rem 0; border:none; border-top:1px solid #eee;">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <div>
                <h3 style="color:#555; font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.5rem;">Background</h3>
                <p style="line-height:1.7; color:#333;"><?= nl2br(htmlspecialchars($restaurant['short_background'])) ?></p>
            </div>
            <div>
                <h3 style="color:#555; font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.5rem;">Our Goals</h3>
                <p style="line-height:1.7; color:#333;"><?= nl2br(htmlspecialchars($restaurant['goals'])) ?></p>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="page-header">
        <h2 style="font-size:1.4rem;">🍔 Menu Items</h2>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="../admin/menu_item_form.php?restaurant_id=<?= $id ?>" class="btn btn-primary btn-sm">＋ Add Item</a>
        <?php endif; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="card" style="text-align:center; padding:2rem;">
            <p style="color:#888;">No menu items available yet.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:1.2rem;">
            <?php foreach ($items as $item): ?>
            <div class="restaurant-card">
                <?php if ($item['image_path']): ?>
                    <img src="../../<?= htmlspecialchars($item['image_path']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>"
                         style="width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:.8rem;">
                <?php else: ?>
                    <div style="width:100%; height:180px; background:#f0f0f0; border-radius:8px; margin-bottom:.8rem; display:flex; align-items:center; justify-content:center; font-size:3rem;">🍽</div>
                <?php endif; ?>

                <h3 style="font-size:1.05rem;"><?= htmlspecialchars($item['name']) ?></h3>
                <p style="color:#888; font-size:.85rem; margin:.4rem 0 .8rem; line-height:1.4;">
                    <?= htmlspecialchars(substr($item['description'], 0, 80)) ?>...
                </p>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="price-badge">৳<?= number_format($item['price'], 2) ?></span>
                    <a href="../menu/detail.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">Details →</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <!-- Restaurant Reviews -->
    <div class="card" style="margin-top:2rem;">
        <h2 style="font-size:1.4rem; margin-bottom:1rem;">⭐ Restaurant Reviews</h2>

        <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] == 'member'): ?>

            <input type="hidden" id="restaurant_id" value="<?= $id ?>">

            <div style="margin-bottom:1rem;">
                <label>Rating</label><br>
                <select id="rating" class="form-control">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Bad</option>
                </select>
            </div>

            <div style="margin-bottom:1rem;">
                <label>Comment</label><br>
                <textarea 
                id="restaurant_comment"
                class="form-control"
                placeholder="Write your restaurant review"
                style="width:100%; min-height:120px; padding:12px; border:1px solid #ddd; border-radius:6px; resize:vertical;"></textarea>
            </div>

            <button class="btn btn-primary" onclick="addRestaurantReview()">
                Post Review
            </button>

        <?php else: ?>

            <p style="color:#888;">Login as member to post restaurant review.</p>

        <?php endif; ?>

        <hr style="margin:1.5rem 0; border:none; border-top:1px solid #eee;">

        <?php if(empty($restaurantReviews)): ?>

            <p style="color:#888;">No restaurant reviews yet.</p>

        <?php else: ?>

            <?php foreach($restaurantReviews as $review): ?>

                <div style="border:1px solid #eee; padding:1rem; border-radius:8px; margin-bottom:1rem;">
                    <p>
                        <b><?= htmlspecialchars($review['name']) ?></b>
                        <span style="color:#e94560;">
                            Rating: <?= htmlspecialchars($review['rating']) ?>/5
                        </span>
                    </p>

                    <p style="line-height:1.6;">
                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                    </p>

                    <small style="color:#888;">
                        <?= htmlspecialchars($review['created_at']) ?>
                    </small>

                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                        <br><br>
                        <button class="btn btn-danger btn-sm" onclick="deleteRestaurantReview(<?= $review['id'] ?>)">
                            Delete
                        </button>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </div>

</div>

<script src="../../assets/js/restaurantReview.js"></script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
