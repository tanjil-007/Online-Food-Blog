<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Experience Page</title>

    <style>

        body{
            background-color: lightblue;
            font-family: Arial, sans-serif;
        }

        form{
            width: 400px;
            margin: 50px auto;
        }

        fieldset{
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #333;
        }

        legend{
            font-size: 22px;
            font-weight: bold;
        }

        input, select{
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        input[type="submit"]{
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover{
            background-color: darkblue;
        }

    </style>

</head>

<body>

    <form method="post" action="../controller/FoodexperienceController.php" enctype="multipart/form-data">

        <fieldset>

            <legend>Food Experience Page</legend>

            ID:
            <input type="number" name="id" value="">

            User ID:
            <input type="number" name="userid" value="">

            Title:
            <input type="text" name="title" value="">

            Content:
            <input type="text" name="content" value="">

            Post Type:
            <select name="posttype">

                <option value="restaurant">
                    Restaurant
                </option>

                <option value="food">
                    Food
                </option>

                <option value="both">
                    Both
                </option>

            </select>

            Restaurant ID:
            <input type="number" name="restaurantid" value="">

            Menu ID:
            <input type="number" name="menuid" value="">

            Created At:
            <input type="time" name="createdat" value="">

            Updated At:
            <input type="time" name="updatedat" value="">

            <input type="submit" name="submit" value="Submit">

        </fieldset>

    </form>

</body>
</html>