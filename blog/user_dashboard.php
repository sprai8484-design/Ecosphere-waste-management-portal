<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Submissions — Ecosphere</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#1a1a18;--forest:#1a3a2a;--moss:#2d5a3d;--leaf:#4a8c5c;--sage:#7ab88a;--cream:#f7f2e8;--parchment:#ede6d6;--warm:#faf8f2;--sand:#e8e0d0;--terra:#c8593a;--gold:#c89b3a;--soft:#5a5750}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Lato',sans-serif;background:var(--warm);color:var(--ink);overflow-x:hidden}
a{text-decoration:none;color:inherit}button{cursor:pointer;font-family:inherit;border:none;outline:none}
input{font-family:inherit;outline:none;border:none}
nav{position:sticky;top:0;z-index:100;background:rgba(247,242,232,.95);backdrop-filter:blur(16px);border-bottom:1px solid rgba(26,58,42,.1);padding:0 clamp(16px,5vw,72px);display:flex;align-items:center;justify-content:space-between;height:62px}
.nav-logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--forest)}
.nav-dot{width:30px;height:30px;background:var(--moss);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem}
.nav-links{display:flex;gap:24px;list-style:none}
.nav-links a{font-size:.82rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;color:var(--soft);transition:color .2s}
.nav-links a:hover{color:var(--forest)}

/* HEADER */
.page-header{background:var(--forest);padding:clamp(44px,6vh,80px) clamp(16px,6vw,80px);position:relative;overflow:hidden;border-bottom:4px solid var(--gold)}
.page-header::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px}
.ph-inner{position:relative;z-index:2}
.ph-kicker{font-family:'DM Mono',monospace;font-size:.66rem;letter-spacing:.16em;text-transform:uppercase;color:var(--sage);margin-bottom:10px}
.ph-title{font-family:'Playfair Display',serif;font-size:clamp(1.6rem,3.5vw,2.5rem);color:#fff;margin-bottom:12px}
.ph-sub{color:rgba(255,255,255,.6);font-size:.9rem;line-height:1.65;max-width:500px}

/* EMAIL LOOKUP */
.lookup-wrap{max-width:680px;margin:0 auto;padding:clamp(28px,5vh,56px) clamp(16px,4vw,32px)}
.lookup-card{background:#fff;border:1px solid var(--sand);padding:32px;margin-bottom:28px}
.lc-title{font-family:'Playfair Display',serif;font-size:1.15rem;color:var(--forest);margin-bottom:6px}
.lc-sub{color:var(--soft);font-size:.85rem;margin-bottom:20px;line-height:1.55}
.lc-row{display:flex;gap:10px}
.lc-inp{flex:1;background:var(--cream);border:1.5px solid var(--sand);padding:12px 14px;font-size:.9rem;color:var(--ink);transition:border-color .2s}
.lc-inp:focus{border-color:var(--leaf)}
.lc-inp::placeholder{color:rgba(90,87,80,.35)}
.lc-btn{background:var(--forest);color:#fff;padding:12px 22px;font-weight:700;font-size:.85rem;letter-spacing:.04em;text-transform:uppercase;transition:all .2s;white-space:nowrap}
.lc-btn:hover{background:var(--moss)}
.lc-btn:disabled{opacity:.6;cursor:not-allowed}

/* STATS ROW */
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px}
.stat-box{background:#fff;border:1px solid var(--sand);padding:18px;text-align:center}
.sb-num{font-family:'Playfair Display',serif;font-size:2rem;display:block}
.sb-lbl{font-family:'DM Mono',monospace;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--soft);margin-top:3px}
.sb-num.pending{color:var(--gold)}
.sb-num.approved{color:var(--leaf)}
.sb-num.rejected{color:var(--terra)}

/* BLOG CARDS */
.blogs-section{display:flex;flex-direction:column;gap:16px}
.blog-status-card{background:#fff;border:1px solid var(--sand);overflow:hidden;transition:box-shadow .2s}
.blog-status-card:hover{box-shadow:0 4px 16px rgba(26,26,24,.1)}
.bsc-top{padding:18px 20px;display:flex;gap:14px;align-items:flex-start;border-bottom:1px solid var(--sand)}
.bsc-thumb{width:72px;height:56px;flex-shrink:0;object-fit:cover;background:var(--parchment);display:flex;align-items:center;justify-content:center;font-size:1.5rem;overflow:hidden}
.bsc-thumb img{width:100%;height:100%;object-fit:cover}
.bsc-info{flex:1;min-width:0}
.bsc-title{font-family:'Playfair Display',serif;font-size:1rem;color:var(--forest);line-height:1.3;margin-bottom:5px}
.bsc-excerpt{font-size:.8rem;color:var(--soft);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.bsc-meta{margin-left:auto;text-align:right;flex-shrink:0}
.status-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 12px;font-family:'DM Mono',monospace;font-size:.62rem;font-weight:500;letter-spacing:.1em;text-transform:uppercase}
.status-badge.pending{background:rgba(200,155,58,.1);color:var(--gold);border:1px solid rgba(200,155,58,.25)}
.status-badge.approved{background:rgba(74,140,92,.1);color:var(--leaf);border:1px solid rgba(74,140,92,.25)}
.status-badge.rejected{background:rgba(200,89,58,.08);color:var(--terra);border:1px solid rgba(200,89,58,.2)}
.bsc-date{font-family:'DM Mono',monospace;font-size:.64rem;letter-spacing:.05em;color:var(--soft);margin-top:6px;display:block}
/* Admin note area */
.bsc-note{padding:14px 20px;display:flex;gap:12px;align-items:flex-start}
.note-icon{font-size:1rem;flex-shrink:0;margin-top:1px}
.note-label{font-family:'DM Mono',monospace;font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;margin-bottom:3px}
.note-text{font-size:.83rem;color:var(--ink);line-height:1.55}
.bsc-note.pending-note{background:rgba(200,155,58,.05)}
.bsc-note.approved-note{background:rgba(74,140,92,.05)}
.bsc-note.rejected-note{background:rgba(200,89,58,.05)}
.bsc-note.pending-note .note-label{color:var(--gold)}
.bsc-note.approved-note .note-label{color:var(--leaf)}
.bsc-note.rejected-note .note-label{color:var(--terra)}
/* Actions */
.bsc-actions{padding:10px 20px;border-top:1px solid var(--sand);display:flex;gap:10px;flex-wrap:wrap;align-items:center}
.act-btn{padding:7px 16px;font-size:.76rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border:1.5px solid var(--forest);color:var(--forest);transition:all .2s}
.act-btn:hover,.act-btn.primary{background:var(--forest);color:#fff}
.act-date{font-family:'DM Mono',monospace;font-size:.64rem;letter-spacing:.05em;color:var(--soft);margin-left:auto}

/* EMPTY / LOADING */
.state-block{text-align:center;padding:52px 20px;color:var(--soft)}
.state-icon{font-size:3rem;margin-bottom:14px}
.state-msg{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--forest);margin-bottom:6px}
.state-sub{font-size:.85rem;line-height:1.6}
.spinner{width:34px;height:34px;border:3px solid var(--sand);border-top-color:var(--leaf);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 12px}
@keyframes spin{to{transform:rotate(360deg)}}

/* Toast */
.toast{position:fixed;bottom:24px;right:24px;z-index:999;background:var(--forest);color:#fff;padding:12px 20px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:8px;box-shadow:0 8px 28px rgba(0,0,0,.25);transform:translateY(16px);opacity:0;transition:all .3s;pointer-events:none;border-left:3px solid var(--gold)}
.toast.show{transform:none;opacity:1}

@media(max-width:540px){.nav-links{display:none}.lc-row{flex-direction:column}.stats-row{grid-template-columns:1fr}.bsc-meta{display:none}}
</style>
</head>
<body>

<nav>
  <div class="nav-logo"><div class="nav-dot">🌿</div>Ecosphere</div>
  <ul class="nav-links">
    <li><a href="blog.php">Journal</a></li>
    <li><a href="submit_blog.php">Write</a></li>
    <li><a href="user_dashboard.php">My Blogs</a></li>
  </ul>
</nav>

<div class="page-header">
  <div class="ph-inner">
    <div class="ph-kicker">// Author Dashboard</div>
    <h1 class="ph-title">My Blog Submissions</h1>
    <p class="ph-sub">Track the status of every article you've submitted. Enter your email below to see all your drafts, pending reviews, approvals, and rejections — along with admin feedback.</p>
  </div>
</div>

<div class="lookup-wrap">

  <div class="lookup-card">
    <div class="lc-title">Find Your Submissions</div>
    <div class="lc-sub">Enter the email address you used when submitting your blog(s). No account required.</div>
    <div class="lc-row">
      <input class="lc-inp" id="emailInput" type="email" placeholder="you@example.com" onkeydown="if(event.key==='Enter')lookupBlogs()">
      <button class="lc-btn" id="lookupBtn" onclick="lookupBlogs()">🔍 Find My Blogs</button>
    </div>
    <div id="lookupError" style="display:none;color:var(--terra);font-size:.83rem;margin-top:10px"></div>
  </div>

  <!-- STATS (hidden until loaded) -->
  <div class="stats-row" id="statsRow" style="display:none">
    <div class="stat-box"><span class="sb-num" id="statTotal">0</span><span class="sb-lbl">Total Submitted</span></div>
    <div class="stat-box"><span class="sb-num approved" id="statApproved">0</span><span class="sb-lbl">Approved</span></div>
    <div class="stat-box"><span class="sb-num pending" id="statPending">0</span><span class="sb-lbl">Pending Review</span></div>
  </div>

  <!-- BLOGS LIST -->
  <div class="blogs-section" id="blogsList">
    <div class="state-block">
      <div class="state-icon">📝</div>
      <div class="state-msg">Enter your email above</div>
      <p class="state-sub">We'll show all the blog posts you've submitted and their current review status.</p>
    </div>
  </div>

  <!-- WRITE NEW CTA -->
  <div id="writeCta" style="display:none;margin-top:28px;background:var(--parchment);border:1px solid var(--sand);padding:24px;text-align:center">
    <div style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--forest);margin-bottom:8px">Have another story to tell?</div>
    <a href="submit_blog.php" style="display:inline-block;background:var(--forest);color:#fff;padding:10px 24px;font-weight:700;font-size:.82rem;letter-spacing:.04em;text-transform:uppercase;margin-top:4px">✍ Write a New Blog →</a>
  </div>

</div>

<div class="toast" id="toast"><span id="toastMsg"></span></div>

<script>
const AV_COLORS=['#4a8c5c','#c8593a','#c89b3a','#7ab88a','#8b6f47','#2d5a3d'];
function toast(msg,dur=2600){const t=document.getElementById('toast');document.getElementById('toastMsg').textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),dur)}
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
function fmtDate(d){return new Date(d).toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric'})}
function fmtDateTime(d){if(!d)return'—';return new Date(d).toLocaleDateString('en-IN',{day:'numeric',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'})}

async function lookupBlogs(){
  const email=document.getElementById('emailInput').value.trim();
  const errDiv=document.getElementById('lookupError');
  const btn=document.getElementById('lookupBtn');
  errDiv.style.display='none';

  if(!email||!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
    errDiv.textContent='Please enter a valid email address.';errDiv.style.display='block';return;
  }
  btn.disabled=true;btn.textContent='Loading…';
  document.getElementById('blogsList').innerHTML='<div class="state-block"><div class="spinner"></div><div class="state-msg">Looking up your submissions…</div></div>';
  document.getElementById('statsRow').style.display='none';
  document.getElementById('writeCta').style.display='none';

  try{
    const res=await fetch('api/fetch_user_blogs.php?email='+encodeURIComponent(email));
    const data=await res.json();
    if(data.error){errDiv.textContent='⚠️ '+data.error;errDiv.style.display='block';document.getElementById('blogsList').innerHTML='';return;}
    renderDashboard(data);
  }catch(e){
    errDiv.textContent='⚠️ Server error. Is XAMPP running?';errDiv.style.display='block';
    document.getElementById('blogsList').innerHTML='';
  }finally{btn.disabled=false;btn.textContent='🔍 Find My Blogs'}
}

function renderDashboard(data){
  const blogs=data.blogs||[];
  const pending=blogs.filter(b=>b.status==='pending').length;
  const approved=blogs.filter(b=>b.status==='approved').length;

  // Stats
  document.getElementById('statTotal').textContent=blogs.length;
  document.getElementById('statApproved').textContent=approved;
  document.getElementById('statPending').textContent=pending;
  document.getElementById('statsRow').style.display='grid';
  document.getElementById('writeCta').style.display='block';

  if(!blogs.length){
    document.getElementById('blogsList').innerHTML=`<div class="state-block"><div class="state-icon">📭</div><div class="state-msg">No submissions found</div><p class="state-sub">We don't have any blogs submitted with this email. Double-check the address or <a href="submit_blog.php" style="color:var(--leaf);font-weight:700">submit your first blog →</a></p></div>`;
    return;
  }

  document.getElementById('blogsList').innerHTML=blogs.map(b=>{
    const statusLabels={pending:'⏳ Pending Review',approved:'✅ Approved',rejected:'❌ Rejected'};
    const noteClasses={pending:'pending-note',approved:'approved-note',rejected:'rejected-note'};
    const noteIcons={pending:'💬',approved:'✓',rejected:'✗'};
    const noteLabels={pending:'Admin Note (Pending)',approved:'Admin Approval Note',rejected:'Reason for Rejection'};
    const noteDefault={pending:'Your blog is in the review queue. Most reviews complete within 24–48 hours.',approved:'',rejected:''};
    const noteText=b.admin_note||(b.status==='pending'?noteDefault.pending:'No note provided.');

    const thumbHtml=''; // no image in list response

    const viewBtn=b.status==='approved'
      ?`<a href="blog_single.php?id=${b.id}" class="act-btn primary">Read Published Article →</a>`
      :`<span class="act-btn" style="opacity:.4;cursor:not-allowed">Not yet published</span>`;

    return `<div class="blog-status-card">
      <div class="bsc-top">
        <div class="bsc-thumb">📝</div>
        <div class="bsc-info">
          <div class="bsc-title">${esc(b.title)}</div>
          <div class="bsc-excerpt">${esc(b.excerpt)}</div>
        </div>
        <div class="bsc-meta">
          <div class="status-badge ${b.status}">${statusLabels[b.status]}</div>
          <span class="bsc-date">Submitted ${fmtDate(b.created_at)}</span>
        </div>
      </div>
      <div class="bsc-note ${noteClasses[b.status]}">
        <div class="note-icon">${noteIcons[b.status]}</div>
        <div>
          <div class="note-label">${noteLabels[b.status]}</div>
          <div class="note-text">${esc(noteText)}</div>
          ${b.reviewed_by?`<div style="font-size:.72rem;color:var(--soft);margin-top:5px;font-family:'DM Mono',monospace;letter-spacing:.05em">Reviewed by ${esc(b.reviewed_by)} · ${fmtDateTime(b.reviewed_at)}</div>`:''}
        </div>
      </div>
      <div class="bsc-actions">
        ${viewBtn}
        <a href="submit_blog.php" class="act-btn" style="border-color:var(--sand);color:var(--soft)">+ Write New</a>
        <span class="act-date">ID: #${b.id}</span>
      </div>
    </div>`;
  }).join('');
}
</script>
</body>
</html>
