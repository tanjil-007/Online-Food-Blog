<?php

require_once('../../model/foodexperiencemodel.php');

$id = $_GET['id'];

$post = getPostById($id);

?>

<!DOCTYPE html>

<html>

<head>

    <title>Edit Post</title>

</head>

<body>

<h1>Edit Post</h1>

<form
method="POST"
action="../../controller/FoodexperienceController.php">

    <input
    type="hidden"
    name="id"
    value="<?php echo $post['id']; ?>">

    <input
    type="text"
    name="title"
    value="<?php echo $post['title']; ?>">

    <br><br>

    <textarea
    name="content"><?php echo $post['content']; ?>
    </textarea>

    <br><br>

    <select name="post_type">

        <option value="food">
            Food
        </option>

        <option value="restaurant">
            Restaurant
        </option>

        <option value="both">
            Both
        </option>

    </select>

    <br><br>

    <button
    type="submit"
    name="updatePost">

        Update

    </button>

</form>

</body>
</html>