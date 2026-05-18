<?php
require_once __DIR__ . '/db.php';

function getAllRestaurants() {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM restaurants ORDER BY created_at DESC");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows   = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($con);
    return $rows;
}

function getRestaurantById($id) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM restaurants WHERE id=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row;
}

function getMenuItemsByRestaurant($restaurant_id) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM menu_items WHERE restaurant_id=? ORDER BY created_at DESC");
    mysqli_stmt_bind_param($stmt, 'i', $restaurant_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows   = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($con);
    return $rows;
}

function getMenuItemById($id) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con,
        "SELECT m.*, r.name AS restaurant_name 
         FROM menu_items m 
         JOIN restaurants r ON m.restaurant_id = r.id 
         WHERE m.id=? LIMIT 1"
    );
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row;
}
