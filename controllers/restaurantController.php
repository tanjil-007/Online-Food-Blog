<?php
session_start();

// Admin gate
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once __DIR__ . '/../models/restaurantModel.php';

$action = $_GET['action'] ?? 'list';

// ── CREATE ──────────────────────────────────────────────────────────────────
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $name             = trim($_POST['name'] ?? '');
    $location         = trim($_POST['location'] ?? '');
    $area             = trim($_POST['area'] ?? '');
    $short_background = trim($_POST['short_background'] ?? '');
    $goals            = trim($_POST['goals'] ?? '');

    if ($name === '')             $errors[] = "Restaurant name is required.";
    if ($location === '')         $errors[] = "Location is required.";
    if ($area === '')             $errors[] = "Area is required.";
    if ($short_background === '') $errors[] = "Short background is required.";
    if ($goals === '')            $errors[] = "Goals are required.";

    if (empty($errors)) {
        $data = compact('name', 'location', 'area', 'short_background', 'goals');
        if (addRestaurant($data)) {
            $_SESSION['flash'] = "Restaurant added successfully!";
            header('Location: ../views/admin/restaurants.php');
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data']   = compact('name', 'location', 'area', 'short_background', 'goals');
    header('Location: ../views/admin/restaurant_form.php');
    exit;
}

// ── UPDATE ──────────────────────────────────────────────────────────────────
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $id               = (int)($_POST['id'] ?? 0);
    $name             = trim($_POST['name'] ?? '');
    $location         = trim($_POST['location'] ?? '');
    $area             = trim($_POST['area'] ?? '');
    $short_background = trim($_POST['short_background'] ?? '');
    $goals            = trim($_POST['goals'] ?? '');

    if ($id <= 0)                 $errors[] = "Invalid restaurant ID.";
    if ($name === '')             $errors[] = "Restaurant name is required.";
    if ($location === '')         $errors[] = "Location is required.";
    if ($area === '')             $errors[] = "Area is required.";
    if ($short_background === '') $errors[] = "Short background is required.";
    if ($goals === '')            $errors[] = "Goals are required.";

    if (empty($errors)) {
        $data = compact('id', 'name', 'location', 'area', 'short_background', 'goals');
        if (updateRestaurant($data)) {
            $_SESSION['flash'] = "Restaurant updated successfully!";
            header('Location: ../views/admin/restaurants.php');
            exit;
        } else {
            $errors[] = "Database error. Please try again.";
        }
    }
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data']   = compact('id', 'name', 'location', 'area', 'short_background', 'goals');
    header('Location: ../views/admin/restaurant_form.php?id=' . $id);
    exit;
}

// ── DELETE ───────────────────────────────────────────────────────────────────
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id > 0) {
        deleteRestaurant($id);
        $_SESSION['flash'] = "Restaurant deleted successfully.";
    }
    header('Location: ../views/admin/restaurants.php');
    exit;
}

header('Location: ../views/admin/restaurants.php');
exit;
