<?php

require_once('db.php');


function addComment($post_id, $user_id, $comment)
{
    $con = getConnection();

    $sql = "INSERT INTO food_experience_comments (post_id, user_id, comment, created_at)
            VALUES ('$post_id', '$user_id', '$comment', NOW())";

    return mysqli_query($con, $sql);
}


function getCommentsByPost($post_id)
{
    $con = getConnection();

    $sql = "SELECT c.*, u.name
            FROM food_experience_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = $post_id
            ORDER BY c.created_at DESC";

    return mysqli_query($con, $sql);
}


function deleteComment($id)
{
    $con = getConnection();

    $sql = "DELETE FROM food_experience_comments WHERE id = $id";

    return mysqli_query($con, $sql);
}

?>