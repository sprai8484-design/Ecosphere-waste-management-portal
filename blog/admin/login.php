<?php
session_start();
require_once '../config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = trim($_POST['username'] ?? '');
  $p = trim($_POST['password'] ?? '');
  if ($u && $p) {
    $stmt = getDB()->prepare("SELECT * FROM blog_admin_users WHERE username=:u AND password=SHA2(:p,256) LIMIT 1");
    $stmt->execute([':u' => $u, ':p' => $p]);
    $admin = $stmt->fetch();
    if ($admin) {
      $_SESSION[ADMIN_SESSION_KEY] = true;
      $_SESSION['admin_user']      = $admin['username'];
      header('Location: manage.php');
      exit;
    } else {
      $error = 'Invalid username or password.';
    }
  } else {
    $error = 'Please fill both fields.';
  }
}
if (!empty($_SESSION[ADMIN_SESSION_KEY])) {
  header('Location: manage.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login — Ecosphere Blog</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Lato:wght@400;700&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --sage: #7ab88a;
      --cream: #f7f2e8;
      --warm: #faf8f2;
      --sand: #e8e0d0;
      --terra: #c8593a;
      --gold: #c89b3a;
      --soft: #5a5750
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    body {
      font-family: 'Lato', sans-serif;
      background: linear-gradient(140deg, var(--forest), var(--moss));
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px
    }

    .card {
      background: var(--warm);
      width: 100%;
      max-width: 400px;
      padding: 44px 40px;
      border-top: 4px solid var(--gold)
    }

    .logo {
      text-align: center;
      margin-bottom: 30px
    }

    .logo-icon {
      width: 52px;
      height: 52px;
      background: var(--forest);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 12px
    }

    .logo-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--forest)
    }

    .logo-sub {
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--soft);
      margin-top: 4px
    }

    .fg {
      margin-bottom: 16px
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
      font-size: .92rem;
      color: #1a1a18;
      font-family: 'Lato', sans-serif;
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
      font-family: 'Lato', sans-serif;
      transition: background .2s;
      margin-top: 4px
    }

    .btn-login:hover {
      background: var(--moss)
    }

    .error {
      background: rgba(200, 89, 58, .07);
      border: 1px solid rgba(200, 89, 58, .22);
      border-left: 2px solid var(--terra);
      padding: 10px 14px;
      font-size: .84rem;
      color: var(--terra);
      margin-bottom: 16px
    }

    .hint {
      font-family: 'DM Mono', monospace;
      font-size: .65rem;
      letter-spacing: .08em;
      color: var(--soft);
      text-align: center;
      margin-top: 16px
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
    <div class="logo">
      <div class="logo-icon">🌿</div>
      <div class="logo-title">Ecosphere Blog</div>
      <div class="logo-sub">Admin Editorial Panel</div>
    </div>
    <?php if ($error): ?><div class="error">⚠ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <div class="fg"><label class="f-label">Username</label><input class="f-inp" type="text" name="username" autocomplete="username" required></div>
      <div class="fg"><label class="f-label">Password</label><input class="f-inp" type="password" name="password" autocomplete="current-password" required></div>
      <button class="btn-login" type="submit">Sign In →</button>
    </form>
    <div class="hint">Default: admin / admin123</div>
    <a href="../blog.php" class="back">← Back to Journal</a>
  </div>
</body>

</html>