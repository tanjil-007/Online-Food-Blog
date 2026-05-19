<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/reviewModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    echo json_encode(['status' => false, 'message' => 'Only members can post reviews.']);
    exit;
}

$menu_item_id = (int)($_POST['menu_item_id'] ?? 0);
$comment      = trim($_POST['comment'] ?? '');

if ($menu_item_id <= 0) {
    echo json_encode(['status' => false, 'message' => 'Invalid food item.']);
    exit;
}

if ($comment === '') {
    echo json_encode(['status' => false, 'message' => 'Comment cannot be empty.']);
    exit;
}

if (strlen($comment) > 500) {
    echo json_encode(['status' => false, 'message' => 'Comment must be within 500 characters.']);
    exit;
}

if (!menuItemExists($menu_item_id)) {
    echo json_encode(['status' => false, 'message' => 'Food item not found.']);
    exit;
}

$review_id = addReview($menu_item_id, $_SESSION['user_id'], $comment);

if ($review_id) {
    echo json_encode([
        'status' => true,
        'message' => 'Review added successfully.',
        'review' => [
            'id' => $review_id,
            'member_name' => $_SESSION['name'],
            'comment' => htmlspecialchars($comment),
            'created_at' => date('Y-m-d H:i:s')
        ]
    ]);
} else {
    echo json_encode(['status' => false, 'message' => 'Review could not be added.']);
}
?>
