<?php
// admin/login.php
session_start();
require_once '../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :u AND password = SHA2(:p, 256) LIMIT 1");
        $stmt->execute([':u' => $username, ':p' => $password]);
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION[ADMIN_SESSION_KEY] = true;
            $_SESSION['admin_user']      = $admin['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}

// Redirect if already logged in
if (!empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: index.php'); exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Login — Ecosphere</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'DM Sans',sans-serif;background:linear-gradient(135deg,#1a3a2a,#2d5a3d);
  min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:18px;padding:44px 40px;width:100%;max-width:400px;
  box-shadow:0 24px 64px rgba(0,0,0,.25)}
.logo{text-align:center;margin-bottom:28px}
.logo-icon{width:52px;height:52px;background:#1a3a2a;border-radius:50%;
  display:inline-flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:10px}
.logo-title{font-family:'Playfair Display',serif;font-size:1.4rem;color:#1a3a2a}
.logo-sub{font-size:.82rem;color:#5a5a5a;margin-top:3px}
.form-group{margin-bottom:16px}
.form-group label{display:block;font-size:.78rem;font-weight:600;color:#1a3a2a;
  text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px}
.form-group input{width:100%;background:#f5f0e8;border:1.5px solid rgba(74,140,92,.18);
  border-radius:8px;padding:12px 16px;font-size:.9rem;font-family:'DM Sans',sans-serif;
  color:#2c2c2c;transition:border-color .2s;outline:none}
.form-group input:focus{border-color:#4a8c5c;box-shadow:0 0 0 3px rgba(74,140,92,.1)}
.btn-login{width:100%;background:linear-gradient(135deg,#1a3a2a,#2d5a3d);color:#fff;
  padding:13px;border-radius:50px;font-weight:600;font-size:.95rem;border:none;cursor:pointer;
  font-family:'DM Sans',sans-serif;transition:opacity .2s;margin-top:4px}
.btn-login:hover{opacity:.9}
.error{background:rgba(212,116,90,.09);border:1px solid rgba(212,116,90,.25);
  border-radius:8px;padding:10px 14px;font-size:.84rem;color:#d4745a;margin-bottom:16px}
.hint{font-size:.75rem;color:#999;text-align:center;margin-top:16px}
.back-link{display:block;text-align:center;margin-top:14px;font-size:.82rem;
  color:#4a8c5c;text-decoration:none}
</style>
</head>
<body>
<div class="card">
  <div class="logo">
    <div class="logo-icon">🌿</div>
    <div class="logo-title">Ecosphere Admin</div>
    <div class="logo-sub">Recycle Module Dashboard</div>
  </div>
  <?php if($error): ?><div class="error">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" placeholder="admin" autocomplete="username" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
    </div>
    <button class="btn-login" type="submit">Sign In →</button>
  </form>
  <div class="hint">Default: admin / admin123</div>
  <a class="back-link" href="../recycle.php">← Back to Ecosphere</a>
</div>
</body>
</html>
