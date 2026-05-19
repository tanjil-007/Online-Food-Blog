<?php
// api/dashboard_stats.php
// Returns live dashboard counts as JSON (AJAX)
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../models/restaurantModel.php';
require_once __DIR__ . '/../models/menuItemModel.php';

echo json_encode([
    'restaurants'        => getRestaurantCount(),
    'menu_items'         => getMenuItemCount(),
    'reviews'            => getTotalReviewCount(),
    'food_exp_posts'     => getTotalFoodExpCount(),
]);
