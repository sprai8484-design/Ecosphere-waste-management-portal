<?php
session_start();
require_once '../config.php';
$error = '';
if (!empty($_SESSION[ADMIN_SESSION])) {
  header('Location: dashboard.php');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $p = trim($_POST['password'] ?? '');
  if ($u && $p) {
    $stmt = getDB()->prepare("SELECT * FROM resell_admin_users WHERE username=:u AND password=SHA2(:p,256) LIMIT 1");
    $stmt->execute([':u' => $u, ':p' => $p]);
    $admin = $stmt->fetch();
    if ($admin) {
      $_SESSION[ADMIN_SESSION] = true;
      $_SESSION['admin_user'] = $admin['username'];
      header('Location: dashboard.php');
      exit;
    } else {
      $error = 'Invalid username or password.';
    }
  } else {
    $error = 'Please fill both fields.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login — Ecosphere Resell</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700&family=DM+Sans:wght@400;600&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --cream: #f5f0e8;
      --warm: #faf8f3;
      --sand: #e8e0d0;
      --terra: #d4745a;
      --gold: #d4a843;
      --soft: #5a5a5a
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: linear-gradient(150deg, #0f2219, var(--forest), var(--moss));
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow: hidden
    }

    body::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px);
      background-size: 36px 36px
    }

    .card {
      position: relative;
      background: var(--warm);
      width: 100%;
      max-width: 400px;
      border-top: 4px solid var(--gold);
      box-shadow: 0 24px 72px rgba(0, 0, 0, .35)
    }

    .card-header {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      padding: 32px 36px;
      text-align: center
    }

    .card-logo {
      width: 52px;
      height: 52px;
      background: rgba(255, 255, 255, .12);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 12px
    }

    .card-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.25rem;
      color: #fff;
      margin-bottom: 4px
    }

    .card-sub {
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .38)
    }

    .card-body {
      padding: 30px 36px
    }

    .fg {
      margin-bottom: 14px
    }

    .f-label {
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--soft);
      display: block;
      margin-bottom: 7px
    }

    .f-inp {
      width: 100%;
      background: var(--cream);
      border: 1.5px solid var(--sand);
      padding: 12px 14px;
      font-size: .9rem;
      color: #1a1a18;
      font-family: 'DM Sans', sans-serif;
      transition: border-color .2s
    }

    .f-inp:focus {
      border-color: var(--leaf);
      outline: none
    }

    .btn-login {
      width: 100%;
      background: var(--forest);
      color: #fff;
      padding: 14px;
      font-weight: 700;
      font-size: .9rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      border: none;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: background .2s;
      margin-top: 4px
    }

    .btn-login:hover {
      background: var(--moss)
    }

    .error {
      background: rgba(212, 116, 90, .07);
      border: 1px solid rgba(212, 116, 90, .2);
      border-left: 2px solid var(--terra);
      padding: 10px 14px;
      font-size: .84rem;
      color: var(--terra);
      margin-bottom: 14px
    }

    .hint {
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .08em;
      color: rgba(90, 90, 90, .45);
      text-align: center;
      margin-top: 14px
    }

    .back {
      display: block;
      text-align: center;
      margin-top: 14px;
      font-size: .8rem;
      color: var(--leaf);
      font-weight: 700;
      letter-spacing: .05em;
      text-transform: uppercase
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="card-header">
      <div class="card-logo">🌿</div>
      <div class="card-title">Resell Admin</div>
      <div class="card-sub">Ecosphere Marketplace Panel</div>
    </div>
    <div class="card-body">
      <?php if ($error): ?><div class="error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="fg"><label class="f-label">Username</label><input class="f-inp" type="text" name="username" placeholder="admin" required></div>
        <div class="fg"><label class="f-label">Password</label><input class="f-inp" type="password" name="password" placeholder="••••••••" required></div>
        <button class="btn-login" type="submit">Sign In →</button>
      </form>
      <div class="hint">Default: admin / admin123</div>
      <a href="../resell.php" class="back">← Back to Marketplace</a>
    </div>
  </div>
</body>

</html>