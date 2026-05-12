<?php
// admin/_layout.php — Shared admin HTML head + nav
// Usage: require_once '_layout.php'; at top of page (after auth)
// $pageTitle must be set before including.
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — Ecosphere</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Mono&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    :root {
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --sage: #7ab88a;
      --mint: #a8d5b5;
      --cream: #f5f0e8;
      --parchment: #ede6d6;
      --warm: #faf8f3;
      --earth: #8b6f47;
      --clay: #c4956a;
      --terra: #d4745a;
      --charcoal: #2c2c2c;
      --soft: #5a5a5a;
      --gold: #d4a843;
      --r: 12px;
      --shadow: 0 4px 18px rgba(26, 58, 42, .09)
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--warm);
      color: var(--charcoal);
      min-height: 100vh;
      display: flex;
      flex-direction: column
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
      width: 5px
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage);
      border-radius: 3px
    }

    /* SIDEBAR + LAYOUT */
    .layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      min-height: 100vh
    }

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
      padding: 22px 20px 14px;
      border-bottom: 1px solid rgba(255, 255, 255, .07)
    }

    .sb-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .sb-logo-dot {
      width: 28px;
      height: 28px;
      background: rgba(255, 255, 255, .12);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem
    }

    .sb-sub {
      font-size: .72rem;
      color: rgba(255, 255, 255, .4);
      margin-top: 4px;
      padding-left: 36px
    }

    .sb-nav {
      flex: 1;
      padding: 16px 0
    }

    .sb-section {
      font-size: .65rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: rgba(255, 255, 255, .3);
      padding: 12px 20px 6px
    }

    .sb-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 20px;
      font-size: .85rem;
      color: rgba(255, 255, 255, .6);
      transition: all .2s
    }

    .sb-link:hover {
      background: rgba(255, 255, 255, .06);
      color: #fff
    }

    .sb-link.active {
      background: rgba(255, 255, 255, .1);
      color: #fff;
      font-weight: 600
    }

    .sb-link .sb-icon {
      font-size: .95rem;
      width: 20px;
      text-align: center
    }

    .sb-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255, 255, 255, .07)
    }

    .sb-user {
      font-size: .8rem;
      color: rgba(255, 255, 255, .5)
    }

    .sb-logout {
      display: inline-block;
      margin-top: 6px;
      font-size: .78rem;
      color: var(--mint);
      opacity: .75;
      transition: opacity .2s
    }

    .sb-logout:hover {
      opacity: 1
    }

    /* MAIN */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden
    }

    .topbar {
      background: #fff;
      border-bottom: 1px solid rgba(74, 140, 92, .1);
      padding: 0 28px;
      height: 58px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 10;
      box-shadow: var(--shadow)
    }

    .topbar-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest)
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 14px
    }

    .tb-badge {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      padding: 4px 12px;
      border-radius: 50px;
      font-size: .78rem;
      font-weight: 600
    }

    .page-body {
      padding: 28px;
      flex: 1
    }

    /* CARDS */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 16px;
      margin-bottom: 28px
    }

    .stat-card {
      background: #fff;
      border-radius: var(--r);
      padding: 20px;
      border: 1px solid rgba(74, 140, 92, .09);
      box-shadow: var(--shadow)
    }

    .sc-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: var(--forest);
      margin-bottom: 4px
    }

    .sc-lbl {
      font-size: .78rem;
      color: var(--soft);
      text-transform: uppercase;
      letter-spacing: .05em
    }

    .sc-icon {
      font-size: 1.3rem;
      float: right;
      margin-top: -4px
    }

    /* TABLE */
    .table-wrap {
      background: #fff;
      border-radius: var(--r);
      box-shadow: var(--shadow);
      border: 1px solid rgba(74, 140, 92, .09);
      overflow: hidden
    }

    .table-header {
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid rgba(74, 140, 92, .08)
    }

    .table-title {
      font-weight: 600;
      color: var(--forest);
      font-size: .95rem
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .07em;
      color: var(--soft);
      padding: 10px 16px;
      background: var(--cream);
      text-align: left;
      border-bottom: 1px solid rgba(74, 140, 92, .1)
    }

    td {
      padding: 12px 16px;
      font-size: .85rem;
      border-bottom: 1px solid rgba(74, 140, 92, .06);
      vertical-align: middle
    }

    tr:last-child td {
      border-bottom: none
    }

    tr:hover td {
      background: rgba(74, 140, 92, .02)
    }

    .tid {
      font-family: 'DM Mono', monospace;
      font-size: .78rem;
      color: var(--forest);
      background: rgba(26, 58, 42, .06);
      padding: 2px 8px;
      border-radius: 4px
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 50px;
      font-size: .72rem;
      font-weight: 700
    }

    /* FORMS */
    .form-card {
      background: #fff;
      border-radius: var(--r);
      padding: 26px;
      box-shadow: var(--shadow);
      border: 1px solid rgba(74, 140, 92, .09);
      max-width: 640px
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px
    }

    .fg {
      display: flex;
      flex-direction: column;
      gap: 6px
    }

    .fg.full {
      grid-column: 1/-1
    }

    .fg label {
      font-size: .74rem;
      font-weight: 700;
      color: var(--forest);
      text-transform: uppercase;
      letter-spacing: .05em
    }

    .fg label span {
      color: var(--terra)
    }

    .fi,
    .fs,
    .fta {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .15);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: .88rem;
      color: var(--charcoal);
      transition: border-color .2s;
      width: 100%
    }

    .fi:focus,
    .fs:focus,
    .fta:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1)
    }

    .fta {
      resize: vertical;
      min-height: 80px
    }

    .btn-save {
      background: var(--forest);
      color: #fff;
      padding: 10px 24px;
      border-radius: 8px;
      font-weight: 600;
      font-size: .88rem;
      transition: all .2s
    }

    .btn-save:hover {
      background: var(--moss)
    }

    .btn-danger {
      background: rgba(212, 116, 90, .1);
      color: var(--terra);
      padding: 8px 16px;
      border-radius: 8px;
      font-size: .82rem;
      font-weight: 600;
      border: 1px solid rgba(212, 116, 90, .2);
      transition: all .2s
    }

    .btn-danger:hover {
      background: var(--terra);
      color: #fff
    }

    .btn-edit {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      padding: 7px 14px;
      border-radius: 8px;
      font-size: .8rem;
      font-weight: 600;
      border: 1px solid rgba(74, 140, 92, .18);
      transition: all .2s
    }

    .btn-edit:hover {
      background: var(--leaf);
      color: #fff
    }

    .btn-sm {
      padding: 6px 14px;
      font-size: .78rem;
      border-radius: 6px
    }

    .msg-success {
      background: rgba(74, 140, 92, .07);
      border: 1px solid rgba(74, 140, 92, .2);
      border-radius: 8px;
      padding: 10px 16px;
      font-size: .84rem;
      color: var(--leaf);
      margin-bottom: 16px
    }

    .msg-error {
      background: rgba(212, 116, 90, .07);
      border: 1px solid rgba(212, 116, 90, .2);
      border-radius: 8px;
      padding: 10px 16px;
      font-size: .84rem;
      color: var(--terra);
      margin-bottom: 16px
    }

    .waste-chip {
      background: rgba(74, 140, 92, .08);
      color: var(--leaf);
      border-radius: 50px;
      padding: 2px 9px;
      font-size: .7rem;
      font-weight: 600;
      display: inline-block;
      margin: 1px
    }

    .img-thumb {
      width: 48px;
      height: 48px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid rgba(74, 140, 92, .15)
    }

    /* Alert / toast */
    .atop-msg {
      position: fixed;
      top: 16px;
      right: 16px;
      z-index: 999;
      background: var(--forest);
      color: #fff;
      padding: 12px 20px;
      border-radius: 10px;
      font-size: .87rem;
      font-weight: 500;
      box-shadow: 0 6px 24px rgba(0, 0, 0, .2);
      display: none
    }

    @media(max-width:768px) {
      .layout {
        grid-template-columns: 1fr
      }

      .sidebar {
        display: none
      }
    }
  </style>
</head>

<body>
  <div class="layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sb-brand">
        <div class="sb-logo">
          <div class="sb-logo-dot">🌿</div>Ecosphere
        </div>
        <div class="sb-sub">Admin Dashboard</div>
      </div>
      <nav class="sb-nav">
        <div class="sb-section">Overview</div>
        <a class="sb-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '') ?>" href="index.php">
          <span class="sb-icon">📊</span>Dashboard
        </a>
        <div class="sb-section">Manage</div>
        <a class="sb-link <?= (basename($_SERVER['PHP_SELF']) === 'requests.php' ? 'active' : '') ?>" href="requests.php">
          <span class="sb-icon">📦</span>Requests
        </a>
        <a class="sb-link <?= (basename($_SERVER['PHP_SELF']) === 'centers.php' ? 'active' : '') ?>" href="centers.php">
          <span class="sb-icon">🏭</span>Recycle Centers
        </a>
        <div class="sb-section">Site</div>
        <a class="sb-link" href="../recycle.php" target="_blank">
          <span class="sb-icon">🌍</span>View Site
        </a>
      </nav>
      <div class="sb-footer">
        <div class="sb-user">👤 <?= htmlspecialchars($adminUser) ?></div>
        <a class="sb-logout" href="?logout=1">Sign Out →</a>
      </div>
    </aside>
    <!-- MAIN -->
    <div class="main">
      <div class="topbar">
        <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
        <div class="topbar-right">
          <span class="tb-badge">♻️ Admin Panel</span>
        </div>
      </div>
      <div class="page-body">