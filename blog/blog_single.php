<?php
require_once 'config.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: blog.php'); exit; }
// Pre-fetch for meta
try {
  $s = getDB()->prepare("SELECT title,author_name,created_at FROM blogs WHERE id=:id AND status='approved' LIMIT 1");
  $s->execute([':id'=>$id]); $meta = $s->fetch();
  if (!$meta) { header('Location: blog.php?err=notfound'); exit; }
} catch(Exception $e) { $meta=['title'=>'Article','author_name'=>'','created_at'=>'']; }
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= esc($meta['title']) ?> — Ecosphere Journal</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Lato:wght@300;400;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
<?php function esc($s){return htmlspecialchars($s,ENT_QUOTES,'UTF-8');} ?>
:root{--ink:#1a1a18;--forest:#1a3a2a;--moss:#2d5a3d;--leaf:#4a8c5c;--sage:#7ab88a;--cream:#f7f2e8;--parchment:#ede6d6;--warm:#faf8f2;--sand:#e8e0d0;--terra:#c8593a;--gold:#c89b3a;--charcoal:#2c2c2c;--soft:#5a5750}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Lato',sans-serif;background:var(--warm);color:var(--ink);overflow-x:hidden}
img{max-width:100%}a{text-decoration:none;color:inherit}button{cursor:pointer;font-family:inherit;border:none;outline:none}
nav{position:sticky;top:0;z-index:100;background:rgba(247,242,232,.95);backdrop-filter:blur(16px);border-bottom:1px solid rgba(26,58,42,.1);padding:0 clamp(16px,5vw,72px);display:flex;align-items:center;justify-content:space-between;height:62px}
.nav-logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--forest)}
.nav-dot{width:30px;height:30px;background:var(--moss);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem}
.back-link{font-size:.82rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--leaf);transition:color .2s}
.back-link:hover{color:var(--forest)}
/* Article layout */
.article-wrap{max-width:760px;margin:0 auto;padding:clamp(32px,6vh,72px) clamp(16px,5vw,40px)}
.article-meta-top{margin-bottom:24px}
.art-kicker{font-family:'DM Mono',monospace;font-size:.66rem;letter-spacing:.16em;text-transform:uppercase;color:var(--sage);margin-bottom:10px}
.art-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.9rem);color:var(--forest);line-height:1.15;margin-bottom:16px}
.art-byline{display:flex;align-items:center;gap:12px;padding:16px 0;border-top:1px solid var(--sand);border-bottom:1px solid var(--sand);margin-bottom:28px}
.art-av{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;color:#fff;flex-shrink:0}
.art-av-info strong{display:block;font-size:.9rem;color:var(--ink)}
.art-av-info span{font-family:'DM Mono',monospace;font-size:.7rem;letter-spacing:.06em;color:var(--soft)}
.art-share{margin-left:auto;display:flex;gap:8px}
.share-btn{padding:7px 14px;background:var(--parchment);border:1px solid var(--sand);font-size:.75rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;color:var(--soft);transition:all .2s}
.share-btn:hover{background:var(--forest);color:#fff;border-color:var(--forest)}
/* Hero image */
.art-hero-img{width:100%;aspect-ratio:16/9;object-fit:cover;margin-bottom:32px;background:var(--parchment)}
.art-hero-img img{width:100%;height:100%;object-fit:cover}
.art-hero-placeholder{width:100%;aspect-ratio:16/7;display:flex;align-items:center;justify-content:center;font-size:6rem;background:linear-gradient(135deg,var(--forest),var(--moss));margin-bottom:32px}
/* Article content */
.art-content{font-size:1.05rem;line-height:1.85;color:var(--ink)}
.art-content p{margin-bottom:1.5em}
.art-content h2,.art-content h3{font-family:'Playfair Display',serif;color:var(--forest);margin:2em 0 .75em}
.art-content h2{font-size:1.5rem}
.art-content h3{font-size:1.2rem}
.art-content blockquote{border-left:3px solid var(--gold);padding:12px 20px;background:var(--parchment);margin:1.5em 0;font-style:italic;color:var(--soft)}
.art-content ul,.art-content ol{padding-left:1.5em;margin-bottom:1.5em}
.art-content li{margin-bottom:.5em}
.art-content strong{font-weight:700;color:var(--forest)}
/* End bar */
.art-end{border-top:2px solid var(--sand);padding-top:28px;margin-top:48px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px}
.art-end-author{font-family:'Playfair Display',serif;font-size:1rem;color:var(--forest)}
.art-end-author span{display:block;font-family:'Lato',sans-serif;font-size:.82rem;color:var(--soft);margin-top:3px}
/* Related */
.related-section{background:var(--parchment);border-top:1px solid var(--sand);padding:clamp(32px,5vh,60px) clamp(16px,5vw,80px)}
.rel-header{display:flex;align-items:center;gap:16px;margin-bottom:28px}
.rel-label{font-family:'DM Mono',monospace;font-size:.7rem;letter-spacing:.16em;text-transform:uppercase;color:var(--sage);white-space:nowrap}
.rel-line{flex:1;height:1px;background:var(--sand)}
.related-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;max-width:1024px;margin:0 auto}
.rel-card{background:#fff;border:1px solid var(--sand);padding:20px;cursor:pointer;transition:all .25s}
.rel-card:hover{border-color:var(--leaf);transform:translateY(-2px);box-shadow:0 6px 20px rgba(26,26,24,.1)}
.rel-kicker{font-family:'DM Mono',monospace;font-size:.62rem;letter-spacing:.12em;text-transform:uppercase;color:var(--sage);margin-bottom:6px}
.rel-title{font-family:'Playfair Display',serif;font-size:.95rem;color:var(--forest);line-height:1.35;margin-bottom:6px}
.rel-author{font-size:.75rem;color:var(--soft)}
/* Loading */
.loading-block{text-align:center;padding:80px 20px;color:var(--soft)}
.spinner{width:36px;height:36px;border:3px solid var(--sand);border-top-color:var(--leaf);border-radius:50%;animation:spin .8s linear infinite;margin:0 auto 14px}
@keyframes spin{to{transform:rotate(360deg)}}
.toast{position:fixed;bottom:24px;right:24px;z-index:999;background:var(--forest);color:#fff;padding:12px 20px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:8px;box-shadow:0 8px 28px rgba(0,0,0,.25);transform:translateY(16px);opacity:0;transition:all .3s;pointer-events:none;border-left:3px solid var(--gold)}
.toast.show{transform:none;opacity:1}
@media(max-width:540px){.art-share{display:none}}
</style>
</head>
<body>
<nav>
  <a href="blog.php" class="nav-logo"><div class="nav-dot">🌿</div>Ecosphere</a>
  <a href="blog.php" class="back-link">← Back to Journal</a>
</nav>

<div id="articleWrap">
  <div class="loading-block"><div class="spinner"></div><p>Loading article…</p></div>
</div>

<div id="relatedSection"></div>
<div class="toast" id="toast"><span id="toastMsg"></span></div>

<script>
const BLOG_ID = <?= $id ?>;
const AV_COLORS = ['#4a8c5c','#c8593a','#c89b3a','#7ab88a','#8b6f47','#2d5a3d','#c4956a','#1a3a2a'];

function toast(msg,dur=2600){const t=document.getElementById('toast');document.getElementById('toastMsg').textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),dur)}
function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
function fmtDate(d){return new Date(d).toLocaleDateString('en-IN',{day:'numeric',month:'long',year:'numeric'})}
function avColor(name){let h=0;for(let i=0;i<name.length;i++)h=name.charCodeAt(i)+((h<<5)-h);return AV_COLORS[Math.abs(h)%AV_COLORS.length]}

async function loadArticle() {
  try {
    const res  = await fetch(`api/fetch_single_blog.php?id=${BLOG_ID}`);
    const data = await res.json();
    if (data.error) { document.getElementById('articleWrap').innerHTML=`<div class="loading-block"><p style="color:#c8593a">Article not found. <a href="blog.php" style="color:var(--leaf)">← Back to Journal</a></p></div>`; return; }
    renderArticle(data);
    if (data.related && data.related.length) renderRelated(data.related);
  } catch(e) {
    document.getElementById('articleWrap').innerHTML='<div class="loading-block"><p>Could not load article. Is the server running?</p></div>';
  }
}

function renderArticle(b) {
  const color = avColor(b.author_name);
  const heroHtml = b.image
    ? `<div class="art-hero-img"><img src="${esc(b.image)}" alt="${esc(b.title)}"></div>`
    : `<div class="art-hero-placeholder">🌿</div>`;

  document.getElementById('articleWrap').innerHTML = `
    <div class="article-wrap">
      <div class="article-meta-top">
        <div class="art-kicker">// Ecosphere Environmental Journal</div>
        <h1 class="art-title">${esc(b.title)}</h1>
        <div class="art-byline">
          <div class="art-av" style="background:${color}">${esc(b.author_name[0])}</div>
          <div class="art-av-info">
            <strong>${esc(b.author_name)}</strong>
            <span>${fmtDate(b.created_at)}</span>
          </div>
          <div class="art-share">
            <button class="share-btn" onclick="shareArticle('copy')">🔗 Copy</button>
            <button class="share-btn" onclick="shareArticle('whatsapp')">💬 Share</button>
          </div>
        </div>
      </div>
      ${heroHtml}
      <div class="art-content">${b.content}</div>
      <div class="art-end">
        <div class="art-end-author">Written by ${esc(b.author_name)}<span>Published ${fmtDate(b.created_at)}</span></div>
        <a href="blog.php" style="font-size:.82rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--leaf)">← All Articles</a>
      </div>
    </div>`;
}

function renderRelated(items) {
  document.getElementById('relatedSection').innerHTML = `
    <div class="related-section">
      <div class="rel-header"><div class="rel-label">More to Read</div><div class="rel-line"></div></div>
      <div class="related-grid">
        ${items.map(r=>`
          <div class="rel-card" onclick="window.location='blog_single.php?id=${r.id}'">
            <div class="rel-kicker">// Article</div>
            <div class="rel-title">${esc(r.title)}</div>
            <div class="rel-author">${esc(r.author_name)} · ${fmtDate(r.created_at)}</div>
          </div>`).join('')}
      </div>
    </div>`;
}

function shareArticle(type) {
  const url = window.location.href;
  if (type==='copy') { navigator.clipboard.writeText(url).then(()=>toast('🔗 Link copied!')).catch(()=>toast('Could not copy')); }
  else { window.open('https://wa.me/?text='+encodeURIComponent(document.title+' '+url),'_blank'); }
}

loadArticle();
</script>
</body>
</html>
