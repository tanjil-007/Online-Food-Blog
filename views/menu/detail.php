<?php
session_start();
require_once __DIR__ . '/../../models/menuItemModel.php';
require_once __DIR__ . '/../../models/reviewModel.php';

$id   = (int)($_GET['id'] ?? 0);
$item = getMenuItemById($id);
if (!$item) { header('Location: ../restaurant/list.php'); exit; }

$reviews = getReviewsByMenuItem($id);
$pageTitle = htmlspecialchars($item['name']) . ' — FoodBlog';
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <!-- Back button -->
    <div class="back-bar">
        <a href="../restaurant/detail.php?id=<?= $item['restaurant_id'] ?>" class="btn btn-back btn-sm">
            ← Back to <?= htmlspecialchars($item['restaurant_name']) ?>
        </a>
    </div>

    <div class="breadcrumb">
        <a href="../restaurant/list.php">Restaurants</a> &rsaquo;
        <a href="../restaurant/detail.php?id=<?= $item['restaurant_id'] ?>"><?= htmlspecialchars($item['restaurant_name']) ?></a>
        &rsaquo; <?= htmlspecialchars($item['name']) ?>
    </div>

    <div class="card">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; align-items:start;">
            <div>
                <?php if ($item['image_path']): ?>
                    <img src="../../<?= htmlspecialchars($item['image_path']) ?>"
                         alt="<?= htmlspecialchars($item['name']) ?>"
                         class="item-detail-img" style="width:100%; border-radius:10px;">
                <?php else: ?>
                    <div style="width:100%; height:300px; background:#f0f0f0; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:5rem;">🍽</div>
                <?php endif; ?>
            </div>

            <div>
                <h1 style="font-size:1.8rem; color:#1a1a2e; margin-bottom:.4rem;">
                    <?= htmlspecialchars($item['name']) ?>
                </h1>
                <p style="color:#888; font-size:.9rem; margin-bottom:.8rem;">
                    From: <a href="../restaurant/detail.php?id=<?= $item['restaurant_id'] ?>"
                             style="color:#e94560; text-decoration:none; font-weight:600;">
                        <?= htmlspecialchars($item['restaurant_name']) ?>
                    </a>
                </p>

                <div class="price-badge" style="font-size:1.3rem;">৳<?= number_format($item['price'], 2) ?></div>

                <hr style="margin:1.2rem 0; border:none; border-top:1px solid #eee;">

                <h3 style="color:#555; font-size:.85rem; text-transform:uppercase; letter-spacing:.5px; margin-bottom:.6rem;">Description</h3>
                <p style="line-height:1.8; color:#333;"><?= nl2br(htmlspecialchars($item['description'])) ?></p>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div style="display:flex; gap:.7rem; margin-top:1.5rem; flex-wrap:wrap;">
                        <a href="../admin/menu_item_form.php?id=<?= $id ?>&restaurant_id=<?= $item['restaurant_id'] ?>"
                           class="btn btn-warning btn-sm">✏️ Edit Item</a>
                        <a href="../admin/menu_items.php?restaurant_id=<?= $item['restaurant_id'] ?>"
                           class="btn btn-back btn-sm">← Back to Menu</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Review Section for Task 3 -->
    <div class="card">
        <h2 style="font-size:1.3rem; margin-bottom:1rem; color:#1a1a2e;">⭐ Reviews</h2>

        <div id="reviewList">
            <?php if (empty($reviews)): ?>
                <p style="color:#888; font-size:.9rem;">No reviews yet.</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-box" id="review-<?= $review['id'] ?>" style="border-bottom:1px solid #eee; padding:1rem 0;">
                        <strong><?= htmlspecialchars($review['member_name']) ?></strong>
                        <p style="line-height:1.6; margin:.4rem 0;">
                            <?= nl2br(htmlspecialchars($review['comment'])) ?>
                        </p>
                        <small style="color:#888;">
                            <?= htmlspecialchars($review['created_at']) ?>
                        </small>

                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $review['user_id']): ?>
                            <br><br>
                            <button class="btn btn-danger btn-sm" onclick="deleteReview(<?= $review['id'] ?>)">Delete</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
            <div id="review-form-area" style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid #eee;">
                <h3 style="font-size:1rem; margin-bottom:.8rem; color:#555;">Post a Review</h3>

                <form onsubmit="return addReview(<?= $id ?>)">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['name']) ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Comment</label>
                        <textarea id="comment" placeholder="Write your review"></textarea>
                        <div class="field-error" id="reviewError"></div>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </form>
            </div>
        <?php elseif (!isset($_SESSION['user_id'])): ?>
            <div style="margin-top:1rem; padding:1rem; background:#f8f9fa; border-radius:8px; text-align:center;">
                <p style="color:#666;">
                    <a href="../../index.php" style="color:#e94560; font-weight:600;">Login</a>
                    to post a review.
                </p>
            </div>
        <?php endif; ?>
    </div></div>

<script src="../../assets/js/review.js"></script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
