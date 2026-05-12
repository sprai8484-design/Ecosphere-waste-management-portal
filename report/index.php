<?php
require_once 'db.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporter    = trim(htmlspecialchars($_POST['reporter']    ?? '')) ?: 'Anonymous';
    $location    = trim(htmlspecialchars($_POST['location']    ?? ''));
    $description = trim(htmlspecialchars($_POST['description'] ?? ''));

    if (!$location || !$description) {
        $error = 'Please fill in location and description.';
    } else {
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = uploadImage($_FILES['image']);
            if (!$imageName) $error = 'Image upload failed. Use JPG/PNG/WEBP under 5 MB.';
        }

        if (!$error) {
            db()->prepare('INSERT INTO reports (reporter, location, description, image) VALUES (?,?,?,?)')
               ->execute([$reporter, $location, $description, $imageName]);
            $success = 'Your report has been submitted! We will review it shortly.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Report Waste — Ecosphere</title>
<style>
/* ── Ecosphere token palette ── */
:root{
  --green-dark:#1a3a2a;--green-mid:#2d5a3d;--green:#4a8c5c;--green-light:#7ab88a;
  --cream:#f5f0e8;--sand:#e4ddd0;--warm:#faf8f3;--terra:#d4745a;--gold:#d4a843;
  --charcoal:#2c2c2c;--soft:#5a5750;--muted:#9a9690;
  --r:10px;--shadow:0 2px 14px rgba(26,58,42,.10);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{font-size:15px;scroll-behavior:smooth}
body{font-family:'Segoe UI',system-ui,sans-serif;background:var(--warm);color:var(--charcoal);min-height:100vh}
a{text-decoration:none;color:inherit}

/* NAV */
nav{background:var(--green-dark);padding:0 clamp(16px,4vw,48px);height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 2px 12px rgba(0,0,0,.18)}
.logo{color:#fff;font-weight:700;font-size:1.15rem;letter-spacing:.02em;display:flex;align-items:center;gap:8px}
.logo span{opacity:.8;font-size:.78rem;font-weight:400}
.nav-links{display:flex;gap:6px}
.nav-links a{color:rgba(255,255,255,.65);font-size:.84rem;padding:6px 13px;border-radius:50px;transition:all .18s}
.nav-links a:hover,.nav-links a.active{background:rgba(255,255,255,.12);color:#fff}

/* HERO */
.hero{background:linear-gradient(140deg,var(--green-dark),var(--green-mid));padding:clamp(40px,8vh,80px) clamp(16px,6vw,80px);position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.05) 1px,transparent 1px);background-size:40px 40px;pointer-events:none}
.hero-inner{position:relative;z-index:1;max-width:600px}
.hero h1{font-size:clamp(1.8rem,4vw,3rem);color:#fff;line-height:1.15;margin-bottom:12px}
.hero h1 em{color:var(--gold);font-style:normal}
.hero p{color:rgba(255,255,255,.7);font-size:.95rem;line-height:1.65;max-width:480px}

/* LAYOUT */
.container{max-width:900px;margin:0 auto;padding:clamp(24px,5vh,56px) clamp(16px,4vw,32px)}

/* CARD */
.card{background:#fff;border-radius:var(--r);box-shadow:var(--shadow);border:1px solid rgba(74,140,92,.1);overflow:hidden}
.card-head{background:linear-gradient(135deg,var(--green-dark),var(--green-mid));padding:18px 24px;display:flex;align-items:center;gap:12px}
.card-head .ico{width:36px;height:36px;background:rgba(255,255,255,.14);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}
.card-head h2{color:#fff;font-size:1rem;font-weight:600}
.card-head p{color:rgba(255,255,255,.6);font-size:.78rem;margin-top:2px}
.card-body{padding:24px}

/* FORM */
.fgroup{margin-bottom:16px}
.fgroup label{display:block;font-size:.74rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--green-dark);margin-bottom:6px}
.fgroup label .opt{font-weight:400;text-transform:none;color:var(--muted);letter-spacing:0}
.fgroup input,.fgroup textarea,.fgroup select{width:100%;border:1.5px solid rgba(74,140,92,.2);border-radius:8px;padding:10px 13px;font-size:.9rem;background:var(--cream);color:var(--charcoal);transition:border-color .2s,box-shadow .2s;font-family:inherit}
.fgroup input:focus,.fgroup textarea:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(74,140,92,.12);outline:none;background:#fff}
.fgroup input::placeholder,.fgroup textarea::placeholder{color:rgba(90,90,90,.4)}
.fgroup textarea{resize:vertical;min-height:96px;line-height:1.6}
.frow{display:grid;grid-template-columns:1fr 1fr;gap:14px}

/* Upload Zone */
.upload-zone{border:2px dashed rgba(74,140,92,.3);border-radius:8px;padding:22px;text-align:center;cursor:pointer;transition:all .2s;background:rgba(74,140,92,.02)}
.upload-zone:hover{border-color:var(--green);background:rgba(74,140,92,.05)}
.upload-zone p{font-size:.84rem;color:var(--soft);margin-top:6px}
.upload-zone p strong{color:var(--green)}
#preview{max-height:120px;border-radius:6px;margin:10px auto 0;display:none;border:1px solid rgba(74,140,92,.15);object-fit:cover}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:7px;padding:11px 26px;border-radius:50px;font-size:.9rem;font-weight:700;border:none;cursor:pointer;transition:all .22s}
.btn-primary{background:linear-gradient(135deg,var(--green-dark),var(--green-mid));color:#fff}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(26,58,42,.28)}
.btn-block{width:100%;justify-content:center;padding:13px}

/* Messages */
.msg{padding:12px 16px;border-radius:8px;font-size:.88rem;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:9px}
.msg-ok{background:rgba(74,140,92,.1);color:var(--green);border:1px solid rgba(74,140,92,.25)}
.msg-err{background:rgba(212,116,90,.1);color:var(--terra);border:1px solid rgba(212,116,90,.25)}

/* Footer */
footer{background:var(--green-dark);color:rgba(255,255,255,.45);text-align:center;padding:22px;font-size:.8rem;margin-top:60px}
footer a{color:var(--green-light);font-weight:600}

@media(max-width:540px){.frow{grid-template-columns:1fr}.nav-links a span{display:none}}
</style>
</head>
<body>

<nav>
  <div class="logo">🌿 Ecosphere <span>Waste Reports</span></div>
  <div class="nav-links">
    <a href="index.php" class="active">📋 <span>Report</span></a>
    <a href="approved.php">🌍 <span>Public</span></a>
    <a href="admin.php">⚙ <span>Admin</span></a>
  </div>
</nav>

<div class="hero">
  <div class="hero-inner">
    <h1>Report an <em>Unclean Area</em></h1>
    <p>See waste or a sanitation issue? Submit a report — our team reviews and takes action. Every complaint helps build a cleaner community.</p>
  </div>
</div>

<div class="container">
  <div class="card">
    <div class="card-head">
      <div class="ico">📋</div>
      <div>
        <h2>Submit Waste Report</h2>
        <p>Your report is reviewed within 24–48 hours</p>
      </div>
    </div>
    <div class="card-body">

      <?php if ($success): ?>
      <div class="msg msg-ok">✅ <?= $success ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
      <div class="msg msg-err">⚠️ <?= $error ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" id="reportForm">

        <div class="frow">
          <div class="fgroup">
            <label>Your Name <span class="opt">(optional)</span></label>
            <input type="text" name="reporter" placeholder="Anonymous">
          </div>
          <div class="fgroup">
            <label>Location *</label>
            <input type="text" name="location" placeholder="e.g. Andheri East, Mumbai" required>
          </div>
        </div>

        <div class="fgroup">
          <label>Describe the Issue *</label>
          <textarea name="description" placeholder="What kind of waste? How serious is it? Any nearby landmarks?" required></textarea>
        </div>

        <div class="fgroup">
          <label>Upload Photo <span class="opt">(optional, max 5 MB)</span></label>
          <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imgInput').click()">
            <div style="font-size:1.8rem">📷</div>
            <p><strong>Click to upload</strong> or drag & drop</p>
            <p style="font-size:.76rem;color:var(--muted)">JPG, PNG, WEBP accepted</p>
            <img id="preview" alt="preview">
          </div>
          <input type="file" id="imgInput" name="image" accept="image/*" style="display:none" onchange="showPreview(event)">
        </div>

        <button type="submit" class="btn btn-primary btn-block">📤 Submit Report</button>

      </form>
    </div>
  </div>

  <!-- Quick link to public view -->
  <div style="text-align:center;margin-top:22px">
    <a href="approved.php" style="color:var(--green);font-size:.86rem;font-weight:600">→ See all approved reports on the public page</a>
  </div>
</div>

<footer>
  © <?= date('Y') ?> Ecosphere · <a href="approved.php">Public Reports</a> · <a href="admin.php">Admin</a>
</footer>

<script>
function showPreview(e){
  const f=e.target.files[0]; if(!f) return;
  const p=document.getElementById('preview');
  p.src=URL.createObjectURL(f); p.style.display='block';
  document.querySelector('#uploadZone p').innerHTML='<strong style="color:var(--green)">✅ '+f.name+'</strong>';
}
const uz=document.getElementById('uploadZone');
uz.addEventListener('dragover',e=>{e.preventDefault();uz.style.borderColor='var(--green)'});
uz.addEventListener('dragleave',()=>uz.style.borderColor='');
uz.addEventListener('drop',e=>{
  e.preventDefault();uz.style.borderColor='';
  const f=e.dataTransfer.files[0];
  if(f){const dt=new DataTransfer();dt.items.add(f);document.getElementById('imgInput').files=dt.files;
        showPreview({target:{files:[f]}});}
});
</script>
</body>
</html>
