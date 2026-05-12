<?php
// reuse_details.php — Single idea detail page
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: reuse.php');
  exit;
}

// Pre-fetch for SEO meta (JS will also fetch it)
try {
  $pdo  = getDB();
  $stmt = $pdo->prepare("SELECT title, description, category, author, image FROM reuse_ideas WHERE id = :id AND is_approved = 1 LIMIT 1");
  $stmt->execute([':id' => $id]);
  $meta = $stmt->fetch();
  if (!$meta) {
    header('Location: reuse.php?error=not_found');
    exit;
  }
} catch (Exception $e) {
  $meta = ['title' => 'Reuse Idea', 'description' => '', 'category' => '', 'author' => '', 'image' => null];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($meta['title']) ?> — Ecosphere Reuse</title>
  <meta name="description" content="<?= htmlspecialchars(mb_substr(strip_tags($meta['description']), 0, 155)) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono&display=swap" rel="stylesheet">
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
      --earth: #8b6f47;
      --clay: #c4956a;
      --terra: #d4745a;
      --charcoal: #2c2c2c;
      --soft: #5a5a5a;
      --gold: #d4a843;
      --r: 16px;
      --rs: 10px;
      --shadow: 0 4px 20px rgba(26, 58, 42, .09);
      --shadow-h: 0 14px 44px rgba(26, 58, 42, .18)
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
      font-family: 'DM Sans', sans-serif;
      background: var(--warm);
      color: var(--charcoal);
      overflow-x: hidden
    }

    img {
      max-width: 100%
    }

    button {
      cursor: pointer;
      font-family: inherit;
      border: none;
      outline: none
    }

    input,
    textarea,
    select {
      font-family: inherit;
      outline: none;
      border: none
    }

    a {
      text-decoration: none;
      color: inherit
    }

    ::-webkit-scrollbar {
      width: 5px
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage);
      border-radius: 3px
    }

    /* NAV */
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
      height: 64px
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 9px;
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
      gap: 26px;
      list-style: none
    }

    .nav-links a {
      font-size: .88rem;
      font-weight: 500;
      color: var(--soft);
      transition: color .2s
    }

    .nav-links a:hover {
      color: var(--forest)
    }

    .nav-cta {
      background: var(--forest);
      color: #fff;
      padding: 9px 22px;
      border-radius: 50px;
      font-size: .85rem;
      font-weight: 500;
      transition: all .2s
    }

    .nav-cta:hover {
      background: var(--moss)
    }

    /* BREADCRUMB */
    .breadcrumb {
      padding: 16px clamp(16px, 5vw, 80px);
      font-size: .82rem;
      color: var(--soft)
    }

    .breadcrumb a {
      color: var(--leaf);
      transition: color .2s
    }

    .breadcrumb a:hover {
      color: var(--forest)
    }

    .breadcrumb span {
      margin: 0 6px;
      color: rgba(90, 90, 90, .4)
    }

    /* HERO DETAIL */
    .detail-hero {
      background: linear-gradient(140deg, var(--forest), var(--moss));
      padding: clamp(40px, 6vh, 72px) clamp(16px, 5vw, 80px);
      display: grid;
      grid-template-columns: 1fr 420px;
      gap: 48px;
      align-items: center;
      position: relative;
      overflow: hidden
    }

    .detail-hero::before {
      content: '';
      position: absolute;
      top: -30%;
      right: -10%;
      width: 50vw;
      height: 50vw;
      border-radius: 50%;
      background: rgba(255, 255, 255, .03);
      pointer-events: none
    }

    .dh-content {
      position: relative;
      z-index: 2
    }

    .dh-badges {
      display: flex;
      gap: 9px;
      flex-wrap: wrap;
      margin-bottom: 18px
    }

    .dh-badge {
      padding: 4px 13px;
      border-radius: 50px;
      font-size: .74rem;
      font-weight: 700
    }

    .dh-badge.cat {
      background: rgba(255, 255, 255, .14);
      color: var(--mint)
    }

    .dh-badge.diff-Easy {
      background: rgba(74, 140, 92, .7);
      color: #fff
    }

    .dh-badge.diff-Medium {
      background: rgba(212, 168, 67, .8);
      color: var(--forest)
    }

    .dh-badge.diff-Hard {
      background: rgba(212, 116, 90, .8);
      color: #fff
    }

    .dh-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 3.5vw, 3rem);
      color: #fff;
      line-height: 1.15;
      margin-bottom: 14px
    }

    .dh-meta {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 22px
    }

    .dh-meta-item {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: .85rem;
      color: rgba(255, 255, 255, .7)
    }

    .dh-author {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 26px
    }

    .dh-av {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #fff;
      font-size: .9rem
    }

    .dh-author-info small {
      display: block;
      color: rgba(255, 255, 255, .5);
      font-size: .72rem;
      text-transform: uppercase;
      letter-spacing: .06em
    }

    .dh-author-info strong {
      color: #fff;
      font-size: .9rem
    }

    .dh-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap
    }

    .btn-like {
      background: rgba(255, 255, 255, .14);
      color: #fff;
      border: 1.5px solid rgba(255, 255, 255, .3);
      padding: 11px 22px;
      border-radius: 50px;
      font-size: .9rem;
      font-weight: 600;
      transition: all .25s;
      display: flex;
      align-items: center;
      gap: 7px
    }

    .btn-like:hover,
    .btn-like.liked {
      background: var(--terra);
      border-color: var(--terra);
      transform: translateY(-1px)
    }

    .btn-save {
      background: rgba(255, 255, 255, .14);
      color: #fff;
      border: 1.5px solid rgba(255, 255, 255, .3);
      padding: 11px 22px;
      border-radius: 50px;
      font-size: .9rem;
      font-weight: 600;
      transition: all .25s;
      display: flex;
      align-items: center;
      gap: 7px
    }

    .btn-save:hover,
    .btn-save.saved {
      background: var(--gold);
      border-color: var(--gold);
      color: var(--forest);
      transform: translateY(-1px)
    }

    .dh-image-wrap {
      position: relative;
      z-index: 2;
      border-radius: 18px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .3)
    }

    .dh-image-wrap img {
      width: 100%;
      display: block
    }

    .dh-image-placeholder {
      width: 100%;
      aspect-ratio: 4/3;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 6rem;
      border-radius: 18px
    }

    /* MAIN BODY */
    .detail-body {
      display: grid;
      grid-template-columns: 1fr 340px;
      gap: 32px;
      padding: clamp(32px, 5vh, 64px) clamp(16px, 5vw, 80px);
      align-items: start
    }

    /* LEFT COLUMN */
    .section-block {
      background: #fff;
      border-radius: var(--r);
      padding: 28px 30px;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      margin-bottom: 24px
    }

    .sb-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
      color: var(--forest);
      margin-bottom: 18px;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .sb-title-line {
      flex: 1;
      height: 1px;
      background: rgba(74, 140, 92, .1);
      margin-left: 10px
    }

    .desc-text {
      font-size: .93rem;
      color: var(--soft);
      line-height: 1.75
    }

    /* Materials */
    .materials-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 9px
    }

    .mat-tag {
      background: var(--cream);
      border: 1px solid rgba(74, 140, 92, .14);
      border-radius: 50px;
      padding: 6px 15px;
      font-size: .82rem;
      color: var(--charcoal);
      display: flex;
      align-items: center;
      gap: 5px
    }

    .mat-tag::before {
      content: '•';
      color: var(--leaf)
    }

    /* Steps */
    .step-item {
      display: flex;
      gap: 0;
      margin-bottom: 0
    }

    .step-left {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 48px;
      flex-shrink: 0
    }

    .step-num {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: var(--forest);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .88rem;
      z-index: 1;
      flex-shrink: 0
    }

    .step-num.current {
      background: var(--gold);
      color: var(--forest)
    }

    .step-vline {
      width: 2px;
      flex: 1;
      min-height: 20px;
      background: rgba(74, 140, 92, .1);
      margin: 4px 0
    }

    .step-item:last-child .step-vline {
      display: none
    }

    .step-right {
      padding: 4px 0 28px 16px;
      flex: 1
    }

    .step-title-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 5px
    }

    .step-title {
      font-weight: 600;
      font-size: .92rem;
      color: var(--forest)
    }

    .step-done-btn {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      border: 2px solid rgba(74, 140, 92, .25);
      background: none;
      transition: all .2s;
      flex-shrink: 0
    }

    .step-done-btn.done {
      background: var(--leaf);
      border-color: var(--leaf);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .step-done-btn.done::after {
      content: '✓';
      font-size: .7rem;
      color: #fff
    }

    .step-desc {
      font-size: .86rem;
      color: var(--soft);
      line-height: 1.6
    }

    .progress-bar-wrap {
      background: var(--cream);
      border-radius: 50px;
      height: 6px;
      margin-top: 16px;
      overflow: hidden
    }

    .progress-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, var(--leaf), var(--sage));
      border-radius: 50px;
      transition: width .5s ease
    }

    /* RIGHT SIDEBAR */
    .sidebar-card {
      background: #fff;
      border-radius: var(--r);
      padding: 22px;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      margin-bottom: 18px
    }

    .sc-title {
      font-size: .8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--soft);
      margin-bottom: 14px
    }

    .quick-stat {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 9px 0;
      border-bottom: 1px solid rgba(74, 140, 92, .06);
      font-size: .88rem
    }

    .quick-stat:last-child {
      border-bottom: none
    }

    .qs-label {
      color: var(--soft)
    }

    .qs-val {
      font-weight: 600;
      color: var(--forest)
    }

    .share-btns {
      display: flex;
      gap: 9px;
      flex-wrap: wrap
    }

    .share-btn {
      flex: 1;
      min-width: 80px;
      padding: 9px 14px;
      border-radius: var(--rs);
      font-size: .8rem;
      font-weight: 600;
      text-align: center;
      border: 1.5px solid rgba(74, 140, 92, .18);
      color: var(--soft);
      transition: all .2s;
      background: #fff;
      cursor: pointer
    }

    .share-btn:hover {
      border-color: var(--leaf);
      color: var(--leaf)
    }

    .related-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 0;
      border-bottom: 1px solid rgba(74, 140, 92, .06);
      cursor: pointer;
      transition: all .2s
    }

    .related-link:last-child {
      border-bottom: none
    }

    .related-link:hover .rl-title {
      color: var(--leaf)
    }

    .rl-img {
      width: 44px;
      height: 44px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      flex-shrink: 0
    }

    .rl-title {
      font-size: .84rem;
      font-weight: 600;
      color: var(--forest);
      transition: color .2s
    }

    .rl-cat {
      font-size: .72rem;
      color: var(--soft);
      margin-top: 2px
    }

    /* COMMENTS */
    .comments-section {
      background: #fff;
      border-radius: var(--r);
      padding: 28px 30px;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow)
    }

    .comment-form {
      margin-bottom: 28px
    }

    .cf-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
      margin-bottom: 14px
    }

    .cf-inp,
    .cf-ta {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .16);
      border-radius: var(--rs);
      padding: 11px 15px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s;
      width: 100%
    }

    .cf-inp:focus,
    .cf-ta:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1)
    }

    .cf-inp::placeholder,
    .cf-ta::placeholder {
      color: rgba(90, 90, 90, .4)
    }

    .cf-ta {
      resize: vertical;
      min-height: 90px
    }

    .btn-comment {
      background: var(--forest);
      color: #fff;
      padding: 11px 26px;
      border-radius: 50px;
      font-weight: 600;
      font-size: .9rem;
      transition: all .2s;
      display: flex;
      align-items: center;
      gap: 7px
    }

    .btn-comment:hover {
      background: var(--moss)
    }

    .btn-comment:disabled {
      opacity: .6;
      cursor: not-allowed
    }

    .comments-list {
      display: flex;
      flex-direction: column;
      gap: 0
    }

    .comment-item {
      display: flex;
      gap: 12px;
      padding: 16px 0;
      border-bottom: 1px solid rgba(74, 140, 92, .06)
    }

    .comment-item:last-child {
      border-bottom: none
    }

    .c-av {
      width: 34px;
      height: 34px;
      min-width: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      font-weight: 700;
      color: #fff
    }

    .c-bubble {
      flex: 1
    }

    .c-name {
      font-weight: 600;
      font-size: .88rem;
      color: var(--forest);
      margin-bottom: 3px
    }

    .c-text {
      font-size: .86rem;
      color: var(--soft);
      line-height: 1.55
    }

    .c-time {
      font-size: .72rem;
      color: rgba(90, 90, 90, .45);
      margin-top: 5px
    }

    .no-comments {
      text-align: center;
      padding: 28px;
      color: var(--soft);
      font-size: .88rem
    }

    /* LOADING SKELETON */
    .skeleton {
      background: linear-gradient(90deg, var(--cream) 25%, var(--parchment) 50%, var(--cream) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite;
      border-radius: 8px
    }

    @keyframes shimmer {
      0% {
        background-position: 200% 0
      }

      100% {
        background-position: -200% 0
      }
    }

    /* TOAST */
    .toast {
      position: fixed;
      bottom: 26px;
      right: 26px;
      z-index: 999;
      background: var(--forest);
      color: #fff;
      padding: 12px 20px;
      border-radius: 12px;
      font-size: .88rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 8px 28px rgba(26, 58, 42, .28);
      transform: translateY(16px);
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      max-width: 310px
    }

    .toast.show {
      transform: none;
      opacity: 1
    }

    /* RESPONSIVE */
    @media(max-width:900px) {
      .detail-hero {
        grid-template-columns: 1fr
      }

      .dh-image-wrap {
        display: none
      }

      .detail-body {
        grid-template-columns: 1fr
      }
    }

    @media(max-width:540px) {
      .cf-row {
        grid-template-columns: 1fr
      }

      .nav-links {
        display: none
      }
    }
  </style>
</head>

<body>

  <nav>
    <a href="reuse.php" class="nav-logo">
      <div class="nav-dot">🌿</div>Ecosphere
    </a>
    <ul class="nav-links">
      <li><a href="reuse.php">Ideas</a></li>
      <li><a href="add_idea.php">Share Idea</a></li>
      <li><a href="recycle.php">Recycle</a></li>
    </ul>
    <a href="add_idea.php" class="nav-cta">+ Share Idea</a>
  </nav>

  <div class="breadcrumb">
    <a href="reuse.php">Reuse Hub</a>
    <span>›</span>
    <span id="breadcrumbTitle"><?= htmlspecialchars($meta['title']) ?></span>
  </div>

  <!-- HERO (populated by JS) -->
  <div class="detail-hero" id="detailHero">
    <div class="dh-content">
      <div class="dh-badges" id="dhBadges">
        <div class="skeleton" style="height:24px;width:80px"></div>
      </div>
      <div class="dh-title" id="dhTitle">
        <div class="skeleton" style="height:44px;width:70%"></div>
      </div>
      <div class="dh-meta" id="dhMeta"></div>
      <div class="dh-author" id="dhAuthor"></div>
      <div class="dh-actions" id="dhActions"></div>
    </div>
    <div id="dhImage"></div>
  </div>

  <!-- BODY -->
  <div class="detail-body">
    <!-- LEFT -->
    <div>
      <!-- Description -->
      <div class="section-block" id="blockDesc">
        <div class="sb-title">📖 About This Idea <div class="sb-title-line"></div>
        </div>
        <div class="desc-text" id="descText">
          <div class="skeleton" style="height:16px;margin-bottom:8px"></div>
          <div class="skeleton" style="height:16px;width:80%;margin-bottom:8px"></div>
          <div class="skeleton" style="height:16px;width:60%"></div>
        </div>
      </div>
      <!-- Materials -->
      <div class="section-block" id="blockMaterials">
        <div class="sb-title">🛠 Materials Needed <div class="sb-title-line"></div>
        </div>
        <div class="materials-grid" id="materialsList">
          <div class="skeleton" style="height:32px;width:100px"></div>
        </div>
      </div>
      <!-- Steps -->
      <div class="section-block" id="blockSteps">
        <div class="sb-title">📋 Step-by-Step Guide <div class="sb-title-line"></div>
        </div>
        <div style="margin-bottom:14px">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
            <span style="font-size:.8rem;color:var(--soft)">Your progress</span>
            <span id="progressLabel" style="font-size:.8rem;font-weight:600;color:var(--leaf)">0 / 0 steps</span>
          </div>
          <div class="progress-bar-wrap">
            <div class="progress-bar-fill" id="progressFill" style="width:0%"></div>
          </div>
        </div>
        <div id="stepsList">
          <div class="skeleton" style="height:80px"></div>
        </div>
      </div>
      <!-- Comments -->
      <div class="comments-section">
        <div class="sb-title" style="margin-bottom:20px">💬 Community Comments <div class="sb-title-line"></div>
        </div>
        <!-- Form -->
        <div class="comment-form">
          <div class="cf-row">
            <input class="cf-inp" id="commentName" type="text" placeholder="Your name *" maxlength="100">
            <input class="cf-inp" id="commentEmail" type="email" placeholder="Email (optional)">
          </div>
          <textarea class="cf-ta" id="commentText" placeholder="Share your experience, tips, or questions…" maxlength="1000"></textarea>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;flex-wrap:wrap;gap:10px">
            <span id="charCount" style="font-size:.75rem;color:var(--soft)">0 / 1000</span>
            <button class="btn-comment" id="commentBtn" onclick="submitComment()">💬 Post Comment</button>
          </div>
          <div id="commentError" style="display:none;color:var(--terra);font-size:.84rem;margin-top:8px"></div>
        </div>
        <div id="commentsList">
          <div class="no-comments">Loading comments…</div>
        </div>
      </div>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div>
      <!-- Quick stats -->
      <div class="sidebar-card" id="quickStats">
        <div class="sc-title">Quick Info</div>
        <div class="skeleton" style="height:20px;margin-bottom:8px"></div>
        <div class="skeleton" style="height:20px;width:80%;margin-bottom:8px"></div>
        <div class="skeleton" style="height:20px;width:60%"></div>
      </div>
      <!-- Share -->
      <div class="sidebar-card">
        <div class="sc-title">Share This Idea</div>
        <div class="share-btns">
          <button class="share-btn" onclick="shareLink('whatsapp')">💬 WhatsApp</button>
          <button class="share-btn" onclick="shareLink('copy')">🔗 Copy Link</button>
          <button class="share-btn" onclick="shareLink('twitter')">🐦 Twitter</button>
        </div>
      </div>
      <!-- Related -->
      <div class="sidebar-card">
        <div class="sc-title">More Ideas</div>
        <div id="relatedList">
          <div class="skeleton" style="height:54px;margin-bottom:8px"></div>
          <div class="skeleton" style="height:54px;width:80%"></div>
        </div>
      </div>
      <!-- Back -->
      <a href="reuse.php" style="display:block">
        <div style="background:var(--forest);border-radius:var(--r);padding:16px 20px;text-align:center;color:#fff;font-weight:600;font-size:.9rem;transition:opacity .2s" onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">← Back to All Ideas</div>
      </a>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    const IDEA_ID = <?= $id ?>;
    const AV_COLORS = ['#4a8c5c', '#d4745a', '#d4a843', '#7ab88a', '#8b6f47', '#2d5a3d', '#c4956a', '#1a3a2a'];
    const CAT_EMOJI = {
      Plastic: '♻️',
      Clothes: '👗',
      Paper: '📄',
      Glass: '🍶',
      Wood: '🪵',
      Metal: '⚙️',
      Garden: '🌿',
      Electronics: '📱'
    };
    const CAT_GRAD = {
      Plastic: 'linear-gradient(135deg,#3d6b8c,#5a9ec4)',
      Clothes: 'linear-gradient(135deg,#6b5a4e,#9e8870)',
      Paper: 'linear-gradient(135deg,#5a4a2d,#8c7a50)',
      Glass: 'linear-gradient(135deg,#2d5a6b,#4a8c9e)',
      Wood: 'linear-gradient(135deg,#5c4a2d,#8b6f47)',
      Metal: 'linear-gradient(135deg,#3a3a5c,#5a5a8c)',
      Garden: 'linear-gradient(135deg,#2d5a3d,#4a8c5c)',
      Electronics: 'linear-gradient(135deg,#2c2c2c,#555)'
    };

    let idea = null;
    let stepsCompleted = new Set();

    function toast(msg, dur = 2600) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur);
    }

    function escHtml(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ── Load idea ──────────────────────────────────────────────────
    async function loadIdea() {
      try {
        const res = await fetch(`api/fetch_single.php?id=${IDEA_ID}`);
        const data = await res.json();
        if (data.error) {
          document.body.innerHTML = '<div style="text-align:center;padding:80px;font-size:1.1rem;color:var(--soft)">Idea not found. <a href="reuse.php" style="color:var(--leaf)">Browse all ideas →</a></div>';
          return;
        }
        idea = data;
        renderHero(data);
        renderBody(data);
        renderSidebar(data);
        renderComments(data.comments);
      } catch (e) {
        document.getElementById('dhTitle').innerHTML = '<span style="color:#fff">Could not load idea. Check server connection.</span>';
      }
    }

    // ── Hero ───────────────────────────────────────────────────────
    function renderHero(d) {
      const avColor = AV_COLORS[d.id % AV_COLORS.length];
      document.getElementById('dhBadges').innerHTML = `
    <span class="dh-badge cat">${escHtml(d.category)}</span>
    <span class="dh-badge diff-${d.difficulty}">${d.difficulty}</span>`;
      document.getElementById('dhTitle').textContent = d.title;
      document.getElementById('dhMeta').innerHTML = `
    <span class="dh-meta-item">⏱ ${escHtml(d.time_required)}</span>
    <span class="dh-meta-item">❤️ ${d.likes} likes</span>
    <span class="dh-meta-item">💬 ${d.comments.length} comments</span>`;
      document.getElementById('dhAuthor').innerHTML = `
    <div class="dh-av" style="background:${avColor}">${escHtml(d.author[0]||'?')}</div>
    <div class="dh-author-info"><small>Shared by</small><strong>${escHtml(d.author)}</strong></div>`;
      document.getElementById('dhActions').innerHTML = `
    <button class="btn-like ${d.is_saved?'':''}}" id="likeBtn" onclick="toggleLike()">
      ❤️ <span id="likeCount">${d.likes}</span> Likes
    </button>
    <button class="btn-save ${d.is_saved?'saved':''}" id="saveBtn" onclick="toggleSave()">
      🔖 ${d.is_saved?'Saved':'Save'}
    </button>`;

      // Image / placeholder
      const imgWrap = document.getElementById('dhImage');
      if (d.image) {
        imgWrap.innerHTML = `<div class="dh-image-wrap"><img src="${escHtml(d.image)}" alt="${escHtml(d.title)}"></div>`;
      } else {
        const grad = CAT_GRAD[d.category] || 'linear-gradient(135deg,var(--moss),var(--leaf))';
        const emoji = CAT_EMOJI[d.category] || '✦';
        imgWrap.innerHTML = `<div class="dh-image-placeholder" style="background:${grad}">${emoji}</div>`;
      }
    }

    // ── Body ───────────────────────────────────────────────────────
    function renderBody(d) {
      document.getElementById('descText').textContent = d.description;
      document.getElementById('materialsList').innerHTML = d.materials_list
        .map(m => `<span class="mat-tag">${escHtml(m.trim())}</span>`).join('');

      const totalSteps = d.steps_array.length;
      document.getElementById('progressLabel').textContent = `0 / ${totalSteps} steps`;

      document.getElementById('stepsList').innerHTML = d.steps_array.map((step, i) => `
    <div class="step-item">
      <div class="step-left">
        <div class="step-num" id="stepNum-${i}">${i+1}</div>
        <div class="step-vline"></div>
      </div>
      <div class="step-right">
        <div class="step-title-row">
          <div class="step-title">Step ${i+1}</div>
          <button class="step-done-btn" id="stepBtn-${i}" onclick="toggleStep(${i})" title="Mark as done"></button>
        </div>
        <div class="step-desc">${escHtml(step)}</div>
      </div>
    </div>
  `).join('');
    }

    // ── Step progress ──────────────────────────────────────────────
    function toggleStep(i) {
      const total = idea.steps_array.length;
      if (stepsCompleted.has(i)) {
        stepsCompleted.delete(i);
        document.getElementById(`stepBtn-${i}`).classList.remove('done');
        document.getElementById(`stepNum-${i}`).style.background = '';
      } else {
        stepsCompleted.add(i);
        document.getElementById(`stepBtn-${i}`).classList.add('done');
        document.getElementById(`stepNum-${i}`).style.background = 'var(--sage)';
      }
      const pct = (stepsCompleted.size / total) * 100;
      document.getElementById('progressFill').style.width = pct + '%';
      document.getElementById('progressLabel').textContent = `${stepsCompleted.size} / ${total} steps`;
      if (stepsCompleted.size === total) toast('🎉 All steps done! Great work!');
    }

    // ── Sidebar ────────────────────────────────────────────────────
    async function renderSidebar(d) {
      const diffEmoji = d.difficulty === 'Easy' ? '😊' : d.difficulty === 'Medium' ? '🔧' : '💪';
      document.getElementById('quickStats').innerHTML = `
    <div class="sc-title">Quick Info</div>
    <div class="quick-stat"><span class="qs-label">⏱ Time Required</span><span class="qs-val">${escHtml(d.time_required)}</span></div>
    <div class="quick-stat"><span class="qs-label">${diffEmoji} Difficulty</span><span class="qs-val">${d.difficulty}</span></div>
    <div class="quick-stat"><span class="qs-label">📁 Category</span><span class="qs-val">${escHtml(d.category)}</span></div>
    <div class="quick-stat"><span class="qs-label">🪜 Steps</span><span class="qs-val">${d.steps_array.length} steps</span></div>
    <div class="quick-stat"><span class="qs-label">❤️ Likes</span><span class="qs-val">${d.likes}</span></div>
  `;

      // Load related ideas
      try {
        const res = await fetch(`api/fetch_ideas.php?category=${encodeURIComponent(d.category)}&limit=4`);
        const data = await res.json();
        const related = (data.ideas || []).filter(i => i.id !== d.id).slice(0, 3);
        if (!related.length) {
          document.getElementById('relatedList').innerHTML = '<div style="font-size:.83rem;color:var(--soft)">No related ideas yet.</div>';
          return;
        }
        const grad = CAT_GRAD[d.category] || '';
        document.getElementById('relatedList').innerHTML = related.map(r => `
      <div class="related-link" onclick="window.location='reuse_details.php?id=${r.id}'">
        <div class="rl-img" style="background:${grad}">${CAT_EMOJI[r.category]||'✦'}</div>
        <div><div class="rl-title">${escHtml(r.title)}</div><div class="rl-cat">${r.difficulty} · ${r.time_required}</div></div>
      </div>`).join('');
      } catch {
        document.getElementById('relatedList').innerHTML = '';
      }
    }

    // ── Like / Save ────────────────────────────────────────────────
    async function toggleLike() {
      try {
        const res = await fetch('api/like.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idea_id: IDEA_ID
          })
        });
        const data = await res.json();
        if (data.success) {
          document.getElementById('likeCount').textContent = data.likes;
          document.getElementById('likeBtn').classList.add('liked');
          toast('❤️ Liked! Thanks for the love!');
        }
      } catch {
        toast('⚠️ Server error');
      }
    }

    async function toggleSave() {
      try {
        const res = await fetch('api/save.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idea_id: IDEA_ID
          })
        });
        const data = await res.json();
        if (data.success) {
          const btn = document.getElementById('saveBtn');
          btn.classList.toggle('saved', data.saved);
          btn.innerHTML = `🔖 ${data.saved ? 'Saved' : 'Save'}`;
          toast(data.saved ? '🔖 Saved to your collection!' : 'Removed from saved');
        }
      } catch {
        toast('⚠️ Server error');
      }
    }

    // ── Comments ───────────────────────────────────────────────────
    function renderComments(comments) {
      const list = document.getElementById('commentsList');
      if (!comments.length) {
        list.innerHTML = '<div class="no-comments">No comments yet. Be the first!</div>';
        return;
      }
      list.innerHTML = comments.map(c => {
        const color = AV_COLORS[c.id % AV_COLORS.length];
        const date = new Date(c.created_at).toLocaleDateString('en-IN', {
          day: 'numeric',
          month: 'short',
          year: 'numeric'
        });
        return `
    <div class="comment-item">
      <div class="c-av" style="background:${color}">${escHtml(c.name[0]||'?')}</div>
      <div class="c-bubble">
        <div class="c-name">${escHtml(c.name)}</div>
        <div class="c-text">${escHtml(c.comment)}</div>
        <div class="c-time">${date}</div>
      </div>
    </div>`;
      }).join('');
    }

    async function submitComment() {
      const name = document.getElementById('commentName').value.trim();
      const comment = document.getElementById('commentText').value.trim();
      const errDiv = document.getElementById('commentError');
      const btn = document.getElementById('commentBtn');
      errDiv.style.display = 'none';

      if (!name) {
        errDiv.textContent = '⚠️ Please enter your name.';
        errDiv.style.display = 'block';
        return;
      }
      if (!comment) {
        errDiv.textContent = '⚠️ Please write a comment.';
        errDiv.style.display = 'block';
        return;
      }

      btn.disabled = true;
      btn.textContent = 'Posting…';
      try {
        const res = await fetch('api/comment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idea_id: IDEA_ID,
            name,
            comment
          })
        });
        const data = await res.json();
        if (data.success) {
          document.getElementById('commentName').value = '';
          document.getElementById('commentText').value = '';
          document.getElementById('charCount').textContent = '0 / 1000';
          // Prepend new comment
          const c = data.comment;
          const color = AV_COLORS[c.id % AV_COLORS.length];
          const newDiv = document.createElement('div');
          newDiv.className = 'comment-item';
          newDiv.style.animation = 'fadeUp .4s ease';
          newDiv.innerHTML = `
        <div class="c-av" style="background:${color}">${escHtml(c.name[0]||'?')}</div>
        <div class="c-bubble">
          <div class="c-name">${escHtml(c.name)}</div>
          <div class="c-text">${escHtml(c.comment)}</div>
          <div class="c-time">Just now</div>
        </div>`;
          const list = document.getElementById('commentsList');
          if (list.querySelector('.no-comments')) list.innerHTML = '';
          list.prepend(newDiv);
          toast('💬 Comment posted!');
        } else {
          errDiv.textContent = '⚠️ ' + (data.error || 'Failed to post comment.');
          errDiv.style.display = 'block';
        }
      } catch {
        errDiv.textContent = '⚠️ Server error.';
        errDiv.style.display = 'block';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '💬 Post Comment';
      }
    }

    // ── Share ──────────────────────────────────────────────────────
    function shareLink(type) {
      const url = window.location.href;
      const title = idea?.title || 'Reuse Idea';
      if (type === 'copy') {
        navigator.clipboard.writeText(url).then(() => toast('🔗 Link copied!')).catch(() => toast('Could not copy link'));
      } else if (type === 'whatsapp') {
        window.open(`https://wa.me/?text=${encodeURIComponent(title + ' — ' + url)}`, '_blank');
      } else if (type === 'twitter') {
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
      }
    }

    // ── Char counter ───────────────────────────────────────────────
    document.getElementById('commentText').addEventListener('input', function() {
      document.getElementById('charCount').textContent = this.value.length + ' / 1000';
    });

    // ── Animate ───────────────────────────────────────────────────
    const style = document.createElement('style');
    style.textContent = '@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}';
    document.head.appendChild(style);

    // ── Init ───────────────────────────────────────────────────────
    loadIdea();
  </script>
</body>

</html>