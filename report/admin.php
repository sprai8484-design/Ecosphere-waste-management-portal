<?php
session_start();
require_once 'db.php'; // Aapka PDO singleton wala db.php

/* ── STEP 1: Admin Session Check ── */
// Aapke purane logic ke hisab se admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php"); // Sahi path check karlein
    exit;
}

/* ── STEP 2: Handle Status Updates (Approved/Solved/Rejected) ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int)$_POST['id'];
    $st = $_POST['action'];

    if ($st === 'approved' || $st === 'rejected') {
        db()->prepare("UPDATE reports SET status = ? WHERE id = ?")->execute([$st, $id]);
    } elseif ($st === 'solved') {
        $solImg = !empty($_FILES['solution_image']['name']) ? uploadImage($_FILES['solution_image']) : null;
        db()->prepare("UPDATE reports SET status = 'solved', solution_image = ? WHERE id = ?")
          ->execute([$solImg, $id]);
    }
    header('Location: admin.php'); exit;
}

$reports = db()->query("SELECT * FROM reports ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecosphere Admin | Waste Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono&family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* ── Modern Admin Layout CSS ── */
        :root {
            --forest: #1a3a2a; --g: #4a8c5c; --sand: #e4ddd0; --warm: #fdfdfb; --r: 12px;
        }
        body { display: flex; margin: 0; font-family: 'Inter', sans-serif; background: var(--warm); }

        /* Sidebar Styling */
        .sidebar { width: 260px; background: white; height: 100vh; position: fixed; border-right: 1px solid var(--sand); }
        .sidebar-brand { padding: 30px; font-family: 'Playfair Display'; color: var(--forest); font-size: 1.5rem; }
        .nav-link { padding: 15px 30px; text-decoration: none; color: #555; display: block; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: #f5f0e8; color: var(--forest); border-left: 4px solid var(--g); }

        /* Main Content */
        .main-content { margin-left: 260px; width: 100%; padding: 40px; }
        .report-card { background: white; border-radius: var(--r); padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid var(--sand); display: flex; gap: 20px; }
        .report-img { width: 120px; height: 120px; object-fit: cover; border-radius: 8px; cursor: pointer; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; text-transform: uppercase; font-weight: bold; }
        .status-pending { background: #fff4e5; color: #b7791f; }
        .status-approved { background: #e6fffa; color: #2c7a7b; }
        
        .btn { border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; }
        .btn-approve { background: var(--g); color: white; }
        .btn-reject { background: #f7fafc; border: 1px solid var(--sand); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">🌿 Ecosphere</div>
        <nav>
            <a href="admin.php" class="nav-link active">📋 Waste Reports</a>
            <a href="resell.php" class="nav-link">💰 Resell Market</a>
            <a href="reuse.php" class="nav-link">🔄 Reuse Guides</a>
            <a href="recycle.php" class="nav-link">♻️ Recycle Blog</a>
            <a href="../../auth/logout.php" class="nav-link" style="color: #e74c3c; margin-top: 50px;">Logout</a>
        </nav>
    </div>

    <div class="main-content">
        <h1 style="font-family:'Playfair Display'; color:var(--forest)">Waste Management Admin</h1>
        <p style="color: #888; margin-bottom: 30px;">Manage community reports for cleaning and waste collection.</p>

        <?php foreach ($reports as $r): ?>
        <div class="report-card">
            <?php if($r['image']): ?>
                <img src="uploads/<?= h($r['image']) ?>" class="report-img" onclick="window.open(this.src)">
            <?php endif; ?>
            
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between;">
                    <h3 style="margin: 0; color: var(--forest);"><?= h($r['location']) ?></h3>
                    <span class="badge status-<?= $r['status'] ?>"><?= $r['status'] ?></span>
                </div>
                <p style="color: #555; font-size: 0.9rem; margin: 10px 0;"><?= h($r['description']) ?></p>
                <small style="color: #aaa;">By: <?= h($r['reporter']) ?> • <?= $r['created_at'] ?></small>
                
                <div style="margin-top: 15px; display: flex; gap: 10px;">
                    <?php if($r['status'] === 'pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button name="action" value="approved" class="btn btn-approve">Approve</button>
                            <button name="action" value="rejected" class="btn btn-reject">Reject</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</body>
</html>