<?php
// api/delete_item.php
// AJAX endpoint — Admin only — deletes a menu item and returns JSON
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../models/menuItemModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid item ID']);
    exit;
}

$item = getMenuItemById($id);
if (!$item) {
    echo json_encode(['success' => false, 'error' => 'Item not found']);
    exit;
}

if (deleteMenuItem($id)) {
    echo json_encode(['success' => true, 'message' => 'Menu item deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete item']);
}
