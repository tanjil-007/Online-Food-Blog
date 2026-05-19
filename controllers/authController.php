<?php
session_start();

require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if($action == 'login' && $_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($email == "" || $password == ""){
        $_SESSION['flash_error'] = "Email and password are required.";
        header("Location: ../index.php");
        exit;
    }

    $conn = getConnection();

    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if($user && password_verify($password, $user['password_hash'])){

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == 'admin'){
            header("Location: ../views/admin/dashboard.php");
            exit;
        }

        if($user['role'] == 'member'){
            header("Location: ../views/restaurant/list.php");
            exit;
        }

        $_SESSION['flash_error'] = "Invalid user role.";
        header("Location: ../index.php");
        exit;

    }else{
        $_SESSION['flash_error'] = "Invalid email or password.";
        header("Location: ../index.php");
        exit;
    }
}

if($action == 'logout'){

    session_destroy();

    header("Location: ../index.php");
    exit;
}

header("Location: ../index.php");
exit;
?>