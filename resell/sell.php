<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sell an Item — Ecosphere Resell</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
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
      --sand: #e8e0d0;
      --terra: #d4745a;
      --gold: #d4a843;
      --charcoal: #2c2c2c;
      --soft: #5a5a5a;
      --r: 14px;
      --rs: 8px;
      --shadow: 0 4px 20px rgba(26, 58, 42, .09)
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
      width: 5px
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage)
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

    .back-link {
      font-size: .85rem;
      font-weight: 600;
      color: var(--leaf);
      letter-spacing: .03em;
      transition: color .2s
    }

    .back-link:hover {
      color: var(--forest)
    }

    /* HEADER */
    .page-header {
      background: linear-gradient(140deg, var(--forest), var(--moss));
      padding: clamp(48px, 7vh, 88px) clamp(16px, 6vw, 80px);
      position: relative;
      overflow: hidden
    }

    .page-header::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
      background-size: 36px 36px
    }

    .ph-inner {
      position: relative;
      z-index: 2
    }

    .ph-tag {
      font-size: .72rem;
      font-weight: 700;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--sage);
      margin-bottom: 10px
    }

    .ph-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.8rem, 4vw, 3rem);
      color: #fff;
      line-height: 1.15;
      margin-bottom: 12px
    }

    .ph-title em {
      color: var(--gold);
      font-style: italic
    }

    .ph-sub {
      color: rgba(255, 255, 255, .65);
      font-size: .95rem;
      line-height: 1.7;
      max-width: 480px
    }

    /* LAYOUT */
    .sell-layout {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 32px;
      max-width: 1180px;
      margin: 0 auto;
      padding: clamp(32px, 5vh, 60px) clamp(16px, 5vw, 60px);
      align-items: start
    }

    /* FORM PANELS */
    .form-panel {
      background: #fff;
      border: 1px solid rgba(74, 140, 92, .1);
      box-shadow: var(--shadow);
      margin-bottom: 20px;
      border-radius: var(--r);
      overflow: hidden
    }

    .fp-header {
      background: linear-gradient(90deg, var(--forest), var(--moss));
      padding: 16px 24px;
      display: flex;
      align-items: center;
      gap: 12px
    }

    .fp-icon {
      width: 36px;
      height: 36px;
      background: rgba(255, 255, 255, .13);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem
    }

    .fp-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1rem
    }

    .fp-sub {
      color: rgba(255, 255, 255, .6);
      font-size: .78rem;
      margin-top: 1px
    }

    .fp-body {
      padding: 24px
    }

    .fg {
      display: flex;
      flex-direction: column;
      gap: 7px;
      margin-bottom: 18px
    }

    .fg:last-child {
      margin-bottom: 0
    }

    .f-label {
      font-size: .72rem;
      font-weight: 700;
      color: var(--forest);
      text-transform: uppercase;
      letter-spacing: .07em
    }

    .f-label .req {
      color: var(--terra)
    }

    .f-inp,
    .f-sel,
    .f-ta {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .16);
      border-radius: var(--rs);
      padding: 11px 14px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s, box-shadow .2s;
      width: 100%
    }

    .f-inp:focus,
    .f-sel:focus,
    .f-ta:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1);
      background: #fff
    }

    .f-inp::placeholder,
    .f-ta::placeholder {
      color: rgba(90, 90, 90, .38)
    }

    .f-ta {
      resize: vertical;
      min-height: 100px;
      line-height: 1.65
    }

    .f-hint {
      font-size: .72rem;
      color: rgba(90, 90, 90, .5);
      line-height: 1.5
    }

    .char-count {
      font-family: 'DM Mono', monospace;
      font-size: .66rem;
      color: var(--soft);
      text-align: right;
      margin-top: 3px
    }

    .row-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px
    }

    /* UPLOAD */
    .upload-zone {
      border: 2px dashed rgba(74, 140, 92, .25);
      border-radius: var(--rs);
      padding: 32px;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
      background: rgba(74, 140, 92, .01)
    }

    .upload-zone:hover {
      border-color: var(--leaf);
      background: rgba(74, 140, 92, .04)
    }

    .uz-icon {
      font-size: 2.2rem;
      margin-bottom: 8px
    }

    .uz-text {
      color: var(--soft);
      font-size: .85rem;
      line-height: 1.55
    }

    .uz-text strong {
      color: var(--leaf)
    }

    /* SUBMIT */
    .btn-submit {
      width: 100%;
      background: linear-gradient(135deg, var(--forest), var(--moss));
      color: #fff;
      padding: 15px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .97rem;
      transition: all .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(26, 58, 42, .28)
    }

    .btn-submit:disabled {
      opacity: .6;
      cursor: not-allowed;
      transform: none;
      box-shadow: none
    }

    .msg-success {
      display: none;
      background: rgba(74, 140, 92, .07);
      border: 1px solid rgba(74, 140, 92, .2);
      border-radius: var(--rs);
      padding: 20px 24px;
      margin-top: 16px;
      text-align: center
    }

    .ms-icon {
      font-size: 2.2rem;
      margin-bottom: 8px
    }

    .ms-title {
      color: var(--forest);
      font-weight: 700;
      font-size: 1.05rem;
      margin-bottom: 6px
    }

    .ms-sub {
      color: var(--soft);
      font-size: .85rem;
      line-height: 1.6;
      margin-bottom: 12px
    }

    .ms-uid {
      font-family: 'DM Mono', monospace;
      background: var(--forest);
      color: #fff;
      display: inline-block;
      padding: 4px 14px;
      border-radius: 50px;
      font-size: .88rem;
      margin-bottom: 14px
    }

    .ms-actions {
      display: flex;
      gap: 10px;
      justify-content: center;
      flex-wrap: wrap
    }

    .ms-btn {
      padding: 10px 22px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .82rem;
      border: 2px solid var(--forest);
      color: var(--forest);
      transition: all .2s
    }

    .ms-btn:hover,
    .ms-btn.filled {
      background: var(--forest);
      color: #fff
    }

    .msg-error {
      display: none;
      background: rgba(212, 116, 90, .07);
      border: 1px solid rgba(212, 116, 90, .22);
      border-left: 3px solid var(--terra);
      border-radius: var(--rs);
      padding: 12px 16px;
      margin-top: 12px;
      color: var(--terra);
      font-size: .86rem
    }

    /* PREVIEW CARD */
    .preview-panel {
      position: sticky;
      top: 86px
    }

    .preview-card {
      background: #fff;
      border: 1px solid rgba(74, 140, 92, .1);
      box-shadow: var(--shadow);
      border-radius: var(--r);
      overflow: hidden
    }

    .preview-label {
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .12em;
      color: var(--soft);
      padding: 14px 18px 0;
      font-family: 'DM Mono', monospace
    }

    .preview-img {
      height: 200px;
      background: var(--parchment);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 5rem;
      margin: 12px 18px;
      border-radius: 10px;
      overflow: hidden
    }

    .preview-img img {
      width: 100%;
      height: 100%;
      object-fit: cover
    }

    .preview-body {
      padding: 0 18px 18px
    }

    .preview-cat {
      font-size: .7rem;
      font-weight: 700;
      color: var(--leaf);
      text-transform: uppercase;
      letter-spacing: .08em;
      margin-bottom: 5px
    }

    .preview-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest);
      margin-bottom: 8px;
      line-height: 1.3
    }

    .preview-price {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: var(--forest);
      font-weight: 700;
      margin-bottom: 4px
    }

    .preview-meta {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 10px
    }

    .preview-badge {
      padding: 3px 10px;
      border-radius: 50px;
      font-size: .68rem;
      font-weight: 700
    }

    .preview-desc {
      font-size: .82rem;
      color: var(--soft);
      line-height: 1.55;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 14px
    }

    .preview-seller {
      font-size: .76rem;
      color: var(--soft);
      border-top: 1px solid var(--sand);
      padding-top: 10px
    }

    .tips-panel {
      background: var(--parchment);
      border: 1px solid var(--sand);
      border-radius: var(--r);
      padding: 20px;
      margin-top: 16px
    }

    .tp-title {
      font-weight: 700;
      color: var(--forest);
      font-size: .88rem;
      margin-bottom: 12px
    }

    .tip-item {
      display: flex;
      gap: 8px;
      margin-bottom: 8px;
      font-size: .81rem;
      color: var(--soft);
      line-height: 1.45
    }

    .tip-item::before {
      content: '✓';
      color: var(--leaf);
      font-weight: 700;
      flex-shrink: 0
    }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 26px;
      right: 26px;
      z-index: 9999;
      background: var(--forest);
      color: #fff;
      padding: 13px 20px;
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

    @media(max-width:900px) {
      .sell-layout {
        grid-template-columns: 1fr
      }

      .preview-panel {
        position: static
      }

      .row-2 {
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
    <a href="resell.php" class="back-link">← Back to Marketplace</a>
  </nav>

  <div class="page-header">
    <div class="ph-inner">
      <div class="ph-tag">// List Your Item</div>
      <h1 class="ph-title">Sell with<br><em>Ecosphere</em></h1>
      <p class="ph-sub">Give your items a second life. List anything from electronics to furniture and reach eco-conscious buyers. All listings are reviewed before going live.</p>
    </div>
  </div>

  <div class="sell-layout">
    <!-- FORM COLUMN -->
    <div>
      <form id="sellForm" enctype="multipart/form-data">

        <!-- Seller Info -->
        <div class="form-panel">
          <div class="fp-header">
            <div class="fp-icon">👤</div>
            <div>
              <div class="fp-title">Your Details</div>
              <div class="fp-sub">Used to contact you about your listing</div>
            </div>
          </div>
          <div class="fp-body">
            <div class="row-2">
              <div class="fg"><label class="f-label">Your Name <span class="req">*</span></label><input class="f-inp" name="seller_name" type="text" placeholder="Full name" required oninput="updatePreview()"></div>
              <div class="fg"><label class="f-label">Email <span class="req">*</span></label><input class="f-inp" name="seller_email" type="email" placeholder="you@example.com" required></div>
            </div>
            <div class="fg"><label class="f-label">Phone Number <span class="req">*</span></label><input class="f-inp" name="seller_phone" type="tel" placeholder="+91 98765 43210" required></div>
          </div>
        </div>

        <!-- Product Info -->
        <div class="form-panel">
          <div class="fp-header">
            <div class="fp-icon">📦</div>
            <div>
              <div class="fp-title">Product Details</div>
              <div class="fp-sub">Be specific — better descriptions get more buyers</div>
            </div>
          </div>
          <div class="fp-body">
            <div class="fg"><label class="f-label">Product Name <span class="req">*</span></label><input class="f-inp" name="product_name" type="text" placeholder="e.g. Dell Latitude Laptop i5 8GB" maxlength="255" required oninput="updatePreview()"></div>
            <div class="row-2">

              <div class="fg">
                <label class="f-label">Category <span class="req">*</span></label>
                <select class="f-sel" name="category" required onchange="updatePreview()">
                  <option value="">Select category</option>
                  <?php foreach (categories() as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
              </div>
              <div class="fg">
                <label class="f-label">Condition <span class="req">*</span></label>
                <select class="f-sel" name="condition" required onchange="updatePreview()">
                  <option value="">Select condition</option>
                  <?php foreach (conditions() as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row-2">
              <div class="fg">
                <label class="f-label">Quantity <span class="req">*</span></label>
                <input class="f-inp" name="quantity" type="number" min="1" max="9999" placeholder="1" required oninput="updatePreview()">
              </div>
              <div class="fg">
                <label class="f-label">Price (₹) <span class="req">*</span></label>
                <input class="f-inp" name="price" type="number" min="1" step="0.01" placeholder="0.00" required oninput="updatePreview()">
              </div>
            </div>
            <div class="fg">
              <label class="f-label">Description <span class="req">*</span></label>
              <textarea class="f-ta" name="description" placeholder="Describe the item in detail — brand, model, specifications, reason for selling, what's included, any defects…" style="min-height:120px" required oninput="updatePreview();countChars(this,'descCount')"></textarea>
              <div class="char-count" id="descCount">0 characters</div>
              <span class="f-hint">Minimum 20 characters. Include model, specs, and any known issues.</span>
            </div>
          </div>
        </div>

        <!-- Image -->
        <div class="form-panel">
          <div class="fp-header">
            <div class="fp-icon">📷</div>
            <div>
              <div class="fp-title">Product Photo</div>
              <div class="fp-sub">Listings with photos get 5× more interest</div>
            </div>
          </div>
          <div class="fp-body">
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('imgInput').click()">
              <div class="uz-icon">📷</div>
              <div class="uz-text"><strong>Click to upload photo</strong> or drag & drop<br><span style="font-size:.75rem;color:rgba(90,90,90,.4)">JPG, PNG, WEBP · Max <?= MAX_UPLOAD_MB ?>MB · Show the item clearly</span></div>
            </div>
            <input type="file" id="imgInput" name="image" accept="image/*" style="display:none" onchange="previewImgFile(event)">
          </div>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">📦 Submit Listing for Review</button>
        <div class="msg-error" id="errMsg"></div>
        <div class="msg-success" id="successMsg">
          <div class="ms-icon">🎉</div>
          <div class="ms-title">Listing Submitted!</div>
          <div class="ms-sub">Your item is now in our review queue. Once approved by our admin, it will appear in the marketplace. This usually takes under 24 hours.</div>
          <div class="ms-uid" id="productUidDisplay">ECO-RSL-XXXXX</div>
          <div class="ms-actions">
            <a href="my_listings.php" class="ms-btn filled">📊 Track Listing →</a>
            <a href="resell.php" class="ms-btn">← Browse Market</a>
          </div>
        </div>

      </form>
    </div>

    <!-- PREVIEW COLUMN -->
    <div class="preview-panel">
      <div class="preview-card">
        <div class="preview-label">Live Preview</div>
        <div class="preview-img" id="previewImgWrap"><span id="previewEmoji">📦</span></div>
        <div class="preview-body">
          <div class="preview-cat" id="previewCat">Category</div>
          <div class="preview-title" id="previewTitle">Your product name</div>
          <div class="preview-price" id="previewPrice">₹ —</div>
          <div class="preview-meta" id="previewMeta"></div>
          <div class="preview-desc" id="previewDesc" style="color:rgba(90,90,90,.4)">Description will appear here…</div>
          <div class="preview-seller" id="previewSeller">by — · Qty: —</div>
        </div>
      </div>
      <div class="tips-panel">
        <div class="tp-title">💡 Tips for Faster Approval</div>
        <div class="tip-item">Use clear, natural-light photos showing the actual item</div>
        <div class="tip-item">Include brand, model number, and specifications</div>
        <div class="tip-item">Be honest about condition — buyers appreciate transparency</div>
        <div class="tip-item">Set a fair price — check similar listings first</div>
        <div class="tip-item">Mention what's included (cables, manuals, boxes)</div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    const CAT_EMOJI = {
      Electronics: '💻',
      Furniture: '🪑',
      Clothes: '👕',
      Books: '📚',
      Fitness: '⚽',
      Kitchen: '🏠',
      Games: '🎮',
      Garden: '🌿',
      Other: '📦'
    };
    const COND_COLOR = {
      New: '#4a8c5c',
      'Like New': '#7ab88a',
      Used: '#d4a843',
      Damaged: '#d4745a'
    };

    function toast(msg, dur = 2800) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur)
    }

    function countChars(ta, id) {
      document.getElementById(id).textContent = ta.value.length + ' characters';
    }

    function updatePreview() {
      const name = document.querySelector('[name="product_name"]').value.trim() || 'Your product name';
      const cat = document.querySelector('[name="category"]').value;
      const cond = document.querySelector('[name="condition"]').value;
      const qty = document.querySelector('[name="quantity"]').value || '—';
      const price = document.querySelector('[name="price"]').value;
      const desc = document.querySelector('[name="description"]').value.trim();
      const seller = document.querySelector('[name="seller_name"]').value.trim() || '—';

      document.getElementById('previewTitle').textContent = name;
      document.getElementById('previewCat').textContent = cat || 'Category';
      document.getElementById('previewPrice').textContent = price ? '₹ ' + parseFloat(price).toLocaleString('en-IN', {
        minimumFractionDigits: 2
      }) : '₹ —';
      document.getElementById('previewDesc').textContent = desc || 'Description will appear here…';
      document.getElementById('previewDesc').style.color = desc ? '' : 'rgba(90,90,90,.4)';
      document.getElementById('previewSeller').textContent = `by ${seller} · Qty: ${qty}`;

      if (!document.querySelector('#previewImgWrap img')) {
        document.getElementById('previewEmoji').textContent = CAT_EMOJI[cat] || '📦';
      }

      let metaHtml = '';
      if (cond) {
        const c = COND_COLOR[cond] || '#999';
        metaHtml += `<span class="preview-badge" style="background:${c}22;color:${c}">${cond}</span>`;
      }
      if (cat) metaHtml += `<span class="preview-badge" style="background:rgba(26,58,42,.09);color:var(--forest)">${cat}</span>`;
      document.getElementById('previewMeta').innerHTML = metaHtml;
    }

    function previewImgFile(e) {
      const f = e.target.files[0];
      if (!f) return;
      if (f.size > <?= MAX_UPLOAD_MB ?> * 1024 * 1024) {
        toast('⚠️ Image too large (max <?= MAX_UPLOAD_MB ?>MB)');
        return;
      }
      const url = URL.createObjectURL(f);
      const wrap = document.getElementById('previewImgWrap');
      wrap.innerHTML = `<img src="${url}" alt="Preview" style="width:100%;height:100%;object-fit:cover">`;
      document.getElementById('uploadZone').innerHTML = `<img src="${url}" style="max-height:160px;object-fit:cover;border-radius:8px;margin-bottom:8px"><div class="uz-text">${f.name} — <strong style="cursor:pointer;color:var(--leaf)" onclick="document.getElementById('imgInput').click()">Change</strong></div>`;
    }
    const uz = document.getElementById('uploadZone');
    uz.addEventListener('dragover', e => {
      e.preventDefault();
      uz.style.borderColor = 'var(--leaf)'
    });
    uz.addEventListener('dragleave', () => uz.style.borderColor = '');
    uz.addEventListener('drop', e => {
      e.preventDefault();
      uz.style.borderColor = '';
      const f = e.dataTransfer.files[0];
      if (f) {
        document.getElementById('imgInput').files = e.dataTransfer.files;
        previewImgFile({
          target: {
            files: [f]
          }
        });
      }
    });

    document.getElementById('sellForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const btn = document.getElementById('submitBtn');
      const err = document.getElementById('errMsg');
      const suc = document.getElementById('successMsg');
      err.style.display = 'none';
      suc.style.display = 'none';
      btn.disabled = true;
      btn.innerHTML = '⏳ Submitting…';
      try {
        const res = await fetch('api/submit_product.php', {
          method: 'POST',
          body: new FormData(this)
        });
        const data = await res.json();
        if (data.success) {
          suc.style.display = 'block';
          document.getElementById('productUidDisplay').textContent = data.product_uid;
          suc.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
          this.reset();
          document.getElementById('uploadZone').innerHTML = '<div class="uz-icon">📷</div><div class="uz-text"><strong>Click to upload photo</strong> or drag & drop</div>';
          document.getElementById('previewImgWrap').innerHTML = '<span id="previewEmoji">📦</span>';
          updatePreview();
          toast('✅ Listing submitted for review!');
        } else {
          err.textContent = '⚠️ ' + (data.error || 'Submission failed.');
          err.style.display = 'block';
          err.scrollIntoView({
            behavior: 'smooth'
          });
        }
      } catch {
        err.textContent = '⚠️ Network error. Is XAMPP running?';
        err.style.display = 'block';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '📦 Submit Listing for Review';
      }
    });
  </script>
</body>

</html>