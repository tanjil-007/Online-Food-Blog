<?php

session_start();
require_once('../model/foodexperiencemodel.php');

$posts = getAllPosts();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Experience Page</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background: #fff;
            padding: 15px;
            margin: 15px auto;
            width: 60%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .post {
            background: #fff;
            padding: 15px;
            margin: 15px auto;
            width: 60%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h3 {
            color: #222;
            margin-bottom: 5px;
        }

        p {
            color: #555;
        }

        small {
            color: #888;
        }

        hr {
            margin: 20px 0;
        }

        a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .update-form {
            margin-top: 10px;
        }
    </style>

</head>
<body>

<h1>Food Experience Posts</h1>

<!-- CREATE FORM -->
<form method="post" action="../controller/FoodexperienceController.php">

    <input type="text" name="title" placeholder="Title">

    <input type="text" name="content" placeholder="Content">

    <select name="posttype">
        <option value="restaurant">Restaurant</option>
        <option value="food">Food</option>
        <option value="both">Both</option>
    </select>

    <input type="number" name="restaurantid" placeholder="Restaurant ID">

    <input type="number" name="menuid" placeholder="Menu ID">

    <button name="submit">Post</button>

</form>

<!-- SHOW POSTS -->
<?php while($row = mysqli_fetch_assoc($posts)){ ?>

    <div class="post">

        <h3><?= $row['title'] ?></h3>
        <p><?= $row['content'] ?></p>
        <small>By: <?= $row['name'] ?></small>

        <br><br>

        <!-- DELETE -->
        <a href="../controller/FoodexperienceController.php?deleteid=<?= $row['id'] ?>">
            Delete
        </a>

        <!-- UPDATE FORM -->
        <form class="update-form" method="post" action="../controller/FoodexperienceController.php">

            <input type="hidden" name="id" value="<?= $row['id'] ?>">

            <input type="text" name="title" value="<?= $row['title'] ?>">

            <input type="text" name="content" value="<?= $row['content'] ?>">

            <select name="posttype">
                <option value="restaurant">Restaurant</option>
                <option value="food">Food</option>
                <option value="both">Both</option>
            </select>

            <input type="number" name="restaurantid" value="<?= $row['restaurant_id'] ?>">

            <input type="number" name="menuid" value="<?= $row['menu_item_id'] ?>">

            <button name="update">Update</button>

        </form>

    </div>

<?php } ?>

</body>
</html>