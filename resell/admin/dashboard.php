<?php
session_start();
require_once '../config.php';
if (!isAdmin()) {
  header('Location: login.php');
  exit;
}
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: login.php');
  exit;
}
$adminUser = $_SESSION['admin_user'] ?? 'admin';

try {
  $pdo = getDB();
  $stats = $pdo->query("SELECT
        (SELECT COUNT(*) FROM resell_products WHERE status='Pending') AS pending,
        (SELECT COUNT(*) FROM resell_products WHERE status='Approved') AS approved,
        (SELECT COUNT(*) FROM resell_products WHERE status='Sold') AS sold,
        (SELECT COUNT(*) FROM resell_products WHERE status='Rejected') AS rejected,
        (SELECT COUNT(*) FROM resell_products) AS total,
        (SELECT COUNT(*) FROM resell_transactions WHERE payment_status='Success') AS txn_count,
        (SELECT COALESCE(SUM(total_price),0) FROM resell_transactions WHERE payment_status='Success') AS revenue
    ")->fetch();
  $recentTxns = $pdo->query("
        SELECT t.*, p.image FROM resell_transactions t
        LEFT JOIN resell_products p ON t.product_id=p.id
        ORDER BY t.transaction_date DESC LIMIT 5
    ")->fetchAll();
} catch (Exception $e) {
  $stats = [];
  $recentTxns = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard — Ecosphere Resell</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --sage: #7ab88a;
      --mint: #a8d5b5;
      --cream: #f5f0e8;
      --parchment: #ede6d6;
      --warm: #faf8f3;
      --sand: #e8e0d0;
      --terra: #d4745a;
      --gold: #d4a843;
      --charcoal: #2c2c2c;
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
      background: var(--warm);
      color: var(--charcoal);
      overflow-x: hidden;
      display: grid;
      grid-template-columns: 230px 1fr;
      min-height: 100vh
    }

    a {
      text-decoration: none;
      color: inherit
    }

    button {
      cursor: pointer;
      font-family: inherit;
      border: none;
      outline: none
    }

    input,
    select,
    textarea {
      font-family: inherit;
      outline: none;
      border: none
    }

    ::-webkit-scrollbar {
      width: 4px
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage)
    }

    /* SIDEBAR */
    .sidebar {
      background: var(--forest);
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto
    }

    .sb-brand {
      padding: 22px 20px 16px;
      border-bottom: 1px solid rgba(255, 255, 255, .07)
    }

    .sb-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 9px
    }

    .sb-dot {
      width: 26px;
      height: 26px;
      background: rgba(255, 255, 255, .12);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .8rem
    }

    .sb-sub {
      font-family: 'DM Mono', monospace;
      font-size: .58rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .25);
      margin-top: 4px;
      padding-left: 35px
    }

    .sb-nav {
      flex: 1;
      padding: 14px 0
    }

    .sb-sec {
      font-family: 'DM Mono', monospace;
      font-size: .58rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .22);
      padding: 12px 20px 5px
    }

    .sb-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;
      font-size: .83rem;
      color: rgba(255, 255, 255, .58);
      transition: all .2s;
      border-left: 3px solid transparent
    }

    .sb-link:hover,
    .sb-link.active {
      background: rgba(255, 255, 255, .07);
      color: #fff;
      border-left-color: var(--sage)
    }

    .sb-icon {
      width: 18px;
      text-align: center
    }

    .sb-badge {
      margin-left: auto;
      background: var(--terra);
      color: #fff;
      padding: 1px 7px;
      font-size: .6rem;
      font-family: 'DM Mono', monospace;
      border-radius: 50px
    }

    .sb-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255, 255, 255, .07)
    }

    .sb-user {
      font-size: .78rem;
      color: rgba(255, 255, 255, .38)
    }

    .sb-logout {
      display: inline-block;
      margin-top: 6px;
      font-size: .74rem;
      color: var(--mint);
      opacity: .7;
      transition: opacity .2s
    }

    .sb-logout:hover {
      opacity: 1
    }

    /* MAIN */
    .main {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      overflow-y: auto
    }

    .topbar {
      background: #fff;
      border-bottom: 1px solid var(--sand);
      padding: 0 26px;
      height: 54px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 10
    }

    .tb-title {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--forest)
    }

    .tb-pill {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      padding: 4px 12px;
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .08em;
      text-transform: uppercase
    }

    .page {
      padding: 22px 26px
    }

    /* STATS */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 12px;
      margin-bottom: 22px
    }

    .sc {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 16px;
      border-top: 3px solid transparent
    }

    .sc.s-pend {
      border-top-color: var(--gold)
    }

    .sc.s-appr {
      border-top-color: var(--leaf)
    }

    .sc.s-sold {
      border-top-color: var(--forest)
    }

    .sc.s-rej {
      border-top-color: var(--terra)
    }

    .sc.s-rev {
      border-top-color: var(--sage)
    }

    .sc-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      display: block;
      color: var(--forest);
      margin-bottom: 2px
    }

    .sc-lbl {
      font-size: .62rem;
      font-family: 'DM Mono', monospace;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft)
    }

    /* FILTER BAR */
    .filter-bar {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 13px 16px;
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 16px
    }

    .status-tabs {
      display: flex;
      gap: 0
    }

    .st-tab {
      padding: 7px 14px;
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft);
      border: 1px solid var(--sand);
      background: var(--cream);
      transition: all .2s;
      cursor: pointer
    }

    .st-tab:not(:last-child) {
      border-right: none
    }

    .st-tab.active {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest)
    }

    .search-inp {
      flex: 1;
      min-width: 160px;
      background: var(--cream);
      border: 1.5px solid var(--sand);
      padding: 8px 12px;
      font-size: .84rem;
      color: var(--charcoal);
      transition: border-color .2s
    }

    .search-inp:focus {
      border-color: var(--leaf)
    }

    /* TABLE */
    .table-card {
      background: #fff;
      border: 1px solid var(--sand);
      overflow: hidden
    }

    .tc-header {
      padding: 13px 16px;
      border-bottom: 1px solid var(--sand);
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .tc-title {
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--soft)
    }

    .tc-count {
      font-size: .82rem;
      color: var(--forest);
      font-weight: 600
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      font-family: 'DM Mono', monospace;
      font-size: .59rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft);
      padding: 9px 13px;
      background: var(--cream);
      text-align: left;
      border-bottom: 1px solid var(--sand);
      white-space: nowrap
    }

    td {
      padding: 11px 13px;
      font-size: .82rem;
      border-bottom: 1px solid var(--sand);
      vertical-align: middle
    }

    tr:last-child td {
      border-bottom: none
    }

    tr:hover td {
      background: rgba(74, 140, 92, .02)
    }

    .puid {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      color: var(--forest);
      background: rgba(26, 58, 42, .06);
      padding: 1px 7px
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 2px 9px;
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      font-weight: 500;
      letter-spacing: .08em;
      text-transform: uppercase;
      border-radius: 50px
    }

    .sb-P {
      background: rgba(212, 168, 67, .1);
      color: var(--gold);
      border: 1px solid rgba(212, 168, 67, .25)
    }

    .sb-A {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      border: 1px solid rgba(74, 140, 92, .25)
    }

    .sb-S {
      background: rgba(26, 58, 42, .1);
      color: var(--forest);
      border: 1px solid rgba(26, 58, 42, .25)
    }

    .sb-R {
      background: rgba(212, 116, 90, .08);
      color: var(--terra);
      border: 1px solid rgba(212, 116, 90, .2)
    }

    .action-btns {
      display: flex;
      gap: 5px;
      white-space: nowrap
    }

    .btn-a {
      padding: 5px 11px;
      font-size: .71rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid rgba(74, 140, 92, .2);
      color: var(--leaf);
      transition: all .2s
    }

    .btn-a:hover {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf)
    }

    .btn-r {
      padding: 5px 11px;
      font-size: .71rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid rgba(212, 116, 90, .2);
      color: var(--terra);
      transition: all .2s
    }

    .btn-r:hover {
      background: var(--terra);
      color: #fff;
      border-color: var(--terra)
    }

    .btn-d {
      padding: 5px 11px;
      font-size: .71rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid var(--sand);
      color: var(--soft);
      transition: all .2s
    }

    .btn-d:hover {
      background: var(--terra);
      color: #fff;
      border-color: var(--terra)
    }

    .btn-v {
      padding: 5px 11px;
      font-size: .71rem;
      border: 1px solid var(--sand);
      color: var(--soft);
      transition: all .2s
    }

    .btn-v:hover {
      border-color: var(--forest);
      color: var(--forest)
    }

    .thumb-cell {
      width: 42px;
      height: 34px;
      border-radius: 4px;
      overflow: hidden;
      background: var(--parchment);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0
    }

    .thumb-cell img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .empty-row td {
      text-align: center;
      color: var(--soft);
      padding: 32px;
      font-size: .84rem
    }

    .spinner {
      width: 26px;
      height: 26px;
      border: 2px solid var(--mint);
      border-top-color: var(--leaf);
      border-radius: 50%;
      animation: spin .8s linear infinite;
      margin: 0 auto
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    /* MODAL */
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(26, 58, 42, .65);
      backdrop-filter: blur(8px);
      z-index: 200;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      opacity: 0;
      pointer-events: none;
      transition: opacity .25s
    }

    .overlay.open {
      opacity: 1;
      pointer-events: all
    }

    .modal {
      background: #fff;
      width: 100%;
      max-width: 500px;
      border-top: 3px solid var(--gold);
      transform: scale(.96) translateY(14px);
      transition: transform .25s
    }

    .overlay.open .modal {
      transform: scale(1) translateY(0)
    }

    .mh {
      background: var(--forest);
      padding: 18px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .mh-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1rem
    }

    .mh-close {
      background: rgba(255, 255, 255, .14);
      color: #fff;
      width: 28px;
      height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      transition: background .2s
    }

    .mh-close:hover {
      background: rgba(255, 255, 255, .25)
    }

    .mb {
      padding: 22px
    }

    .mb-info {
      font-family: 'Playfair Display', serif;
      font-size: .95rem;
      color: var(--forest);
      margin-bottom: 3px
    }

    .mb-sub {
      font-size: .8rem;
      color: var(--soft);
      margin-bottom: 16px
    }

    .action-choice {
      display: flex;
      gap: 10px;
      margin-bottom: 14px
    }

    .ac-btn {
      flex: 1;
      padding: 11px;
      text-align: center;
      font-weight: 700;
      font-size: .8rem;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 2px solid var(--sand);
      color: var(--soft);
      cursor: pointer;
      background: #fff;
      transition: all .2s
    }

    .ac-btn.app.sel {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf)
    }

    .ac-btn.rej.sel {
      background: var(--terra);
      color: #fff;
      border-color: var(--terra)
    }

    .note-ta {
      width: 100%;
      background: var(--cream);
      border: 1.5px solid var(--sand);
      padding: 10px 13px;
      font-size: .86rem;
      color: var(--charcoal);
      min-height: 76px;
      resize: vertical;
      transition: border-color .2s
    }

    .note-ta:focus {
      border-color: var(--leaf)
    }

    .note-label {
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft);
      display: block;
      margin-bottom: 6px
    }

    .btn-confirm {
      width: 100%;
      padding: 12px;
      font-weight: 700;
      font-size: .88rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #fff;
      background: var(--forest);
      transition: all .2s;
      margin-top: 12px
    }

    .btn-confirm:hover {
      background: var(--moss)
    }

    .btn-confirm:disabled {
      opacity: .6;
      cursor: not-allowed
    }

    .modal-err {
      font-size: .8rem;
      color: var(--terra);
      margin-top: 8px;
      display: none
    }

    /* BOTTOM GRID */
    .bottom-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      margin-top: 20px
    }

    .txn-row {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 14px;
      border-bottom: 1px solid var(--sand)
    }

    .txn-row:last-child {
      border-bottom: none
    }

    .txn-uid {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      color: var(--forest);
      background: rgba(26, 58, 42, .06);
      padding: 1px 8px;
      border-radius: 50px
    }

    .txn-info {
      flex: 1
    }

    .txn-name {
      font-size: .82rem;
      font-weight: 600;
      color: var(--forest)
    }

    .txn-meta {
      font-size: .72rem;
      color: var(--soft)
    }

    .txn-amount {
      font-family: 'Playfair Display', serif;
      font-size: .98rem;
      color: var(--leaf);
      font-weight: 700
    }

    /* PAGINATION */
    #pagination {
      display: flex;
      gap: 6px;
      margin-top: 14px;
      flex-wrap: wrap
    }

    .pg-btn {
      min-width: 34px;
      height: 34px;
      padding: 0 10px;
      font-size: .82rem;
      font-weight: 600;
      background: #fff;
      border: 1.5px solid var(--sand);
      color: var(--soft);
      transition: all .2s
    }

    .pg-btn:hover,
    .pg-btn.active {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest)
    }

    .pg-btn:disabled {
      opacity: .3;
      cursor: not-allowed
    }

    /* TOAST */
    .toast {
      position: fixed;
      bottom: 22px;
      right: 22px;
      z-index: 9999;
      background: var(--forest);
      color: #fff;
      padding: 11px 18px;
      font-size: .87rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 8px 28px rgba(0, 0, 0, .25);
      transform: translateY(14px);
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      border-left: 3px solid var(--gold)
    }

    .toast.show {
      transform: none;
      opacity: 1
    }

    @media(max-width:900px) {
      body {
        grid-template-columns: 1fr
      }

      .sidebar {
        display: none
      }

      .stats-row {
        grid-template-columns: 1fr 1fr
      }
    }
  </style>
</head>

<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sb-brand">
      <div class="sb-logo">
        <div class="sb-dot">🌿</div>Resell Admin
      </div>
      <div class="sb-sub">Ecosphere Marketplace</div>
    </div>
    <nav class="sb-nav">
      <div class="sb-sec">Management</div>
      <a class="sb-link active" href="dashboard.php"><span class="sb-icon">📊</span>Dashboard
        <?php if (($stats['pending'] ?? 0) > 0): ?><span class="sb-badge"><?= $stats['pending'] ?></span><?php endif; ?>
      </a>
      <a class="sb-link" href="../resell.php" target="_blank"><span class="sb-icon">🌍</span>View Marketplace</a>
    </nav>
    <div class="sb-footer">
      <div class="sb-user">👤 <?= htmlspecialchars($adminUser) ?></div>
      <a class="sb-logout" href="?logout=1">Sign Out →</a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="main">
    <div class="topbar">
      <div class="tb-title">Product Management — <?= date('d F Y') ?></div>
      <div class="tb-right"><span class="tb-pill">🌿 Admin Panel</span></div>
    </div>

    <div class="page">
      <!-- STATS -->
      <div class="stats-row">
        <div class="sc s-pend"><span class="sc-num" style="color:var(--gold)"><?= $stats['pending'] ?? 0 ?></span>
          <div class="sc-lbl">Pending Review</div>
        </div>
        <div class="sc s-appr"><span class="sc-num" style="color:var(--leaf)"><?= $stats['approved'] ?? 0 ?></span>
          <div class="sc-lbl">Live Listings</div>
        </div>
        <div class="sc s-sold"><span class="sc-num"><?= $stats['sold'] ?? 0 ?></span>
          <div class="sc-lbl">Sold Items</div>
        </div>
        <div class="sc s-rej"><span class="sc-num" style="color:var(--terra)"><?= $stats['rejected'] ?? 0 ?></span>
          <div class="sc-lbl">Rejected</div>
        </div>
        <div class="sc s-rev"><span class="sc-num" style="color:var(--leaf)">₹<?= number_format((float)($stats['revenue'] ?? 0), 0) ?></span>
          <div class="sc-lbl">Total Revenue</div>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div class="filter-bar">
        <div class="status-tabs">
          <button class="st-tab active" onclick="setStatus('all',this)">All</button>
          <button class="st-tab" onclick="setStatus('Pending',this)">⏳ Pending</button>
          <button class="st-tab" onclick="setStatus('Approved',this)">✅ Approved</button>
          <button class="st-tab" onclick="setStatus('Sold',this)">🏷️ Sold</button>
          <button class="st-tab" onclick="setStatus('Rejected',this)">❌ Rejected</button>
        </div>
        <input class="search-inp" id="searchInput" type="text" placeholder="Search products, sellers, UIDs…" oninput="debounce()">
      </div>

      <!-- PRODUCTS TABLE -->
      <div class="table-card">
        <div class="tc-header">
          <span class="tc-title">Product Listings</span>
          <span class="tc-count" id="tableCount">Loading…</span>
        </div>
        <div style="overflow-x:auto">
          <table>
            <thead>
              <tr>
                <th>Photo</th>
                <th>UID</th>
                <th>Product</th>
                <th>Seller</th>
                <th>Category</th>
                <th>Condition</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Listed</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="productTableBody">
              <tr>
                <td colspan="11" style="text-align:center;padding:36px">
                  <div class="spinner"></div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div id="pagination"></div>

      <!-- RECENT TRANSACTIONS -->
      <div class="table-card bottom-grid" style="margin-top:20px">
        <div class="tc-header">
          <span class="tc-title">Recent Transactions (<?= $stats['txn_count'] ?? 0 ?> total)</span>
          <span class="tc-count">Revenue: ₹<?= number_format((float)($stats['revenue'] ?? 0), 2) ?></span>
        </div>
        <?php if (empty($recentTxns)): ?>
          <div class="txn-row">
            <div style="color:var(--soft);font-size:.84rem;padding:8px">No transactions yet.</div>
          </div>
          <?php else: foreach ($recentTxns as $t): ?>
            <div class="txn-row">
              <div class="thumb-cell"><?= $t['image'] ? "<img src='../{$t['image']}' alt=''>" : '📦' ?></div>
              <div class="txn-info">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px">
                  <span class="txn-uid"><?= htmlspecialchars($t['transaction_uid']) ?></span>
                </div>
                <div class="txn-name"><?= htmlspecialchars($t['product_name']) ?></div>
                <div class="txn-meta">Buyer: <?= htmlspecialchars($t['buyer_name']) ?> · <?= htmlspecialchars($t['buyer_email']) ?> · <?= htmlspecialchars($t['payment_method']) ?><?= $t['card_last4'] ? ' ****' . $t['card_last4'] : '' ?></div>
              </div>
              <div>
                <div class="txn-amount">₹<?= number_format((float)$t['total_price'], 2) ?></div>
                <div style="font-size:.7rem;color:var(--soft);text-align:right"><?= date('d M Y H:i', strtotime($t['transaction_date'])) ?></div>
              </div>
            </div>
        <?php endforeach;
        endif; ?>
      </div>

    </div><!-- /page -->
  </div><!-- /main -->

  <!-- REVIEW MODAL -->
  <div class="overlay" id="reviewOverlay" onclick="closeModal(event)">
    <div class="modal">
      <div class="mh">
        <div class="mh-title" id="mhTitle">Review Product</div>
        <button class="mh-close" onclick="closeModalDirect()">✕</button>
      </div>
      <div class="mb">
        <div class="mb-info" id="mbProdName"></div>
        <div class="mb-sub" id="mbProdSeller"></div>
        <div class="action-choice">
          <button class="ac-btn app" id="btnApp" onclick="selAction('approve')">✅ Approve</button>
          <button class="ac-btn rej" id="btnRej" onclick="selAction('reject')">❌ Reject</button>
        </div>
        <label class="note-label">Admin Note (required)</label>
        <textarea class="note-ta" id="reviewNote" placeholder="Write your decision note here…"></textarea>
        <button class="btn-confirm" id="reviewBtn" onclick="submitReview()">Confirm Decision</button>
        <div class="modal-err" id="modalErr"></div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    const CURRENCY = '₹';
    let currentStatus = 'all';
    let currentPage = 1;
    let debTimer = null;
    let reviewId = null;
    let reviewAction = null;

    function toast(msg, dur = 2800) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur)
    }

    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    }

    function fmtDate(d) {
      return new Date(d).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      })
    }

    function setStatus(s, btn) {
      currentStatus = s;
      currentPage = 1;
      document.querySelectorAll('.st-tab').forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      loadProducts()
    }

    function debounce() {
      clearTimeout(debTimer);
      debTimer = setTimeout(() => {
        currentPage = 1;
        loadProducts()
      }, 300)
    }

    async function loadProducts() {
      const body = document.getElementById('productTableBody');
      body.innerHTML = '<tr><td colspan="11" style="text-align:center;padding:32px"><div class="spinner"></div></td></tr>';
      const params = new URLSearchParams({
        status: currentStatus,
        search: document.getElementById('searchInput').value.trim(),
        page: currentPage
      });
      try {
        const res = await fetch('../api/admin_fetch_products.php?' + params);
        const data = await res.json();
        if (data.error) {
          body.innerHTML = `<tr><td colspan="11" style="text-align:center;color:var(--terra);padding:28px">${esc(data.error)}</td></tr>`;
          return;
        }
        document.getElementById('tableCount').textContent = data.total + ' result' + (data.total !== 1 ? 's' : '');
        renderTable(data.products);
        renderPagination(data.page, data.pages);
      } catch (e) {
        body.innerHTML = '<tr><td colspan="11" style="text-align:center;color:var(--terra);padding:28px">Server error — is XAMPP running?</td></tr>';
      }
    }

    const statusMap = {
      'Pending': ['sb-P', '⏳ Pending'],
      'Approved': ['sb-A', '✅ Approved'],
      'Sold': ['sb-S', '🏷️ Sold'],
      'Rejected': ['sb-R', '❌ Rejected']
    };

    function renderTable(prods) {
      const body = document.getElementById('productTableBody');
      if (!prods.length) {
        body.innerHTML = '<tr class="empty-row"><td colspan="11">No products found.</td></tr>';
        return;
      }
      body.innerHTML = prods.map(p => {
        const [sc, slbl] = statusMap[p.status] || statusMap['Pending'];
        const thumb = p.image ? `<img src="../${esc(p.image)}" alt="">` : categoryEmoji(p.category);
        return `<tr>
      <td><div class="thumb-cell">${p.image?`<img src="../${esc(p.image)}" alt="">`:categoryEmoji(p.category)}</div></td>
      <td><span class="puid">${esc(p.product_uid)}</span></td>
      <td style="max-width:150px;font-weight:600;color:var(--forest);font-size:.81rem">${esc(p.product_name.length>40?p.product_name.slice(0,40)+'…':p.product_name)}</td>
      <td><div style="font-size:.81rem;font-weight:600">${esc(p.seller_name)}</div><div style="font-size:.72rem;color:var(--soft)">${esc(p.seller_email)}</div></td>
      <td style="font-size:.8rem">${esc(p.category)}</td>
      <td><span style="font-size:.74rem;font-weight:700;padding:2px 8px;border-radius:50px;background:${condBg(p.condition)};color:${condFg(p.condition)}">${esc(p.condition)}</span></td>
      <td style="font-size:.82rem">${p.quantity}</td>
      <td style="font-weight:700;color:var(--forest);font-size:.85rem">${esc(p.price_fmt)}</td>
      <td><span class="status-badge ${sc}">${slbl}</span></td>
      <td style="font-family:'DM Mono',monospace;font-size:.7rem;color:var(--soft);white-space:nowrap">${fmtDate(p.created_at)}</td>
      <td><div class="action-btns">
        ${p.status!=='Approved'&&p.status!=='Sold'?`<button class="btn-a" onclick="openReview(${p.id},'${esc(p.product_name)}','${esc(p.seller_name)}')">Approve</button>`:''}
        ${p.status!=='Rejected'&&p.status!=='Sold'?`<button class="btn-r" onclick="openReview(${p.id},'${esc(p.product_name)}','${esc(p.seller_name)}','reject')">Reject</button>`:''}
        <button class="btn-d" onclick="deleteProduct(${p.id},'${esc(p.product_name)}')">Del</button>
      </div></td>
    </tr>`;
      }).join('');
    }

    function condBg(c) {
      return {
        New: 'rgba(74,140,92,.12)',
        'Like New': 'rgba(122,184,138,.15)',
        Used: 'rgba(212,168,67,.12)',
        Damaged: 'rgba(212,116,90,.1)'
      } [c] || 'var(--cream)'
    }

    function condFg(c) {
      return {
        New: '#4a8c5c',
        'Like New': '#2d5a3d',
        Used: '#8b6f47',
        Damaged: '#d4745a'
      } [c] || 'var(--soft)'
    }

    function categoryEmoji(cat) {
      return {
        Electronics: '💻',
        Furniture: '🪑',
        Clothes: '👕',
        Books: '📚',
        'Sports & Fitness': '⚽',
        'Home & Kitchen': '🏠',
        'Toys & Games': '🎮',
        Garden: '🌿'
      } [cat] || '📦'
    }

    function renderPagination(cur, total) {
      const pg = document.getElementById('pagination');
      if (total <= 1) {
        pg.innerHTML = '';
        return;
      }
      let h = `<button class="pg-btn" onclick="goPg(${cur-1})" ${cur===1?'disabled':''}>‹</button>`;
      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || Math.abs(i - cur) <= 1) h += `<button class="pg-btn ${i===cur?'active':''}" onclick="goPg(${i})">${i}</button>`;
        else if (Math.abs(i - cur) === 2) h += `<button class="pg-btn" disabled>…</button>`;
      }
      h += `<button class="pg-btn" onclick="goPg(${cur+1})" ${cur===total?'disabled':''}>›</button>`;
      pg.innerHTML = h;
    }

    function goPg(p) {
      currentPage = p;
      loadProducts()
    }

    function openReview(id, name, seller, preAction = null) {
      reviewId = id;
      reviewAction = preAction;
      document.getElementById('mbProdName').textContent = name;
      document.getElementById('mbProdSeller').textContent = 'Seller: ' + seller;
      document.getElementById('reviewNote').value = preAction ? {
        approve: 'Good listing, verified and approved.',
        reject: 'Listing does not meet our quality standards.'
      } [preAction] || '' : '';
      document.getElementById('reviewBtn').disabled = false;
      document.getElementById('reviewBtn').textContent = 'Confirm Decision';
      document.getElementById('btnApp').classList.toggle('sel', preAction === 'approve');
      document.getElementById('btnRej').classList.toggle('sel', preAction === 'reject');
      document.getElementById('mhTitle').textContent = preAction === 'reject' ? 'Reject Listing' : 'Review Listing';
      document.getElementById('modalErr').style.display = 'none';
      document.getElementById('reviewOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function selAction(a) {
      reviewAction = a;
      document.getElementById('btnApp').classList.toggle('sel', a === 'approve');
      document.getElementById('btnRej').classList.toggle('sel', a === 'reject');
    }

    function closeModal(e) {
      if (e.target === document.getElementById('reviewOverlay')) closeModalDirect()
    }

    function closeModalDirect() {
      document.getElementById('reviewOverlay').classList.remove('open');
      document.body.style.overflow = ''
    }

    async function submitReview() {
      const note = document.getElementById('reviewNote').value.trim();
      const err = document.getElementById('modalErr');
      const btn = document.getElementById('reviewBtn');
      err.style.display = 'none';
      if (!reviewAction) {
        err.textContent = 'Please choose Approve or Reject.';
        err.style.display = 'block';
        return;
      }
      if (!note) {
        err.textContent = 'Admin note is required.';
        err.style.display = 'block';
        return;
      }
      btn.disabled = true;
      btn.textContent = 'Saving…';
      try {
        const res = await fetch('../api/admin_product_action.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            product_id: reviewId,
            action: reviewAction,
            admin_note: note
          })
        });
        const data = await res.json();
        if (data.success) {
          closeModalDirect();
          toast(reviewAction === 'approve' ? '✅ Product approved!' : '❌ Product rejected.');
          loadProducts();
        } else {
          err.textContent = '⚠️ ' + (data.error || 'Failed');
          err.style.display = 'block';
          btn.disabled = false;
          btn.textContent = 'Confirm Decision';
        }
      } catch (e) {
        err.textContent = '⚠️ Server error';
        err.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Confirm Decision';
      }
    }

    async function deleteProduct(id, name) {
      if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;
      try {
        const res = await fetch('../api/admin_product_action.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            product_id: id,
            action: 'delete'
          })
        });
        const data = await res.json();
        if (data.success) {
          toast('🗑️ Product deleted.');
          loadProducts();
        } else toast('⚠️ ' + (data.error || 'Failed to delete'));
      } catch {
        toast('⚠️ Server error')
      }
    }

    loadProducts();
  </script>
</body>

</html>