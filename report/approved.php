<?php
require_once 'db.php';

// Fetch all approved + solved reports, newest first
$reports = db()->query("
    SELECT * FROM reports
    WHERE status IN ('approved','solved')
    ORDER BY
        CASE status WHEN 'solved' THEN 1 ELSE 0 END ASC,
        created_at DESC
")->fetchAll();

// Stats for hero
$total   = db()->query("SELECT COUNT(*) FROM reports")->fetchColumn();
$solved  = db()->query("SELECT COUNT(*) FROM reports WHERE status='solved'")->fetchColumn();
$pending = db()->query("SELECT COUNT(*) FROM reports WHERE status='pending'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Community Reports — Ecosphere</title>
  <style>
    :root {
      --gd: #1a3a2a;
      --gm: #2d5a3d;
      --g: #4a8c5c;
      --gl: #7ab88a;
      --cream: #f5f0e8;
      --sand: #e4ddd0;
      --warm: #faf8f3;
      --terra: #d4745a;
      --gold: #d4a843;
      --sky: #4a8caa;
      --ch: #2c2c2c;
      --soft: #5a5750;
      --mu: #9a9690;
      --r: 12px;
      --sh: 0 2px 14px rgba(26, 58, 42, .09);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    html {
      font-size: 15px;
      scroll-behavior: smooth
    }

    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      background: var(--warm);
      color: var(--ch)
    }

    a {
      text-decoration: none;
      color: inherit
    }

    /* NAV */
    nav {
      background: var(--gd);
      padding: 0 clamp(16px, 4vw, 48px);
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 12px rgba(0, 0, 0, .18)
    }

    .logo {
      color: #fff;
      font-weight: 700;
      font-size: 1.15rem;
      display: flex;
      align-items: center;
      gap: 8px
    }

    .logo span {
      opacity: .8;
      font-size: .78rem;
      font-weight: 400
    }

    .nav-links {
      display: flex;
      gap: 6px
    }

    .nav-links a {
      color: rgba(255, 255, 255, .65);
      font-size: .84rem;
      padding: 6px 13px;
      border-radius: 50px;
      transition: all .18s
    }

    .nav-links a:hover,
    .nav-links a.active {
      background: rgba(255, 255, 255, .12);
      color: #fff
    }

    /* HERO */
    .hero {
      background: linear-gradient(140deg, var(--gd), var(--gm));
      padding: clamp(44px, 8vh, 88px) clamp(16px, 6vw, 80px);
      position: relative;
      overflow: hidden
    }

    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none
    }

    .hero-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none
    }

    .orb1 {
      width: 50vw;
      height: 50vw;
      top: -20%;
      right: -15%;
      background: radial-gradient(circle, rgba(122, 184, 138, .12) 0%, transparent 70%)
    }

    .orb2 {
      width: 28vw;
      height: 28vw;
      bottom: -12%;
      left: 5%;
      background: radial-gradient(circle, rgba(212, 168, 67, .08) 0%, transparent 70%)
    }

    .hero-inner {
      position: relative;
      z-index: 1;
      max-width: 640px
    }

    .hero h1 {
      font-size: clamp(1.9rem, 4.5vw, 3.2rem);
      color: #fff;
      line-height: 1.12;
      margin-bottom: 14px
    }

    .hero h1 em {
      color: var(--gold);
      font-style: normal
    }

    .hero-desc {
      color: rgba(255, 255, 255, .72);
      font-size: .96rem;
      line-height: 1.7;
      max-width: 500px;
      margin-bottom: 28px
    }

    .hero-stats {
      display: flex;
      gap: 32px;
      border-top: 1px solid rgba(255, 255, 255, .12);
      padding-top: 24px;
      flex-wrap: wrap
    }

    .hs .num {
      font-size: 1.9rem;
      font-weight: 800;
      color: #fff;
      display: block
    }

    .hs .lbl {
      font-size: .72rem;
      color: rgba(255, 255, 255, .5);
      text-transform: uppercase;
      letter-spacing: .06em
    }

    /* FILTER BAR */
    .filter-bar {
      background: var(--cream);
      border-bottom: 1px solid var(--sand);
      padding: 14px clamp(16px, 4vw, 48px);
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap
    }

    .fbtn {
      padding: 7px 16px;
      border-radius: 50px;
      font-size: .82rem;
      font-weight: 600;
      border: 1.5px solid var(--sand);
      background: #fff;
      color: var(--soft);
      cursor: pointer;
      transition: all .2s
    }

    .fbtn:hover {
      border-color: var(--g);
      color: var(--g)
    }

    .fbtn.on {
      background: var(--gd);
      color: #fff;
      border-color: var(--gd)
    }

    .f-count {
      margin-left: auto;
      font-size: .78rem;
      color: var(--mu)
    }

    /* CONTAINER */
    .container {
      max-width: 1240px;
      margin: 0 auto;
      padding: clamp(28px, 5vh, 52px) clamp(16px, 4vw, 48px)
    }

    /* GRID */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px
    }

    /* REPORT CARD */
    .rcard {
      background: #fff;
      border-radius: var(--r);
      box-shadow: var(--sh);
      border: 1px solid rgba(74, 140, 92, .1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform .25s, box-shadow .25s
    }

    .rcard:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 28px rgba(26, 58, 42, .14)
    }

    /* Image section */
    .rcard-img {
      position: relative;
      flex-shrink: 0
    }

    .rcard-img.single img {
      width: 100%;
      height: 210px;
      object-fit: cover;
      display: block;
      cursor: pointer;
      transition: transform .35s
    }

    .rcard:hover .rcard-img.single img {
      transform: scale(1.03)
    }

    .rcard-img.double {
      display: grid;
      grid-template-columns: 1fr 1fr;
      height: 200px
    }

    .rcard-img.double .img-half {
      overflow: hidden;
      position: relative;
      cursor: pointer
    }

    .rcard-img.double .img-half img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .35s
    }

    .rcard-img.double .img-half:hover img {
      transform: scale(1.05)
    }

    .img-label {
      position: absolute;
      bottom: 8px;
      left: 8px;
      background: rgba(0, 0, 0, .62);
      color: #fff;
      font-size: .64rem;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 50px;
      letter-spacing: .06em;
      text-transform: uppercase
    }

    .rcard-img.no-img {
      height: 120px;
      background: var(--cream);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      color: var(--mu);
      flex-direction: column;
      gap: 6px
    }

    .rcard-img.no-img span {
      font-size: .76rem;
      color: var(--mu)
    }

    /* Status bar */
    .status-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 14px;
      background: var(--cream)
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 3px 10px;
      border-radius: 50px;
      font-size: .68rem;
      font-weight: 700
    }

    .b-approved {
      background: rgba(74, 140, 92, .12);
      color: var(--g);
      border: 1px solid rgba(74, 140, 92, .25)
    }

    .b-solved {
      background: rgba(45, 90, 61, .12);
      color: var(--gm);
      border: 1px solid rgba(45, 90, 61, .25)
    }

    .rcard-date {
      font-size: .7rem;
      color: var(--mu)
    }

    /* Solved overlay ribbon */
    .solved-ribbon {
      position: absolute;
      top: 14px;
      right: -8px;
      background: var(--gm);
      color: #fff;
      font-size: .66rem;
      font-weight: 700;
      padding: 4px 16px 4px 10px;
      letter-spacing: .08em;
      text-transform: uppercase;
      box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
      clip-path: polygon(0 0, 100% 0, 92% 50%, 100% 100%, 0 100%)
    }

    /* Card body */
    .rcard-body {
      padding: 16px 18px;
      flex: 1;
      display: flex;
      flex-direction: column
    }

    .rcard-loc {
      font-weight: 700;
      font-size: .92rem;
      color: var(--gd);
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px
    }

    .rcard-desc {
      font-size: .83rem;
      color: var(--soft);
      line-height: 1.6;
      flex: 1;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden
    }

    /* Resolved callout */
    .resolved-box {
      background: linear-gradient(135deg, var(--gd), var(--gm));
      border-radius: 8px;
      padding: 12px 14px;
      margin-top: 12px;
      display: flex;
      align-items: center;
      gap: 10px
    }

    .resolved-box .ico {
      font-size: 1.2rem;
      flex-shrink: 0
    }

    .resolved-box .txt {
      color: #fff;
      font-size: .82rem;
      font-weight: 600
    }

    .resolved-box .sub {
      color: rgba(255, 255, 255, .6);
      font-size: .74rem;
      margin-top: 2px
    }

    /* Empty state */
    .empty {
      text-align: center;
      padding: 56px 20px;
      color: var(--mu)
    }

    .empty-ico {
      font-size: 3rem;
      margin-bottom: 12px
    }

    .empty h3 {
      font-size: 1.05rem;
      color: var(--gd);
      margin-bottom: 6px
    }

    .empty a {
      color: var(--g);
      font-weight: 700
    }

    /* Lightbox */
    .lb {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, .88);
      z-index: 999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      opacity: 0;
      pointer-events: none;
      transition: opacity .22s
    }

    .lb.open {
      opacity: 1;
      pointer-events: all
    }

    .lb img {
      max-width: 100%;
      max-height: 92vh;
      border-radius: 10px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, .5)
    }

    .lb-close {
      position: absolute;
      top: 16px;
      right: 20px;
      background: rgba(255, 255, 255, .15);
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      font-size: .9rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s
    }

    .lb-close:hover {
      background: rgba(255, 255, 255, .28)
    }

    /* Awareness banner */
    .awareness {
      background: var(--gd);
      padding: clamp(28px, 4vh, 48px) clamp(16px, 6vw, 80px);
      text-align: center;
      position: relative;
      overflow: hidden
    }

    .awareness::before {
      content: '"';
      position: absolute;
      top: -20px;
      left: 20px;
      font-size: 8rem;
      color: rgba(255, 255, 255, .05);
      font-style: italic;
      line-height: 1
    }

    .awareness p {
      color: #fff;
      font-size: clamp(.94rem, 2vw, 1.12rem);
      font-style: italic;
      line-height: 1.7;
      max-width: 620px;
      margin: 0 auto;
      position: relative;
      z-index: 1
    }

    .awareness .attr {
      color: rgba(255, 255, 255, .45);
      font-size: .78rem;
      margin-top: 10px;
      font-style: normal
    }

    footer {
      background: var(--gd);
      color: rgba(255, 255, 255, .45);
      text-align: center;
      padding: 20px;
      font-size: .8rem
    }

    footer a {
      color: var(--gl);
      font-weight: 600
    }

    @media(max-width:540px) {
      .nav-links a span {
        display: none
      }

      .hero-stats {
        gap: 20px
      }

      .grid {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>

  <nav>
    <div class="logo">🌿 Ecosphere <span>Community Reports</span></div>
    <div class="nav-links">
      <a href="index.php">📋 <span>Report</span></a>
      <a href="approved.php" class="active">🌍 <span>Public</span></a>
      <a href="admin.php">⚙ <span>Admin</span></a>
    </div>
  </nav>

  <div class="hero">
    <div class="orb1 hero-orb"></div>
    <div class="orb2 hero-orb"></div>
    <div class="hero-inner">
      <h1>Community Waste<br><em>Reports & Resolutions</em></h1>
      <p class="hero-desc">Approved reports from citizens across the city. See the real-world impact of community action — before and after cleanup proof.</p>
      <div class="hero-stats">
        <div class="hs"><span class="num"><?= $total ?></span><span class="lbl">Total Reports</span></div>
        <div class="hs"><span class="num"><?= count($reports) ?></span><span class="lbl">Active</span></div>
        <div class="hs"><span class="num"><?= $solved ?></span><span class="lbl">Solved</span></div>
        <div class="hs"><span class="num"><?= $pending ?></span><span class="lbl">Pending Review</span></div>
      </div>
    </div>
  </div>

  <!-- Filter bar -->
  <div class="filter-bar" id="filterBar">
    <button class="fbtn on" onclick="filterCards('all',this)">All (<?= count($reports) ?>)</button>
    <button class="fbtn" onclick="filterCards('approved',this)">Approved (<?= array_sum(array_map(fn($r) => $r['status'] === 'approved' ? 1 : 0, $reports)) ?>)</button>
    <button class="fbtn" onclick="filterCards('solved',this)">✅ Solved (<?= $solved ?>)</button>
    <span class="f-count" id="fcount"><?= count($reports) ?> shown</span>
  </div>

  <div class="container">

    <?php if (empty($reports)): ?>
      <div class="empty">
        <div class="empty-ico">📭</div>
        <h3>No approved reports yet</h3>
        <p>Be the first — <a href="index.php">submit a report</a> to help your community.</p>
      </div>

    <?php else: ?>
      <div class="grid" id="reportGrid">
        <?php foreach ($reports as $r): ?>
          <div class="rcard" data-status="<?= $r['status'] ?>">

            <!-- Image area -->
            <?php if ($r['status'] === 'solved' && $r['image'] && $r['solution_image']): ?>
              <!-- BEFORE & AFTER -->
              <div class="rcard-img double" style="position:relative">
                <div class="img-half">
                  <img src="uploads/<?= h($r['image']) ?>" alt="before"
                    onclick="showLb('uploads/<?= h($r['image']) ?>')">
                  <span class="img-label">Before</span>
                </div>
                <div class="img-half">
                  <img src="uploads/<?= h($r['solution_image']) ?>" alt="after"
                    onclick="showLb('uploads/<?= h($r['solution_image']) ?>')">
                  <span class="img-label">After</span>
                </div>
                <div class="solved-ribbon">Resolved</div>
              </div>

            <?php elseif ($r['status'] === 'solved' && $r['solution_image']): ?>
              <!-- Solved — only after image -->
              <div class="rcard-img single" style="position:relative">
                <img src="uploads/<?= h($r['solution_image']) ?>" alt="after"
                  onclick="showLb('uploads/<?= h($r['solution_image']) ?>')">
                <span class="img-label" style="background:var(--gm)">After Cleanup</span>
                <div class="solved-ribbon">Resolved</div>
              </div>

            <?php elseif ($r['image']): ?>
              <!-- Regular with image -->
              <div class="rcard-img single">
                <img src="uploads/<?= h($r['image']) ?>" alt="waste"
                  onclick="showLb('uploads/<?= h($r['image']) ?>')">
              </div>

            <?php else: ?>
              <!-- No image -->
              <div class="rcard-img no-img">
                <div>🗑️</div><span>No photo provided</span>
              </div>
            <?php endif; ?>

            <!-- Status bar -->
            <div class="status-bar">
              <?php if ($r['status'] === 'solved'): ?>
                <span class="badge b-solved">🌿 Solved</span>
              <?php else: ?>
                <span class="badge b-approved">✅ Approved</span>
              <?php endif; ?>
              <span class="rcard-date">📅 <?= date('d M Y', strtotime($r['created_at'])) ?></span>
            </div>

            <!-- Card body -->
            <div class="rcard-body">
              <div class="rcard-loc">📍 <?= h($r['location']) ?></div>
              <p class="rcard-desc"><?= h($r['description']) ?></p>
              <div style="font-size:.74rem;color:var(--mu);margin-top:8px">by <?= h($r['reporter']) ?></div>

              <?php if ($r['status'] === 'solved'): ?>
                <div class="resolved-box">
                  <div class="ico">✅</div>
                  <div>
                    <div class="txt">Issue Resolved</div>
                    <div class="sub">Cleanup completed · Thank you to all volunteers</div>
                  </div>
                </div>
              <?php endif; ?>
            </div>

          </div>
        <?php endforeach; ?>
      </div><!-- /grid -->
    <?php endif; ?>

  </div><!-- /container -->

  <!-- Awareness -->
  <div class="awareness">
    <p>"Cleanliness is not only the government's responsibility, but also ours as responsible citizens. Every report, every action, every volunteer makes a difference."</p>
    <p class="attr">— Ecosphere Community Initiative</p>
  </div>

  <footer>
    © <?= date('Y') ?> Ecosphere · <a href="index.php">Submit a Report</a> · <a href="admin.php">Admin Panel</a>
  </footer>

  <!-- Lightbox -->
  <div class="lb" id="lb" onclick="closeLb()">
    <button class="lb-close" onclick="closeLb()">✕</button>
    <img id="lbImg" src="" alt="">
  </div>

  <script>
    function showLb(src) {
      document.getElementById('lbImg').src = src;
      document.getElementById('lb').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeLb() {
      document.getElementById('lb').classList.remove('open');
      document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') closeLb();
    });

    function filterCards(status, btn) {
      document.querySelectorAll('.fbtn').forEach(b => b.classList.remove('on'));
      btn.classList.add('on');
      const cards = document.querySelectorAll('.rcard');
      let visible = 0;
      cards.forEach(c => {
        const show = status === 'all' || c.dataset.status === status;
        c.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      document.getElementById('fcount').textContent = visible + ' shown';
    }
  </script>
</body>

</html>