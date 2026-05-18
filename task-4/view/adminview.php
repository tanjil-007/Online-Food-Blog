<?php
session_start();


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

require_once('../model/userModel.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Register</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            margin: 0;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1a252f;
        }

        .note {
            text-align: center;
            font-size: 12px;
            color: gray;
        }
    </style>

</head>
<body>

<div class="container">

    <h2>Create Admin Account</h2>

    <form method="post" action="../controller/authController.php" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Admin Name" required>

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="file" name="profilepicture">

        <button name="admin_register">Create Admin</button>

    </form>

    <p class="note">Only existing admin can create new admin accounts</p>

</div>

</body>
</html>