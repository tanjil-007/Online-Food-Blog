<?php
session_start();
require_once __DIR__ . '/../model/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── LOGIN ────────────────────────────────────────────────────────────────────
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email       = trim($_POST['email']       ?? '');
    $password    = $_POST['password']          ?? '';

   

    // Basic field check
    if ($email === '' || $password === '') {
        $_SESSION['flash_error'] = 'Email and password are required.';
        $_SESSION['form_data']   = ['email' => $email];
        header('Location: ../index.php');
        exit;
    }

    // Look up user
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT id, name, email, password_hash, role FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_close($con);

    if ($user && password_verify($password, $user['password_hash'])) {
        // Only admin can log in here
        if ($user['role'] !== 'admin') {
            $_SESSION['flash_error'] = 'This portal is for administrators only.';
            $_SESSION['form_data']   = ['email' => $email];
            header('Location: ../index.php');
            exit;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['flash']   = 'Welcome back, ' . $user['name'] . '!';
        header('Location: ../view/admin/dashboard.php');
        exit;
    } else {
        $_SESSION['flash_error'] = 'Incorrect email or password. Please try again.';
        $_SESSION['form_data']   = ['email' => $email];
        header('Location: ../index.php');
        exit;
    }
}

// ── LOGOUT ───────────────────────────────────────────────────────────────────
if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: ../index.php');
    exit;
}

// Fallback
header('Location: ../index.php');
exit;
