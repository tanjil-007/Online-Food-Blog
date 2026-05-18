<?php

session_start();

require_once('../model/foodExperienceModel.php');


if(isset($_POST['submit'])){

    $id = $_POST['id'];
    $userid = $_POST['userid'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if($id == ""){
        echo "ID cannot be empty";
    }

    else if($userid == ""){
        echo "User ID cannot be empty";
    }

    else if($title == ""){
        echo "Title cannot be empty";
    }

    else if($content == ""){
        echo "Content cannot be empty";
    }

    else{
        echo "Validation Passed";
    }

}



?>