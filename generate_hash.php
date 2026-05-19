<?php
/**
 * generate_hash.php
 * Open this once in browser: http://localhost/task2/generate_hash.php
 * It generates the correct bcrypt hash for "12345678" on YOUR PHP version
 * and updates the admin account in the database.
 * Delete this file after use.
 */
require_once __DIR__ . '/model/db.php';

$password = '12345678';
$email    = 'tanjil@gmail.com';
$hash     = password_hash($password, PASSWORD_DEFAULT);

$con  = getConnection();

// Ensure admin row exists first
$check = mysqli_query($con, "SELECT id FROM users WHERE email = 'tanjil@gmail.com' LIMIT 1");
if (mysqli_num_rows($check) === 0) {
    $ins = mysqli_prepare($con, "INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'admin')");
    $name = 'Tanjil';
    mysqli_stmt_bind_param($ins, 'sss', $name, $email, $hash);
    mysqli_stmt_execute($ins);
} else {
    $stmt = mysqli_prepare($con, "UPDATE users SET password_hash = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, 'ss', $hash, $email);
    mysqli_stmt_execute($stmt);
}
mysqli_close($con);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8">
<title>Hash Generator</title>
<style>
  body { font-family: Segoe UI, sans-serif; background:#f5f5f0; display:flex; align-items:center; justify-content:center; min-height:100vh; }
  .box { background:#fff; border-radius:12px; padding:2.5rem; max-width:500px; width:90%; box-shadow:0 4px 20px rgba(0,0,0,.1); text-align:center; }
  .ok { color:#155724; background:#d4edda; border:1px solid #c3e6cb; padding:.8rem 1.2rem; border-radius:6px; margin:1rem 0; }
  code { background:#f0f0f0; padding:.2rem .5rem; border-radius:4px; font-size:.9rem; }
  a.btn { display:inline-block; margin-top:1.2rem; padding:.6rem 1.4rem; background:#e94560; color:#fff; border-radius:6px; text-decoration:none; font-weight:600; }
</style>
</head><body>
<div class="box">
  <div style="font-size:3rem;">✅</div>
  <h2 style="margin:.8rem 0; color:#1a1a2e;">Admin Password Set!</h2>
  <div class="ok">
    The database has been updated successfully.<br>
    <strong>Email:</strong> tanjil@gmail.com<br>
    <strong>Password:</strong> 12345678
  </div>
  <p style="color:#666; font-size:.9rem; margin-top:.8rem;">
    Generated hash:<br>
    <code style="word-break:break-all;"><?= htmlspecialchars($hash) ?></code>
  </p>
  <p style="color:#e94560; font-size:.85rem; margin-top:1rem;">
    ⚠️ Please delete <strong>generate_hash.php</strong> from your server now.
  </p>
  <a class="btn" href="index.php">← Go to Login</a>
</div>
</body></html>
