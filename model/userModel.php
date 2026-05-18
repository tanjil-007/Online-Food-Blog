<?php
require_once __DIR__ . '/db.php';

function getUserByEmail($email) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}

function getUserById($id) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}

function registerUser($data) {
    $con  = getConnection();
    $hash = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($con,
        "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, 'ssss',
        $data['name'], $data['email'], $hash, $data['role']
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function updateUserProfile($id, $name, $email, $picturePath = null) {
    $con = getConnection();
    if ($picturePath) {
        $stmt = mysqli_prepare($con,
            "UPDATE users SET name=?, email=?, profile_picture=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $email, $picturePath, $id);
    } else {
        $stmt = mysqli_prepare($con,
            "UPDATE users SET name=?, email=? WHERE id=?"
        );
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $email, $id);
    }
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function updateUserPassword($id, $newPassword) {
    $con  = getConnection();
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($con, "UPDATE users SET password_hash=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $hash, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_close($con);
    return $ok;
}

function setRememberToken($userId, $token) {
    $con  = getConnection();
    $hash = hash('sha256', $token);
    $stmt = mysqli_prepare($con, "UPDATE users SET remember_token=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'si', $hash, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_close($con);
}

function getUserByRememberToken($token) {
    $hash = hash('sha256', $token);
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE remember_token=? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $hash);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);
    mysqli_close($con);
    return $user;
}

function clearRememberToken($userId) {
    $con  = getConnection();
    $stmt = mysqli_prepare($con, "UPDATE users SET remember_token=NULL WHERE id=?");
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_close($con);
}

function emailExists($email, $excludeId = null) {
    $con = getConnection();
    if ($excludeId) {
        $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE email=? AND id!=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 'si', $email, $excludeId);
    } else {
        $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $email);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $exists = mysqli_num_rows($result) > 0;
    mysqli_close($con);
    return $exists;
}
