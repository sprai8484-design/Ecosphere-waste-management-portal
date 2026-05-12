<?php
session_start();

// Database connection manually because folders are different
function getLocalDB() {
    $host = 'localhost';
    $db   = 'ecosphere_db'; // Aapka shared database name
    $user = 'root';
    $pass = '';
    try {
        return new PDO("mysql:host=$host;dbname=$db", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        return null;
    }
}

// ─── Auth ─────────────────────────────────────────────────────────────────────
$authed = false;
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') $authed = true;
if (isset($_SESSION['admin_user']) || isset($_SESSION['admin_logged_in'])) $authed = true;

if (!$authed) {
    header('Location: login.php');
    exit;
}

$adminUser = $_SESSION['admin_user'] ?? $_SESSION['admin_name'] ?? 'Super Admin';

// ─── Initialize stats array ───────────────────────────────────────────────────
$stats = [
    'resell'  => ['pending' => 0, 'approved' => 0, 'sold' => 0, 'rejected' => 0, 'revenue' => 0, 'total' => 0],
    'recycle' => ['total' => 0, 'pending' => 0, 'centers' => 0, 'completed' => 0],
    'reuse'   => ['pending' => 0, 'approved' => 0, 'rejected' => 0],
    'blog'    => ['total' => 0, 'published' => 0, 'draft' => 0],
];

// ─── Fetch Data (Exact same logic as Resell Dashboard) ───────────────────────
$pdo = getLocalDB();

if ($pdo) {
    try {
        // 1. Resell Module Data (Copied from your dashboard.php logic)
        $resellData = $pdo->query("SELECT
            (SELECT COUNT(*) FROM resell_products WHERE status='Pending') AS pending,
            (SELECT COUNT(*) FROM resell_products WHERE status='Approved') AS approved,
            (SELECT COUNT(*) FROM resell_products WHERE status='Sold') AS sold,
            (SELECT COUNT(*) FROM resell_products WHERE status='Rejected') AS rejected,
            (SELECT COUNT(*) FROM resell_products) AS total,
            (SELECT COALESCE(SUM(total_price),0) FROM resell_transactions WHERE payment_status='Success') AS revenue
        ")->fetch(PDO::FETCH_ASSOC);
        
        if ($resellData) $stats['resell'] = $resellData;

        // 2. Recycle Module Data (Auto-fetch from recycle tables)
$recycleData = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM recycle_requests) AS total,
    (SELECT COUNT(*) FROM recycle_requests WHERE status = 1) AS pending,
    (SELECT COUNT(*) FROM recycle_centers WHERE is_active = 1) AS centers,
    (SELECT COUNT(*) FROM recycle_requests WHERE status >= 4) AS completed
")->fetch(PDO::FETCH_ASSOC);

if ($recycleData) {
    $stats['recycle'] = [
        'total'     => (int)$recycleData['total'],
        'pending'   => (int)$recycleData['pending'],
        'centers'   => (int)$recycleData['centers'],
        'completed' => (int)$recycleData['completed']
    ];
}

        // 3. Reuse Module Data
        // 3. Reuse Module Data (Database settings ke hisaab se updated)
$reuseData = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 0) AS pending,
    (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 1) AS approved,
    (SELECT COUNT(*) FROM reuse_ideas WHERE is_approved = 2) AS rejected
")->fetch(PDO::FETCH_ASSOC);

if ($reuseData) {
    $stats['reuse'] = $reuseData;
}

    } catch (Exception $e) {
        error_log("Query Error: " . $e->getMessage());
    }
}
// 4. Blog / Journal Module Data (Auto-fetch from 'blogs' table)
$blogQuery = $pdo->query("SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN status = 'published' OR status = 'approved' THEN 1 ELSE 0 END) AS published,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS draft
    FROM blogs");

$blogData = $blogQuery->fetch(PDO::FETCH_ASSOC);

if ($blogData) {
    $stats['blog'] = [
        'total'     => (int)$blogData['total'],
        'published' => (int)$blogData['published'],
        'draft'     => (int)$blogData['draft']
    ];
}

// Helper: format INR
function fmtINR($n): string {
    $val = (float)$n;
    if ($val >= 100000) return '₹' . number_format($val/100000, 1) . 'L';
    if ($val >= 1000)   return '₹' . number_format($val/1000, 1) . 'K';
    return '₹' . number_format($val, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Ecosphere · Admin Hub</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ── Reset & Tokens ──────────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --forest:#1a3a2a;--moss:#2d5a3d;--leaf:#4a8c5c;
  --sage:#7ab88a;--mint:#a8d5b5;--cream:#f5f0e8;
  --warm:#faf8f3;--sand:#e8e0d0;--parchment:#ede6d6;
  --terra:#d4745a;--gold:#d4a843;--amber:#e09830;
  --charcoal:#2c2c2c;--soft:#5a5a5a;
  --r:14px;--shadow:0 4px 24px rgba(26,58,42,.10)
}
body{font-family:'DM Sans',sans-serif;background:var(--warm);color:var(--charcoal);
     display:grid;grid-template-columns:240px 1fr;min-height:100vh;overflow-x:hidden}
a{text-decoration:none;color:inherit}
button{cursor:pointer;font-family:inherit;border:none;outline:none}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:var(--sage);border-radius:2px}

/* ── Sidebar ─────────────────────────────────────────────────────────── */
.sidebar{
  background:var(--forest);
  display:flex;flex-direction:column;
  position:sticky;top:0;height:100vh;overflow-y:auto;
}
.sb-brand{padding:24px 20px 16px;border-bottom:1px solid rgba(255,255,255,.07)}
.sb-logo{
  font-family:'Playfair Display',serif;font-size:1.1rem;color:#fff;
  display:flex;align-items:center;gap:10px
}
.sb-logo-icon{
  width:30px;height:30px;border-radius:50%;
  background:rgba(255,255,255,.12);
  display:flex;align-items:center;justify-content:center;font-size:.9rem
}
.sb-sub{font-size:.62rem;color:rgba(255,255,255,.3);letter-spacing:.1em;
        text-transform:uppercase;margin-top:5px;padding-left:40px}
.sb-nav{flex:1;padding:18px 0}
.sb-section{
  font-size:.6rem;font-weight:700;letter-spacing:.13em;text-transform:uppercase;
  color:rgba(255,255,255,.28);padding:12px 20px 6px
}
.sb-link{
  display:flex;align-items:center;gap:10px;
  padding:10px 20px;font-size:.84rem;
  color:rgba(255,255,255,.6);
  border-left:3px solid transparent;
  transition:all .2s
}
.sb-link:hover,.sb-link.active{
  background:rgba(255,255,255,.07);color:#fff;border-left-color:var(--sage)
}
.sb-icon{width:20px;text-align:center;font-size:.95rem}
.sb-badge{
  margin-left:auto;background:var(--terra);color:#fff;
  padding:1px 8px;font-size:.58rem;font-family:'DM Mono',monospace;border-radius:50px
}
.sb-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.07)}
.sb-user{font-size:.78rem;color:rgba(255,255,255,.4)}
.sb-logout{
  display:inline-block;margin-top:6px;font-size:.74rem;
  color:var(--mint);opacity:.7;transition:opacity .2s
}
.sb-logout:hover{opacity:1}

/* ── Main ────────────────────────────────────────────────────────────── */
.main{display:flex;flex-direction:column;min-height:100vh;overflow-y:auto}

/* Topbar */
.topbar{
  background:#fff;border-bottom:1px solid var(--sand);
  padding:0 28px;height:58px;
  display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:10;box-shadow:var(--shadow)
}
.tb-title{font-family:'Playfair Display',serif;font-size:1.05rem;color:var(--forest)}
.tb-right{display:flex;align-items:center;gap:12px}
.tb-pill{
  background:rgba(74,140,92,.1);color:var(--leaf);
  padding:4px 14px;border-radius:50px;
  font-size:.72rem;font-family:'DM Mono',monospace;letter-spacing:.06em;text-transform:uppercase
}
.tb-time{font-size:.72rem;color:var(--soft);font-family:'DM Mono',monospace}

/* Page body */
.page{padding:28px}

/* ── Hero greeting ───────────────────────────────────────────────────── */
.hero{
  background:linear-gradient(135deg,var(--forest) 0%,var(--moss) 60%,#3a6b4a 100%);
  border-radius:var(--r);padding:28px 32px;margin-bottom:28px;
  display:flex;justify-content:space-between;align-items:center;
  box-shadow:0 8px 32px rgba(26,58,42,.25);overflow:hidden;position:relative;
}
.hero::before{
  content:'';position:absolute;right:-40px;top:-40px;
  width:200px;height:200px;border-radius:50%;
  background:rgba(255,255,255,.04);pointer-events:none
}
.hero::after{
  content:'';position:absolute;right:60px;bottom:-60px;
  width:140px;height:140px;border-radius:50%;
  background:rgba(255,255,255,.03);pointer-events:none
}
.hero-text h1{
  font-family:'Playfair Display',serif;font-size:1.5rem;
  color:#fff;margin-bottom:6px
}
.hero-text p{font-size:.84rem;color:rgba(255,255,255,.55);max-width:380px}
.hero-badge{
  background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);
  border-radius:50px;padding:8px 20px;
  font-size:.75rem;color:rgba(255,255,255,.8);
  font-family:'DM Mono',monospace;letter-spacing:.06em;white-space:nowrap
}

/* ── Module Cards Grid ───────────────────────────────────────────────── */
.modules-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
  gap:20px;margin-bottom:32px
}

.module-card{
  background:#fff;border-radius:var(--r);
  border:1px solid rgba(74,140,92,.09);
  box-shadow:var(--shadow);
  overflow:hidden;
  transition:transform .25s,box-shadow .25s;
  display:flex;flex-direction:column
}
.module-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(26,58,42,.14)}

.mc-header{
  padding:20px 22px 16px;
  border-bottom:1px solid rgba(74,140,92,.07);
  display:flex;align-items:flex-start;justify-content:space-between
}
.mc-icon{
  width:44px;height:44px;border-radius:12px;
  display:flex;align-items:center;justify-content:center;font-size:1.3rem;
  flex-shrink:0
}
.mc-info{flex:1;margin-left:14px}
.mc-title{font-weight:600;font-size:.95rem;color:var(--forest);margin-bottom:2px}
.mc-desc{font-size:.76rem;color:var(--soft);line-height:1.4}

.mc-stats{
  padding:16px 22px;display:grid;gap:10px;flex:1
}
.mc-stat-row{display:grid;grid-template-columns:1fr 1fr;gap:8px}
.ms{
  background:var(--warm);border-radius:8px;
  padding:10px 12px;border:1px solid var(--sand)
}
.ms-num{
  font-family:'Playfair Display',serif;font-size:1.3rem;
  color:var(--forest);display:block;line-height:1.1
}
.ms-lbl{font-size:.68rem;color:var(--soft);text-transform:uppercase;
        letter-spacing:.05em;margin-top:2px}

.mc-footer{
  padding:14px 22px;
  border-top:1px solid rgba(74,140,92,.07);
  display:flex;align-items:center;justify-content:space-between
}
.mc-go{
  display:inline-flex;align-items:center;gap:6px;
  background:var(--forest);color:#fff;
  padding:8px 18px;border-radius:8px;
  font-size:.8rem;font-weight:600;
  transition:background .2s
}
.mc-go:hover{background:var(--moss)}
.mc-go-arrow{transition:transform .2s}
.mc-go:hover .mc-go-arrow{transform:translateX(3px)}
.mc-pending{
  font-size:.72rem;font-family:'DM Mono',monospace;
  color:var(--terra);background:rgba(212,116,90,.08);
  border:1px solid rgba(212,116,90,.15);
  padding:3px 10px;border-radius:50px
}
.mc-ok{
  font-size:.72rem;font-family:'DM Mono',monospace;
  color:var(--leaf);background:rgba(74,140,92,.08);
  border:1px solid rgba(74,140,92,.15);
  padding:3px 10px;border-radius:50px
}

/* Color accents per module */
.mod-resell .mc-icon{background:rgba(212,168,67,.12);color:var(--gold)}
.mod-recycle .mc-icon{background:rgba(74,140,92,.12);color:var(--leaf)}
.mod-reuse .mc-icon{background:rgba(74,140,92,.08);color:var(--moss)}
.mod-blog .mc-icon{background:rgba(212,116,90,.1);color:var(--terra)}

.mod-resell .mc-header{border-top:3px solid var(--gold)}
.mod-recycle .mc-header{border-top:3px solid var(--leaf)}
.mod-reuse .mc-header{border-top:3px solid var(--moss)}
.mod-blog .mc-header{border-top:3px solid var(--terra)}

/* ── Quick Stats Strip ───────────────────────────────────────────────── */
.strip{
  display:grid;grid-template-columns:repeat(4,1fr);
  gap:16px;margin-bottom:32px
}
.strip-card{
  background:#fff;border-radius:var(--r);
  border:1px solid rgba(74,140,92,.09);
  box-shadow:var(--shadow);
  padding:18px 20px;
  display:flex;align-items:center;gap:14px
}
.strip-icon{
  width:40px;height:40px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;flex-shrink:0
}
.strip-info .s-num{
  font-family:'Playfair Display',serif;font-size:1.5rem;
  color:var(--forest);line-height:1
}
.strip-info .s-lbl{font-size:.72rem;color:var(--soft);text-transform:uppercase;
                   letter-spacing:.05em;margin-top:3px}

/* ── Activity / Status section ───────────────────────────────────────── */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:32px}
.panel{
  background:#fff;border-radius:var(--r);
  border:1px solid rgba(74,140,92,.09);
  box-shadow:var(--shadow);overflow:hidden
}
.panel-head{
  padding:16px 20px;border-bottom:1px solid rgba(74,140,92,.07);
  display:flex;align-items:center;justify-content:space-between
}
.panel-title{font-weight:600;font-size:.9rem;color:var(--forest)}
.panel-body{padding:16px 20px}

/* Donut mini chart */
.donut-row{display:flex;flex-direction:column;gap:10px}
.donut-item{display:flex;align-items:center;gap:10px}
.donut-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0}
.donut-label{font-size:.8rem;color:var(--soft);flex:1}
.donut-bar-bg{flex:2;height:6px;border-radius:3px;background:var(--sand);overflow:hidden}
.donut-bar-fill{height:100%;border-radius:3px;transition:width 1s ease}
.donut-val{font-family:'DM Mono',monospace;font-size:.75rem;color:var(--charcoal);width:28px;text-align:right}

/* Pending list */
.pending-list{display:flex;flex-direction:column;gap:8px}
.pending-item{
  display:flex;align-items:center;justify-content:space-between;
  padding:10px 12px;background:var(--warm);
  border-radius:8px;border:1px solid var(--sand)
}
.pi-left{display:flex;align-items:center;gap:10px}
.pi-icon{font-size:1rem}
.pi-module{font-weight:600;font-size:.82rem;color:var(--forest)}
.pi-sub{font-size:.72rem;color:var(--soft)}
.pi-count{
  font-family:'Playfair Display',serif;font-size:1.2rem;
  color:var(--gold);font-weight:700
}

/* ── Quick Links ─────────────────────────────────────────────────────── */
.quick-links{margin-bottom:28px}
.ql-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;
          color:var(--soft);margin-bottom:12px}
.ql-grid{display:flex;flex-wrap:wrap;gap:10px}
.ql-btn{
  display:inline-flex;align-items:center;gap:7px;
  padding:9px 16px;border-radius:8px;font-size:.8rem;font-weight:600;
  border:1.5px solid;transition:all .2s
}
.ql-green{background:rgba(74,140,92,.07);border-color:rgba(74,140,92,.2);color:var(--leaf)}
.ql-green:hover{background:var(--leaf);color:#fff}
.ql-gold{background:rgba(212,168,67,.07);border-color:rgba(212,168,67,.25);color:var(--gold)}
.ql-gold:hover{background:var(--gold);color:#fff}
.ql-terra{background:rgba(212,116,90,.07);border-color:rgba(212,116,90,.2);color:var(--terra)}
.ql-terra:hover{background:var(--terra);color:#fff}
.ql-moss{background:rgba(45,90,61,.07);border-color:rgba(45,90,61,.2);color:var(--moss)}
.ql-moss:hover{background:var(--moss);color:#fff}

/* ── Toast ───────────────────────────────────────────────────────────── */
.toast{
  position:fixed;bottom:24px;right:24px;z-index:999;
  background:var(--forest);color:#fff;
  padding:12px 22px;border-radius:10px;font-size:.84rem;font-weight:500;
  box-shadow:0 8px 28px rgba(0,0,0,.2);
  transform:translateY(80px);opacity:0;
  transition:all .35s cubic-bezier(.34,1.56,.64,1);pointer-events:none
}
.toast.show{transform:translateY(0);opacity:1}

/* ── Responsive ──────────────────────────────────────────────────────── */
@media(max-width:900px){
  body{grid-template-columns:1fr}
  .sidebar{display:none}
  .strip{grid-template-columns:repeat(2,1fr)}
  .two-col{grid-template-columns:1fr}
}
@media(max-width:600px){
  .strip{grid-template-columns:1fr}
  .modules-grid{grid-template-columns:1fr}
  .hero{flex-direction:column;gap:16px}
}
</style>
</head>
<body>

<!-- ═══════════════════ SIDEBAR ═══════════════════ -->
<aside class="sidebar">
  <div class="sb-brand">
    <div class="sb-logo">
      <div class="sb-logo-icon">🌿</div>Ecosphere
    </div>
    <div class="sb-sub">Super Admin Hub</div>
  </div>

  <nav class="sb-nav">
    <div class="sb-section">Overview</div>
    <a class="sb-link active" href="super_admin_hub.php">
      <span class="sb-icon">🏠</span>Hub Dashboard
    </a>

    <div class="sb-section">Modules</div>
    <a class="sb-link" href="../../resell/admin/dashboard.php">
      <span class="sb-icon">🏷️</span>Resell
      <?php if($stats['resell']['pending']>0): ?>
        <span class="sb-badge"><?= $stats['resell']['pending'] ?></span>
      <?php endif; ?>
    </a>
    <a class="sb-link" href="../../recycle/admin/index.php">
      <span class="sb-icon">♻️</span>Recycle
      <?php if($stats['recycle']['pending']>0): ?>
        <span class="sb-badge"><?= $stats['recycle']['pending'] ?></span>
      <?php endif; ?>
    </a>
    <a class="sb-link" href="../../reuse/admin/Manage_reuse.php">
      <span class="sb-icon">🌱</span>Reuse Ideas
      <?php if($stats['reuse']['pending']>0): ?>
        <span class="sb-badge"><?= $stats['reuse']['pending'] ?></span>
      <?php endif; ?>
    </a>
    <a class="sb-link" href="../../blog/admin/manage.php">
      <span class="sb-icon">📝</span>Blog 
    </a>

    <div class="sb-section">Site</div>
    <a class="sb-link" href="../../index.php" target="_blank">
      <span class="sb-icon">🌍</span>View Live Site
    </a>
  </nav>

  
</aside>

<!-- ═══════════════════ MAIN ═══════════════════════ -->
<div class="main">

  <!-- Topbar -->
  <div class="topbar">
    <div class="tb-title">Admin Hub</div>
    <div class="tb-right">
      <span class="tb-time" id="clockEl"></span>
      <span class="tb-pill">🌿 Ecosphere Admin</span>
    </div>
  </div>

  <div class="page">

    <!-- Hero -->
    <div class="hero">
      <div class="hero-text">
        <h1>Welcome back, <?= htmlspecialchars($adminUser) ?></h1>
        <p>Central command for all Ecosphere modules. Manage resell listings, recycling requests, reuse ideas, and the blog from one place.</p>
      </div>
      <div class="hero-badge">🗓 <?= date('d M Y') ?></div>
    </div>

    <!-- Quick Stats Strip -->
    <div class="strip">
      <div class="strip-card">
        <div class="strip-icon" style="background:rgba(212,168,67,.1)">💰</div>
        <div class="strip-info">
          <div class="s-num"><?= fmtINR((float)$stats['resell']['revenue']) ?></div>
          <div class="s-lbl">Resell Revenue</div>
        </div>
      </div>
      <div class="strip-card">
        <div class="strip-icon" style="background:rgba(74,140,92,.1)">♻️</div>
        <div class="strip-info">
          <div class="s-num"><?= $stats['recycle']['total'] ?></div>
          <div class="s-lbl">Recycle Requests</div>
        </div>
      </div>
      <div class="strip-card">
        <div class="strip-icon" style="background:rgba(45,90,61,.1)">🌱</div>
        <div class="strip-info">
          <div class="s-num"><?= $stats['reuse']['approved'] ?></div>
          <div class="s-lbl">Live Reuse Ideas</div>
        </div>
      </div>
      <div class="strip-card">
        <div class="strip-icon" style="background:rgba(212,116,90,.1)">📝</div>
        <div class="strip-info">
          <div class="s-num"><?= $stats['blog']['published'] ?></div>
          <div class="s-lbl">Published Posts</div>
        </div>
      </div>
    </div>

    <!-- Module Cards -->
    <div class="modules-grid">

      <!-- Resell -->
      <div class="module-card mod-resell">
        <div class="mc-header">
          <div class="mc-icon">🏷️</div>
          <div class="mc-info">
            <div class="mc-title">Resell Marketplace</div>
            <div class="mc-desc">Review, approve & manage secondhand product listings and transactions.</div>
          </div>
        </div>
        <div class="mc-stats">
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num" style="color:var(--gold)"><?= $stats['resell']['pending'] ?></span>
              <div class="ms-lbl">Pending</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--leaf)"><?= $stats['resell']['approved'] ?></span>
              <div class="ms-lbl">Approved</div>
            </div>
          </div>
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num" style="color:var(--moss)"><?= $stats['resell']['sold'] ?></span>
              <div class="ms-lbl">Sold</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--terra)"><?= $stats['resell']['rejected'] ?></span>
              <div class="ms-lbl">Rejected</div>
            </div>
          </div>
        </div>
        <div class="mc-footer">
          <a class="mc-go" href="../../resell/admin/dashboard.php">
            Open Dashboard <span class="mc-go-arrow">→</span>
          </a>
          <?php if($stats['resell']['pending']>0): ?>
            <span class="mc-pending"><?= $stats['resell']['pending'] ?> need review</span>
          <?php else: ?>
            <span class="mc-ok">All clear ✓</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Recycle -->
      <div class="module-card mod-recycle">
        <div class="mc-header">
          <div class="mc-icon">♻️</div>
          <div class="mc-info">
            <div class="mc-title">Recycle Management</div>
            <div class="mc-desc">Track pickup/drop requests, manage active recycling centers.</div>
          </div>
        </div>
        <div class="mc-stats">
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num"><?= $stats['recycle']['total'] ?></span>
              <div class="ms-lbl">Total Requests</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--gold)"><?= $stats['recycle']['pending'] ?></span>
              <div class="ms-lbl">Pending Pickup</div>
            </div>
          </div>
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num" style="color:var(--leaf)"><?= $stats['recycle']['centers'] ?></span>
              <div class="ms-lbl">Active Centers</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--moss)"><?= $stats['recycle']['completed'] ?></span>
              <div class="ms-lbl">Completed</div>
            </div>
          </div>
        </div>
        <div class="mc-footer">
          <a class="mc-go" href="../../recycle/admin/index.php">
            Open Dashboard <span class="mc-go-arrow">→</span>
          </a>
          <?php if($stats['recycle']['pending']>0): ?>
            <span class="mc-pending"><?= $stats['recycle']['pending'] ?> pending</span>
          <?php else: ?>
            <span class="mc-ok">All clear ✓</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Reuse Ideas -->
      <div class="module-card mod-reuse">
        <div class="mc-header">
          <div class="mc-icon">🌱</div>
          <div class="mc-info">
            <div class="mc-title">Reuse / DIY Ideas</div>
            <div class="mc-desc">Approve or reject community-submitted upcycling ideas.</div>
          </div>
        </div>
        <div class="mc-stats">
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num" style="color:var(--gold)"><?= $stats['reuse']['pending'] ?></span>
              <div class="ms-lbl">Pending Review</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--leaf)"><?= $stats['reuse']['approved'] ?></span>
              <div class="ms-lbl">Approved & Live</div>
            </div>
          </div>
          <div class="ms" style="grid-column:1/-1">
            <span class="ms-num" style="color:var(--terra)"><?= $stats['reuse']['rejected'] ?></span>
            <div class="ms-lbl">Rejected</div>
          </div>
        </div>
        <div class="mc-footer">
          <a class="mc-go" href="../../reuse/admin/Manage_reuse.php">
            Open Dashboard <span class="mc-go-arrow">→</span>
          </a>
          <?php if($stats['reuse']['pending']>0): ?>
            <span class="mc-pending"><?= $stats['reuse']['pending'] ?> need review</span>
          <?php else: ?>
            <span class="mc-ok">All clear ✓</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- Blog -->
      <div class="module-card mod-blog">
        <div class="mc-header">
          <div class="mc-icon">📝</div>
          <div class="mc-info">
            <div class="mc-title">Blog</div>
            <div class="mc-desc">Write, edit and publish articles, tips, and eco-content.</div>
          </div>
        </div>
        <div class="mc-stats">
          <div class="mc-stat-row">
            <div class="ms">
              <span class="ms-num"><?= $stats['blog']['total'] ?></span>
              <div class="ms-lbl">Total Posts</div>
            </div>
            <div class="ms">
              <span class="ms-num" style="color:var(--leaf)"><?= $stats['blog']['published'] ?></span>
              <div class="ms-lbl">Published</div>
            </div>
          </div>
          <div class="ms">
            <span class="ms-num" style="color:var(--terra)"><?= $stats['blog']['draft'] ?></span>
            <div class="ms-lbl">Drafts</div>
          </div>
        </div>
        <div class="mc-footer">
          <a class="mc-go" href="../../blog/admin/manage.php">
            Open Dashboard <span class="mc-go-arrow">→</span>
          </a>
          <?php if($stats['blog']['draft']>0): ?>
            <span class="mc-pending"><?= $stats['blog']['draft'] ?> drafts</span>
          <?php else: ?>
            <span class="mc-ok">All published ✓</span>
          <?php endif; ?>
        </div>
      </div>

    </div><!-- /modules-grid -->

    <!-- Two column: Pending overview + Activity chart -->
    <div class="two-col">

      <!-- Pending Actions Panel -->
      <div class="panel">
        <div class="panel-head">
          <div class="panel-title">⚡ Items Awaiting Action</div>
        </div>
        <div class="panel-body">
          <div class="pending-list">
            <div class="pending-item">
              <div class="pi-left">
                <span class="pi-icon">🏷️</span>
                <div>
                  <div class="pi-module">Resell Listings</div>
                  <div class="pi-sub">Awaiting approval</div>
                </div>
              </div>
              <div class="pi-count"><?= $stats['resell']['pending'] ?></div>
            </div>
            <div class="pending-item">
              <div class="pi-left">
                <span class="pi-icon">♻️</span>
                <div>
                  <div class="pi-module">Recycle Pickups</div>
                  <div class="pi-sub">Pending scheduling</div>
                </div>
              </div>
              <div class="pi-count"><?= $stats['recycle']['pending'] ?></div>
            </div>
            <div class="pending-item">
              <div class="pi-left">
                <span class="pi-icon">🌱</span>
                <div>
                  <div class="pi-module">Reuse Ideas</div>
                  <div class="pi-sub">Awaiting review</div>
                </div>
              </div>
              <div class="pi-count"><?= $stats['reuse']['pending'] ?></div>
            </div>
            <div class="pending-item">
              <div class="pi-left">
                <span class="pi-icon">📝</span>
                <div>
                  <div class="pi-module">Blog Drafts</div>
                  <div class="pi-sub">Ready to publish</div>
                </div>
              </div>
              <div class="pi-count"><?= $stats['blog']['draft'] ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Module Health Bar Chart -->
      <div class="panel">
        <div class="panel-head">
          <div class="panel-title">📊 Module Activity Snapshot</div>
        </div>
        <div class="panel-body">
          <?php
            $totalResell  = array_sum([$stats['resell']['pending'],$stats['resell']['approved'],$stats['resell']['sold'],$stats['resell']['rejected']]) ?: 1;
            $totalRecycle = max($stats['recycle']['total'],1);
            $totalReuse   = array_sum([$stats['reuse']['pending'],$stats['reuse']['approved'],$stats['reuse']['rejected']]) ?: 1;
            $totalBlog    = max($stats['blog']['total'],1);
            $grand = $totalResell + $totalRecycle + $totalReuse + $totalBlog ?: 1;
            $bars = [
              ['🏷️ Resell', $totalResell,  '#d4a843'],
              ['♻️ Recycle',$totalRecycle, '#4a8c5c'],
              ['🌱 Reuse',  $totalReuse,   '#2d5a3d'],
              ['📝 Blog',   $totalBlog,    '#d4745a'],
            ];
          ?>
          <div class="donut-row">
            <?php foreach($bars as [$lbl,$val,$col]): ?>
            <div class="donut-item">
              <div class="donut-dot" style="background:<?= $col ?>"></div>
              <div class="donut-label"><?= $lbl ?></div>
              <div class="donut-bar-bg">
                <div class="donut-bar-fill"
                     style="width:<?= round($val/$grand*100) ?>%;background:<?= $col ?>"></div>
              </div>
              <div class="donut-val"><?= $val ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

    </div><!-- /two-col -->

    <!-- Quick Links -->
    
      <div class="quick-links">
        <div class="ql-title">Quick Actions</div>
        <div class="ql-grid">
          <a class="ql-btn ql-green" href="../../resell/admin/dashboard.php">🏷️ Resell Dashboard</a>
          <a class="ql-btn ql-green" href="../../recycle/admin/index.php">♻️ Recycle Dashboard</a>
          <a class="ql-btn ql-green" href="../../reuse/admin/manage_reuse.php">🌱 Reuse Dashboard</a>
          <a class="ql-btn ql-terra" href="../../blog/admin/manage.php">📝 Blog Dashboard</a>
          <a class="ql-btn ql-gold" href="../../recycle/admin/centers.php">🏭 Manage Centers</a>
          <a class="ql-btn ql-gold" href="../../recycle/admin/requests.php">📦 Recycle Requests</a>
          <a class="ql-btn ql-moss" href="../../index.php" target="_blank">🌍 View Live Site</a>
          
        </div>
      </div>


  </div><!-- /page -->
</div><!-- /main -->

<div class="toast" id="toast"></div>

<script>
// Live clock
(function tick(){
  const d=new Date();
  document.getElementById('clockEl').textContent=
    d.toLocaleTimeString('en-IN',{hour:'2-digit',minute:'2-digit',second:'2-digit'});
  setTimeout(tick,1000);
})();

// Animate bars on load
window.addEventListener('load',()=>{
  document.querySelectorAll('.donut-bar-fill').forEach(el=>{
    const w=el.style.width;
    el.style.width='0';
    setTimeout(()=>el.style.width=w,120);
  });
});

// Toast helper (usable from PHP redirects with ?msg=)
const params=new URLSearchParams(location.search);
if(params.get('msg')){
  const t=document.getElementById('toast');
  t.textContent=decodeURIComponent(params.get('msg'));
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),3000);
}
</script>
</body>
</html>
