<?php

session_start();
require_once('../model/foodexperiencemodel.php');

$user_id = $_SESSION['user_id'] ?? 1; // demo user


if (isset($_POST['add_comment'])) {

    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    addComment($post_id, $user_id, $comment);

    header("Location: ../view/foodexperience.php");
}


if (isset($_GET['delete_comment'])) {

    $id = $_GET['delete_comment'];

    deleteComment($id);

    header("Location: ../view/foodexperience.php");
}

?>