<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_redirect.php'); exit;
}
require_once __DIR__ . '/../../models/restaurantModel.php';
require_once __DIR__ . '/../../models/menuItemModel.php';

$restaurant_id = (int)($_GET['restaurant_id'] ?? 0);
$restaurant    = getRestaurantById($restaurant_id);
if (!$restaurant) { header('Location: restaurants.php'); exit; }

$items     = getMenuItemsByRestaurant($restaurant_id);
$pageTitle = 'Menu — ' . $restaurant['name'];
$flash     = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <?php if ($flash): ?>
        <div class="flash success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <!-- Back button -->
    <div class="back-bar">
        <a href="restaurants.php" class="btn btn-back btn-sm">← Back to Restaurants</a>
    </div>

    <div class="breadcrumb">
        <a href="restaurants.php">Restaurants</a> &rsaquo;
        <a href="../restaurant/detail.php?id=<?= $restaurant_id ?>"><?= htmlspecialchars($restaurant['name']) ?></a>
        &rsaquo; Menu Items
    </div>

    <div class="page-header">
        <h1>🍔 Menu — <?= htmlspecialchars($restaurant['name']) ?></h1>
        <a href="menu_item_form.php?restaurant_id=<?= $restaurant_id ?>" class="btn btn-primary">＋ Add Item</a>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <?php if (empty($items)): ?>
            <p style="padding:2rem; color:#888; text-align:center;">No menu items yet. Add the first one!</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php if ($item['image_path']): ?>
                            <img src="../../<?= htmlspecialchars($item['image_path']) ?>"
                                 alt="<?= htmlspecialchars($item['name']) ?>" class="thumb">
                        <?php else: ?>
                            <div class="thumb" style="background:#eee; display:flex; align-items:center; justify-content:center; font-size:1.4rem;">🍽</div>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($item['name']) ?></strong></td>
                    <td style="max-width:220px; color:#666; font-size:.88rem;">
                        <?= htmlspecialchars(substr($item['description'], 0, 80)) ?>...
                    </td>
                    <td><span class="price-badge" style="font-size:.85rem;">৳<?= number_format($item['price'], 2) ?></span></td>
                    <td style="color:#888; font-size:.85rem;"><?= date('M d, Y', strtotime($item['created_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../menu/detail.php?id=<?= $item['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                            <a href="menu_item_form.php?id=<?= $item['id'] ?>&restaurant_id=<?= $restaurant_id ?>"
                               class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmDelete(<?= $item['id'] ?>, <?= $restaurant_id ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>')">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Delete modal -->
<div id="deleteModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:2rem; max-width:420px; width:90%; box-shadow:0 10px 40px rgba(0,0,0,.2);">
        <h3 style="margin-bottom:.8rem; color:#1a1a2e;">⚠️ Confirm Delete</h3>
        <p id="deleteMsg" style="color:#555; margin-bottom:1.5rem;"></p>
        <div style="display:flex; gap:1rem; justify-content:flex-end;">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <a id="deleteLink" href="#" class="btn btn-danger">Yes, Delete</a>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, restId, name) {
    document.getElementById('deleteMsg').textContent = 'Are you sure you want to delete "' + name + '"?';
    document.getElementById('deleteLink').href =
        '../../controllers/menuItemController.php?action=delete&id=' + id + '&restaurant_id=' + restId;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal() { document.getElementById('deleteModal').style.display = 'none'; }
window.addEventListener('click', e => { if (e.target === document.getElementById('deleteModal')) closeModal(); });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
