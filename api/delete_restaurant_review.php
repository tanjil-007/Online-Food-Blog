<?php

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../models/restaurantReviewModel.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    echo json_encode([
        "status" => false,
        "message" => "Only members can delete review."
    ]);
    exit;
}

$review_id = $_POST['review_id'] ?? '';

if($review_id == ""){
    echo json_encode([
        "status" => false,
        "message" => "Invalid review."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$status = deleteRestaurantReview($review_id, $user_id);

if($status){
    echo json_encode([
        "status" => true,
        "message" => "Review deleted successfully."
    ]);
}else{
    echo json_encode([
        "status" => false,
        "message" => "Delete failed."
    ]);
}

?>