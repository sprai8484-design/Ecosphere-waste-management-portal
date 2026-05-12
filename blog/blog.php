<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog — Ecosphere</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Lato:wght@300;400;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <style>
    /* ── TOKENS ──────────────────────────────────────────────────── */
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
      --soft: #5a5750;
      --r: 4px;
      --r2: 12px;
      --shadow: 0 2px 16px rgba(26, 26, 24, .07);
      --shadow-h: 0 8px 36px rgba(26, 26, 24, .14);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      scroll-behavior: smooth;
      font-size: 16px
    }

    body {
      font-family: 'Lato', sans-serif;
      background: var(--warm);
      color: var(--ink);
      overflow-x: hidden;
      line-height: 1.6
    }

    img {
      max-width: 100%;
      display: block
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
    select {
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

    /* ── NAV ─────────────────────────────────────────────────────── */
    nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(247, 242, 232, .95);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(26, 58, 42, .1);
      padding: 0 clamp(16px, 5vw, 72px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 62px;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      font-family: 'Playfair Display', serif;
      font-size: 1.25rem;
      color: var(--forest)
    }

    .nav-dot {
      width: 30px;
      height: 30px;
      background: var(--moss);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem
    }

    .nav-links {
      display: flex;
      gap: 28px;
      list-style: none
    }

    .nav-links a {
      font-size: .85rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      color: var(--soft);
      transition: color .2s
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--forest)
    }

    .nav-cta {
      background: var(--forest);
      color: #fff;
      padding: 9px 22px;
      font-size: .82rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-transform: uppercase;
      transition: all .2s
    }

    .nav-cta:hover {
      background: var(--moss)
    }

    /* ── MASTHEAD ─────────────────────────────────────────────────── */
    .masthead {
      background: var(--forest);
      padding: clamp(56px, 9vh, 110px) clamp(16px, 6vw, 80px) clamp(44px, 7vh, 80px);
      position: relative;
      overflow: hidden;
    }

    .masthead::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .04) 1px, transparent 1px);
      background-size: 36px 36px
    }

    .masthead-inner {
      position: relative;
      z-index: 2;
      max-width: 760px
    }

    .masthead-kicker {
      font-family: 'DM Mono', monospace;
      font-size: .72rem;
      font-weight: 500;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--sage);
      margin-bottom: 16px
    }

    .masthead h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.4rem, 5vw, 4rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 18px;
      animation: fadeUp .7s ease both
    }

    .masthead h1 em {
      font-style: italic;
      color: var(--gold)
    }

    .masthead-sub {
      color: rgba(255, 255, 255, .65);
      font-size: 1.05rem;
      max-width: 520px;
      line-height: 1.7;
      margin-bottom: 32px;
      animation: fadeUp .7s .1s ease both
    }

    .masthead-row {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      animation: fadeUp .7s .2s ease both
    }

    .btn-write {
      background: var(--gold);
      color: var(--forest);
      padding: 12px 26px;
      font-weight: 700;
      font-size: .88rem;
      letter-spacing: .03em;
      transition: all .25s;
      display: inline-flex;
      align-items: center;
      gap: 7px
    }

    .btn-write:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(200, 155, 58, .35)
    }

    .btn-ghost {
      background: transparent;
      color: #fff;
      padding: 12px 26px;
      border: 1.5px solid rgba(255, 255, 255, .28);
      font-weight: 600;
      font-size: .88rem;
      letter-spacing: .03em;
      transition: all .25s
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, .08);
      border-color: #fff
    }

    /* issue number decoration */
    .masthead-issue {
      position: absolute;
      right: clamp(20px, 6vw, 80px);
      bottom: 28px;
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .1em;
      color: rgba(255, 255, 255, .25);
      text-transform: uppercase
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(16px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    /* ── DIVIDER ─────────────────────────────────────────────────── */
    .section-divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 36px
    }

    .div-label {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--sage);
      white-space: nowrap
    }

    .div-line {
      flex: 1;
      height: 1px;
      background: rgba(26, 58, 42, .12)
    }

    /* ── SEARCH ──────────────────────────────────────────────────── */
    .search-bar {
      background: var(--parchment);
      border-bottom: 1px solid var(--sand);
      padding: 18px clamp(16px, 6vw, 80px);
      display: flex;
      gap: 12px;
      align-items: center;
      flex-wrap: wrap;
    }

    .search-wrap {
      flex: 1;
      min-width: 200px;
      position: relative
    }

    .search-wrap svg {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--sage)
    }

    .search-inp {
      width: 100%;
      background: #fff;
      border: 1.5px solid rgba(26, 58, 42, .14);
      padding: 10px 14px 10px 42px;
      font-size: .9rem;
      color: var(--ink);
      transition: border-color .2s;
      letter-spacing: .01em;
    }

    .search-inp:focus {
      border-color: var(--leaf)
    }

    .search-inp::placeholder {
      color: rgba(90, 87, 80, .38)
    }

    .result-meta {
      font-family: 'DM Mono', monospace;
      font-size: .72rem;
      letter-spacing: .06em;
      color: var(--soft);
      white-space: nowrap
    }

    .result-meta strong {
      color: var(--forest)
    }

    /* ── MAIN LAYOUT ─────────────────────────────────────────────── */
    .main-content {
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 0;
      max-width: 1280px;
      margin: 0 auto;
      padding: clamp(36px, 5vh, 64px) clamp(16px, 6vw, 80px)
    }

    .blog-column {
      padding-right: 48px;
      border-right: 1px solid var(--sand)
    }

    .sidebar {
      padding-left: 40px
    }

    /* ── FEATURED CARD (first blog, bigger) ─────────────────────── */
    .featured-card {
      margin-bottom: 44px;
      border-bottom: 1px solid var(--sand);
      padding-bottom: 44px;
      cursor: pointer
    }

    .featured-img {
      width: 100%;
      aspect-ratio: 16/9;
      object-fit: cover;
      overflow: hidden;
      position: relative;
      background: var(--parchment)
    }

    .featured-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .5s
    }

    .featured-card:hover .featured-img img {
      transform: scale(1.03)
    }

    .featured-img-placeholder {
      width: 100%;
      aspect-ratio: 16/9;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 5rem;
      background: linear-gradient(135deg, var(--forest), var(--moss))
    }

    .featured-kicker {
      font-family: 'DM Mono', monospace;
      font-size: .66rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--sage);
      margin: 16px 0 8px
    }

    .featured-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.5rem, 2.5vw, 2.1rem);
      color: var(--forest);
      line-height: 1.2;
      margin-bottom: 12px;
      transition: color .2s
    }

    .featured-card:hover .featured-title {
      color: var(--leaf)
    }

    .featured-excerpt {
      color: var(--soft);
      line-height: 1.7;
      font-size: .95rem;
      margin-bottom: 16px
    }

    .featured-byline {
      display: flex;
      align-items: center;
      gap: 14px
    }

    .byline-av {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .82rem;
      color: #fff;
      flex-shrink: 0
    }

    .byline-text {
      font-size: .82rem;
      color: var(--soft)
    }

    .byline-text strong {
      color: var(--ink);
      font-weight: 700
    }

    .byline-date {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .06em;
      color: var(--soft);
      margin-left: auto
    }

    .featured-badge {
      display: inline-block;
      background: var(--forest);
      color: #fff;
      padding: 3px 10px;
      font-family: 'DM Mono', monospace;
      font-size: .64rem;
      letter-spacing: .12em;
      text-transform: uppercase;
      margin-bottom: 10px
    }

    /* ── STANDARD CARDS ──────────────────────────────────────────── */
    .blog-list {
      display: flex;
      flex-direction: column;
      gap: 0
    }

    .blog-card {
      display: grid;
      grid-template-columns: 200px 1fr;
      gap: 20px;
      padding: 24px 0;
      border-bottom: 1px solid var(--sand);
      cursor: pointer;
      transition: all .2s;
      animation: cardIn .4s ease both;
    }

    @keyframes cardIn {
      from {
        opacity: 0;
        transform: translateY(8px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .blog-card:hover .bc-title {
      color: var(--leaf)
    }

    .bc-thumb {
      width: 200px;
      height: 130px;
      object-fit: cover;
      background: var(--parchment);
      flex-shrink: 0;
      overflow: hidden;
      position: relative
    }

    .bc-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .4s
    }

    .blog-card:hover .bc-thumb img {
      transform: scale(1.06)
    }

    .bc-thumb-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      background: linear-gradient(135deg, var(--forest), var(--moss))
    }

    .bc-body {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 4px 0
    }

    .bc-kicker {
      font-family: 'DM Mono', monospace;
      font-size: .63rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: var(--sage);
      margin-bottom: 6px
    }

    .bc-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest);
      line-height: 1.3;
      margin-bottom: 8px;
      transition: color .2s
    }

    .bc-excerpt {
      font-size: .84rem;
      color: var(--soft);
      line-height: 1.6;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 10px
    }

    .bc-footer {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .bc-author {
      font-size: .78rem;
      font-weight: 700;
      color: var(--ink)
    }

    .bc-sep {
      color: var(--sand)
    }

    .bc-date {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .04em;
      color: var(--soft)
    }

    .bc-read {
      margin-left: auto;
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--leaf)
    }

    /* ── EMPTY / LOADING ─────────────────────────────────────────── */
    .state-block {
      text-align: center;
      padding: 56px 20px;
      color: var(--soft)
    }

    .state-icon {
      font-size: 3rem;
      margin-bottom: 14px
    }

    .state-msg {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest);
      margin-bottom: 6px
    }

    .state-sub {
      font-size: .85rem
    }

    .spinner {
      width: 36px;
      height: 36px;
      border: 3px solid var(--sand);
      border-top-color: var(--leaf);
      border-radius: 50%;
      animation: spin .8s linear infinite;
      margin: 0 auto 14px
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    /* ── PAGINATION ──────────────────────────────────────────────── */
    #pagination {
      display: flex;
      gap: 6px;
      margin-top: 36px;
      flex-wrap: wrap
    }

    .pg-btn {
      min-width: 36px;
      height: 36px;
      padding: 0 10px;
      font-size: .82rem;
      font-weight: 700;
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
      opacity: .35;
      cursor: not-allowed
    }

    /* ── SIDEBAR ─────────────────────────────────────────────────── */
    .sidebar-block {
      margin-bottom: 36px
    }

    .sb-label {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .16em;
      text-transform: uppercase;
      color: var(--sage);
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 10px
    }

    .sb-label::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--sand)
    }

    .sb-cta-box {
      background: var(--forest);
      padding: 24px 20px;
      text-align: center
    }

    .sb-cta-icon {
      font-size: 2rem;
      margin-bottom: 10px
    }

    .sb-cta-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem;
      color: #fff;
      margin-bottom: 8px
    }

    .sb-cta-sub {
      color: rgba(255, 255, 255, .6);
      font-size: .82rem;
      line-height: 1.55;
      margin-bottom: 16px
    }

    .sb-cta-btn {
      display: block;
      background: var(--gold);
      color: var(--forest);
      padding: 10px 18px;
      font-weight: 700;
      font-size: .82rem;
      letter-spacing: .04em;
      text-transform: uppercase;
      text-align: center;
      transition: all .2s
    }

    .sb-cta-btn:hover {
      background: #d4a843
    }

    .topics-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px
    }

    .topic-tag {
      padding: 5px 12px;
      background: var(--parchment);
      border: 1px solid var(--sand);
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .03em;
      color: var(--soft);
      cursor: pointer;
      transition: all .2s
    }

    .topic-tag:hover {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest)
    }

    .latest-item {
      display: flex;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--sand);
      cursor: pointer
    }

    .latest-item:last-child {
      border-bottom: none
    }

    .latest-num {
      font-family: 'DM Mono', monospace;
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--sand);
      min-width: 24px;
      line-height: 1.3
    }

    .latest-title {
      font-family: 'Playfair Display', serif;
      font-size: .88rem;
      color: var(--forest);
      line-height: 1.35;
      transition: color .2s
    }

    .latest-item:hover .latest-title {
      color: var(--leaf)
    }

    .latest-meta {
      font-size: .72rem;
      color: var(--soft);
      margin-top: 3px
    }

    /* ── FOOTER ──────────────────────────────────────────────────── */
    footer {
      background: var(--forest);
      color: rgba(255, 255, 255, .5);
      padding: 48px clamp(16px, 6vw, 80px) 28px;
      border-top: 4px solid var(--gold)
    }

    .ft-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 40px;
      margin-bottom: 36px
    }

    .ft-logo {
      font-family: 'Playfair Display', serif;
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 10px
    }

    .ft-tagline {
      font-size: .83rem;
      line-height: 1.65
    }

    .ft-col h4 {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      letter-spacing: .14em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .4);
      margin-bottom: 14px
    }

    .ft-col a {
      display: block;
      font-size: .82rem;
      margin-bottom: 7px;
      transition: color .2s
    }

    .ft-col a:hover {
      color: var(--sage)
    }

    .ft-bottom {
      border-top: 1px solid rgba(255, 255, 255, .07);
      padding-top: 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      font-size: .78rem
    }

    /* ── TOAST ───────────────────────────────────────────────────── */
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
      max-width: 320px;
      border-left: 3px solid var(--gold)
    }

    .toast.show {
      transform: none;
      opacity: 1
    }

    /* ── REVEAL ──────────────────────────────────────────────────── */
    .reveal {
      opacity: 0;
      transform: translateY(18px);
      transition: opacity .55s, transform .55s
    }

    .reveal.visible {
      opacity: 1;
      transform: none
    }

    /* ── RESPONSIVE ──────────────────────────────────────────────── */
    @media(max-width:1024px) {
      .main-content {
        grid-template-columns: 1fr
      }

      .blog-column {
        padding-right: 0;
        border-right: none
      }

      .sidebar {
        padding-left: 0;
        border-top: 1px solid var(--sand);
        padding-top: 40px;
        margin-top: 8px
      }
    }

    @media(max-width:640px) {
      .blog-card {
        grid-template-columns: 1fr
      }

      .bc-thumb {
        width: 100%;
        height: 180px
      }

      .nav-links {
        display: none
      }

      .ft-grid {
        grid-template-columns: 1fr 1fr
      }
    }

    @media(max-width:400px) {
      .ft-grid {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>

  <!-- NAV -->
  <nav>
    <div class="nav-logo">
      <div class="nav-dot">🌿</div>Ecosphere
    </div>
    <ul class="nav-links">
      <li><a href="/Project/index.php" class="active">Home</a></li>
      <li><a href="submit_blog.php">Write</a></li>
      <li><a href="user_dashboard.php">My Blogs</a></li>
    </ul>
    <a href="submit_blog.php" class="nav-cta">Write a Blog</a>
  </nav>

  <!-- MASTHEAD -->
  <section class="masthead">
    <div class="masthead-inner">
      <div class="masthead-kicker">// Ecosphere Environmental Journal</div>
      <h1>Ideas for a<br><em>Living Planet</em></h1>
      <p class="masthead-sub">Curated stories on waste reduction, recycling, sustainability, and the people building a circular future. Written by the community, for the community.</p>
      <div class="masthead-row">
        <a href="submit_blog.php" class="btn-write">✍ Write for Us</a>
        <a href="user_dashboard.php" class="btn-ghost">My Submissions</a>
      </div>
    </div>
    <div class="masthead-issue" id="issueDate">Vol. I · <?= date('F Y') ?></div>
  </section>

  <!-- SEARCH BAR -->
  <div class="search-bar">
    <div class="search-wrap">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8" />
        <path d="m21 21-4.35-4.35" />
      </svg>
      <input class="search-inp" id="searchInput" type="text" placeholder="Search articles, authors…" oninput="debounceSearch()">
    </div>
    <div class="result-meta"><strong id="blogCount">—</strong> articles published</div>
  </div>

  <!-- MAIN CONTENT -->
  <div class="main-content">

    <!-- BLOG COLUMN -->
    <div class="blog-column">
      <div id="featuredArea"></div>

      <div class="section-divider reveal">
        <div class="div-label">Latest Articles</div>
        <div class="div-line"></div>
      </div>

      <div class="blog-list" id="blogList">
        <div class="state-block">
          <div class="spinner"></div>
          <div class="state-msg">Loading articles…</div>
        </div>
      </div>

      <div id="pagination"></div>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar">
      <div class="sidebar-block">
        <div class="sb-label">Contribute</div>
        <div class="sb-cta-box">
          <div class="sb-cta-icon">✍</div>
          <div class="sb-cta-title">Share Your Story</div>
          <div class="sb-cta-sub">Have insights on waste, sustainability, or eco-living? Write for Ecosphere. All approved articles appear publicly.</div>
          <a href="submit_blog.php" class="sb-cta-btn">Submit Article →</a>
        </div>
      </div>

      <div class="sidebar-block">
        <div class="sb-label">Topics</div>
        <div class="topics-list">
          <?php
          $topics = ['Zero Waste', 'Composting', 'Recycling', 'Plastic-Free', 'E-Waste', 'Fast Fashion', 'Urban Farming', 'Sustainability'];
          foreach ($topics as $t): ?>
            <span class="topic-tag" onclick="searchTopic('<?= $t ?>')"><?= $t ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="sidebar-block">
        <div class="sb-label">Recent Articles</div>
        <div id="latestList">
          <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="latest-item">
              <div class="latest-num">0<?= $i ?></div>
              <div>
                <div class="latest-title" style="background:var(--parchment);height:14px;border-radius:2px;margin-bottom:4px"></div>
                <div class="latest-meta" style="background:var(--sand);height:10px;border-radius:2px;width:60%"></div>
              </div>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

  </div>

  <!-- FOOTER -->
  <footer>
    <div class="ft-grid">
      <div class="ft-brand">
        <div class="ft-logo">🌿 Ecosphere Journal</div>
        <p class="ft-tagline">Community stories on waste management, recycling, reuse, and sustainable living — reviewed and curated for accuracy and relevance.</p>
      </div>
      <div class="ft-col">
        <h4>Read</h4><a href="blog.php">All Articles</a><a href="submit_blog.php">Write for Us</a><a href="user_dashboard.php">My Blogs</a>
      </div>
      <div class="ft-col">
        <h4>Ecosphere</h4><a href="reuse.php">Reuse Hub</a><a href="recycle.php">Recycle Module</a>
      </div>
      <div class="ft-col">
        <h4>Admin</h4><a href="admin/login.php">Admin Panel</a>
      </div>
    </div>
    <div class="ft-bottom">
      <div>© <?= date('Y') ?> Ecosphere Environmental Journal</div>
      <div>All blogs reviewed before publication 🌍</div>
    </div>
  </footer>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    const AV_COLORS = ['#4a8c5c', '#c8593a', '#c89b3a', '#7ab88a', '#8b6f47', '#2d5a3d', '#c4956a', '#1a3a2a'];
    let currentPage = 1;
    let currentSearch = '';
    let debounceTimer = null;

    function toast(msg, dur = 2600) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur);
    }

    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function fmtDate(d) {
      return new Date(d).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      });
    }

    function avColor(name) {
      let hash = 0;
      for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
      return AV_COLORS[Math.abs(hash) % AV_COLORS.length];
    }

    function debounceSearch() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        currentSearch = document.getElementById('searchInput').value.trim();
        loadBlogs(1);
      }, 320);
    }

    function searchTopic(topic) {
      document.getElementById('searchInput').value = topic;
      currentSearch = topic;
      loadBlogs(1);
    }

    async function loadBlogs(page = 1) {
      currentPage = page;
      const params = new URLSearchParams({
        search: currentSearch,
        page,
        limit: 8
      });
      document.getElementById('blogList').innerHTML = '<div class="state-block"><div class="spinner"></div><div class="state-msg">Loading…</div></div>';
      document.getElementById('featuredArea').innerHTML = '';

      try {
        const res = await fetch('api/fetch_blogs.php?' + params);
        const data = await res.json();
        if (data.error) {
          renderEmpty('Failed to load: ' + data.error);
          return;
        }
        document.getElementById('blogCount').textContent = data.total;
        renderBlogs(data);
        renderPagination(data.page, data.pages);
        loadLatestSidebar();
      } catch (e) {
        renderEmpty('Could not connect. Is XAMPP running?');
      }
    }

    function renderBlogs(data) {
      const all = data.blogs;
      if (!all.length) {
        renderEmpty('No articles found. Try a different search term.');
        return;
      }

      // Featured = first on page 1, no search
      if (currentPage === 1 && !currentSearch && all.length > 0) {
        const f = all[0];
        const color = avColor(f.author_name);
        const imgHtml = f.image ?
          `<div class="featured-img"><img src="${esc(f.image)}" alt="${esc(f.title)}" loading="lazy"></div>` :
          `<div class="featured-img"><div class="featured-img-placeholder">🌿</div></div>`;
        document.getElementById('featuredArea').innerHTML = `
      <div class="featured-card" onclick="window.location='blog_single.php?id=${f.id}'">
        <div class="featured-badge">Featured</div>
        ${imgHtml}
        <div class="featured-kicker">// Community Feature</div>
        <div class="featured-title">${esc(f.title)}</div>
        <div class="featured-excerpt">${esc(f.excerpt)}</div>
        <div class="featured-byline">
          <div class="byline-av" style="background:${color}">${esc(f.author_name[0])}</div>
          <div class="byline-text"><strong>${esc(f.author_name)}</strong></div>
          <div class="byline-date">${fmtDate(f.created_at)}</div>
        </div>
      </div>`;
        all.shift();
      }

      if (!all.length) {
        document.getElementById('blogList').innerHTML = '';
        return;
      }

      document.getElementById('blogList').innerHTML = all.map((b, i) => {
        const color = avColor(b.author_name);
        const thumb = b.image ?
          `<div class="bc-thumb"><img src="${esc(b.image)}" alt="${esc(b.title)}" loading="lazy"></div>` :
          `<div class="bc-thumb"><div class="bc-thumb-placeholder">🌱</div></div>`;
        return `
    <div class="blog-card" style="animation-delay:${i*0.05}s" onclick="window.location='blog_single.php?id=${b.id}'">
      ${thumb}
      <div class="bc-body">
        <div>
          <div class="bc-kicker">// Article</div>
          <div class="bc-title">${esc(b.title)}</div>
          <div class="bc-excerpt">${esc(b.excerpt)}</div>
        </div>
        <div class="bc-footer">
          <span class="bc-author">${esc(b.author_name)}</span>
          <span class="bc-sep">·</span>
          <span class="bc-date">${fmtDate(b.created_at)}</span>
          <span class="bc-read">Read →</span>
        </div>
      </div>
    </div>`;
      }).join('');
    }

    function renderEmpty(msg) {
      document.getElementById('featuredArea').innerHTML = '';
      document.getElementById('blogList').innerHTML = `<div class="state-block"><div class="state-icon">📰</div><div class="state-msg">No articles found</div><p class="state-sub">${msg}</p><br><a href="submit_blog.php" style="color:var(--leaf);font-weight:700;font-size:.85rem">Be the first to write one →</a></div>`;
    }

    async function loadLatestSidebar() {
      try {
        const res = await fetch('api/fetch_blogs.php?limit=4');
        const data = await res.json();
        const items = data.blogs || [];
        if (!items.length) return;
        document.getElementById('latestList').innerHTML = items.map((b, i) => `
      <div class="latest-item" onclick="window.location='blog_single.php?id=${b.id}'">
        <div class="latest-num">0${i+1}</div>
        <div>
          <div class="latest-title">${esc(b.title)}</div>
          <div class="latest-meta">${esc(b.author_name)} · ${fmtDate(b.created_at)}</div>
        </div>
      </div>`).join('');
      } catch {}
    }

    function renderPagination(cur, total) {
      const pg = document.getElementById('pagination');
      if (total <= 1) {
        pg.innerHTML = '';
        return;
      }
      let h = `<button class="pg-btn" onclick="loadBlogs(${cur-1})" ${cur===1?'disabled':''}>‹ Prev</button>`;
      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || Math.abs(i - cur) <= 1) h += `<button class="pg-btn ${i===cur?'active':''}" onclick="loadBlogs(${i})">${i}</button>`;
        else if (Math.abs(i - cur) === 2) h += `<button class="pg-btn" disabled>…</button>`;
      }
      h += `<button class="pg-btn" onclick="loadBlogs(${cur+1})" ${cur===total?'disabled':''}>Next ›</button>`;
      pg.innerHTML = h;
    }

    // Reveal observer
    const obs = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    }), {
      threshold: .1
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    loadBlogs(1);
  </script>
</body>

</html>