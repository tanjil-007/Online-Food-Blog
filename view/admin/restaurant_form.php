<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login_redirect.php'); exit;
}
require_once __DIR__ . '/../../model/restaurantModel.php';

$isEdit     = isset($_GET['id']);
$restaurant = null;
$formData   = $_SESSION['form_data']   ?? null;
$errors     = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_data'], $_SESSION['form_errors']);

if ($isEdit) {
    $restaurant = getRestaurantById((int)$_GET['id']);
    if (!$restaurant) { header('Location: restaurants.php'); exit; }
}

$val = function($key) use ($formData, $restaurant) {
    if ($formData && isset($formData[$key]))     return htmlspecialchars($formData[$key]);
    if ($restaurant && isset($restaurant[$key])) return htmlspecialchars($restaurant[$key]);
    return '';
};

$pageTitle = $isEdit ? 'Edit Restaurant — FoodBlog' : 'Add Restaurant — FoodBlog';
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <!-- Back button -->
    <div class="back-bar">
        <a href="restaurants.php" class="btn btn-back btn-sm">← Back to Restaurants</a>
    </div>

    <div class="page-header">
        <h1><?= $isEdit ? '✏️ Edit Restaurant' : '＋ Add Restaurant' ?></h1>
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
        <form id="restaurantForm" method="POST"
              action="../../controller/restaurantController.php?action=<?= $isEdit ? 'update' : 'create' ?>"
              novalidate>
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $restaurant['id'] ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">Restaurant Name *</label>
                    <input type="text" id="name" name="name" value="<?= $val('name') ?>" >
                    <div class="field-error" id="nameErr"></div>
                </div>
                <div class="form-group">
                    <label for="location">Location (City) *</label>
                    <input type="text" id="location" name="location" value="<?= $val('location') ?>">
                    <div class="field-error" id="locationErr"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="area">Area (Neighborhood) *</label>
                <input type="text" id="area" name="area" value="<?= $val('area') ?>">
                <div class="field-error" id="areaErr"></div>
            </div>

            <div class="form-group">
                <label for="short_background">Short Background *</label>
                <textarea id="short_background" name="short_background"
                         >  <?= $val('short_background') ?></textarea>
                <div class="field-error" id="bgErr"></div>
            </div>

            <div class="form-group">
                <label for="goals">Goals *</label>
                <textarea id="goals" name="goals"
                          ><?= $val('goals') ?></textarea>
                <div class="field-error" id="goalsErr"></div>
            </div>

            <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? '💾 Update Restaurant' : '＋ Add Restaurant' ?>
                </button>
                <a href="restaurants.php" class="btn btn-back">← Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('restaurantForm').addEventListener('submit', function(e) {
    let valid = true;
    function showErr(id, msg) { document.getElementById(id).textContent = msg; valid = false; }
    function clearErr(id)     { document.getElementById(id).textContent = ''; }

    const name     = document.getElementById('name').value.trim();
    const location = document.getElementById('location').value.trim();
    const area     = document.getElementById('area').value.trim();
    const bg       = document.getElementById('short_background').value.trim();
    const goals    = document.getElementById('goals').value.trim();

    clearErr('nameErr'); clearErr('locationErr'); clearErr('areaErr'); clearErr('bgErr'); clearErr('goalsErr');

    if (name === '')         showErr('nameErr',     'Restaurant name is required.');
    else if (name.length<2)  showErr('nameErr',     'Name must be at least 2 characters.');
    if (location === '')     showErr('locationErr', 'Location is required.');
    if (area === '')         showErr('areaErr',     'Area is required.');
    if (bg === '')           showErr('bgErr',       'Short background is required.');
    else if (bg.length<10)   showErr('bgErr',       'Background must be at least 10 characters.');
    if (goals === '')        showErr('goalsErr',    'Goals are required.');
    else if (goals.length<10) showErr('goalsErr',   'Goals must be at least 10 characters.');

    if (!valid) e.preventDefault();
});
['name','location','area','short_background','goals'].forEach(id => {
    document.getElementById(id).addEventListener('input', () => {
        const m = {name:'nameErr',location:'locationErr',area:'areaErr',short_background:'bgErr',goals:'goalsErr'};
        document.getElementById(m[id]).textContent = '';
    });
});
</script>

