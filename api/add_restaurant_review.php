<?php

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../models/restaurantReviewModel.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'member'){
    echo json_encode([
        "status" => false,
        "message" => "Only members can post review."
    ]);
    exit;
}

$restaurant_id = $_POST['restaurant_id'] ?? '';
$rating = $_POST['rating'] ?? '';
$comment = trim($_POST['comment'] ?? '');

if($restaurant_id == "" || $rating == "" || $comment == ""){
    echo json_encode([
        "status" => false,
        "message" => "All fields are required."
    ]);
    exit;
}

if(strlen($comment) > 500){
    echo json_encode([
        "status" => false,
        "message" => "Comment must be within 500 characters."
    ]);
    exit;
}

if($rating < 1 || $rating > 5){
    echo json_encode([
        "status" => false,
        "message" => "Rating must be between 1 and 5."
    ]);
    exit;
}

if(!checkRestaurantExists($restaurant_id)){
    echo json_encode([
        "status" => false,
        "message" => "Invalid restaurant."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$comment = htmlspecialchars($comment);

$status = addRestaurantReview($restaurant_id, $user_id, $rating, $comment);

if($status){
    echo json_encode([
        "status" => true,
        "message" => "Review posted successfully."
    ]);
}else{
    echo json_encode([
        "status" => false,
        "message" => "Database error."
    ]);
}

?>