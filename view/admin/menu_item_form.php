<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_redirect.php'); exit;
}
require_once __DIR__ . '/../../model/restaurantModel.php';
require_once __DIR__ . '/../../model/menuItemModel.php';

$restaurant_id = (int)($_GET['restaurant_id'] ?? 0);
$isEdit        = isset($_GET['id']);
$item          = null;
$formData      = $_SESSION['form_data']   ?? null;
$errors        = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);

if ($isEdit) {
    $item = getMenuItemById((int)$_GET['id']);
    if (!$item) { header('Location: restaurants.php'); exit; }
    $restaurant_id = $item['restaurant_id'];
}

$restaurant = getRestaurantById($restaurant_id);
if (!$restaurant) { header('Location: restaurants.php'); exit; }

$val = function($key) use ($formData, $item) {
    if ($formData && isset($formData[$key])) return htmlspecialchars($formData[$key]);
    if ($item && isset($item[$key]))         return htmlspecialchars($item[$key]);
    return '';
};

$pageTitle = $isEdit ? 'Edit Menu Item — FoodBlog' : 'Add Menu Item — FoodBlog';
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <!-- Back button -->
    <div class="back-bar">
        <a href="menu_items.php?restaurant_id=<?= $restaurant_id ?>" class="btn btn-back btn-sm">
            ← Back to <?= htmlspecialchars($restaurant['name']) ?> Menu
        </a>
    </div>

    <div class="breadcrumb">
        <a href="restaurants.php">Restaurants</a> &rsaquo;
        <a href="menu_items.php?restaurant_id=<?= $restaurant_id ?>"><?= htmlspecialchars($restaurant['name']) ?></a>
        &rsaquo; <?= $isEdit ? 'Edit Item' : 'Add Item' ?>
    </div>

    <div class="page-header">
        <h1><?= $isEdit ? '✏️ Edit Menu Item' : '＋ Add Menu Item' ?></h1>
        <span style="color:#888; font-size:.9rem;">Restaurant: <strong><?= htmlspecialchars($restaurant['name']) ?></strong></span>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="flash error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top:.5rem; padding-left:1.2rem;">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <form id="menuForm" method="POST" enctype="multipart/form-data"
              action="../../controller/menuItemController.php?action=<?= $isEdit ? 'update' : 'create' ?>"
              novalidate>

            <input type="hidden" name="restaurant_id" value="<?= $restaurant_id ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Item Name *</label>
                <input type="text" id="name" name="name" value="<?= $val('name') ?>">
                <div class="field-error" id="nameErr"></div>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description"
                          ><?= $val('description') ?></textarea>
                <div class="field-error" id="descErr"></div>
            </div>

            <div class="form-group" style="max-width:250px;">
                <label for="price">Price (৳) *</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01"
                       value="<?= $val('price') ?>">
                <div class="field-error" id="priceErr"></div>
            </div>

            <div class="form-group">
                <label for="image">
                    Food Image <?= $isEdit ? '(leave blank to keep existing)' : '*' ?>
                    <span style="color:#888; font-size:.8rem;">&nbsp;JPEG/PNG, max 2MB</span>
                </label>

                <?php if ($isEdit && $item['image_path']): ?>
                    <div style="margin-bottom:.8rem;">
                        <img src="../../<?= htmlspecialchars($item['image_path']) ?>"
                             alt="Current image"
                             style="width:120px; height:120px; object-fit:cover; border-radius:8px; border:2px solid #eee;">
                        <p style="font-size:.8rem; color:#888; margin-top:.3rem;">Current image</p>
                    </div>
                <?php endif; ?>

                <input type="file" id="image" name="image" accept="image/jpeg,image/png"
                       onchange="previewImage(this)">
                <div class="field-error" id="imgErr"></div>
                <img id="imgPreview" src="#" alt="Preview"
                     style="display:none; margin-top:.8rem; width:150px; height:150px; object-fit:cover; border-radius:8px; border:2px solid #e94560;">
            </div>

            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? '💾 Update Item' : '＋ Add Item' ?>
                </button>
                <a href="menu_items.php?restaurant_id=<?= $restaurant_id ?>" class="btn btn-back">← Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('menuForm').addEventListener('submit', function(e) {
    let valid = true;
    const isEdit = <?= $isEdit ? 'true' : 'false' ?>;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const name    = document.getElementById('name').value.trim();
    const desc    = document.getElementById('description').value.trim();
    const price   = parseFloat(document.getElementById('price').value);
    const imgFile = document.getElementById('image').files[0];

    clearErr('nameErr'); clearErr('descErr'); clearErr('priceErr'); clearErr('imgErr');

    if (name === '')          showErr('nameErr',  'Item name is required.');
    else if (name.length < 2) showErr('nameErr',  'Name must be at least 2 characters.');
    if (desc === '')          showErr('descErr',  'Description is required.');
    else if (desc.length < 10) showErr('descErr', 'Description must be at least 10 characters.');
    if (isNaN(price) || price <= 0) showErr('priceErr', 'Price must be a positive number.');

    if (!isEdit && !imgFile) {
        showErr('imgErr', 'Please upload an image.');
    } else if (imgFile) {
        if (!['image/jpeg','image/png'].includes(imgFile.type))
            showErr('imgErr', 'Only JPEG and PNG images are allowed.');
        else if (imgFile.size > 2 * 1024 * 1024)
            showErr('imgErr', 'Image must be under 2MB.');
    }

    if (!valid) e.preventDefault();
});
['name','description','price'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const m = {name:'nameErr', description:'descErr', price:'priceErr'};
        document.getElementById(m[id]).textContent = '';
    });
});
</script>

