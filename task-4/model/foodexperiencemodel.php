<?php

require_once('db.php');


function getAllPosts(){

    $con = getConnection();

    $sql = "SELECT food_experience_posts.*, users.name
            FROM food_experience_posts
            JOIN users ON food_experience_posts.user_id = users.id
            ORDER BY food_experience_posts.id DESC";

    return mysqli_query($con, $sql);
}


function createPost($userid, $title, $content, $posttype, $restaurantid, $menuid){

    $con = getConnection();

    $sql = "INSERT INTO food_experience_posts
    (user_id, title, content, post_type, restaurant_id, menu_item_id)
    VALUES
    ('$userid', '$title', '$content', '$posttype', '$restaurantid', '$menuid')";

    return mysqli_query($con, $sql);
}

# DELETE POST
function deletePost($id){

    $con = getConnection();

    $sql = "DELETE FROM food_experience_posts WHERE id='$id'";

    return mysqli_query($con, $sql);
}

# GET SINGLE POST (FOR EDIT)
function getPostById($id){

    $con = getConnection();

    $sql = "SELECT * FROM food_experience_posts WHERE id='$id'";

    return mysqli_query($con, $sql);
}


function updatePost($id, $title, $content, $posttype, $restaurantid, $menuid){

    $con = getConnection();

    $sql = "UPDATE food_experience_posts
            SET title='$title',
                content='$content',
                post_type='$posttype',
                restaurant_id='$restaurantid',
                menu_item_id='$menuid'
            WHERE id='$id'";

    return mysqli_query($con, $sql);
}

?>