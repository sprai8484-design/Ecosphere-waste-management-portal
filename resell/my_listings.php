<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>My Listings — Ecosphere Resell</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
  <style>
    :root {
      --forest: #1a3a2a;
      --moss: #2d5a3d;
      --leaf: #4a8c5c;
      --sage: #7ab88a;
      --cream: #f5f0e8;
      --parchment: #ede6d6;
      --warm: #faf8f3;
      --sand: #e8e0d0;
      --terra: #d4745a;
      --gold: #d4a843;
      --charcoal: #2c2c2c;
      --soft: #5a5a5a;
      --r: 12px;
      --rs: 8px;
      --shadow: 0 4px 18px rgba(26, 58, 42, .09)
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
      color: var(--charcoal)
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

    input {
      font-family: inherit;
      outline: none;
      border: none
    }

    nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(245, 240, 232, .94);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(74, 140, 92, .13);
      padding: 0 clamp(16px, 4vw, 60px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 66px
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--forest)
    }

    .nav-dot {
      width: 32px;
      height: 32px;
      background: var(--moss);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .9rem
    }

    .nav-links {
      display: flex;
      gap: 22px;
      list-style: none
    }

    .nav-links a {
      font-size: .85rem;
      font-weight: 500;
      color: var(--soft);
      transition: color .2s
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--forest)
    }

    .page-header {
      background: var(--forest);
      padding: clamp(44px, 6vh, 80px) clamp(16px, 6vw, 80px);
      position: relative;
      overflow: hidden;
      border-bottom: 4px solid var(--gold)
    }

    .page-header::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px);
      background-size: 36px 36px
    }

    .ph-tag {
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--sage);
      margin-bottom: 10px;
      position: relative;
      z-index: 2
    }

    .ph-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.6rem, 3.5vw, 2.6rem);
      color: #fff;
      margin-bottom: 10px;
      position: relative;
      z-index: 2
    }

    .ph-sub {
      color: rgba(255, 255, 255, .6);
      font-size: .9rem;
      max-width: 480px;
      line-height: 1.65;
      position: relative;
      z-index: 2
    }

    /* LOOKUP */
    .lookup-wrap {
      max-width: 700px;
      margin: 0 auto;
      padding: clamp(28px, 5vh, 52px) clamp(16px, 4vw, 32px)
    }

    .lookup-card {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 28px 32px;
      box-shadow: var(--shadow);
      border-radius: var(--r);
      margin-bottom: 24px
    }

    .lc-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest);
      margin-bottom: 6px
    }

    .lc-sub {
      color: var(--soft);
      font-size: .84rem;
      margin-bottom: 18px;
      line-height: 1.55
    }

    .lc-row {
      display: flex;
      gap: 10px
    }

    .lc-inp {
      flex: 1;
      background: var(--cream);
      border: 1.5px solid var(--sand);
      border-radius: 50px;
      padding: 12px 18px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s
    }

    .lc-inp:focus {
      border-color: var(--leaf);
      outline: none
    }

    .lc-inp::placeholder {
      color: rgba(90, 90, 90, .38)
    }

    .lc-btn {
      background: var(--forest);
      color: #fff;
      padding: 12px 24px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .85rem;
      transition: all .2s;
      white-space: nowrap
    }

    .lc-btn:hover {
      background: var(--moss)
    }

    .lc-btn:disabled {
      opacity: .6;
      cursor: not-allowed
    }

    .lc-err {
      font-size: .82rem;
      color: var(--terra);
      margin-top: 8px;
      display: none
    }

    /* STATS */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 24px
    }

    .stat-box {
      background: #fff;
      border: 1px solid var(--sand);
      padding: 18px;
      text-align: center;
      border-radius: var(--r)
    }

    .sb-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      display: block;
      margin-bottom: 2px
    }

    .sb-lbl {
      font-size: .68rem;
      font-family: 'DM Mono', monospace;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--soft)
    }

    /* LISTINGS */
    .listing-card {
      background: #fff;
      border: 1px solid var(--sand);
      border-radius: var(--r);
      overflow: hidden;
      margin-bottom: 16px;
      box-shadow: var(--shadow);
      transition: box-shadow .2s
    }

    .listing-card:hover {
      box-shadow: 0 6px 24px rgba(26, 58, 42, .12)
    }

    .lc-top {
      display: flex;
      gap: 14px;
      padding: 18px 20px;
      border-bottom: 1px solid var(--sand)
    }

    .lc-thumb {
      width: 72px;
      height: 58px;
      flex-shrink: 0;
      border-radius: 8px;
      overflow: hidden;
      background: var(--parchment);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem
    }

    .lc-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .lc-info {
      flex: 1
    }

    .lci-uid {
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      color: var(--soft);
      letter-spacing: .06em;
      margin-bottom: 3px
    }

    .lci-name {
      font-family: 'Playfair Display', serif;
      font-size: .98rem;
      color: var(--forest);
      line-height: 1.3;
      margin-bottom: 4px
    }

    .lci-meta {
      font-size: .78rem;
      color: var(--soft)
    }

    .lc-right {
      text-align: right;
      flex-shrink: 0
    }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 11px;
      font-size: .68rem;
      font-family: 'DM Mono', monospace;
      font-weight: 500;
      letter-spacing: .08em;
      text-transform: uppercase;
      border-radius: 50px;
      margin-bottom: 5px
    }

    .sb-pending {
      background: rgba(212, 168, 67, .1);
      color: var(--gold);
      border: 1px solid rgba(212, 168, 67, .25)
    }

    .sb-approved {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf);
      border: 1px solid rgba(74, 140, 92, .25)
    }

    .sb-sold {
      background: rgba(26, 58, 42, .1);
      color: var(--forest);
      border: 1px solid rgba(26, 58, 42, .25)
    }

    .sb-rejected {
      background: rgba(212, 116, 90, .08);
      color: var(--terra);
      border: 1px solid rgba(212, 116, 90, .2)
    }

    .lc-price {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--forest);
      font-weight: 700
    }

    .lc-note {
      padding: 12px 20px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      font-size: .83rem
    }

    .lc-note.pending-note {
      background: rgba(212, 168, 67, .05)
    }

    .lc-note.approved-note {
      background: rgba(74, 140, 92, .04)
    }

    .lc-note.rejected-note {
      background: rgba(212, 116, 90, .05)
    }

    .note-icon {
      font-size: 1rem;
      flex-shrink: 0
    }

    .note-label {
      font-size: .66rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 2px
    }

    .pending-note .note-label {
      color: var(--gold)
    }

    .approved-note .note-label {
      color: var(--leaf)
    }

    .rejected-note .note-label {
      color: var(--terra)
    }

    .lc-action {
      padding: 10px 20px;
      border-top: 1px solid var(--sand);
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center
    }

    .act-link {
      padding: 7px 16px;
      font-size: .76rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      border: 1.5px solid var(--forest);
      color: var(--forest);
      transition: all .2s;
      border-radius: 50px
    }

    .act-link:hover,
    .act-link.filled {
      background: var(--forest);
      color: #fff
    }

    .act-link.faded {
      border-color: var(--sand);
      color: var(--soft);
      pointer-events: none;
      opacity: .5
    }

    .state-block {
      text-align: center;
      padding: 52px 20px;
      color: var(--soft)
    }

    .state-icon {
      font-size: 3rem;
      margin-bottom: 12px
    }

    .spinner {
      width: 34px;
      height: 34px;
      border: 3px solid var(--mint);
      border-top-color: var(--leaf);
      border-radius: 50%;
      animation: spin .8s linear infinite;
      margin: 0 auto 12px
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    .toast {
      position: fixed;
      bottom: 26px;
      right: 26px;
      z-index: 9999;
      background: var(--forest);
      color: #fff;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: .88rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 8px 28px rgba(26, 58, 42, .3);
      transform: translateY(16px);
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      max-width: 320px
    }

    .toast.show {
      transform: none;
      opacity: 1
    }

    @media(max-width:540px) {
      .nav-links {
        display: none
      }

      .lc-row {
        flex-direction: column
      }

      .stats-row {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>
  <nav>
    <a href="resell.php" class="nav-logo">
      <div class="nav-dot">🌿</div>Ecosphere
    </a>
    <ul class="nav-links">
      <li><a href="resell.php">Marketplace</a></li>
      <li><a href="sell.php">Sell Item</a></li>
      <li><a href="my_listings.php" class="active">My Listings</a></li>
    </ul>
  </nav>

  <div class="page-header">
    <div class="ph-tag">// Seller Dashboard</div>
    <h1 class="ph-title">My Resell Listings</h1>
    <p class="ph-sub">Track your listings, see admin decisions, and check how many items you've sold. Enter your email to find all your submissions.</p>
  </div>

  <div class="lookup-wrap">
    <div class="lookup-card">
      <div class="lc-title">Find Your Listings</div>
      <div class="lc-sub">Enter the email you used when listing your items. No account needed.</div>
      <div class="lc-row">
        <input class="lc-inp" id="emailInput" type="email" placeholder="you@example.com" onkeydown="if(event.key==='Enter')lookup()">
        <button class="lc-btn" id="lookupBtn" onclick="lookup()">🔍 Find Listings</button>
      </div>
      <div class="lc-err" id="lookupErr"></div>
    </div>

    <div id="statsRow" style="display:none" class="stats-row">
      <div class="stat-box"><span class="sb-num" id="statTotal">0</span><span class="sb-lbl">Total Listed</span></div>
      <div class="stat-box"><span class="sb-num" id="statApproved" style="color:var(--leaf)">0</span><span class="sb-lbl">Live / Approved</span></div>
      <div class="stat-box"><span class="sb-num" id="statSold" style="color:var(--forest)">0</span><span class="sb-lbl">Sold</span></div>
    </div>

    <div id="listingsWrap">
      <div class="state-block">
        <div class="state-icon">📦</div>
        <p style="font-size:.88rem">Enter your email above to see your listings</p>
      </div>
    </div>

    <div id="addMoreBtn" style="display:none;text-align:center;margin-top:16px">
      <a href="sell.php" style="display:inline-block;background:var(--forest);color:#fff;padding:12px 28px;border-radius:50px;font-weight:700;font-size:.88rem;transition:all .2s">+ List Another Item →</a>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    const CAT_EMOJI = {
      Electronics: '💻',
      Furniture: '🪑',
      Clothes: '👕',
      Books: '📚',
      'Sports & Fitness': '⚽',
      'Home & Kitchen': '🏠',
      'Toys & Games': '🎮',
      Garden: '🌿',
      Other: '📦'
    };

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

    async function lookup() {
      const email = document.getElementById('emailInput').value.trim();
      const err = document.getElementById('lookupErr');
      const btn = document.getElementById('lookupBtn');
      err.style.display = 'none';

      if (!email || !/\S+@\S+\.\S+/.test(email)) {
        err.textContent = 'Please enter a valid email address.';
        err.style.display = 'block';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Loading…';
      document.getElementById('listingsWrap').innerHTML = '<div class="state-block"><div class="spinner"></div><p style="font-size:.85rem;color:var(--soft)">Looking up your listings…</p></div>';
      document.getElementById('statsRow').style.display = 'none';
      document.getElementById('addMoreBtn').style.display = 'none';

      try {
        const res = await fetch('api/fetch_my_listings.php?email=' + encodeURIComponent(email));
        const data = await res.json();
        if (data.error) {
          err.textContent = '⚠️ ' + data.error;
          err.style.display = 'block';
          document.getElementById('listingsWrap').innerHTML = '';
          return;
        }
        renderListings(data.listings);
      } catch {
        err.textContent = '⚠️ Server error. Is XAMPP running?';
        err.style.display = 'block';
        document.getElementById('listingsWrap').innerHTML = '';
      } finally {
        btn.disabled = false;
        btn.textContent = '🔍 Find Listings';
      }
    }

    function renderListings(listings) {
      const total = listings.length;
      const approved = listings.filter(l => l.status === 'Approved').length;
      const sold = listings.filter(l => l.status === 'Sold').length;
      document.getElementById('statTotal').textContent = total;
      document.getElementById('statApproved').textContent = approved;
      document.getElementById('statSold').textContent = sold;
      document.getElementById('statsRow').style.display = 'grid';
      document.getElementById('addMoreBtn').style.display = 'block';

      if (!listings.length) {
        document.getElementById('listingsWrap').innerHTML = `<div class="state-block"><div class="state-icon">📭</div><p style="font-size:.88rem">No listings found for this email.<br><a href="sell.php" style="color:var(--leaf);font-weight:700">List your first item →</a></p></div>`;
        return;
      }

      const statusMap = {
        Pending: {
          cls: 'sb-pending',
          icon: '⏳',
          label: 'Pending Review'
        },
        Approved: {
          cls: 'sb-approved',
          icon: '✅',
          label: 'Approved'
        },
        Sold: {
          cls: 'sb-sold',
          icon: '🏷️',
          label: 'Sold'
        },
        Rejected: {
          cls: 'sb-rejected',
          icon: '❌',
          label: 'Rejected'
        }
      };
      const noteClass = {
        Pending: 'pending-note',
        Approved: 'approved-note',
        Sold: 'approved-note',
        Rejected: 'rejected-note'
      };
      const noteDefaults = {
        Pending: 'Under review. Usually completes within 24 hours.',
        Approved: 'Your listing is live and visible to buyers.',
        Sold: 'Item sold. Congratulations!',
        Rejected: ''
      };

      document.getElementById('listingsWrap').innerHTML = listings.map(l => {
        const st = statusMap[l.status] || statusMap.Pending;
        const emoji = CAT_EMOJI[l.category] || '📦';
        const thumb = l.image ? `<img src="${esc(l.image)}" alt="">` : emoji;
        const noteText = l.admin_note || noteDefaults[l.status] || '';

        return `<div class="listing-card">
      <div class="lc-top">
        <div class="lc-thumb">${l.image ? `<img src="${esc(l.image)}" alt="">` : emoji}</div>
        <div class="lc-info">
          <div class="lci-uid">${esc(l.product_uid)}</div>
          <div class="lci-name">${esc(l.product_name)}</div>
          <div class="lci-meta">${esc(l.category)} · ${esc(l.condition)} · Qty: ${l.quantity} · Listed ${fmtDate(l.created_at)}</div>
        </div>
        <div class="lc-right">
          <div class="status-badge ${st.cls}">${st.icon} ${st.label}</div>
          <div class="lc-price">${esc(l.price_fmt)}</div>
          ${l.sale_count > 0 ? `<div style="font-size:.72rem;color:var(--soft);margin-top:3px">${l.sale_count} sale${l.sale_count>1?'s':''}</div>` : ''}
        </div>
      </div>
      <div class="lc-note ${noteClass[l.status] || 'pending-note'}">
        <div class="note-icon">${st.icon}</div>
        <div><div class="note-label">Admin Note</div><div>${esc(noteText)}</div></div>
      </div>
      <div class="lc-action">
        ${l.status==='Approved' ? `<a href="resell.php" class="act-link filled">View in Marketplace →</a>` : `<span class="act-link faded">Not yet live</span>`}
        <a href="sell.php" class="act-link">+ New Listing</a>
      </div>
    </div>`;
      }).join('');
    }
  </script>
</body>

</html>