<?php

require_once __DIR__ . '/../config/db.php';

function getRestaurantReviews($restaurant_id){

    $conn = getConnection();

    $sql = "SELECT restaurant_reviews.*, users.name 
            FROM restaurant_reviews 
            JOIN users ON restaurant_reviews.user_id = users.id
            WHERE restaurant_reviews.restaurant_id = ?
            ORDER BY restaurant_reviews.created_at DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $reviews = [];

    while($row = mysqli_fetch_assoc($result)){
        $reviews[] = $row;
    }

    return $reviews;
}


function addRestaurantReview($restaurant_id, $user_id, $rating, $comment){

    $conn = getConnection();

    $sql = "INSERT INTO restaurant_reviews (restaurant_id, user_id, rating, comment)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $restaurant_id, $user_id, $rating, $comment);

    return mysqli_stmt_execute($stmt);
}


function deleteRestaurantReview($review_id, $user_id){

    $conn = getConnection();

    $sql = "DELETE FROM restaurant_reviews 
            WHERE id = ? AND user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);

    return mysqli_stmt_execute($stmt);
}


function checkRestaurantExists($restaurant_id){

    $conn = getConnection();

    $sql = "SELECT id FROM restaurants WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}

?>