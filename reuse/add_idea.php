<?php
// add_idea.php — Submit a new reuse idea
require_once 'config.php';
$prefillCat = clean($_GET['category'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Share Your Idea — Ecosphere Reuse</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--forest:#1a3a2a;--moss:#2d5a3d;--leaf:#4a8c5c;--sage:#7ab88a;--mint:#a8d5b5;--cream:#f5f0e8;--parchment:#ede6d6;--warm:#faf8f3;--earth:#8b6f47;--clay:#c4956a;--terra:#d4745a;--charcoal:#2c2c2c;--soft:#5a5a5a;--gold:#d4a843;--r:16px;--rs:10px;--shadow:0 4px 20px rgba(26,58,42,.09);--shadow-h:0 12px 40px rgba(26,58,42,.18)}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}html{scroll-behavior:smooth}
body{font-family:'DM Sans',sans-serif;background:var(--warm);color:var(--charcoal);overflow-x:hidden}
button{cursor:pointer;font-family:inherit;border:none;outline:none}
input,textarea,select{font-family:inherit;outline:none;border:none}a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}::-webkit-scrollbar-thumb{background:var(--sage);border-radius:3px}

/* NAV */
nav{position:sticky;top:0;z-index:100;background:rgba(245,240,232,.94);backdrop-filter:blur(20px);border-bottom:1px solid rgba(74,140,92,.13);padding:0 clamp(16px,4vw,60px);display:flex;align-items:center;justify-content:space-between;height:64px}
.nav-logo{display:flex;align-items:center;gap:9px;font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--forest)}
.nav-dot{width:32px;height:32px;background:var(--moss);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.9rem}
.nav-back{font-size:.88rem;color:var(--leaf);font-weight:600;transition:color .2s}
.nav-back:hover{color:var(--forest)}

/* PAGE HEADER */
.page-header{background:linear-gradient(140deg,var(--forest),var(--moss));padding:clamp(44px,7vh,80px) clamp(16px,6vw,100px);text-align:center;position:relative;overflow:hidden}
.page-header::before{content:'';position:absolute;top:-30%;right:-10%;width:50vw;height:50vw;border-radius:50%;background:rgba(255,255,255,.03);pointer-events:none}
.ph-tag{display:inline-block;color:var(--mint);font-size:.74rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px}
.ph-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,3rem);color:#fff;margin-bottom:12px}
.ph-title em{font-style:italic;color:var(--gold)}
.ph-sub{color:rgba(255,255,255,.68);font-size:.97rem;max-width:480px;margin:0 auto;line-height:1.65}

/* FORM WRAPPER */
.form-wrapper{max-width:800px;margin:0 auto;padding:clamp(28px,5vh,60px) clamp(16px,4vw,40px)}
.form-panel{background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid rgba(74,140,92,.09);overflow:hidden;margin-bottom:24px}
.fp-header{background:linear-gradient(135deg,var(--forest),var(--moss));padding:22px 28px;display:flex;align-items:center;gap:13px}
.fp-icon{width:42px;height:42px;background:rgba(255,255,255,.14);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem}
.fp-title{color:#fff;font-family:'Playfair Display',serif;font-size:1.1rem}
.fp-sub{color:rgba(255,255,255,.6);font-size:.82rem;margin-top:2px}
.fp-body{padding:28px}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px}
.form-grid.three{grid-template-columns:1fr 1fr 1fr}
.fg{display:flex;flex-direction:column;gap:7px}
.fg.full{grid-column:1/-1}
.f-label{font-size:.76rem;font-weight:700;color:var(--forest);text-transform:uppercase;letter-spacing:.06em}
.f-label span{color:var(--terra)}
.f-inp,.f-sel,.f-ta{background:var(--cream);border:1.5px solid rgba(74,140,92,.16);border-radius:var(--rs);padding:11px 15px;font-size:.9rem;color:var(--charcoal);transition:border-color .2s,box-shadow .2s;width:100%}
.f-inp:focus,.f-sel:focus,.f-ta:focus{border-color:var(--leaf);box-shadow:0 0 0 3px rgba(74,140,92,.1)}
.f-inp::placeholder,.f-ta::placeholder{color:rgba(90,90,90,.38)}
.f-ta{resize:vertical;min-height:100px}
.f-hint{font-size:.73rem;color:rgba(90,90,90,.5);margin-top:2px}

/* Difficulty selector */
.diff-row{display:flex;gap:10px}
.diff-opt{flex:1;border:2px solid rgba(74,140,92,.16);border-radius:var(--rs);padding:12px 10px;text-align:center;cursor:pointer;transition:all .2s;background:var(--cream)}
.diff-opt input{display:none}
.diff-opt .do-icon{font-size:1.2rem;display:block;margin-bottom:4px}
.diff-opt .do-label{font-size:.82rem;font-weight:600;color:var(--soft)}
.diff-opt.sel-Easy{border-color:var(--leaf);background:rgba(74,140,92,.07)}
.diff-opt.sel-Easy .do-label{color:var(--leaf)}
.diff-opt.sel-Medium{border-color:var(--gold);background:rgba(212,168,67,.07)}
.diff-opt.sel-Medium .do-label{color:var(--earth)}
.diff-opt.sel-Hard{border-color:var(--terra);background:rgba(212,116,90,.08)}
.diff-opt.sel-Hard .do-label{color:var(--terra)}

/* Steps builder */
.steps-list{display:flex;flex-direction:column;gap:11px;margin-bottom:12px}
.step-builder-row{display:flex;gap:10px;align-items:flex-start}
.sb-num{width:30px;height:30px;min-width:30px;background:var(--sage);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;margin-top:11px}
.sb-del{background:none;color:rgba(90,90,90,.5);padding:10px 8px;transition:color .2s;margin-top:6px}
.sb-del:hover{color:var(--terra)}
.add-step-btn{background:none;color:var(--leaf);border:1.5px dashed rgba(74,140,92,.3);border-radius:var(--rs);padding:10px;width:100%;font-size:.88rem;font-weight:600;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:6px}
.add-step-btn:hover{border-color:var(--leaf);background:rgba(74,140,92,.04)}

/* Upload zone */
.upload-zone{border:2px dashed rgba(74,140,92,.25);border-radius:var(--rs);padding:36px;text-align:center;cursor:pointer;transition:all .2s;background:rgba(74,140,92,.01)}
.upload-zone:hover{border-color:var(--leaf);background:rgba(74,140,92,.04)}
.uz-icon{font-size:2.4rem;margin-bottom:10px}
.uz-text{color:var(--soft);font-size:.88rem;line-height:1.55}
.uz-text strong{color:var(--leaf)}

/* Submit */
.btn-submit{width:100%;background:linear-gradient(135deg,var(--forest),var(--moss));color:#fff;padding:16px;border-radius:50px;font-weight:700;font-size:1rem;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:9px}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,58,42,.28)}
.btn-submit:disabled{opacity:.6;cursor:not-allowed;transform:none}

/* Messages */
.msg-success{display:none;background:rgba(74,140,92,.07);border:1px solid rgba(74,140,92,.2);border-radius:var(--rs);padding:20px 24px;text-align:center;margin-top:18px}
.ms-icon{font-size:2.2rem;margin-bottom:8px}
.ms-title{color:var(--forest);font-weight:700;font-size:1.05rem;margin-bottom:6px}
.ms-sub{color:var(--soft);font-size:.87rem}
.msg-error{display:none;background:rgba(212,116,90,.07);border:1px solid rgba(212,116,90,.2);border-radius:var(--rs);padding:13px 17px;margin-top:12px;color:var(--terra);font-size:.87rem}

/* Tips panel */
.tips-panel{background:var(--parchment);border-radius:var(--r);padding:22px 24px;border:1px solid rgba(74,140,92,.1)}
.tp-title{font-weight:700;color:var(--forest);font-size:.9rem;margin-bottom:14px;display:flex;align-items:center;gap:7px}
.tip-item{display:flex;gap:9px;margin-bottom:10px;font-size:.84rem;color:var(--soft);line-height:1.5}
.tip-item::before{content:'✓';color:var(--leaf);font-weight:700;flex-shrink:0}

/* Toast */
.toast{position:fixed;bottom:26px;right:26px;z-index:999;background:var(--forest);color:#fff;padding:12px 20px;border-radius:12px;font-size:.88rem;font-weight:500;display:flex;align-items:center;gap:8px;box-shadow:0 8px 28px rgba(26,58,42,.28);transform:translateY(16px);opacity:0;transition:all .3s;pointer-events:none;max-width:310px}
.toast.show{transform:none;opacity:1}

/* Responsive */
@media(max-width:640px){.form-grid,.form-grid.three{grid-template-columns:1fr}.diff-row{flex-direction:column}}
</style>
</head>
<body>

<nav>
  <a href="reuse.php" class="nav-logo"><div class="nav-dot">🌿</div>Ecosphere</a>
  <a href="reuse.php" class="nav-back">← Back to Ideas</a>
</nav>

<div class="page-header">
  <div class="ph-tag">✦ Contribute</div>
  <h1 class="ph-title">Share Your <em>Reuse Idea</em></h1>
  <p class="ph-sub">Inspire thousands to create instead of waste. Every idea shared is a step towards a circular future.</p>
</div>

<div class="form-wrapper">

  <!-- Tips -->
  <div class="tips-panel" style="margin-bottom:24px">
    <div class="tp-title">💡 Tips for a great submission</div>
    <div class="tip-item">Be specific about materials — "old denim jeans" is better than "old clothes"</div>
    <div class="tip-item">Write each step as a clear action — start with a verb like "Cut", "Fold", "Sew"</div>
    <div class="tip-item">Add a photo if you can — ideas with images get 3× more engagement</div>
    <div class="tip-item">Honest difficulty ratings help others choose projects they can complete</div>
  </div>

  <form id="ideaForm" enctype="multipart/form-data">

    <!-- Basic Info -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">📝</div><div><div class="fp-title">Basic Information</div><div class="fp-sub">Tell us about your reuse project</div></div></div>
      <div class="fp-body">
        <div class="form-grid">
          <div class="fg full">
            <label class="f-label">Project Title <span>*</span></label>
            <input class="f-inp" name="title" type="text" placeholder="e.g. Denim Jeans to Tote Bag" maxlength="200" required>
          </div>
          <div class="fg">
            <label class="f-label">Category <span>*</span></label>
            <select class="f-sel" name="category" id="catSel" required>
              <option value="">Select category</option>
              <?php foreach(categories() as $c): ?>
              <option value="<?=$c?>" <?=$prefillCat===$c?'selected':''?>><?=$c?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="fg">
            <label class="f-label">Time Required <span>*</span></label>
            <input class="f-inp" name="time_required" type="text" placeholder="e.g. 45 minutes" required>
          </div>
          <div class="fg full">
            <label class="f-label">Difficulty <span>*</span></label>
            <div class="diff-row">
              <label class="diff-opt sel-Easy" id="opt-Easy">
                <input type="radio" name="difficulty" value="Easy" checked>
                <span class="do-icon">😊</span><span class="do-label">Easy</span>
              </label>
              <label class="diff-opt" id="opt-Medium">
                <input type="radio" name="difficulty" value="Medium">
                <span class="do-icon">🔧</span><span class="do-label">Medium</span>
              </label>
              <label class="diff-opt" id="opt-Hard">
                <input type="radio" name="difficulty" value="Hard">
                <span class="do-icon">💪</span><span class="do-label">Hard</span>
              </label>
            </div>
          </div>
          <div class="fg full">
            <label class="f-label">Author Name <span>*</span></label>
            <input class="f-inp" name="author" type="text" placeholder="Your name or nickname" maxlength="100" required>
          </div>
        </div>
      </div>
    </div>

    <!-- Description & Materials -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">📖</div><div><div class="fp-title">Description & Materials</div><div class="fp-sub">Help people understand your project</div></div></div>
      <div class="fp-body">
        <div class="fg" style="margin-bottom:18px">
          <label class="f-label">Description <span>*</span></label>
          <textarea class="f-ta" name="description" placeholder="Describe your reuse idea. What waste item does it use? What does it become? Why is it useful or beautiful?" style="min-height:120px" required></textarea>
        </div>
        <div class="fg">
          <label class="f-label">Materials Needed <span>*</span></label>
          <textarea class="f-ta" name="materials" placeholder="Old denim jeans, Sharp fabric scissors, Needle and thread, Rope handles…" style="min-height:80px" required></textarea>
          <span class="f-hint">Separate each item with a comma. Be specific about sizes or quantities if helpful.</span>
        </div>
      </div>
    </div>

    <!-- Steps -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">📋</div><div><div class="fp-title">Step-by-Step Instructions</div><div class="fp-sub">Guide others through your process clearly</div></div></div>
      <div class="fp-body">
        <div class="steps-list" id="stepsList">
          <div class="step-builder-row">
            <div class="sb-num">1</div>
            <input class="f-inp" name="steps[]" type="text" placeholder="e.g. Lay your jeans flat and cut both legs off 3cm below the crotch seam." style="flex:1" required>
            <button type="button" class="sb-del" onclick="removeStep(this)" title="Remove step">✕</button>
          </div>
          <div class="step-builder-row">
            <div class="sb-num">2</div>
            <input class="f-inp" name="steps[]" type="text" placeholder="e.g. Turn inside out and sew the bottom edge closed with a double stitch." style="flex:1">
            <button type="button" class="sb-del" onclick="removeStep(this)" title="Remove step">✕</button>
          </div>
        </div>
        <button type="button" class="add-step-btn" onclick="addStep()">+ Add Another Step</button>
      </div>
    </div>

    <!-- Image Upload -->
    <div class="form-panel">
      <div class="fp-header"><div class="fp-icon">📷</div><div><div class="fp-title">Photo (Optional but Recommended)</div><div class="fp-sub">Ideas with photos get 3× more views</div></div></div>
      <div class="fp-body">
        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imgFile').click()">
          <div class="uz-icon">📷</div>
          <div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.76rem;color:rgba(90,90,90,.4)">JPG, PNG, WEBP up to 5MB — show the before & after if possible!</span></div>
        </div>
        <input type="file" id="imgFile" name="image" accept="image/*" style="display:none" onchange="previewImage(event)">
      </div>
    </div>

    <button type="submit" class="btn-submit" id="submitBtn">🌿 Share My Idea with the Community</button>
    <div class="msg-error" id="errMsg"></div>
    <div class="msg-success" id="successMsg">
      <div class="ms-icon">🎉</div>
      <div class="ms-title">Your idea has been shared!</div>
      <div class="ms-sub">Thanks for contributing to a more sustainable world. Others can now discover and learn from your project.</div>
      <div style="display:flex;gap:12px;justify-content:center;margin-top:18px;flex-wrap:wrap">
        <a id="viewIdeaLink" href="reuse.php" style="background:var(--forest);color:#fff;padding:10px 22px;border-radius:50px;font-weight:600;font-size:.88rem">View My Idea →</a>
        <a href="reuse.php" style="background:var(--cream);color:var(--forest);padding:10px 22px;border-radius:50px;font-weight:600;font-size:.88rem">Browse All Ideas</a>
      </div>
    </div>

  </form>
</div>

<div class="toast" id="toast"><span id="toastMsg"></span></div>

<script>
function toast(msg,dur=2600){const t=document.getElementById('toast');document.getElementById('toastMsg').textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),dur)}

// ── Difficulty selector ────────────────────────────────────────
document.querySelectorAll('.diff-opt').forEach(opt => {
  opt.addEventListener('click', () => {
    document.querySelectorAll('.diff-opt').forEach(o => {
      const v = o.querySelector('input').value;
      o.className = 'diff-opt';
    });
    const v = opt.querySelector('input').value;
    opt.classList.add('sel-' + v);
  });
});

// ── Steps builder ──────────────────────────────────────────────
function addStep() {
  const list  = document.getElementById('stepsList');
  const count = list.children.length + 1;
  const row   = document.createElement('div');
  row.className = 'step-builder-row';
  row.innerHTML = `
    <div class="sb-num">${count}</div>
    <input class="f-inp" name="steps[]" type="text" placeholder="Describe step ${count}…" style="flex:1">
    <button type="button" class="sb-del" onclick="removeStep(this)" title="Remove">✕</button>`;
  list.appendChild(row);
  row.querySelector('input').focus();
}
function removeStep(btn) {
  const list = document.getElementById('stepsList');
  if (list.children.length <= 1) { toast('⚠️ At least one step is required'); return; }
  btn.closest('.step-builder-row').remove();
  // Renumber
  list.querySelectorAll('.sb-num').forEach((n, i) => n.textContent = i + 1);
}

// ── Image preview ──────────────────────────────────────────────
function previewImage(e) {
  const f = e.target.files[0]; if (!f) return;
  const url = URL.createObjectURL(f);
  document.getElementById('uploadZone').innerHTML = `
    <img src="${url}" style="max-height:160px;border-radius:10px;margin-bottom:10px">
    <div class="uz-text">${f.name} <span style="color:var(--leaf);font-weight:600;cursor:pointer" onclick="document.getElementById('imgFile').click()">Change</span></div>`;
}
// Drag & drop
const uz = document.getElementById('uploadZone');
uz.addEventListener('dragover', e => { e.preventDefault(); uz.style.borderColor='var(--leaf)'; });
uz.addEventListener('dragleave', () => uz.style.borderColor = '');
uz.addEventListener('drop', e => {
  e.preventDefault(); uz.style.borderColor = '';
  const f = e.dataTransfer.files[0];
  if (f) { document.getElementById('imgFile').files = e.dataTransfer.files; previewImage({target:{files:[f]}}); }
});

// ── Form submission ────────────────────────────────────────────
document.getElementById('ideaForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn    = document.getElementById('submitBtn');
  const errDiv = document.getElementById('errMsg');
  const sucDiv = document.getElementById('successMsg');
  errDiv.style.display = 'none'; sucDiv.style.display = 'none';

  // Validate at least one non-empty step
  const stepVals = [...document.querySelectorAll('#stepsList input[name="steps[]"]')]
    .map(i => i.value.trim()).filter(v => v !== '');
  if (!stepVals.length) { errDiv.textContent = '⚠️ Please add at least one step.'; errDiv.style.display='block'; return; }

  btn.disabled = true; btn.innerHTML = '⏳ Submitting…';

  const fd = new FormData(this);
  // Replace steps with cleaned array
  fd.delete('steps[]');
  stepVals.forEach(s => fd.append('steps[]', s));

  try {
    const res  = await fetch('api/add_idea.php', { method: 'POST', body: fd });
    const data = await res.json();
    if (data.success) {
      sucDiv.style.display = 'block';
      document.getElementById('viewIdeaLink').href = 'reuse_details.php?id=' + data.id;
      sucDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
      this.reset();
      document.getElementById('uploadZone').innerHTML = '<div class="uz-icon">📷</div><div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.76rem;color:rgba(90,90,90,.4)">JPG, PNG, WEBP up to 5MB</span></div>';
      toast('🎉 Idea shared successfully!');
    } else {
      errDiv.textContent = '⚠️ ' + (data.error || 'Submission failed. Please try again.');
      errDiv.style.display = 'block';
      errDiv.scrollIntoView({ behavior: 'smooth' });
    }
  } catch (ex) {
    errDiv.textContent = '⚠️ Network error. Is the server running?';
    errDiv.style.display = 'block';
  } finally {
    btn.disabled = false; btn.innerHTML = '🌿 Share My Idea with the Community';
  }
});
</script>
</body>
</html>
