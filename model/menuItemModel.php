<?php
require_once __DIR__ . '/db.php';

function getMenuItemsByRestaurant($restaurant_id) {
    $con = getConnection();
    $sql = "SELECT * FROM menu_items WHERE restaurant_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($con);
    return $rows;
}

function getMenuItemById($id) {
    $con = getConnection();
    $sql = "SELECT m.*, r.name as restaurant_name FROM menu_items m 
            JOIN restaurants r ON m.restaurant_id = r.id 
            WHERE m.id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row;
}

function addMenuItem($data, $image_path) {
    $con = getConnection();
    $sql = "INSERT INTO menu_items (restaurant_id, name, description, price, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "issds",
        $data['restaurant_id'], $data['name'],
        $data['description'], $data['price'], $image_path
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function updateMenuItem($data, $image_path) {
    $con = getConnection();
    if ($image_path) {
        $sql = "UPDATE menu_items SET name=?, description=?, price=?, image_path=? WHERE id=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssdsi",
            $data['name'], $data['description'], $data['price'], $image_path, $data['id']
        );
    } else {
        $sql = "UPDATE menu_items SET name=?, description=?, price=? WHERE id=?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "ssdi",
            $data['name'], $data['description'], $data['price'], $data['id']
        );
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function deleteMenuItem($id) {
    $con = getConnection();
    $sql = "DELETE FROM menu_items WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function getMenuItemCount() {
    $con = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM menu_items");
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}

function getTotalReviewCount() {
    $con = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM reviews");
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}

function getTotalFoodExpCount() {
    $con = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM food_experience_posts");
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}
