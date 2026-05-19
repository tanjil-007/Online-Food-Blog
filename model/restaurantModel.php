<?php
require_once __DIR__ . '/db.php';

function getAllRestaurants() {
    $con = getConnection();
    $sql = "SELECT * FROM restaurants ORDER BY created_at DESC";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_close($con);
    return $rows;
}

function getRestaurantById($id) {
    $con = getConnection();
    $sql = "SELECT * FROM restaurants WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row;
}

function addRestaurant($data) {
    $con = getConnection();
    $sql = "INSERT INTO restaurants (name, location, area, short_background, goals) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssss",
        $data['name'], $data['location'], $data['area'],
        $data['short_background'], $data['goals']
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function updateRestaurant($data) {
    $con = getConnection();
    $sql = "UPDATE restaurants SET name=?, location=?, area=?, short_background=?, goals=? WHERE id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "sssssi",
        $data['name'], $data['location'], $data['area'],
        $data['short_background'], $data['goals'], $data['id']
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function deleteRestaurant($id) {
    $con = getConnection();
    // Cascade: delete menu items first
    $sql1 = "DELETE FROM menu_items WHERE restaurant_id = ?";
    $stmt1 = mysqli_prepare($con, $sql1);
    mysqli_stmt_bind_param($stmt1, "i", $id);
    mysqli_stmt_execute($stmt1);

    $sql2 = "DELETE FROM restaurants WHERE id = ?";
    $stmt2 = mysqli_prepare($con, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $id);
    $ok = mysqli_stmt_execute($stmt2);
    mysqli_close($con);
    return $ok;
}

function getRestaurantCount() {
    $con = getConnection();
    $result = mysqli_query($con, "SELECT COUNT(*) as cnt FROM restaurants");
    $row = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $row['cnt'];
}
