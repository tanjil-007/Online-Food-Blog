<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_redirect.php'); exit;
}
require_once __DIR__ . '/../../model/restaurantModel.php';

$pageTitle   = 'Manage Restaurants — FoodBlog';
$restaurants = getAllRestaurants();
$flash       = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <?php if ($flash): ?>
        <div class="flash success"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <!-- Back button -->
    <div class="back-bar">
        <a href="dashboard.php" class="btn btn-back btn-sm">← Back to Dashboard</a>
    </div>

    <div class="page-header">
        <h1>🏪 Restaurants</h1>
        <a href="restaurant_form.php" class="btn btn-primary">＋ Add Restaurant</a>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <?php if (empty($restaurants)): ?>
            <p style="padding:2rem; color:#888; text-align:center;">No restaurants yet. Add your first one!</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Area</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($restaurants as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><strong><?= htmlspecialchars($r['name']) ?></strong></td>
                    <td><?= htmlspecialchars($r['location']) ?></td>
                    <td><?= htmlspecialchars($r['area']) ?></td>
                    <td style="color:#888; font-size:.85rem;"><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="../restaurant/detail.php?id=<?= $r['id'] ?>" class="btn btn-secondary btn-sm">View</a>
                            <a href="menu_items.php?restaurant_id=<?= $r['id'] ?>" class="btn btn-warning btn-sm">Menu</a>
                            <a href="restaurant_form.php?id=<?= $r['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm"
                                onclick="confirmDelete(<?= $r['id'] ?>, '<?= htmlspecialchars(addslashes($r['name'])) ?>')">
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
function confirmDelete(id, name) {
    document.getElementById('deleteMsg').textContent =
        'Are you sure you want to delete "' + name + '"? This will also delete all its menu items.';
    document.getElementById('deleteLink').href =
        '../../controller/restaurantController.php?action=delete&id=' + id;
    document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal() { document.getElementById('deleteModal').style.display = 'none'; }
window.addEventListener('click', e => { if (e.target === document.getElementById('deleteModal')) closeModal(); });
</script>

