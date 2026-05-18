<?php
session_start();
require_once __DIR__ . '/../model/userModel.php';

// Secret admin access code
define('ADMIN_ACCESS_CODE', '19191');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ── LOGIN ────────────────────────────────────────────────────────────────────
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email']    ?? '');
    $password   =      $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);

    if ($email === '' || $password === '') {
        $_SESSION['flash_error'] = 'Email and password are required.';
        $_SESSION['form_data']   = ['email' => $email];
        header('Location: ../view/auth/login.php');
        exit;
    }

    $user = getUserByEmail($email);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['flash']   = 'Welcome back, ' . $user['name'] . '!';

        // Remember Me: store hashed token in DB + cookie (30 days)
        if ($rememberMe) {
            $token = bin2hex(random_bytes(32));
            setRememberToken($user['id'], $token);
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
        }

        if ($user['role'] === 'admin') {
            header('Location: ../view/admin/dashboard.php');
        } else {
            header('Location: ../view/browse/restaurants.php');
        }
        exit;
    } else {
        $_SESSION['flash_error'] = 'Incorrect email or password.';
        $_SESSION['form_data']   = ['email' => $email];
        header('Location: ../view/auth/login.php');
        exit;
    }
}

// ── REGISTER ─────────────────────────────────────────────────────────────────
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name']     ?? '');
    $email       = trim($_POST['email']    ?? '');
    $password    =      $_POST['password'] ?? '';
    $confirm     =      $_POST['confirm']  ?? '';
    $role        =      $_POST['role']     ?? 'member';
    $accessCode  = trim($_POST['admin_access_code'] ?? '');

    $errors = [];

    // Sanitise role
    if (!in_array($role, ['admin', 'member'])) $role = 'member';

    // Field validation
    if ($name === '')              $errors[] = 'Full name is required.';
    elseif (strlen($name) < 2)    $errors[] = 'Name must be at least 2 characters.';

    if ($email === '')             $errors[] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    elseif (emailExists($email))   $errors[] = 'That email is already registered.';

    if ($password === '')          $errors[] = 'Password is required.';
    elseif (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
    elseif ($password !== $confirm) $errors[] = 'Passwords do not match.';

    // Admin access code check
    if ($role === 'admin') {
        if ($accessCode === '') {
            $errors[] = 'Admin access code is required for admin registration.';
        } elseif ($accessCode !== ADMIN_ACCESS_CODE) {
            $errors[] = 'Invalid admin access code.';
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data']   = compact('name', 'email', 'role');
        header('Location: ../view/auth/register.php');
        exit;
    }

    if (registerUser(compact('name', 'email', 'password', 'role'))) {
        $_SESSION['flash'] = 'Registration successful! Please log in.';
        header('Location: ../view/auth/login.php');
    } else {
        $_SESSION['flash_error'] = 'Registration failed. Please try again.';
        $_SESSION['form_data']   = compact('name', 'email', 'role');
        header('Location: ../view/auth/register.php');
    }
    exit;
}

// ── LOGOUT ───────────────────────────────────────────────────────────────────
if ($action === 'logout') {
    // Clear remember token from DB
    if (isset($_SESSION['user_id'])) {
        clearRememberToken($_SESSION['user_id']);
    }
    // Expire cookie
    setcookie('remember_token', '', time() - 3600, '/');
    $_SESSION = [];
    session_destroy();
    header('Location: ../view/auth/login.php');
    exit;
}

// Fallback
header('Location: ../view/auth/login.php');
exit;
