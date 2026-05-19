<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../models/reviewModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'member') {
    echo json_encode(['status' => false, 'message' => 'Only members can delete reviews.']);
    exit;
}

$review_id = (int)($_POST['review_id'] ?? 0);

if ($review_id <= 0) {
    echo json_encode(['status' => false, 'message' => 'Invalid review.']);
    exit;
}

$status = deleteOwnReview($review_id, $_SESSION['user_id']);

if ($status) {
    echo json_encode(['status' => true, 'message' => 'Review deleted successfully.']);
} else {
    echo json_encode(['status' => false, 'message' => 'You can delete only your own review.']);
}
?>
