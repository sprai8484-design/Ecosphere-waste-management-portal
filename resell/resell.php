<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Resell Marketplace — Ecosphere</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <!-- Razorpay Checkout Script -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <style>
    /* ── TOKENS ──────────────────────────────────────────────────────── */
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
      --earth: #8b6f47;
      --clay: #c4956a;
      --terra: #d4745a;
      --gold: #d4a843;
      --ink: #1a1a18;
      --charcoal: #2c2c2c;
      --soft: #5a5a5a;
      --r: 14px;
      --rs: 8px;
      --shadow: 0 4px 20px rgba(26, 58, 42, .09);
      --shadow-h: 0 14px 44px rgba(26, 58, 42, .18);
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
      gap: 24px;
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

    .nav-cta {
      background: var(--forest);
      color: #fff;
      padding: 9px 22px;
      border-radius: 50px;
      font-size: .85rem;
      font-weight: 600;
      transition: all .2s
    }

    .nav-cta:hover {
      background: var(--moss)
    }

    /* HERO */
    .hero {
      background: linear-gradient(140deg, var(--forest) 0%, var(--moss) 55%, #3d7a52 100%);
      padding: clamp(56px, 9vh, 100px) clamp(16px, 6vw, 80px);
      position: relative;
      overflow: hidden
    }

    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
      background-size: 40px 40px
    }

    .hero-inner {
      position: relative;
      z-index: 2;
      max-width: 640px
    }

    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255, 255, 255, .11);
      border: 1px solid rgba(255, 255, 255, .2);
      color: var(--mint);
      padding: 5px 14px;
      border-radius: 50px;
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 20px;
      animation: fadeUp .6s ease both
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.2rem, 5vw, 3.8rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 16px;
      animation: fadeUp .7s .1s ease both
    }

    .hero h1 em {
      color: var(--gold);
      font-style: italic
    }

    .hero-sub {
      color: rgba(255, 255, 255, .7);
      font-size: 1rem;
      max-width: 500px;
      line-height: 1.7;
      margin-bottom: 32px;
      animation: fadeUp .7s .2s ease both
    }

    .hero-btns {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      animation: fadeUp .7s .3s ease both
    }

    .btn-gold {
      background: var(--gold);
      color: var(--forest);
      padding: 13px 28px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .9rem;
      transition: all .25s;
      display: inline-flex;
      align-items: center;
      gap: 7px
    }

    .btn-gold:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(212, 168, 67, .4)
    }

    .btn-ghost {
      background: transparent;
      color: #fff;
      padding: 13px 28px;
      border-radius: 50px;
      border: 1.5px solid rgba(255, 255, 255, .3);
      font-size: .9rem;
      font-weight: 500;
      transition: all .25s
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, .1);
      border-color: #fff
    }

    .hero-stats {
      display: flex;
      gap: 36px;
      margin-top: 44px;
      animation: fadeUp .7s .4s ease both
    }

    .h-stat span {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #fff;
      display: block
    }

    .h-stat small {
      color: rgba(255, 255, 255, .5);
      font-size: .74rem;
      letter-spacing: .06em;
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

    /* SEARCH + FILTERS */
    .search-strip {
      background: var(--parchment);
      border-bottom: 1px solid var(--sand);
      padding: 20px clamp(16px, 6vw, 80px)
    }

    .search-row {
      display: flex;
      gap: 12px;
      align-items: center;
      flex-wrap: wrap
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
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: 50px;
      padding: 11px 18px 11px 44px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s
    }

    .search-inp:focus {
      border-color: var(--leaf)
    }

    .search-inp::placeholder {
      color: rgba(90, 90, 90, .4)
    }

    .filter-sel {
      background: #fff;
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: 50px;
      padding: 11px 18px;
      font-size: .85rem;
      color: var(--charcoal);
      cursor: pointer;
      min-width: 140px
    }

    .filter-sel:focus {
      border-color: var(--leaf);
      outline: none
    }

    .btn-reset {
      background: transparent;
      color: var(--soft);
      padding: 11px 16px;
      border-radius: 50px;
      border: 1px solid var(--sand);
      font-size: .82rem;
      transition: all .2s
    }

    .btn-reset:hover {
      border-color: var(--terra);
      color: var(--terra)
    }

    /* MAIN LAYOUT */
    .main-wrap {
      padding: clamp(28px, 5vh, 56px) clamp(16px, 6vw, 80px)
    }

    /* SORT META */
    .sort-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 22px;
      flex-wrap: wrap;
      gap: 10px
    }

    .result-count {
      font-size: .88rem;
      color: var(--soft)
    }

    .result-count strong {
      color: var(--forest)
    }

    .sort-pills {
      display: flex;
      gap: 7px
    }

    .sort-pill {
      padding: 7px 14px;
      border-radius: 50px;
      font-size: .8rem;
      border: 1px solid rgba(74, 140, 92, .18);
      color: var(--soft);
      transition: all .2s;
      background: #fff
    }

    .sort-pill.active,
    .sort-pill:hover {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf)
    }

    /* PRODUCT GRID */
    #productGrid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 22px
    }

    /* PRODUCT CARD */
    .prod-card {
      background: #fff;
      border-radius: var(--r);
      overflow: hidden;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      transition: all .3s;
      animation: cardIn .4s ease both
    }

    @keyframes cardIn {
      from {
        opacity: 0;
        transform: translateY(10px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .prod-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-h)
    }

    .card-img {
      position: relative;
      aspect-ratio: 4/3;
      overflow: hidden;
      background: var(--parchment);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem
    }

    .card-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .4s
    }

    .prod-card:hover .card-img img {
      transform: scale(1.05)
    }

    .card-img-emoji {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4rem;
      transition: transform .4s
    }

    .prod-card:hover .card-img-emoji {
      transform: scale(1.06)
    }

    .card-cat-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: var(--forest);
      color: #fff;
      padding: 3px 11px;
      border-radius: 50px;
      font-size: .68rem;
      font-weight: 700;
      z-index: 2
    }

    .card-cond-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 3px 11px;
      border-radius: 50px;
      font-size: .68rem;
      font-weight: 700;
      z-index: 2
    }

    .cond-New {
      background: rgba(74, 140, 92, .9);
      color: #fff
    }

    .cond-Like-New {
      background: rgba(122, 184, 138, .9);
      color: var(--forest)
    }

    .cond-Used {
      background: rgba(212, 168, 67, .9);
      color: var(--forest)
    }

    .cond-Damaged {
      background: rgba(212, 116, 90, .9);
      color: #fff
    }

    .card-body {
      padding: 16px 18px 14px
    }

    .card-uid {
      font-family: 'DM Mono', monospace;
      font-size: .65rem;
      color: var(--soft);
      letter-spacing: .06em;
      margin-bottom: 5px
    }

    .card-title {
      font-family: 'Playfair Display', serif;
      font-size: 1rem;
      color: var(--forest);
      line-height: 1.3;
      margin-bottom: 6px
    }

    .card-excerpt {
      font-size: .82rem;
      color: var(--soft);
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      margin-bottom: 12px
    }

    .card-price {
      font-family: 'Playfair Display', serif;
      font-size: 1.4rem;
      color: var(--forest);
      font-weight: 700;
      margin-bottom: 4px
    }

    .card-seller {
      font-size: .75rem;
      color: var(--soft);
      margin-bottom: 12px
    }

    .card-footer {
      display: flex;
      gap: 8px;
      padding-top: 12px;
      border-top: 1px solid rgba(74, 140, 92, .07)
    }

    .btn-details {
      flex: 1;
      background: var(--cream);
      color: var(--forest);
      padding: 9px 14px;
      border-radius: var(--rs);
      font-size: .82rem;
      font-weight: 600;
      transition: all .2s;
      text-align: center
    }

    .btn-details:hover {
      background: var(--parchment)
    }

    .btn-buy {
      flex: 1;
      background: var(--forest);
      color: #fff;
      padding: 9px 14px;
      border-radius: var(--rs);
      font-size: .82rem;
      font-weight: 700;
      transition: all .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px
    }

    .btn-buy:hover {
      background: var(--moss);
      transform: translateY(-1px)
    }

    .sold-banner {
      position: absolute;
      inset: 0;
      background: rgba(26, 58, 42, .7);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 5
    }

    .sold-text {
      background: var(--terra);
      color: #fff;
      padding: 6px 18px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .85rem;
      letter-spacing: .05em
    }

    /* EMPTY / LOADING */
    .state-block {
      text-align: center;
      padding: 64px 20px;
      color: var(--soft);
      grid-column: 1/-1
    }

    .state-icon {
      font-size: 3.5rem;
      margin-bottom: 14px
    }

    .state-msg {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--forest);
      margin-bottom: 6px
    }

    .state-sub {
      font-size: .86rem;
      line-height: 1.6
    }

    .spinner {
      width: 38px;
      height: 38px;
      border: 3px solid var(--mint);
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

    /* PAGINATION */
    #pagination {
      display: flex;
      gap: 7px;
      margin-top: 36px;
      flex-wrap: wrap
    }

    .pg-btn {
      min-width: 38px;
      height: 38px;
      padding: 0 12px;
      border-radius: var(--rs);
      font-size: .85rem;
      font-weight: 600;
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

    /* ══ MODALS ══ */
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(26, 58, 42, .65);
      backdrop-filter: blur(10px);
      z-index: 300;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 16px;
      opacity: 0;
      pointer-events: none;
      transition: opacity .25s
    }

    .overlay.open {
      opacity: 1;
      pointer-events: all
    }

    .modal {
      background: #fff;
      border-radius: 20px;
      width: 100%;
      overflow-y: auto;
      transform: scale(.96) translateY(16px);
      transition: transform .25s;
      max-height: 92vh
    }

    .overlay.open .modal {
      transform: scale(1) translateY(0)
    }

    .modal-close-btn {
      position: absolute;
      top: 14px;
      right: 14px;
      width: 32px;
      height: 32px;
      background: rgba(255, 255, 255, .18);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .9rem;
      color: #fff;
      cursor: pointer;
      transition: background .2s
    }

    .modal-close-btn:hover {
      background: rgba(255, 255, 255, .3)
    }

    /* PRODUCT DETAIL MODAL */
    .detail-modal {
      max-width: 760px
    }

    .detail-hero {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      padding: 32px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 200px;
      font-size: 6rem;
      overflow: hidden
    }

    .detail-hero img {
      width: 100%;
      max-height: 280px;
      object-fit: cover;
      border-radius: 12px
    }

    .detail-hero-placeholder {
      font-size: 5rem
    }

    .detail-body {
      padding: 28px 32px
    }

    .detail-uid {
      font-family: 'DM Mono', monospace;
      font-size: .68rem;
      color: var(--soft);
      letter-spacing: .1em;
      margin-bottom: 8px
    }

    .detail-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.6rem;
      color: var(--forest);
      margin-bottom: 10px;
      line-height: 1.2
    }

    .detail-meta {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 14px
    }

    .meta-badge {
      padding: 4px 12px;
      border-radius: 50px;
      font-size: .74rem;
      font-weight: 700
    }

    .detail-price {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      color: var(--forest);
      margin-bottom: 4px
    }

    .detail-qty {
      font-size: .82rem;
      color: var(--soft);
      margin-bottom: 16px
    }

    .detail-desc-title {
      font-size: .74rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: var(--soft);
      margin-bottom: 6px
    }

    .detail-desc {
      font-size: .9rem;
      color: var(--soft);
      line-height: 1.7;
      margin-bottom: 20px
    }

    .detail-seller {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      background: var(--cream);
      border-radius: var(--rs);
      margin-bottom: 20px
    }

    .seller-av {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--moss);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .9rem;
      flex-shrink: 0
    }

    .seller-info small {
      display: block;
      font-size: .72rem;
      color: var(--soft);
      text-transform: uppercase;
      letter-spacing: .06em
    }

    .seller-info strong {
      font-size: .88rem;
      color: var(--forest)
    }

    .btn-buy-now {
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

    .btn-buy-now:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(26, 58, 42, .28)
    }

    /* ══ RAZORPAY QTY MODAL ══ */
    .qty-modal {
      max-width: 420px
    }

    .qty-header {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      padding: 24px 28px;
      position: relative
    }

    .qty-header-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
      color: #fff
    }

    .qty-header-sub {
      color: rgba(255, 255, 255, .65);
      font-size: .82rem;
      margin-top: 2px
    }

    .qty-body {
      padding: 24px 28px
    }

    .qty-summary {
      background: var(--cream);
      border-radius: var(--rs);
      padding: 14px 16px;
      margin-bottom: 22px;
      border: 1px solid var(--sand)
    }

    .qs-row {
      display: flex;
      justify-content: space-between;
      font-size: .85rem;
      margin-bottom: 5px;
      color: var(--soft)
    }

    .qs-row:last-child {
      margin-bottom: 0;
      padding-top: 8px;
      border-top: 1px solid var(--sand);
      font-weight: 700;
      color: var(--forest);
      font-size: .95rem
    }

    .qty-form {
      display: flex;
      flex-direction: column;
      gap: 14px
    }

    .qf-group {
      display: flex;
      flex-direction: column;
      gap: 6px
    }

    .qf-label {
      font-size: .72rem;
      font-weight: 700;
      color: var(--forest);
      text-transform: uppercase;
      letter-spacing: .06em
    }

    .qf-inp {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .16);
      border-radius: var(--rs);
      padding: 11px 13px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s
    }

    .qf-inp:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1)
    }

    .qf-inp::placeholder {
      color: rgba(90, 90, 90, .38)
    }

    .btn-rzp {
      width: 100%;
      background: linear-gradient(135deg, #528FF0, #3b6fd4);
      color: #fff;
      padding: 15px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .97rem;
      transition: all .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 4px;
      border: none;
      cursor: pointer
    }

    .btn-rzp:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(82, 143, 240, .35)
    }

    .btn-rzp:disabled {
      opacity: .6;
      cursor: not-allowed;
      transform: none;
      box-shadow: none
    }

    .rzp-badge {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      font-size: .74rem;
      color: var(--soft);
      margin-top: 10px
    }

    .rzp-badge svg {
      opacity: .5
    }

    .qty-error {
      display: none;
      background: rgba(212, 116, 90, .08);
      border: 1px solid rgba(212, 116, 90, .22);
      border-radius: var(--rs);
      padding: 11px 14px;
      color: var(--terra);
      font-size: .86rem;
      margin-top: 10px
    }

    /* RECEIPT MODAL */
    .receipt-modal {
      max-width: 580px
    }

    .receipt-inner {
      padding: 32px;
      background: #fff
    }

    .receipt-header {
      text-align: center;
      padding-bottom: 24px;
      border-bottom: 2px dashed var(--sand);
      margin-bottom: 24px
    }

    .receipt-logo {
      width: 48px;
      height: 48px;
      background: var(--forest);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      margin: 0 auto 10px
    }

    .receipt-brand {
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
      color: var(--forest);
      margin-bottom: 2px
    }

    .receipt-sub {
      font-size: .74rem;
      color: var(--soft);
      text-transform: uppercase;
      letter-spacing: .08em;
      font-family: 'DM Mono', monospace
    }

    .receipt-success {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(74, 140, 92, .07);
      border: 1px solid rgba(74, 140, 92, .2);
      border-radius: var(--rs);
      padding: 14px 16px;
      margin-bottom: 24px
    }

    .receipt-success-icon {
      font-size: 1.8rem
    }

    .receipt-success-title {
      font-weight: 700;
      color: var(--forest);
      font-size: .92rem
    }

    .receipt-success-sub {
      font-size: .78rem;
      color: var(--soft);
      margin-top: 2px
    }

    .receipt-section {
      margin-bottom: 20px
    }

    .receipt-section-label {
      font-size: .68rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .1em;
      color: var(--soft);
      margin-bottom: 10px;
      font-family: 'DM Mono', monospace
    }

    .receipt-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      border-bottom: 1px solid rgba(74, 140, 92, .06);
      font-size: .85rem
    }

    .receipt-row:last-child {
      border-bottom: none
    }

    .receipt-row .rl {
      color: var(--soft)
    }

    .receipt-row .rv {
      color: var(--charcoal);
      font-weight: 600;
      text-align: right;
      max-width: 60%
    }

    .receipt-total-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-top: 2px solid var(--forest);
      margin-top: 8px
    }

    .receipt-total-label {
      font-weight: 700;
      color: var(--forest)
    }

    .receipt-total-val {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--forest);
      font-weight: 700
    }

    .receipt-tid {
      text-align: center;
      padding: 14px;
      background: var(--cream);
      border-radius: var(--rs);
      margin-bottom: 20px
    }

    .receipt-tid-label {
      font-size: .68rem;
      color: var(--soft);
      text-transform: uppercase;
      letter-spacing: .08em;
      font-family: 'DM Mono', monospace
    }

    .receipt-tid-val {
      font-family: 'DM Mono', monospace;
      font-size: .92rem;
      font-weight: 700;
      color: var(--forest);
      margin-top: 3px;
      letter-spacing: .06em
    }

    .receipt-actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap
    }

    .btn-print {
      flex: 1;
      background: var(--forest);
      color: #fff;
      padding: 12px 20px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .87rem;
      transition: all .2s;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px
    }

    .btn-print:hover {
      background: var(--moss)
    }

    .btn-continue {
      flex: 1;
      background: var(--cream);
      color: var(--forest);
      padding: 12px 20px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .87rem;
      transition: all .2s;
      border: 1.5px solid var(--sand);
      text-align: center
    }

    .btn-continue:hover {
      background: var(--parchment)
    }

    /* TOAST */
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

    /* REVEAL */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity .55s, transform .55s
    }

    .reveal.visible {
      opacity: 1;
      transform: none
    }

    /* FOOTER */
    footer {
      background: var(--forest);
      color: rgba(255, 255, 255, .55);
      padding: 44px clamp(16px, 6vw, 80px) 28px;
      margin-top: 64px
    }

    .ft-grid {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 40px;
      margin-bottom: 32px
    }

    .ft-logo {
      font-family: 'Playfair Display', serif;
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 10px
    }

    .ft-tagline {
      font-size: .82rem;
      line-height: 1.65
    }

    .ft-col h4 {
      color: rgba(255, 255, 255, .8);
      font-size: .74rem;
      text-transform: uppercase;
      letter-spacing: .09em;
      margin-bottom: 14px
    }

    .ft-col a {
      display: block;
      font-size: .82rem;
      margin-bottom: 7px;
      transition: color .2s
    }

    .ft-col a:hover {
      color: var(--mint)
    }

    .ft-bottom {
      border-top: 1px solid rgba(255, 255, 255, .07);
      padding-top: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      font-size: .78rem
    }

    /* RESPONSIVE */
    @media(max-width:768px) {
      .nav-links {
        display: none
      }

      .hero-stats {
        display: none
      }

      .search-row {
        flex-direction: column
      }

      .ft-grid {
        grid-template-columns: 1fr
      }

      .detail-body {
        padding: 20px
      }

      .qty-body {
        padding: 18px 20px
      }
    }

    @media(max-width:480px) {
      .receipt-actions {
        flex-direction: column
      }
    }
  </style>
</head>

<body>

  <!-- NAV -->
  <nav>
    <a href="../index.php" class="nav-logo">
      <div class="nav-dot">🌿</div>Ecosphere
    </a>
    <ul class="nav-links">
      <li><a href="../index.php">Home</a></li>
      <li><a href="resell.php" class="active">Marketplace</a></li>
      <li><a href="sell.php">Sell Item</a></li>
      <li><a href="my_listings.php">My Listings</a></li>
    </ul>
    <a href="sell.php" class="nav-cta">+ List Item</a>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-inner">
      <div class="hero-tag">♻️ Eco Resell Marketplace</div>
      <h1>Give Items a<br><em>Second Life</em></h1>
      <p class="hero-sub">Buy and sell pre-loved items. Every purchase keeps waste out of landfill and puts money in someone's pocket. All listings are verified by our team.</p>
      <div class="hero-btns">
        <a href="sell.php" class="btn-gold">✦ List Your Item</a>
        <a href="#marketplace" class="btn-ghost">Browse Listings</a>
      </div>
      <div class="hero-stats">
        <div class="h-stat"><span id="heroCount">—</span><small>Active Listings</small></div>
        <div class="h-stat"><span>🔒</span><small>Secure Payment</small></div>
        <div class="h-stat"><span>✅</span><small>Verified Items</small></div>
      </div>
    </div>
  </section>

  <!-- SEARCH + FILTERS -->
  <div class="search-strip" id="marketplace">
    <div class="search-row">
      <div class="search-wrap">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
        </svg>
        <input class="search-inp" id="searchInput" type="text" placeholder="Search products, sellers…" oninput="debounce()">
      </div>
      <select class="filter-sel" id="catFilter" onchange="loadProducts(1)">
        <option value="">All Categories</option>
        <?php foreach (categories() as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
      </select>
      <select class="filter-sel" id="condFilter" onchange="loadProducts(1)">
        <option value="">Any Condition</option>
        <?php foreach (conditions() as $c): ?><option value="<?= $c ?>"><?= $c ?></option><?php endforeach; ?>
      </select>
      <select class="filter-sel" id="sortSel" onchange="loadProducts(1)">
        <option value="newest">Newest First</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
      </select>
      <button class="btn-reset" onclick="resetFilters()">✕ Clear</button>
    </div>
  </div>

  <!-- PRODUCTS -->
  <div class="main-wrap">
    <div class="sort-meta reveal">
      <span class="result-count"><strong id="resultCount">—</strong> items available</span>
      <div class="sort-pills">
        <button class="sort-pill active" onclick="setSort('newest',this)">🕐 Newest</button>
        <button class="sort-pill" onclick="setSort('price_asc',this)">↑ Price</button>
        <button class="sort-pill" onclick="setSort('price_desc',this)">↓ Price</button>
      </div>
    </div>
    <div id="productGrid">
      <div class="state-block">
        <div class="spinner"></div>
        <div class="state-msg">Loading marketplace…</div>
      </div>
    </div>
    <div id="pagination"></div>
  </div>
 
  <!-- FOOTER -->
  <footer>
    <div class="ft-grid">
      <div>
        <div class="ft-logo">🌿 Ecosphere Resell</div>
        <p class="ft-tagline">Buy and sell pre-loved items. Every transaction diverts waste from landfill and supports a circular economy.</p>
      </div>
      <div class="ft-col">
        <h4>Marketplace</h4><a href="resell.php">Browse Items</a><a href="sell.php">Sell an Item</a><a href="my_listings.php">My Listings</a>
      </div>
      <div class="ft-col">
        <h4>Platform</h4><a href="../index.php">Ecosphere Home</a><a href="../recycle/recycle.php">Recycle</a><a href="../reuse/reuse.php">Reuse Hub</a>
      </div>
    </div>
    <div class="ft-bottom">
      <div>© <?= date('Y') ?> Ecosphere. Secure payments powered by Razorpay.</div>
      <div>🔒 SSL Encrypted</div>
    </div>
  </footer>

  <!-- ══ PRODUCT DETAIL MODAL ══ -->
  <div class="overlay" id="detailOverlay" onclick="closeOverlay('detailOverlay',event)">
    <div class="modal detail-modal">
      <div class="detail-hero" id="detailHero">
        <button class="modal-close-btn" onclick="closeOverlayDirect('detailOverlay')">✕</button>
      </div>
      <div class="detail-body" id="detailBody"></div>
    </div>
  </div>

  <!-- ══ RAZORPAY QTY + BUYER MODAL ══ -->
  <div class="overlay" id="qtyOverlay" onclick="closeOverlay('qtyOverlay',event)">
    <div class="modal qty-modal">
      <div class="qty-header">
        <button class="modal-close-btn" onclick="closeOverlayDirect('qtyOverlay')">✕</button>
        <div class="qty-header-title">🛒 Complete Purchase</div>
        <div class="qty-header-sub">Powered by Razorpay — Secure Checkout</div>
      </div>
      <div class="qty-body">
        <!-- Order Summary -->
        <div class="qty-summary" id="qtySummary"></div>

        <!-- Buyer Info + Qty Form -->
        <div class="qty-form">
          <div class="qf-group">
            <label class="qf-label">Your Name *</label>
            <input class="qf-inp" id="buyerName" type="text" placeholder="Full name">
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            <div class="qf-group">
              <label class="qf-label">Email *</label>
              <input class="qf-inp" id="buyerEmail" type="email" placeholder="you@example.com">
            </div>
            <div class="qf-group">
              <label class="qf-label">Phone</label>
              <input class="qf-inp" id="buyerPhone" type="tel" placeholder="+91 98765 43210">
            </div>
          </div>
          <div class="qf-group">
            <label class="qf-label">Quantity</label>
            <input class="qf-inp" id="buyQty" type="number" min="1" value="1" oninput="updateQtySummary()">
          </div>
        </div>

        <div class="qty-error" id="qtyError"></div>

        <button class="btn-rzp" id="rzpBtn" onclick="launchRazorpay()">
          <svg width="20" height="20" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 0C8.954 0 0 8.954 0 20s8.954 20 20 20 20-8.954 20-20S31.046 0 20 0z" fill="#fff" fill-opacity=".15" />
            <path d="M14 28l4-16h4l2 8 4-8h4L24 28h-4l-2-8-4 8h-4z" fill="#fff" />
          </svg>
          Pay with Razorpay
        </button>

        <div class="rzp-badge">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 4l5 2.18V11c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5z" />
          </svg>
          256-bit SSL · Secured by Razorpay
        </div>
      </div>
    </div>
  </div>

  <!-- ══ RECEIPT MODAL ══ -->
  <div class="overlay" id="receiptOverlay">
    <div class="modal receipt-modal" id="receiptModal">
      <div class="receipt-inner" id="receiptContent">
        <!-- Filled by JS -->
      </div>
    </div>
  </div>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    // ── CONFIG ────────────────────────────────────────────────────────
    const CURRENCY = '<?= CURRENCY ?>';
    const RZP_KEY = 'rzp_test_jYGvkH814Lxjoo'; // Razorpay Test Key

    let currentPage = 1;
    let debTimer = null;
    let currentProduct = null;

    // ── TOAST ─────────────────────────────────────────────────────────
    function toast(msg, dur = 3000) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur);
    }

    // ── HELPERS ───────────────────────────────────────────────────────
    function esc(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function fmtDate(d) {
      return new Date(d).toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
      });
    }

    function fmtDateTime(d) {
      return new Date(d).toLocaleString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function fmtPrice(n) {
      return CURRENCY + parseFloat(n).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

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
    const CAT_GRAD = {
      Electronics: '#1a2a3a,#2d4a6b',
      Furniture: '#3a2a1a,#6b4a2d',
      Clothes: '#3a1a2a,#6b2d4a',
      Books: '#1a3a2a,#2d6b3d',
      'Fitness': '#1a2a3a,#2d4a7a',
      'Kitchen': '#3a2d1a,#6b5a2d',
      'Games': '#3a1a3a,#6b2d6b',
      Garden: '#1a3a2a,#2d5a3d',
      Other: '#2a2a2a,#4a4a4a'
    };
    const AV_COLORS = ['#4a8c5c', '#d4745a', '#d4a843', '#7ab88a', '#8b6f47', '#2d5a3d', '#c4956a'];

    // ── LOAD PRODUCTS ─────────────────────────────────────────────────
    async function loadProducts(page = 1) {
      currentPage = page;
      const params = new URLSearchParams({
        search: document.getElementById('searchInput').value.trim(),
        category: document.getElementById('catFilter').value,
        condition: document.getElementById('condFilter').value,
        sort: document.getElementById('sortSel').value,
        page,
        limit: 9,
      });
      document.getElementById('productGrid').innerHTML = '<div class="state-block"><div class="spinner"></div><div class="state-msg">Loading…</div></div>';

      try {
        const res = await fetch('api/fetch_products.php?' + params);
        const data = await res.json();
        if (data.error) {
          showEmpty(data.error);
          return;
        }
        document.getElementById('resultCount').textContent = data.total;
        document.getElementById('heroCount').textContent = data.total + '+';
        renderGrid(data.products);
        renderPagination(data.page, data.pages);
      } catch (e) {
        showEmpty('Could not load products. Is XAMPP running?');
      }
    }

    function renderGrid(products) {
      const grid = document.getElementById('productGrid');
      if (!products.length) {
        showEmpty('No listings found. Try different filters.');
        return;
      }
      grid.innerHTML = products.map((p, i) => {
        const emoji = CAT_EMOJI[p.category] || '📦';
        const grad = CAT_GRAD[p.category] || '2d5a3d,4a8c5c';
        const condClass = 'cond-' + p.condition.replace(' ', '-');
        const imgHtml = p.image ?
          `<img src="${esc(p.image)}" alt="${esc(p.product_name)}" loading="lazy">` :
          `<div class="card-img-emoji" style="background:linear-gradient(135deg,#${grad})">${emoji}</div>`;
        return `
    <div class="prod-card" style="animation-delay:${i*0.04}s">
      <div class="card-img">
        ${imgHtml}
        <span class="card-cat-badge">${esc(p.category)}</span>
        <span class="card-cond-badge ${condClass}">${esc(p.condition)}</span>
      </div>
      <div class="card-body">
        <div class="card-uid">${esc(p.product_uid)}</div>
        <div class="card-title">${esc(p.product_name)}</div>
        <div class="card-excerpt">${esc(p.excerpt)}</div>
        <div class="card-price">${esc(p.price_fmt)}</div>
        <div class="card-seller">by ${esc(p.seller_name)} · ${esc(p.condition)} · Qty: ${p.quantity}</div>
        <div class="card-footer">
          <button class="btn-details" onclick="openDetail(${p.id})">View Details</button>
          <button class="btn-buy" onclick="openPayment(${p.id})">🛒 Buy Now</button>
        </div>
      </div>
    </div>`;
      }).join('');
    }

    function showEmpty(msg) {
      document.getElementById('productGrid').innerHTML = `<div class="state-block"><div class="state-icon">🔍</div><div class="state-msg">No items found</div><p class="state-sub">${msg}<br><br><a href="sell.php" style="color:var(--leaf);font-weight:700">+ List the first item →</a></p></div>`;
    }

    // ── PAGINATION ────────────────────────────────────────────────────
    function renderPagination(cur, total) {
      const pg = document.getElementById('pagination');
      if (total <= 1) {
        pg.innerHTML = '';
        return;
      }
      let h = `<button class="pg-btn" onclick="loadProducts(${cur-1})" ${cur===1?'disabled':''}>‹ Prev</button>`;
      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || Math.abs(i - cur) <= 1) h += `<button class="pg-btn ${i===cur?'active':''}" onclick="loadProducts(${i})">${i}</button>`;
        else if (Math.abs(i - cur) === 2) h += `<button class="pg-btn" disabled>…</button>`;
      }
      h += `<button class="pg-btn" onclick="loadProducts(${cur+1})" ${cur===total?'disabled':''}>Next ›</button>`;
      pg.innerHTML = h;
    }

    // ── FILTER HELPERS ────────────────────────────────────────────────
    function debounce() {
      clearTimeout(debTimer);
      debTimer = setTimeout(() => loadProducts(1), 320);
    }

    function setSort(sort, btn) {
      document.getElementById('sortSel').value = sort;
      document.querySelectorAll('.sort-pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      loadProducts(1);
    }

    function resetFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('catFilter').value = '';
      document.getElementById('condFilter').value = '';
      document.getElementById('sortSel').value = 'newest';
      document.querySelectorAll('.sort-pill').forEach((p, i) => p.classList.toggle('active', i === 0));
      loadProducts(1);
    }

    // ── OVERLAYS ──────────────────────────────────────────────────────
    function openOverlay(id) {
      document.getElementById(id).classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeOverlayDirect(id) {
      document.getElementById(id).classList.remove('open');
      document.body.style.overflow = '';
    }

    function closeOverlay(id, e) {
      if (e.target === document.getElementById(id)) closeOverlayDirect(id);
    }

    // ── PRODUCT DETAIL MODAL ──────────────────────────────────────────
    async function openDetail(id) {
      document.getElementById('detailHero').innerHTML = `<button class="modal-close-btn" onclick="closeOverlayDirect('detailOverlay')">✕</button><div class="spinner" style="width:32px;height:32px;border-color:var(--mint);border-top-color:#fff"></div>`;
      document.getElementById('detailBody').innerHTML = '';
      openOverlay('detailOverlay');

      try {
        const res = await fetch('api/fetch_single_product.php?id=' + id);
        const p = await res.json();
        if (p.error) {
          document.getElementById('detailBody').innerHTML = `<p style="padding:20px;color:var(--terra)">${esc(p.error)}</p>`;
          return;
        }
        currentProduct = p;
        const emoji = CAT_EMOJI[p.category] || '📦';
        const grad = CAT_GRAD[p.category] || '2d5a3d,4a8c5c';
        const condClass = 'cond-' + p.condition.replace(' ', '-');
        const avIdx = p.id % AV_COLORS.length;

        document.getElementById('detailHero').innerHTML = `
      <button class="modal-close-btn" onclick="closeOverlayDirect('detailOverlay')">✕</button>
      ${p.image ? `<img src="${esc(p.image)}" alt="${esc(p.product_name)}" style="width:100%;max-height:280px;object-fit:cover;border-radius:12px">` : `<div class="detail-hero-placeholder" style="font-size:6rem">${emoji}</div>`}`;

        document.getElementById('detailBody').innerHTML = `
      <div class="detail-uid">${esc(p.product_uid)}</div>
      <div class="detail-title">${esc(p.product_name)}</div>
      <div class="detail-meta">
        <span class="meta-badge" style="background:rgba(26,58,42,.1);color:var(--forest)">${esc(p.category)}</span>
        <span class="meta-badge ${condClass}">${esc(p.condition)}</span>
      </div>
      <div class="detail-price">${esc(p.price_fmt)}</div>
      <div class="detail-qty">📦 ${p.quantity} unit${p.quantity>1?'s':''} available</div>
      <div class="detail-desc-title">Description</div>
      <div class="detail-desc">${esc(p.description)}</div>
      <div class="detail-seller">
        <div class="seller-av" style="background:${AV_COLORS[avIdx]}">${esc(p.seller_name[0])}</div>
        <div class="seller-info"><small>Seller</small><strong>${esc(p.seller_name)}</strong></div>
        <div style="margin-left:auto;font-size:.74rem;color:var(--soft)">Listed ${fmtDate(p.created_at)}</div>
      </div>
      <button class="btn-buy-now" onclick="closeOverlayDirect('detailOverlay');openPayment(${p.id})">🛒 Buy Now — ${esc(p.price_fmt)}</button>
    `;
      } catch (e) {
        document.getElementById('detailBody').innerHTML = '<p style="padding:20px;color:var(--terra)">Failed to load product.</p>';
      }
    }

    // ── PAYMENT MODAL (Qty + Buyer Info) ─────────────────────────────
    async function openPayment(id) {
      if (!currentProduct || currentProduct.id !== id) {
        try {
          const res = await fetch('api/fetch_single_product.php?id=' + id);
          currentProduct = await res.json();
        } catch {
          toast('⚠️ Could not load product');
          return;
        }
      }
      if (currentProduct.error) {
        toast('⚠️ ' + currentProduct.error);
        return;
      }

      // Reset form
      document.getElementById('buyQty').value = 1;
      document.getElementById('buyQty').max = currentProduct.quantity;
      document.getElementById('buyerName').value = '';
      document.getElementById('buyerEmail').value = '';
      document.getElementById('buyerPhone').value = '';
      document.getElementById('qtyError').style.display = 'none';
      updateQtySummary();
      openOverlay('qtyOverlay');
    }

    function updateQtySummary() {
      if (!currentProduct) return;
      const qty = Math.max(1, parseInt(document.getElementById('buyQty').value) || 1);
      const total = (parseFloat(currentProduct.price) * qty).toFixed(2);
      document.getElementById('qtySummary').innerHTML = `
    <div class="qs-row"><span>Item</span><span>${esc(currentProduct.product_name)}</span></div>
    <div class="qs-row"><span>Unit Price</span><span>${fmtPrice(currentProduct.price)}</span></div>
    <div class="qs-row"><span>Quantity</span><span>${qty}</span></div>
    <div class="qs-row"><span>Total</span><span>${fmtPrice(total)}</span></div>`;
    }

    function showQtyError(msg) {
      const e = document.getElementById('qtyError');
      e.textContent = '⚠️ ' + msg;
      e.style.display = 'block';
    }

    // ── RAZORPAY LAUNCH ───────────────────────────────────────────────
    async function launchRazorpay() {
      const errEl = document.getElementById('qtyError');
      errEl.style.display = 'none';

      const qty = parseInt(document.getElementById('buyQty').value) || 1;
      const buyerName = document.getElementById('buyerName').value.trim();
      const buyerEmail = document.getElementById('buyerEmail').value.trim();
      const buyerPhone = document.getElementById('buyerPhone').value.trim();

      // Validate
      if (!buyerName) {
        showQtyError('Please enter your name.');
        return;
      }
      if (!buyerEmail || !/\S+@\S+\.\S+/.test(buyerEmail)) {
        showQtyError('Please enter a valid email.');
        return;
      }
      if (qty < 1 || qty > currentProduct.quantity) {
        showQtyError(`Quantity must be between 1 and ${currentProduct.quantity}.`);
        return;
      }

      const totalINR = parseFloat((parseFloat(currentProduct.price) * qty).toFixed(2));
      const amountPaise = Math.round(totalINR * 100); // Razorpay uses paise (smallest currency unit)

      // ── Razorpay Options ─────────────────────────────────────────
      const options = {
        key: RZP_KEY,
        amount: amountPaise, // in paise
        currency: 'INR',
        name: 'Ecosphere Resell',
        description: `${currentProduct.product_name} (Qty: ${qty})`,
        image: 'https://i.imgur.com/3g7nmJC.png', // Replace with your logo URL
        prefill: {
          name: buyerName,
          email: buyerEmail,
          contact: buyerPhone || '',
        },
        notes: {
          product_id: currentProduct.id,
          product_name: currentProduct.product_name,
          product_uid: currentProduct.product_uid,
          quantity: qty,
          seller_name: currentProduct.seller_name,
        },
        theme: {
          color: '#1a3a2a',
        },
        modal: {
          ondismiss: function() {
            toast('Payment cancelled.');
          }
        },
        handler: async function(response) {
          // Payment successful — verify & record on backend
          await handlePaymentSuccess(response, {
            product_id: currentProduct.id,
            buyer_name: buyerName,
            buyer_email: buyerEmail,
            buyer_phone: buyerPhone,
            quantity: qty,
            total_price: totalINR,
          });
        }
      };

      // Close the qty modal and open Razorpay checkout
      closeOverlayDirect('qtyOverlay');

      const rzp = new Razorpay(options);
      rzp.on('payment.failed', function(response) {
        toast('❌ Payment failed: ' + (response.error.description || 'Unknown error'));
        openOverlay('qtyOverlay'); // re-open so user can retry
      });
      rzp.open();
    }

    // ── HANDLE PAYMENT SUCCESS ────────────────────────────────────────
    async function handlePaymentSuccess(rzpResponse, orderData) {
      try {
        const payload = {
          razorpay_payment_id: rzpResponse.razorpay_payment_id,
          razorpay_order_id: rzpResponse.razorpay_order_id || '',
          razorpay_signature: rzpResponse.razorpay_signature || '',
          product_id: orderData.product_id,
          buyer_name: orderData.buyer_name,
          buyer_email: orderData.buyer_email,
          buyer_phone: orderData.buyer_phone,
          quantity: orderData.quantity,
          total_price: orderData.total_price,
          payment_method: 'Razorpay',
        };

        const res = await fetch('api/process_payment.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (data.success) {
          showReceipt(data, rzpResponse.razorpay_payment_id);
          loadProducts(currentPage);
        } else {
          toast('⚠️ Payment recorded but order save failed: ' + (data.error || 'Unknown error'));
        }
      } catch (e) {
        // Payment went through on Razorpay — still show success, log backend error
        toast('✅ Payment received! Reference: ' + rzpResponse.razorpay_payment_id);
        console.error('Backend record error:', e);
        loadProducts(currentPage);
      }
    }

    // ── RECEIPT ───────────────────────────────────────────────────────
    function showReceipt(txn, rzpPaymentId) {
      const paymentRef = rzpPaymentId || txn.transaction_uid || '—';

      document.getElementById('receiptContent').innerHTML = `
    <div class="receipt-header">
      <div class="receipt-logo">🌿</div>
      <div class="receipt-brand">Ecosphere Resell</div>
      <div class="receipt-sub">Payment Receipt</div>
    </div>
    <div class="receipt-success">
      <div class="receipt-success-icon">✅</div>
      <div>
        <div class="receipt-success-title">Payment Successful!</div>
        <div class="receipt-success-sub">Your Razorpay payment has been processed securely.</div>
      </div>
    </div>
    <div class="receipt-tid">
      <div class="receipt-tid-label">Razorpay Payment ID</div>
      <div class="receipt-tid-val">${esc(paymentRef)}</div>
    </div>
    <div class="receipt-section">
      <div class="receipt-section-label">Product Details</div>
      <div class="receipt-row"><span class="rl">Product Name</span><span class="rv">${esc(txn.product_name || currentProduct?.product_name || '—')}</span></div>
      <div class="receipt-row"><span class="rl">Product ID</span><span class="rv">${esc(txn.product_uid || currentProduct?.product_uid || '—')}</span></div>
      <div class="receipt-row"><span class="rl">Condition</span><span class="rv">${esc(currentProduct?.condition || '—')}</span></div>
      <div class="receipt-row"><span class="rl">Seller</span><span class="rv">${esc(txn.seller_name || currentProduct?.seller_name || '—')}</span></div>
    </div>
    <div class="receipt-section">
      <div class="receipt-section-label">Buyer Details</div>
      <div class="receipt-row"><span class="rl">Name</span><span class="rv">${esc(txn.buyer_name || document.getElementById('buyerName')?.value || '—')}</span></div>
      <div class="receipt-row"><span class="rl">Email</span><span class="rv">${esc(txn.buyer_email || document.getElementById('buyerEmail')?.value || '—')}</span></div>
    </div>
    <div class="receipt-section">
      <div class="receipt-section-label">Payment Summary</div>
      <div class="receipt-row"><span class="rl">Quantity</span><span class="rv">${txn.quantity || '—'}</span></div>
      <div class="receipt-row"><span class="rl">Unit Price</span><span class="rv">${fmtPrice(txn.unit_price || currentProduct?.price || 0)}</span></div>
      <div class="receipt-row"><span class="rl">Payment Method</span><span class="rv">Razorpay</span></div>
      <div class="receipt-row"><span class="rl">Date & Time</span><span class="rv">${fmtDateTime(txn.transaction_date || new Date().toISOString())}</span></div>
      <div class="receipt-total-row">
        <span class="receipt-total-label">Total Paid</span>
        <span class="receipt-total-val">${fmtPrice(txn.total_price || 0)}</span>
      </div>
    </div>
    <div style="text-align:center;font-size:.76rem;color:var(--soft);margin-bottom:20px;line-height:1.6">
      Thank you for choosing Ecosphere Resell!<br>
      Every purchase supports the circular economy. 🌍
    </div>
    <div class="receipt-actions">
      <button class="btn-print" onclick="printReceipt()">🖨️ Print Receipt</button>
      <button class="btn-continue" onclick="closeOverlayDirect('receiptOverlay')">Continue Shopping</button>
    </div>`;

      openOverlay('receiptOverlay');
      toast('🎉 Payment successful! Check your receipt.');
    }

    function printReceipt() {
      const content = document.getElementById('receiptContent').innerHTML;
      const win = window.open('', '', 'width=700,height=900');
      win.document.write(`<!DOCTYPE html><html><head><title>Ecosphere Receipt</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@400;600&family=DM+Mono:wght@400&display=swap" rel="stylesheet">
    <style>
      *{box-sizing:border-box;margin:0;padding:0}
      body{font-family:'DM Sans',sans-serif;background:#fff;color:#1a1a18;padding:32px;max-width:480px;margin:0 auto}
      .receipt-header{text-align:center;padding-bottom:20px;border-bottom:2px dashed #e8e0d0;margin-bottom:20px}
      .receipt-logo{width:44px;height:44px;background:#1a3a2a;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin:0 auto 8px}
      .receipt-brand{font-family:'Playfair Display',serif;font-size:1.1rem;color:#1a3a2a}
      .receipt-sub{font-size:.68rem;color:#5a5a5a;text-transform:uppercase;letter-spacing:.08em;font-family:'DM Mono',monospace}
      .receipt-success{display:flex;align-items:center;gap:12px;background:#f0f8f4;border:1px solid #c5e0d0;padding:12px 14px;margin-bottom:20px;border-radius:8px}
      .receipt-success-icon{font-size:1.6rem}
      .receipt-success-title{font-weight:700;color:#1a3a2a;font-size:.9rem}
      .receipt-success-sub{font-size:.76rem;color:#5a5a5a}
      .receipt-tid{text-align:center;padding:12px;background:#f5f0e8;border-radius:8px;margin-bottom:20px}
      .receipt-tid-label{font-size:.66rem;color:#5a5a5a;text-transform:uppercase;letter-spacing:.08em;font-family:'DM Mono',monospace}
      .receipt-tid-val{font-family:'DM Mono',monospace;font-size:.88rem;font-weight:700;color:#1a3a2a;margin-top:3px}
      .receipt-section{margin-bottom:18px}
      .receipt-section-label{font-size:.66rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#5a5a5a;margin-bottom:8px;font-family:'DM Mono',monospace}
      .receipt-row{display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid rgba(74,140,92,.06);font-size:.82rem}
      .receipt-row .rl{color:#5a5a5a}
      .receipt-row .rv{font-weight:600;color:#1a1a18;text-align:right;max-width:55%}
      .receipt-total-row{display:flex;justify-content:space-between;padding:12px 0;border-top:2px solid #1a3a2a;margin-top:6px}
      .receipt-total-label{font-weight:700;color:#1a3a2a}
      .receipt-total-val{font-family:'Playfair Display',serif;font-size:1.25rem;color:#1a3a2a;font-weight:700}
      .receipt-actions{display:none}
    </style>
    </head><body>${content}</body></html>`);
      win.document.close();
      win.focus();
      setTimeout(() => win.print(), 600);
    }

    // ── REVEAL ────────────────────────────────────────────────────────
    const obs = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    }), {
      threshold: .1
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    // ── INIT ──────────────────────────────────────────────────────────
    loadProducts(1);
  </script>
</body>

</html>