<?php
session_start();
require_once '../config.php';
if (empty($_SESSION[ADMIN_SESSION_KEY])) {
  header('Location: login.php');
  exit;
}
if (isset($_GET['logout'])) {
  session_destroy();
  header('Location: login.php');
  exit;
}
$adminUser = $_SESSION['admin_user'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Blog Management — Ecosphere Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #1a1a18;
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --sage: #7ab88a;
      --cream: #f7f2e8;
      --parchment: #ede6d6;
      --warm: #faf8f2;
      --sand: #e8e0d0;
      --terra: #c8593a;
      --gold: #c89b3a;
      --charcoal: #2c2c2c;
      --soft: #5a5750
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth
    }

    body {
      font-family: 'Lato', sans-serif;
      background: var(--warm);
      color: var(--ink);
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
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

    /* LAYOUT */
    .layout {
      display: grid;
      grid-template-columns: 220px 1fr;
      min-height: 100vh
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
      font-size: 1.1rem;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 9px
    }

    .sb-dot {
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
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .3);
      margin-top: 4px;
      padding-left: 37px
    }

    .sb-nav {
      flex: 1;
      padding: 16px 0
    }

    .sb-section {
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .25);
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

    .sb-link:hover,
    .sb-link.active {
      background: rgba(255, 255, 255, .08);
      color: #fff
    }

    .sb-link .si {
      width: 18px;
      text-align: center;
      font-size: .95rem
    }

    .sb-badge {
      margin-left: auto;
      background: var(--terra);
      color: #fff;
      padding: 1px 8px;
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      border-radius: 50px
    }

    .sb-footer {
      padding: 16px 20px;
      border-top: 1px solid rgba(255, 255, 255, .07)
    }

    .sb-user {
      font-size: .8rem;
      color: rgba(255, 255, 255, .45)
    }

    .sb-logout {
      display: inline-block;
      margin-top: 6px;
      font-size: .75rem;
      color: var(--sage);
      opacity: .75;
      transition: opacity .2s
    }

    .sb-logout:hover {
      opacity: 1
    }

    /* MAIN */
    .main-area {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden
    }

    .topbar {
      background: #fff;
      border-bottom: 1px solid var(--sand);
      padding: 0 28px;
      height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 10
    }

    .topbar-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem;
      color: var(--forest)
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 12px
    }

    .topbar-badge {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      padding: 4px 12px;
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .1em;
      text-transform: uppercase
    }

    .page-body {
      padding: 24px 28px;
      flex: 1
    }

    /* STATS */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 24px
    }

    .stat-card {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 18px;
      border-top: 3px solid transparent
    }

    .stat-card.all {
      border-top-color: var(--soft)
    }

    .stat-card.pending {
      border-top-color: var(--gold)
    }

    .stat-card.approved {
      border-top-color: var(--leaf)
    }

    .stat-card.rejected {
      border-top-color: var(--terra)
    }

    .sc-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      display: block;
      margin-bottom: 2px
    }

    .sc-num.all {
      color: var(--ink)
    }

    .sc-num.pending {
      color: var(--gold)
    }

    .sc-num.approved {
      color: var(--leaf)
    }

    .sc-num.rejected {
      color: var(--terra)
    }

    .sc-lbl {
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--soft)
    }

    /* FILTER BAR */
    .filter-bar {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 14px 18px;
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 18px
    }

    .status-tabs {
      display: flex;
      gap: 0
    }

    .status-tab {
      padding: 8px 16px;
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft);
      border: 1px solid var(--sand);
      background: var(--cream);
      transition: all .2s;
      cursor: pointer
    }

    .status-tab:not(:last-child) {
      border-right: none
    }

    .status-tab.active {
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
      font-size: .85rem;
      color: var(--ink);
      transition: border-color .2s
    }

    .search-inp:focus {
      border-color: var(--leaf)
    }

    .search-inp::placeholder {
      color: rgba(90, 87, 80, .35)
    }

    /* BLOG TABLE */
    .table-wrap {
      background: #fff;
      border: 1px solid var(--sand);
      overflow: hidden
    }

    .tw-header {
      padding: 14px 18px;
      border-bottom: 1px solid var(--sand);
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .tw-title {
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--soft)
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--soft);
      padding: 10px 14px;
      background: var(--parchment);
      text-align: left;
      border-bottom: 1px solid var(--sand);
      white-space: nowrap
    }

    td {
      padding: 13px 14px;
      font-size: .84rem;
      border-bottom: 1px solid var(--sand);
      vertical-align: middle
    }

    tr:last-child td {
      border-bottom: none
    }

    tr:hover td {
      background: rgba(74, 140, 92, .02)
    }

    .cell-title {
      font-weight: 700;
      color: var(--forest);
      max-width: 200px
    }

    .cell-excerpt {
      font-size: .76rem;
      color: var(--soft);
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis
    }

    .cell-author strong {
      display: block;
      font-size: .84rem;
      color: var(--ink)
    }

    .cell-author span {
      font-family: 'DM Mono', monospace;
      font-size: .7rem;
      color: var(--soft)
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 10px;
      font-family: 'DM Mono', monospace;
      font-size: .6rem;
      font-weight: 500;
      letter-spacing: .1em;
      text-transform: uppercase;
      white-space: nowrap
    }

    .status-badge.pending {
      background: rgba(200, 155, 58, .1);
      color: var(--gold);
      border: 1px solid rgba(200, 155, 58, .25)
    }

    .status-badge.approved {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      border: 1px solid rgba(74, 140, 92, .25)
    }

    .status-badge.rejected {
      background: rgba(200, 89, 58, .08);
      color: var(--terra);
      border: 1px solid rgba(200, 89, 58, .2)
    }

    .date-cell {
      font-family: 'DM Mono', monospace;
      font-size: .7rem;
      color: var(--soft);
      white-space: nowrap
    }

    .action-btns {
      display: flex;
      gap: 6px;
      white-space: nowrap
    }

    .btn-approve {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      padding: 6px 12px;
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid rgba(74, 140, 92, .2);
      transition: all .2s
    }

    .btn-approve:hover {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf)
    }

    .btn-reject {
      background: rgba(200, 89, 58, .08);
      color: var(--terra);
      padding: 6px 12px;
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid rgba(200, 89, 58, .18);
      transition: all .2s
    }

    .btn-reject:hover {
      background: var(--terra);
      color: #fff;
      border-color: var(--terra)
    }

    .btn-view {
      padding: 6px 10px;
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1px solid var(--sand);
      color: var(--soft);
      transition: all .2s
    }

    .btn-view:hover {
      border-color: var(--forest);
      color: var(--forest)
    }

    .empty-row td {
      text-align: center;
      padding: 44px;
      color: var(--soft);
      font-style: italic
    }

    .spinner {
      width: 28px;
      height: 28px;
      border: 2px solid var(--sand);
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

    /* ACTION MODAL */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(26, 26, 24, .6);
      backdrop-filter: blur(6px);
      z-index: 200;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      opacity: 0;
      pointer-events: none;
      transition: opacity .25s
    }

    .modal-overlay.open {
      opacity: 1;
      pointer-events: all
    }

    .modal {
      background: #fff;
      width: 100%;
      max-width: 520px;
      border-top: 4px solid var(--gold);
      transform: scale(.96) translateY(16px);
      transition: transform .25s
    }

    .modal-overlay.open .modal {
      transform: scale(1) translateY(0)
    }

    .modal-header {
      background: var(--forest);
      padding: 20px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between
    }

    .modal-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem
    }

    .modal-close {
      background: rgba(255, 255, 255, .15);
      color: #fff;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .9rem;
      transition: background .2s
    }

    .modal-close:hover {
      background: rgba(255, 255, 255, .25)
    }

    .modal-body {
      padding: 24px
    }

    .modal-blog-title {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--forest);
      margin-bottom: 4px
    }

    .modal-blog-author {
      font-size: .82rem;
      color: var(--soft);
      margin-bottom: 20px
    }

    .action-choice {
      display: flex;
      gap: 10px;
      margin-bottom: 20px
    }

    .choice-btn {
      flex: 1;
      padding: 12px;
      text-align: center;
      font-weight: 700;
      font-size: .82rem;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 2px solid var(--sand);
      color: var(--soft);
      transition: all .2s;
      cursor: pointer;
      background: #fff
    }

    .choice-btn.approve.sel {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf)
    }

    .choice-btn.reject.sel {
      background: var(--terra);
      color: #fff;
      border-color: var(--terra)
    }

    .note-label {
      font-family: 'DM Mono', monospace;
      font-size: .62rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--soft);
      margin-bottom: 7px;
      display: block
    }

    .note-ta {
      width: 100%;
      background: var(--cream);
      border: 1.5px solid var(--sand);
      padding: 11px 13px;
      font-size: .87rem;
      color: var(--ink);
      min-height: 90px;
      resize: vertical;
      transition: border-color .2s;
      margin-bottom: 16px
    }

    .note-ta:focus {
      border-color: var(--leaf)
    }

    .note-ta::placeholder {
      color: rgba(90, 87, 80, .35)
    }

    .modal-submit {
      width: 100%;
      padding: 13px;
      font-weight: 700;
      font-size: .88rem;
      letter-spacing: .05em;
      text-transform: uppercase;
      color: #fff;
      background: var(--forest);
      transition: all .2s
    }

    .modal-submit:hover {
      background: var(--moss)
    }

    .modal-submit:disabled {
      opacity: .6;
      cursor: not-allowed
    }

    .modal-error {
      font-size: .82rem;
      color: var(--terra);
      margin-top: 8px;
      display: none
    }

    /* PAGINATION */
    #pagination {
      display: flex;
      gap: 6px;
      margin-top: 18px;
      flex-wrap: wrap
    }

    .pg-btn {
      min-width: 34px;
      height: 34px;
      padding: 0 10px;
      font-family: 'DM Mono', monospace;
      font-size: .74rem;
      font-weight: 500;
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

    /* Toast */
    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 999;
      background: var(--forest);
      color: #fff;
      padding: 12px 20px;
      font-size: .88rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 8px 28px rgba(0, 0, 0, .25);
      transform: translateY(16px);
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
      .layout {
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
  <div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sb-brand">
        <div class="sb-logo">
          <div class="sb-dot">🌿</div>Ecosphere
        </div>
        <div class="sb-sub">Editorial Admin Panel</div>
      </div>
      <nav class="sb-nav">
        <div class="sb-section">Blog Management</div>
        <a class="sb-link active" href="manage.php"><span class="si">📝</span>All Submissions <span class="sb-badge" id="pendingBadge">…</span></a>
        <div class="sb-section">Site</div>
        <a class="sb-link" href="../blog.php" target="_blank"><span class="si">🌍</span>View Public Blog</a>
        <a class="sb-link" href="../submit_blog.php" target="_blank"><span class="si">✍</span>Submit Form</a>
      </nav>
      <div class="sb-footer">
        <div class="sb-user">👤 <?= htmlspecialchars($adminUser) ?></div>
        <a class="sb-logout" href="?logout=1">Sign Out →</a>
      </div>
    </aside>

    <!-- MAIN -->
    <div class="main-area">
      <div class="topbar">
        <div class="topbar-title">Blog Submissions</div>
        <div class="topbar-right">
          <span class="topbar-badge">Admin Panel</span>
        </div>
      </div>

      <div class="page-body">

        <!-- STATS -->
        <div class="stats-row">
          <div class="stat-card all"><span class="sc-num all" id="cntAll">—</span><span class="sc-lbl">Total</span></div>
          <div class="stat-card pending"><span class="sc-num pending" id="cntPending">—</span><span class="sc-lbl">Pending Review</span></div>
          <div class="stat-card approved"><span class="sc-num approved" id="cntApproved">—</span><span class="sc-lbl">Approved</span></div>
          <div class="stat-card rejected"><span class="sc-num rejected" id="cntRejected">—</span><span class="sc-lbl">Rejected</span></div>
        </div>

        <!-- FILTER -->
        <div class="filter-bar">
          <div class="status-tabs">
            <button class="status-tab active" onclick="setStatus('all',this)">All</button>
            <button class="status-tab" onclick="setStatus('pending',this)">⏳ Pending</button>
            <button class="status-tab" onclick="setStatus('approved',this)">✅ Approved</button>
            <button class="status-tab" onclick="setStatus('rejected',this)">❌ Rejected</button>
          </div>
          <input class="search-inp" id="searchInput" type="text" placeholder="Search title, author, email…" oninput="debounceSearch()">
        </div>

        <!-- TABLE -->
        <div class="table-wrap">
          <div class="tw-header">
            <span class="tw-title" id="tableTitle">All Submissions</span>
            <span class="tw-title" id="tableCount" style="color:var(--ink)">—</span>
          </div>
          <div style="overflow-x:auto">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title & Excerpt</th>
                  <th>Author</th>
                  <th>Status</th>
                  <th>Submitted</th>
                  <th>Reviewed By</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="blogTableBody">
                <tr>
                  <td colspan="7" style="text-align:center;padding:40px">
                    <div class="spinner"></div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div id="pagination"></div>

      </div><!-- /page-body -->
    </div><!-- /main-area -->
  </div><!-- /layout -->

  <!-- ACTION MODAL -->
  <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
    <div class="modal">
      <div class="modal-header">
        <div class="modal-title" id="modalTitle">Review Blog</div>
        <button class="modal-close" onclick="closeModalDirect()">✕</button>
      </div>
      <div class="modal-body">
        <div class="modal-blog-title" id="modalBlogTitle"></div>
        <div class="modal-blog-author" id="modalBlogAuthor"></div>
        <div class="action-choice">
          <button class="choice-btn approve" id="chooseApprove" onclick="selectAction('approve')">✅ Approve</button>
          <button class="choice-btn reject" id="chooseReject" onclick="selectAction('reject')">❌ Reject</button>
        </div>
        <label class="note-label">Admin Note (shown to author)</label>
        <textarea class="note-ta" id="adminNote" placeholder="e.g. Good content, very relevant to sustainability topics. Approved.&#10;or&#10;Content is not related to waste management. Please revise."></textarea>
        <button class="modal-submit" id="modalSubmitBtn" onclick="submitAction()">Submit Review</button>
        <div class="modal-error" id="modalError"></div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    let currentStatus = 'all';
    let currentSearch = '';
    let currentPage = 1;
    let selectedAction = null;
    let selectedBlogId = null;
    let debTimer = null;

    function toast(msg, dur = 2800) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur)
    }

    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;')
    }

    function fmtDate(d) {
      if (!d) return '—';
      return new Date(d).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      })
    }

    function fmtDT(d) {
      if (!d) return '—';
      return new Date(d).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    function setStatus(s, btn) {
      currentStatus = s;
      currentPage = 1;
      document.querySelectorAll('.status-tab').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      loadBlogs();
    }

    function debounceSearch() {
      clearTimeout(debTimer);
      debTimer = setTimeout(() => {
        currentSearch = document.getElementById('searchInput').value.trim();
        currentPage = 1;
        loadBlogs();
      }, 300)
    }

    async function loadBlogs() {
      const body = document.getElementById('blogTableBody');
      body.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px"><div class="spinner"></div></td></tr>';
      const params = new URLSearchParams({
        status: currentStatus,
        search: currentSearch,
        page: currentPage
      });
      try {
        const res = await fetch('../api/admin_fetch_blogs.php?' + params);
        const data = await res.json();
        if (data.error) {
          body.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--terra)">${esc(data.error)}</td></tr>`;
          return;
        }
        updateStats(data.counts);
        renderTable(data.blogs);
        renderPagination(data.page, data.pages);
        document.getElementById('tableCount').textContent = data.total + ' result' + (data.total !== 1 ? 's' : '');
        document.getElementById('tableTitle').textContent = currentStatus === 'all' ? 'All Submissions' : currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1) + ' Blogs';
      } catch (e) {
        body.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--terra)">Server error — is XAMPP running?</td></tr>'
      }
    }

    function updateStats(c) {
      document.getElementById('cntAll').textContent = c.all || 0;
      document.getElementById('cntPending').textContent = c.pending || 0;
      document.getElementById('cntApproved').textContent = c.approved || 0;
      document.getElementById('cntRejected').textContent = c.rejected || 0;
      document.getElementById('pendingBadge').textContent = c.pending || 0;
    }

    function renderTable(blogs) {
      const body = document.getElementById('blogTableBody');
      if (!blogs.length) {
        body.innerHTML = '<tr class="empty-row"><td colspan="7">No blogs found with these filters.</td></tr>';
        return;
      }
      body.innerHTML = blogs.map(b => {
        const statusBadge = `<span class="status-badge ${b.status}">${b.status==='pending'?'⏳ Pending':b.status==='approved'?'✅ Approved':'❌ Rejected'}</span>`;
        const actions = `
      <div class="action-btns">
        ${b.status!=='approved'?`<button class="btn-approve" onclick="openModal(${b.id},'${esc(b.title)}','${esc(b.author_name)}')">Approve</button>`:''}
        ${b.status!=='rejected'?`<button class="btn-reject" onclick="openModal(${b.id},'${esc(b.title)}','${esc(b.author_name)}','reject')">Reject</button>`:''}
        ${b.status==='approved'?`<a href="../blog_single.php?id=${b.id}" target="_blank" class="btn-view">View</a>`:''}
      </div>`;
        return `<tr>
      <td style="font-family:'DM Mono',monospace;font-size:.7rem;color:var(--soft)">#${b.id}</td>
      <td><div class="cell-title">${esc(b.title)}</div>${b.admin_note?`<div class="cell-excerpt">${esc(b.admin_note)}</div>`:''}</td>
      <td class="cell-author"><strong>${esc(b.author_name)}</strong><span>${esc(b.email)}</span></td>
      <td>${statusBadge}</td>
      <td class="date-cell">${fmtDate(b.created_at)}</td>
      <td class="date-cell">${b.reviewed_by?esc(b.reviewed_by)+'<br>'+fmtDT(b.reviewed_at):'—'}</td>
      <td>${actions}</td>
    </tr>`;
      }).join('');
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
      loadBlogs();
    }

    // ── MODAL ──────────────────────────────────────────────────────
    function openModal(id, title, author, preselect = null) {
      selectedBlogId = id;
      selectedAction = preselect;
      document.getElementById('modalBlogTitle').textContent = title;
      document.getElementById('modalBlogAuthor').textContent = 'by ' + author;
      document.getElementById('adminNote').value = '';
      document.getElementById('modalError').style.display = 'none';
      document.getElementById('chooseApprove').classList.toggle('sel', preselect === 'approve');
      document.getElementById('chooseReject').classList.toggle('sel', preselect === 'reject');
      if (preselect) {
        const defaults = {
          approve: 'Great content, well-written and relevant to waste management. Approved.',
          reject: 'Content does not appear related to waste management or sustainability topics.'
        };
        document.getElementById('adminNote').value = defaults[preselect] || '';
      }
      document.getElementById('modalTitle').textContent = preselect === 'reject' ? 'Reject Blog' : 'Review & Approve';
      document.getElementById('modalOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function selectAction(a) {
      selectedAction = a;
      document.getElementById('chooseApprove').classList.toggle('sel', a === 'approve');
      document.getElementById('chooseReject').classList.toggle('sel', a === 'reject');
      const defaults = {
        approve: 'Great content, well-written and relevant to waste management. Approved.',
        reject: 'Content does not appear related to waste management or sustainability topics.'
      };
      if (!document.getElementById('adminNote').value.trim()) document.getElementById('adminNote').value = defaults[a];
      document.getElementById('modalTitle').textContent = a === 'reject' ? 'Reject Blog' : 'Approve Blog';
    }

    function closeModal(e) {
      if (e.target === document.getElementById('modalOverlay')) closeModalDirect()
    }

    function closeModalDirect() {
      document.getElementById('modalOverlay').classList.remove('open');
      document.body.style.overflow = ''
    }

    async function submitAction() {
      const note = document.getElementById('adminNote').value.trim();
      const errDiv = document.getElementById('modalError');
      const btn = document.getElementById('modalSubmitBtn');
      errDiv.style.display = 'none';
      if (!selectedAction) {
        errDiv.textContent = 'Please choose Approve or Reject.';
        errDiv.style.display = 'block';
        return;
      }
      if (!note) {
        errDiv.textContent = 'Please write an admin note before submitting.';
        errDiv.style.display = 'block';
        return;
      }
      btn.disabled = true;
      btn.textContent = 'Submitting…';
      try {
        const res = await fetch('../api/admin_blog_action.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            blog_id: selectedBlogId,
            action: selectedAction,
            admin_note: note
          })
        });
        const data = await res.json();
        if (data.success) {
          closeModalDirect();
          toast(selectedAction === 'approve' ? '✅ Blog approved and published!' : '❌ Blog rejected with note.');
          loadBlogs();
        } else {
          errDiv.textContent = '⚠️ ' + (data.error || 'Failed.');
          errDiv.style.display = 'block'
        }
      } catch (e) {
        errDiv.textContent = '⚠️ Server error.';
        errDiv.style.display = 'block'
      } finally {
        btn.disabled = false;
        btn.textContent = 'Submit Review'
      }
    }

    loadBlogs();
  </script>
</body>

</html>