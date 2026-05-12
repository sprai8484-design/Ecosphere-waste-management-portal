<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Write a Blog — Ecosphere</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{--ink:#1a1a18;--forest:#1a3a2a;--moss:#2d5a3d;--leaf:#4a8c5c;--sage:#7ab88a;--cream:#f7f2e8;--parchment:#ede6d6;--warm:#faf8f2;--sand:#e8e0d0;--terra:#c8593a;--gold:#c89b3a;--soft:#5a5750;--r:3px}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'Lato',sans-serif;background:var(--warm);color:var(--ink);overflow-x:hidden}
a{text-decoration:none;color:inherit}button{cursor:pointer;font-family:inherit;border:none;outline:none}
input,textarea,select{font-family:inherit;outline:none;border:none}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-thumb{background:var(--sage)}
nav{position:sticky;top:0;z-index:100;background:rgba(247,242,232,.95);backdrop-filter:blur(16px);border-bottom:1px solid rgba(26,58,42,.1);padding:0 clamp(16px,5vw,72px);display:flex;align-items:center;justify-content:space-between;height:62px}
.nav-logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--forest)}
.nav-dot{width:30px;height:30px;background:var(--moss);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem}
.back-link{font-size:.82rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--leaf);transition:color .2s}
.back-link:hover{color:var(--forest)}

/* PAGE HEADER */
.page-header{background:var(--forest);padding:clamp(44px,7vh,88px) clamp(16px,6vw,80px);position:relative;overflow:hidden}
.page-header::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.04) 1px,transparent 1px);background-size:36px 36px}
.ph-inner{position:relative;z-index:2;max-width:620px}
.ph-kicker{font-family:'DM Mono',monospace;font-size:.68rem;letter-spacing:.16em;text-transform:uppercase;color:var(--sage);margin-bottom:12px}
.ph-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,3rem);color:#fff;line-height:1.15;margin-bottom:14px}
.ph-title em{color:var(--gold);font-style:italic}
.ph-sub{color:rgba(255,255,255,.65);font-size:.95rem;line-height:1.7}

/* FORM LAYOUT */
.form-wrap{max-width:820px;margin:0 auto;padding:clamp(28px,5vh,60px) clamp(16px,4vw,40px)}

/* GUIDELINES */
.guidelines{background:var(--parchment);border:1px solid var(--sand);border-left:3px solid var(--gold);padding:20px 22px;margin-bottom:28px}
.gl-title{font-family:'Playfair Display',serif;font-size:1rem;color:var(--forest);margin-bottom:12px;display:flex;align-items:center;gap:8px}
.gl-list{display:grid;grid-template-columns:1fr 1fr;gap:8px 20px}
.gl-item{font-size:.83rem;color:var(--soft);display:flex;align-items:flex-start;gap:7px;line-height:1.45}
.gl-item::before{content:'✓';color:var(--leaf);font-weight:700;flex-shrink:0;margin-top:1px}

/* PANELS */
.form-panel{background:#fff;border:1px solid var(--sand);margin-bottom:20px;overflow:hidden}
.fp-header{background:linear-gradient(90deg,var(--forest),var(--moss));padding:16px 24px;display:flex;align-items:center;gap:12px}
.fp-icon{width:36px;height:36px;background:rgba(255,255,255,.13);display:flex;align-items:center;justify-content:center;font-size:1.1rem}
.fp-title{color:#fff;font-family:'Playfair Display',serif;font-size:1rem}
.fp-sub{color:rgba(255,255,255,.6);font-size:.78rem;margin-top:1px}
.fp-body{padding:24px}

/* FORM FIELDS */
.fg{display:flex;flex-direction:column;gap:7px;margin-bottom:18px}
.fg:last-child{margin-bottom:0}
.f-label{font-family:'DM Mono',monospace;font-size:.66rem;letter-spacing:.14em;text-transform:uppercase;color:var(--soft)}
.f-label .req{color:var(--terra)}
.f-inp,.f-sel,.f-ta{background:var(--cream);border:1.5px solid var(--sand);padding:11px 14px;font-size:.9rem;color:var(--ink);transition:border-color .2s,box-shadow .2s;width:100%}
.f-inp:focus,.f-sel:focus,.f-ta:focus{border-color:var(--leaf);box-shadow:0 0 0 3px rgba(74,140,92,.1);background:#fff}
.f-inp::placeholder,.f-ta::placeholder{color:rgba(90,87,80,.35)}
.f-ta{resize:vertical;line-height:1.65}
.f-hint{font-size:.73rem;color:rgba(90,87,80,.5);line-height:1.45}
.char-count{font-family:'DM Mono',monospace;font-size:.66rem;color:var(--soft);text-align:right;margin-top:3px}

/* KEYWORD CHIPS */
.keyword-chips{display:flex;flex-wrap:wrap;gap:7px;margin-top:10px}
.kw-chip{padding:4px 12px;background:rgba(74,140,92,.08);border:1px solid rgba(74,140,92,.2);font-size:.75rem;font-weight:700;color:var(--leaf);letter-spacing:.03em}

/* UPLOAD ZONE */
.upload-zone{border:2px dashed rgba(26,58,42,.2);padding:32px;text-align:center;cursor:pointer;transition:all .2s;background:var(--cream)}
.upload-zone:hover{border-color:var(--leaf);background:rgba(74,140,92,.04)}
.uz-icon{font-size:2rem;margin-bottom:9px}
.uz-text{color:var(--soft);font-size:.87rem;line-height:1.55}
.uz-text strong{color:var(--leaf)}

/* SUBMIT */
.submit-bar{display:flex;gap:12px;align-items:center;flex-wrap:wrap;margin-top:8px}
.btn-submit{background:var(--forest);color:#fff;padding:14px 30px;font-weight:700;font-size:.92rem;letter-spacing:.04em;text-transform:uppercase;transition:all .2s;display:flex;align-items:center;gap:8px;border:2px solid var(--forest)}
.btn-submit:hover{background:var(--moss);border-color:var(--moss);transform:translateY(-1px);box-shadow:0 6px 20px rgba(26,58,42,.25)}
.btn-submit:disabled{opacity:.6;cursor:not-allowed;transform:none;box-shadow:none}
.btn-preview{background:transparent;color:var(--forest);padding:14px 24px;font-weight:700;font-size:.88rem;border:2px solid var(--forest);letter-spacing:.04em;text-transform:uppercase;transition:all .2s}
.btn-preview:hover{background:var(--forest);color:#fff}
.submit-note{font-size:.78rem;color:var(--soft);line-height:1.5;max-width:360px}

/* MESSAGES */
.msg-success{display:none;background:#fff;border:2px solid var(--leaf);padding:28px;text-align:center;margin-top:20px}
.ms-icon{font-size:2.4rem;margin-bottom:12px}
.ms-title{font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--forest);margin-bottom:8px}
.ms-sub{color:var(--soft);font-size:.87rem;line-height:1.6;margin-bottom:16px}
.ms-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.ms-btn{padding:10px 22px;font-weight:700;font-size:.82rem;letter-spacing:.04em;text-transform:uppercase;border:2px solid var(--forest);color:var(--forest);transition:all .2s}
.ms-btn:hover,.ms-btn.filled{background:var(--forest);color:#fff}
.msg-error{display:none;background:rgba(200,89,58,.06);border:1px solid rgba(200,89,58,.25);border-left:3px solid var(--terra);padding:13px 16px;margin-top:12px;color:var(--terra);font-size:.87rem;line-height:1.55}

/* RELEVANCE INDICATOR */
.relevance-bar{margin-top:10px;display:none}
.rel-bar-track{height:4px;background:var(--sand);margin-bottom:5px;overflow:hidden}
.rel-bar-fill{height:100%;background:var(--leaf);transition:width .4s ease}
.rel-bar-label{font-family:'DM Mono',monospace;font-size:.64rem;letter-spacing:.1em;color:var(--soft)}

/* GRID */
.fg-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.fg-row.three{grid-template-columns:1fr 1fr 1fr}

/* Toast */
.toast{position:fixed;bottom:24px;right:24px;z-index:999;background:var(--forest);color:#fff;padding:12px 20px;font-size:.88rem;font-weight:700;display:flex;align-items:center;gap:8px;box-shadow:0 8px 28px rgba(0,0,0,.25);transform:translateY(16px);opacity:0;transition:all .3s;pointer-events:none;border-left:3px solid var(--gold)}
.toast.show{transform:none;opacity:1}

@media(max-width:600px){.fg-row,.fg-row.three{grid-template-columns:1fr}.gl-list{grid-template-columns:1fr}}
</style>
</head>
<body>

<nav>
  <a href="blog.php" class="nav-logo"><div class="nav-dot">🌿</div>Ecosphere</a>
  <a href="blog.php" class="back-link">← Back to Journal</a>
</nav>

<div class="page-header">
  <div class="ph-inner">
    <div class="ph-kicker">// Submit Your Story</div>
    <h1 class="ph-title">Write for<br><em>Ecosphere</em></h1>
    <p class="ph-sub">Share your insights, experiences, and knowledge about waste management, recycling, sustainability, or eco-living. All submissions are reviewed before publication.</p>
  </div>
</div>

<div class="form-wrap">

  <!-- GUIDELINES -->
  <div class="guidelines">
    <div class="gl-title">📋 Submission Guidelines</div>
    <div class="gl-list">
      <div class="gl-item">Must be about waste management, recycling, composting, sustainability, or related environmental topics</div>
      <div class="gl-item">Minimum 100 characters of original content</div>
      <div class="gl-item">No promotional content, spam, or unrelated topics</div>
      <div class="gl-item">All blogs are reviewed by our admin team before going live</div>
      <div class="gl-item">You'll receive a tracking ID to check your submission status</div>
      <div class="gl-item">Approved blogs appear on the public journal page</div>
    </div>
  </div>

  <!-- KEYWORDS REFERENCE -->
  <div style="margin-bottom:24px">
    <div style="font-family:'DM Mono',monospace;font-size:.64rem;letter-spacing:.12em;text-transform:uppercase;color:var(--soft);margin-bottom:8px">Accepted Topics Include</div>
    <div class="keyword-chips">
      <?php
      $chips = ['Waste Reduction','Composting','Recycling','Zero Waste','Plastic-Free','E-Waste','Sustainability','Upcycling','Organic Waste','Green Living','Circular Economy','Fast Fashion Waste','Carbon Footprint','Eco-friendly Habits'];
      foreach ($chips as $c): ?>
      <span class="kw-chip"><?= $c ?></span>
      <?php endforeach; ?>
    </div>
  </div>

  <form id="blogForm" enctype="multipart/form-data">

    <!-- AUTHOR INFO -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">👤</div><div><div class="fp-title">Author Details</div><div class="fp-sub">We use your email to send you status updates</div></div></div>
      <div class="fp-body">
        <div class="fg-row">
          <div class="fg">
            <label class="f-label">Author Name <span class="req">*</span></label>
            <input class="f-inp" name="author_name" type="text" placeholder="Your full name or pen name" maxlength="100" required>
          </div>
          <div class="fg">
            <label class="f-label">Email Address <span class="req">*</span></label>
            <input class="f-inp" name="email" type="email" placeholder="you@example.com" required>
            <span class="f-hint">Used to track your submission status. Not shown publicly.</span>
          </div>
        </div>
      </div>
    </div>

    <!-- BLOG CONTENT -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">✍</div><div><div class="fp-title">Blog Content</div><div class="fp-sub">Write clearly, specifically, and from experience where possible</div></div></div>
      <div class="fp-body">
        <div class="fg">
          <label class="f-label">Article Title <span class="req">*</span></label>
          <input class="f-inp" name="title" id="titleField" type="text" placeholder="e.g. How I Reduced My Household Waste by 60% in 3 Months" maxlength="300" required oninput="checkRelevance()">
          <div class="char-count" id="titleCount">0 / 300</div>
        </div>
        <div class="fg">
          <label class="f-label">Blog Content <span class="req">*</span></label>
          <textarea class="f-ta" name="content" id="contentField" placeholder="Write your full blog post here. You can use basic HTML tags like &lt;h3&gt;, &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;li&gt; for formatting.&#10;&#10;Aim for at least 300 words for a good quality article." style="min-height:320px" required oninput="checkRelevance()"></textarea>
          <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;flex-wrap:wrap;gap:8px">
            <span class="f-hint">Basic HTML formatting supported. Minimum 100 characters.</span>
            <div class="char-count" id="contentCount">0 characters</div>
          </div>
          <div class="relevance-bar" id="relevanceBar">
            <div class="rel-bar-track"><div class="rel-bar-fill" id="relFill" style="width:0%"></div></div>
            <div class="rel-bar-label" id="relLabel">Checking topic relevance…</div>
          </div>
        </div>
      </div>
    </div>

    <!-- IMAGE UPLOAD -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">🖼</div><div><div class="fp-title">Cover Image (Optional)</div><div class="fp-sub">Articles with images get significantly more engagement</div></div></div>
      <div class="fp-body">
        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imgInput').click()">
          <div class="uz-icon">📷</div>
          <div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.75rem;color:rgba(90,87,80,.4)">JPG, PNG, WEBP · Max <?= MAX_UPLOAD_MB ?>MB · Landscape orientation preferred</span></div>
        </div>
        <input type="file" id="imgInput" name="image" accept="image/*" style="display:none" onchange="previewImg(event)">
      </div>
    </div>

    <!-- SUBMIT -->
    <div class="submit-bar">
      <button type="submit" class="btn-submit" id="submitBtn">📮 Submit for Review</button>
      <div class="submit-note">Your blog will be reviewed by our editorial team. You'll be notified of the outcome via your email tracking — no account needed.</div>
    </div>

    <div class="msg-error" id="errMsg"></div>

    <div class="msg-success" id="successMsg">
      <div class="ms-icon">✅</div>
      <div class="ms-title">Blog Submitted!</div>
      <div class="ms-sub">Your article is now in our review queue. Check its status anytime on your dashboard using the email you provided. Most reviews complete within 24–48 hours.</div>
      <div class="ms-actions">
        <a href="user_dashboard.php" class="ms-btn filled">📊 Check Status →</a>
        <a href="blog.php" class="ms-btn">← Read the Journal</a>
      </div>
    </div>

  </form>
</div>

<div class="toast" id="toast"><span id="toastMsg"></span></div>

<script>
const KEYWORDS = ['waste','recycle','recycling','reuse','upcycle','compost','composting','sustainability','sustainable','environment','environmental','landfill','plastic','e-waste','zero waste','pollution','green','eco','biodegradable','carbon','climate','circular','textile','food waste','organic','reduce','conservation','energy','ecology','nature','planet'];

function toast(msg,dur=2600){const t=document.getElementById('toast');document.getElementById('toastMsg').textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),dur)}

// Char counters
document.getElementById('titleField').addEventListener('input',function(){
  document.getElementById('titleCount').textContent=this.value.length+' / 300';
});
document.getElementById('contentField').addEventListener('input',function(){
  document.getElementById('contentCount').textContent=this.value.length+' characters';
});

// Relevance checker
function checkRelevance(){
  const text=(document.getElementById('titleField').value+' '+document.getElementById('contentField').value).toLowerCase();
  if(text.trim().length<10){document.getElementById('relevanceBar').style.display='none';return;}
  const found=KEYWORDS.filter(k=>text.includes(k));
  const pct=Math.min(100,Math.round((found.length/Math.max(1,KEYWORDS.length*0.15))*100));
  const bar=document.getElementById('relevanceBar');
  bar.style.display='block';
  document.getElementById('relFill').style.width=pct+'%';
  document.getElementById('relFill').style.background=pct>=60?'#4a8c5c':pct>=30?'#c89b3a':'#c8593a';
  document.getElementById('relLabel').textContent=pct>=60?'✓ Content looks relevant to environmental topics'
    :pct>=30?'⚠ Add more detail about environmental topics for better chances of approval'
    :'✗ Content may not be related to waste management or sustainability';
}

// Image preview
function previewImg(e){
  const f=e.target.files[0];if(!f)return;
  if(f.size>5*1024*1024){toast('⚠️ Image too large (max 5MB)');return;}
  const url=URL.createObjectURL(f);
  document.getElementById('uploadZone').innerHTML=`<img src="${url}" style="max-height:180px;object-fit:cover;margin-bottom:10px"><div class="uz-text">${f.name} — <strong style="cursor:pointer;color:var(--leaf)" onclick="document.getElementById('imgInput').click()">Change</strong></div>`;
}
const uz=document.getElementById('uploadZone');
uz.addEventListener('dragover',e=>{e.preventDefault();uz.style.borderColor='var(--leaf)'});
uz.addEventListener('dragleave',()=>uz.style.borderColor='');
uz.addEventListener('drop',e=>{e.preventDefault();uz.style.borderColor='';const f=e.dataTransfer.files[0];if(f){document.getElementById('imgInput').files=e.dataTransfer.files;previewImg({target:{files:[f]}});}});

// Form submit
document.getElementById('blogForm').addEventListener('submit',async function(e){
  e.preventDefault();
  const btn=document.getElementById('submitBtn');
  const err=document.getElementById('errMsg');
  const suc=document.getElementById('successMsg');
  err.style.display='none';suc.style.display='none';
  btn.disabled=true;btn.innerHTML='⏳ Submitting…';

  try{
    const res=await fetch('api/submit_blog.php',{method:'POST',body:new FormData(this)});
    const data=await res.json();
    if(data.success){
      suc.style.display='block';
      suc.scrollIntoView({behavior:'smooth',block:'center'});
      this.reset();
      document.getElementById('titleCount').textContent='0 / 300';
      document.getElementById('contentCount').textContent='0 characters';
      document.getElementById('relevanceBar').style.display='none';
      document.getElementById('uploadZone').innerHTML='<div class="uz-icon">📷</div><div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.75rem;color:rgba(90,87,80,.4)">JPG, PNG, WEBP · Max 5MB</span></div>';
      toast('✅ Blog submitted for review!');
    }else{
      err.textContent='⚠️ '+(data.error||'Submission failed.');
      err.style.display='block';
      err.scrollIntoView({behavior:'smooth'});
    }
  }catch(ex){
    err.textContent='⚠️ Network error. Is the server running?';
    err.style.display='block';
  }finally{
    btn.disabled=false;btn.innerHTML='📮 Submit for Review';
  }
});
</script>
</body>
</html>
