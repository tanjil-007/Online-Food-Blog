<?php
require_once __DIR__ . '/../config/db.php';

function getReviewsByMenuItem($menu_item_id) {
    $con = getConnection();

    $sql = "SELECT reviews.*, users.name AS member_name
            FROM reviews
            JOIN users ON reviews.user_id = users.id
            WHERE reviews.menu_item_id = ?
            ORDER BY reviews.created_at DESC";

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $menu_item_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $rows = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    mysqli_close($con);
    return $rows;
}

function addReview($menu_item_id, $user_id, $comment) {
    $con = getConnection();

    $sql = "INSERT INTO reviews (menu_item_id, user_id, comment) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $menu_item_id, $user_id, $comment);

    $ok = mysqli_stmt_execute($stmt);
    $insert_id = mysqli_insert_id($con);

    mysqli_close($con);

    if ($ok) {
        return $insert_id;
    }
    return false;
}

function deleteOwnReview($review_id, $user_id) {
    $con = getConnection();

    $sql = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);

    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);

    mysqli_close($con);

    if ($ok && $affected > 0) {
        return true;
    }
    return false;
}

function menuItemExists($menu_item_id) {
    $con = getConnection();

    $sql = "SELECT id FROM menu_items WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $menu_item_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;

    mysqli_close($con);
    return $exists;
}
?>
