<?php
session_start();

// Session gate
if (!isset($_SESSION['user_id'])) {
    header('Location: ../view/auth/login.php');
    exit;
}

require_once __DIR__ . '/../model/userModel.php';

$action = $_POST['action'] ?? '';

// ── UPDATE PROFILE ────────────────────────────────────────────────────────────
if ($action === 'update_profile' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = (int)$_SESSION['user_id'];
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');
    $errors = [];

    if ($name === '')           $errors[] = 'Full name is required.';
    elseif (strlen($name) < 2)  $errors[] = 'Name must be at least 2 characters.';

    if ($email === '')          $errors[] = 'Email is required.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email.';
    elseif (emailExists($email, $id))                   $errors[] = 'That email is already taken.';

    // Handle profile picture upload
    $picturePath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['profile_picture'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Upload error occurred.';
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime  = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            $allowed = ['image/jpeg', 'image/png'];
            if (!in_array($mime, $allowed)) {
                $errors[] = 'Profile picture must be JPEG or PNG.';
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Profile picture must be under 2MB.';
            } else {
                $ext      = ($mime === 'image/jpeg') ? 'jpg' : 'png';
                $filename = 'profile_' . $id . '_' . uniqid() . '.' . $ext;
                $dest     = __DIR__ . '/../assets/uploads/profiles/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $picturePath = 'assets/uploads/profiles/' . $filename;
                } else {
                    $errors[] = 'Could not save profile picture.';
                }
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        header('Location: ../view/profile/profile.php');
        exit;
    }

    if (updateUserProfile($id, $name, $email, $picturePath)) {
        $_SESSION['name']  = $name; // keep session in sync
        $_SESSION['flash'] = 'Profile updated successfully!';
    } else {
        $_SESSION['flash_error'] = 'Update failed. Please try again.';
    }
    header('Location: ../view/profile/profile.php');
    exit;
}

// ── CHANGE PASSWORD ───────────────────────────────────────────────────────────
if ($action === 'change_password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = (int)$_SESSION['user_id'];
    $current    =      $_POST['current_password']  ?? '';
    $newPass    =      $_POST['new_password']       ?? '';
    $confirm    =      $_POST['confirm_password']   ?? '';
    $errors     = [];

    $user = getUserById($id);

    if ($current === '')            $errors[] = 'Current password is required.';
    elseif (!password_verify($current, $user['password_hash']))
                                    $errors[] = 'Current password is incorrect.';

    if ($newPass === '')            $errors[] = 'New password is required.';
    elseif (strlen($newPass) < 8)   $errors[] = 'New password must be at least 8 characters.';
    elseif ($newPass !== $confirm)  $errors[] = 'New passwords do not match.';

    if (!empty($errors)) {
        $_SESSION['form_errors_pw'] = $errors;
        header('Location: ../view/profile/profile.php');
        exit;
    }

    if (updateUserPassword($id, $newPass)) {
        $_SESSION['flash'] = 'Password changed successfully!';
    } else {
        $_SESSION['flash_error'] = 'Password change failed. Please try again.';
    }
    header('Location: ../view/profile/profile.php');
    exit;
}

header('Location: ../view/profile/profile.php');
exit;
