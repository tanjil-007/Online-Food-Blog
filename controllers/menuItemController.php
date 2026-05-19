<?php
session_start();

// Admin gate
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../models/menuItemModel.php';

$action = $_GET['action'] ?? 'list';

// ── Helper: handle image upload ───────────────────────────────────────────
function handleImageUpload($fileKey) {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['path' => null, 'error' => null];
    }
    $file = $_FILES[$fileKey];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['path' => null, 'error' => 'Upload error occurred.'];
    }
    $allowed_mime = ['image/jpeg', 'image/png'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, $allowed_mime)) {
        return ['path' => null, 'error' => 'Only JPEG and PNG images are allowed.'];
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return ['path' => null, 'error' => 'Image must be under 2MB.'];
    }
    $ext      = ($mime === 'image/jpeg') ? 'jpg' : 'png';
    $filename = uniqid('menu_', true) . '.' . $ext;
    $dest     = __DIR__ . '/../public/uploads/menu/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return ['path' => null, 'error' => 'Could not save image. Check folder permissions.'];
    }
    return ['path' => 'public/uploads/menu/' . $filename, 'error' => null];
}

// ── CREATE ──────────────────────────────────────────────────────────────────
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors        = [];
    $restaurant_id = (int)($_POST['restaurant_id'] ?? 0);
    $name          = trim($_POST['name'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $price         = $_POST['price'] ?? '';

    if ($restaurant_id <= 0) $errors[] = "Restaurant is required.";
    if ($name === '')         $errors[] = "Item name is required.";
    if ($description === '')  $errors[] = "Description is required.";
    if (!is_numeric($price) || (float)$price <= 0) $errors[] = "Price must be a positive number.";

    $upload = handleImageUpload('image');
    if ($upload['error']) $errors[] = $upload['error'];
    // Image required for new item
    if (!$upload['path'] && !$upload['error']) $errors[] = "Please upload an image.";

    if (empty($errors)) {
        $data = compact('restaurant_id', 'name', 'description') + ['price' => (float)$price];
        if (addMenuItem($data, $upload['path'])) {
            $_SESSION['flash'] = "Menu item added!";
            header('Location: ../views/admin/menu_items.php?restaurant_id=' . $restaurant_id);
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data']   = compact('restaurant_id', 'name', 'description', 'price');
    header('Location: ../views/admin/menu_item_form.php?restaurant_id=' . $restaurant_id);
    exit;
}

// ── UPDATE ──────────────────────────────────────────────────────────────────
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors        = [];
    $id            = (int)($_POST['id'] ?? 0);
    $restaurant_id = (int)($_POST['restaurant_id'] ?? 0);
    $name          = trim($_POST['name'] ?? '');
    $description   = trim($_POST['description'] ?? '');
    $price         = $_POST['price'] ?? '';

    if ($id <= 0)             $errors[] = "Invalid item.";
    if ($name === '')         $errors[] = "Item name is required.";
    if ($description === '')  $errors[] = "Description is required.";
    if (!is_numeric($price) || (float)$price <= 0) $errors[] = "Price must be a positive number.";

    $upload = handleImageUpload('image');
    if ($upload['error']) $errors[] = $upload['error'];

    if (empty($errors)) {
        $data = compact('id', 'name', 'description') + ['price' => (float)$price];
        if (updateMenuItem($data, $upload['path'])) {
            $_SESSION['flash'] = "Menu item updated!";
            header('Location: ../views/admin/menu_items.php?restaurant_id=' . $restaurant_id);
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data']   = compact('id', 'restaurant_id', 'name', 'description', 'price');
    header('Location: ../views/admin/menu_item_form.php?id=' . $id . '&restaurant_id=' . $restaurant_id);
    exit;
}

// ── DELETE ───────────────────────────────────────────────────────────────────
if ($action === 'delete') {
    $id            = (int)($_GET['id'] ?? 0);
    $restaurant_id = (int)($_GET['restaurant_id'] ?? 0);
    if ($id > 0) {
        deleteMenuItem($id);
        $_SESSION['flash'] = "Menu item deleted.";
    }
    header('Location: ../views/admin/menu_items.php?restaurant_id=' . $restaurant_id);
    exit;
}

header('Location: ../views/admin/restaurants.php');
exit;
