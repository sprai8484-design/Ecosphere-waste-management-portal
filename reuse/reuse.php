<?php
// reuse.php — Ecosphere Reuse Hub main page
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reuse Hub — Ecosphere</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono&display=swap" rel="stylesheet">
  <style>
    /* ================================================================
   DESIGN TOKENS
================================================================ */
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
      --shadow-h: 0 12px 40px rgba(26, 58, 42, .18);
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--warm);
      color: var(--charcoal);
      overflow-x: hidden;
    }

    img {
      max-width: 100%;
    }

    button {
      cursor: pointer;
      font-family: inherit;
      border: none;
      outline: none;
    }

    input,
    textarea,
    select {
      font-family: inherit;
      outline: none;
      border: none;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    ::-webkit-scrollbar {
      width: 5px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage);
      border-radius: 3px;
    }

    /* ── NAV ──────────────────────────────────────────────────────── */
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
      height: 64px;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 9px;
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--forest);
    }

    .nav-dot {
      width: 32px;
      height: 32px;
      background: var(--moss);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .9rem;
    }

    .nav-links {
      display: flex;
      gap: 26px;
      list-style: none;
    }

    .nav-links a {
      font-size: .88rem;
      font-weight: 500;
      color: var(--soft);
      transition: color .2s;
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--forest);
    }

    .nav-cta {
      background: var(--forest);
      color: #fff;
      padding: 9px 22px;
      border-radius: 50px;
      font-size: .85rem;
      font-weight: 500;
      transition: all .2s;
    }

    .nav-cta:hover {
      background: var(--moss);
      transform: translateY(-1px);
    }

    /* ── HERO ─────────────────────────────────────────────────────── */
    .hero {
      background: linear-gradient(140deg, var(--forest) 0%, var(--moss) 55%, #3d7a52 100%);
      min-height: 80vh;
      display: flex;
      align-items: center;
      padding: clamp(60px, 10vh, 120px) clamp(20px, 6vw, 100px);
      position: relative;
      overflow: hidden;
    }

    .hero-bg-grid {
      position: absolute;
      inset: 0;
      pointer-events: none;
      background-image: radial-gradient(rgba(255, 255, 255, .05) 1px, transparent 1px);
      background-size: 44px 44px;
    }

    .hero-orb {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }

    .hero-orb.o1 {
      width: 60vw;
      height: 60vw;
      top: -25%;
      right: -20%;
      background: rgba(255, 255, 255, .025);
    }

    .hero-orb.o2 {
      width: 30vw;
      height: 30vw;
      bottom: -10%;
      left: 20%;
      background: rgba(212, 168, 67, .07);
    }

    .hero-orb.o3 {
      width: 18vw;
      height: 18vw;
      top: 20%;
      left: -5%;
      background: rgba(122, 184, 138, .08);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 620px;
    }

    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255, 255, 255, .11);
      border: 1px solid rgba(255, 255, 255, .18);
      color: var(--mint);
      padding: 5px 14px;
      border-radius: 50px;
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 22px;
      animation: fadeUp .6s ease both;
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.6rem, 5.5vw, 4.4rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 18px;
      animation: fadeUp .7s .1s ease both;
    }

    .hero h1 em {
      color: var(--gold);
      font-style: italic;
    }

    .hero-sub {
      color: rgba(255, 255, 255, .72);
      font-size: 1.02rem;
      max-width: 460px;
      margin-bottom: 36px;
      line-height: 1.68;
      animation: fadeUp .7s .2s ease both;
    }

    .hero-actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      animation: fadeUp .7s .3s ease both;
    }

    .btn-gold {
      background: var(--gold);
      color: var(--forest);
      padding: 13px 28px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .92rem;
      transition: all .25s;
      display: inline-flex;
      align-items: center;
      gap: 7px;
    }

    .btn-gold:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(212, 168, 67, .42);
    }

    .btn-ghost {
      background: transparent;
      color: #fff;
      padding: 13px 28px;
      border-radius: 50px;
      border: 1.5px solid rgba(255, 255, 255, .32);
      font-size: .92rem;
      font-weight: 500;
      transition: all .25s;
    }

    .btn-ghost:hover {
      background: rgba(255, 255, 255, .1);
      border-color: #fff;
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      margin-top: 50px;
      animation: fadeUp .7s .4s ease both;
    }

    .h-stat span {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      color: #fff;
      display: block;
    }

    .h-stat small {
      color: rgba(255, 255, 255, .5);
      font-size: .75rem;
      letter-spacing: .05em;
      text-transform: uppercase;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(18px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    /* ── SECTION WRAPPER ──────────────────────────────────────────── */
    .section {
      padding: clamp(56px, 7vh, 96px) clamp(16px, 6vw, 80px);
      position: relative;
      z-index: 1;
    }

    .sec-tag {
      display: inline-block;
      color: var(--leaf);
      font-size: .74rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .sec-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.7rem, 3.2vw, 2.6rem);
      color: var(--forest);
      line-height: 1.2;
    }

    .sec-title em {
      color: var(--leaf);
      font-style: italic;
    }

    .sec-sub {
      color: var(--soft);
      margin-top: 10px;
      font-size: .94rem;
      max-width: 500px;
    }

    /* ── CHALLENGE STRIP ──────────────────────────────────────────── */
    .challenge-strip {
      margin: 0 clamp(16px, 6vw, 80px) 0;
      background: linear-gradient(135deg, var(--terra), var(--clay));
      border-radius: var(--r);
      padding: 22px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 14px;
      box-shadow: 0 8px 28px rgba(212, 116, 90, .25);
      position: relative;
      overflow: hidden;
    }

    .challenge-strip::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -5%;
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: rgba(255, 255, 255, .08);
    }

    .cs-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .cs-icon {
      font-size: 2.2rem;
    }

    .cs-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem;
    }

    .cs-desc {
      color: rgba(255, 255, 255, .75);
      font-size: .84rem;
      margin-top: 3px;
    }

    .cs-btn {
      background: #fff;
      color: var(--terra);
      padding: 10px 22px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .84rem;
      transition: all .2s;
      white-space: nowrap;
    }

    .cs-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 14px rgba(0, 0, 0, .15);
    }

    /* ── SEARCH + FILTER BAR ──────────────────────────────────────── */
    .filter-bar {
      background: #fff;
      border-radius: var(--r);
      padding: 22px 26px;
      border: 1px solid rgba(74, 140, 92, .1);
      box-shadow: var(--shadow);
      margin-bottom: 28px;
    }

    .filter-row-1 {
      display: flex;
      gap: 12px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 16px;
    }

    .search-wrap {
      flex: 1;
      min-width: 200px;
      position: relative;
    }

    .search-wrap svg {
      position: absolute;
      left: 13px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--sage);
      pointer-events: none;
    }

    .search-inp {
      width: 100%;
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: 50px;
      padding: 11px 18px 11px 42px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s;
    }

    .search-inp:focus {
      border-color: var(--leaf);
    }

    .search-inp::placeholder {
      color: rgba(90, 90, 90, .4);
    }

    .sort-sel {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: 50px;
      padding: 11px 18px;
      font-size: .88rem;
      color: var(--charcoal);
      min-width: 150px;
      cursor: pointer;
    }

    .filter-row-2 {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filter-label {
      font-size: .74rem;
      font-weight: 700;
      color: var(--soft);
      text-transform: uppercase;
      letter-spacing: .06em;
      align-self: center;
      margin-right: 4px;
    }

    .pill {
      padding: 7px 16px;
      border-radius: 50px;
      font-size: .82rem;
      font-weight: 500;
      cursor: pointer;
      background: #fff;
      border: 1.5px solid rgba(74, 140, 92, .18);
      color: var(--soft);
      transition: all .2s;
    }

    .pill:hover {
      border-color: var(--leaf);
      color: var(--leaf);
    }

    .pill.active {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest);
    }

    /* ── SORT BAR ─────────────────────────────────────────────────── */
    .grid-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 22px;
      flex-wrap: wrap;
      gap: 10px;
    }

    .grid-count {
      font-size: .88rem;
      color: var(--soft);
    }

    .grid-count strong {
      color: var(--forest);
    }

    .sort-pills {
      display: flex;
      gap: 7px;
    }

    .sort-pill {
      padding: 6px 14px;
      border-radius: 50px;
      font-size: .8rem;
      background: transparent;
      border: 1px solid rgba(74, 140, 92, .18);
      color: var(--soft);
      transition: all .2s;
    }

    .sort-pill.active,
    .sort-pill:hover {
      background: var(--leaf);
      color: #fff;
      border-color: var(--leaf);
    }

    /* ── IDEAS GRID ───────────────────────────────────────────────── */
    #ideasGrid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
      gap: 22px;
    }

    /* ── IDEA CARD ────────────────────────────────────────────────── */
    .idea-card {
      background: #fff;
      border-radius: var(--r);
      overflow: hidden;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      transition: all .3s;
      cursor: pointer;
      position: relative;
      animation: cardIn .4s ease both;
    }

    @keyframes cardIn {
      from {
        opacity: 0;
        transform: translateY(12px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    .idea-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-h);
    }

    .idea-card.trending-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--gold), var(--terra));
      z-index: 2;
    }

    .card-media {
      position: relative;
      aspect-ratio: 16/10;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4.5rem;
    }

    .card-media img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform .4s;
    }

    .idea-card:hover .card-media img {
      transform: scale(1.05);
    }

    .card-media-emoji {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 4.5rem;
      transition: transform .4s;
    }

    .idea-card:hover .card-media-emoji {
      transform: scale(1.06);
    }

    .card-cat-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: var(--forest);
      color: #fff;
      padding: 3px 11px;
      border-radius: 50px;
      font-size: .7rem;
      font-weight: 700;
      z-index: 2;
    }

    .card-diff-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 3px 11px;
      border-radius: 50px;
      font-size: .7rem;
      font-weight: 700;
      z-index: 2;
    }

    .diff-Easy {
      background: rgba(74, 140, 92, .9);
      color: #fff;
    }

    .diff-Medium {
      background: rgba(212, 168, 67, .9);
      color: var(--forest);
    }

    .diff-Hard {
      background: rgba(212, 116, 90, .9);
      color: #fff;
    }

    .card-overlay {
      position: absolute;
      inset: 0;
      background: rgba(26, 58, 42, .35);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity .2s;
      z-index: 3;
    }

    .idea-card:hover .card-overlay {
      opacity: 1;
    }

    .overlay-btn {
      background: #fff;
      color: var(--forest);
      padding: 10px 22px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .85rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .card-body {
      padding: 17px 19px 15px;
    }

    .card-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.08rem;
      color: var(--forest);
      margin-bottom: 8px;
      line-height: 1.3;
    }

    .card-meta {
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      margin-bottom: 10px;
    }

    .card-meta-item {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: .78rem;
      color: var(--soft);
    }

    .card-desc {
      font-size: .84rem;
      color: var(--soft);
      line-height: 1.55;
      margin-bottom: 14px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 12px;
      border-top: 1px solid rgba(74, 140, 92, .07);
    }

    .card-author {
      display: flex;
      align-items: center;
      gap: 7px;
    }

    .author-av {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .75rem;
      font-weight: 700;
      color: #fff;
    }

    .author-name {
      font-size: .78rem;
      color: var(--soft);
    }

    .card-actions {
      display: flex;
      gap: 10px;
    }

    .card-act-btn {
      display: flex;
      align-items: center;
      gap: 4px;
      font-size: .8rem;
      color: var(--soft);
      background: none;
      transition: color .2s;
    }

    .card-act-btn:hover,
    .card-act-btn.liked {
      color: var(--terra);
    }

    .card-act-btn.saved {
      color: var(--gold);
    }

    .empty-state {
      text-align: center;
      padding: 56px 20px;
      grid-column: 1/-1;
      color: var(--soft);
    }

    .empty-state .es-icon {
      font-size: 3.5rem;
      margin-bottom: 14px;
    }

    .empty-state p {
      max-width: 300px;
      margin: 0 auto;
      font-size: .9rem;
      line-height: 1.6;
    }

    .loading-state {
      text-align: center;
      padding: 48px;
      grid-column: 1/-1;
    }

    .spinner {
      width: 40px;
      height: 40px;
      border: 3px solid var(--mint);
      border-top-color: var(--leaf);
      border-radius: 50%;
      animation: spin .8s linear infinite;
      margin: 0 auto 12px;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* ── PAGINATION ───────────────────────────────────────────────── */
    #pagination {
      display: flex;
      gap: 8px;
      justify-content: center;
      margin-top: 36px;
      flex-wrap: wrap;
    }

    .page-btn {
      width: 38px;
      height: 38px;
      border-radius: var(--rs);
      font-size: .88rem;
      font-weight: 600;
      border: 1.5px solid rgba(74, 140, 92, .18);
      background: #fff;
      color: var(--soft);
      transition: all .2s;
    }

    .page-btn:hover,
    .page-btn.active {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest);
    }

    .page-btn:disabled {
      opacity: .35;
      cursor: not-allowed;
    }

    /* ── WHY REUSE ────────────────────────────────────────────────── */
    .why-section {
      background: var(--parchment);
    }

    .why-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
    }

    .why-card {
      background: #fff;
      border-radius: var(--r);
      padding: 28px 24px;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      transition: all .3s;
      position: relative;
      overflow: hidden;
    }

    .why-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-h);
    }

    .why-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 4px;
      border-radius: 0 0 var(--r) var(--r);
    }

    .why-card:nth-child(1)::after {
      background: var(--leaf);
    }

    .why-card:nth-child(2)::after {
      background: var(--gold);
    }

    .why-card:nth-child(3)::after {
      background: var(--terra);
    }

    .why-card:nth-child(4)::after {
      background: var(--sage);
    }

    .why-icon {
      width: 50px;
      height: 50px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 16px;
    }

    .why-card:nth-child(1) .why-icon {
      background: rgba(74, 140, 92, .1);
    }

    .why-card:nth-child(2) .why-icon {
      background: rgba(212, 168, 67, .1);
    }

    .why-card:nth-child(3) .why-icon {
      background: rgba(212, 116, 90, .1);
    }

    .why-card:nth-child(4) .why-icon {
      background: rgba(122, 184, 138, .15);
    }

    .why-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem;
      color: var(--forest);
      margin-bottom: 8px;
    }

    .why-desc {
      font-size: .84rem;
      color: var(--soft);
      line-height: 1.6;
    }

    .why-stat {
      margin-top: 14px;
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      color: var(--leaf);
      font-weight: 700;
    }

    /* ── TOAST ────────────────────────────────────────────────────── */
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
      max-width: 310px;
    }

    .toast.show {
      transform: none;
      opacity: 1;
    }

    /* ── FOOTER ───────────────────────────────────────────────────── */
    footer {
      background: var(--forest);
      color: rgba(255, 255, 255, .55);
      padding: 44px clamp(16px, 6vw, 80px) 28px;
    }

    .ft-top {
      display: flex;
      gap: 48px;
      flex-wrap: wrap;
      margin-bottom: 32px;
    }

    .ft-brand {
      max-width: 240px;
    }

    .ft-logo {
      font-family: 'Playfair Display', serif;
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    .ft-tagline {
      font-size: .83rem;
      line-height: 1.6;
    }

    .ft-col h4 {
      color: rgba(255, 255, 255, .8);
      font-size: .78rem;
      text-transform: uppercase;
      letter-spacing: .09em;
      margin-bottom: 14px;
    }

    .ft-col a {
      display: block;
      color: rgba(255, 255, 255, .45);
      font-size: .83rem;
      margin-bottom: 7px;
      transition: color .2s;
    }

    .ft-col a:hover {
      color: var(--mint);
    }

    .ft-bottom {
      border-top: 1px solid rgba(255, 255, 255, .07);
      padding-top: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      font-size: .8rem;
    }

    /* ── REVEAL ───────────────────────────────────────────────────── */
    .reveal {
      opacity: 0;
      transform: translateY(20px);
      transition: opacity .55s, transform .55s;
    }

    .reveal.visible {
      opacity: 1;
      transform: none;
    }

    /* ── RESPONSIVE ───────────────────────────────────────────────── */
    @media (max-width: 768px) {
      .nav-links {
        display: none;
      }

      .hero-stats {
        display: none;
      }
    }

    @media (max-width: 540px) {
      .filter-row-1 {
        flex-direction: column;
      }

      .sort-sel {
        width: 100%;
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
      <li><a href="/Project/index.php">Home</a></li>
      <li><a href="#ideas" class="active">Ideas</a></li>
      <li><a href="#why">Why Reuse?</a></li>
      <li><a href="add_idea.php">Share Idea</a></li>
    </ul>
    <a href="add_idea.php" class="nav-cta">+ Share Your Idea</a>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg-grid"></div>
    <div class="hero-orb o1"></div>
    <div class="hero-orb o2"></div>
    <div class="hero-orb o3"></div>
    <div class="hero-content">
      <div class="hero-badge">🌱 Community Reuse Platform</div>
      <h1>Turn Waste Into<br><em>Wonder</em></h1>
      <p class="hero-sub">Explore thousands of creative DIY ideas, discover step-by-step tutorials, and share your own reuse projects with a community that cares.</p>
      <div class="hero-actions">
        <a href="#ideas" class="btn-gold">✦ Explore Ideas</a>
        <a href="add_idea.php" class="btn-ghost">Upload Your Idea</a>
      </div>
      <div class="hero-stats">
        <div class="h-stat"><span id="heroCount">—</span><small>Ideas Shared</small></div>
        <div class="h-stat"><span>5 Cats</span><small>Categories</small></div>
        <div class="h-stat"><span>♾</span><small>Creativity</small></div>
      </div>
    </div>
  </section>

  <!-- CHALLENGE STRIP -->
  <div style="padding: 28px clamp(16px,6vw,80px) 0; position:relative;z-index:1;" class="reveal">
    <div class="challenge-strip">
      <div class="cs-left">
        <div class="cs-icon">🏆</div>
        <div>
          <div class="cs-title">Challenge of the Week: Plastic Bottle Makeover</div>
          <div class="cs-desc">Turn any plastic bottle into something useful or beautiful. Best entry wins an Eco Starter Kit!</div>
        </div>
      </div>
      <a href="add_idea.php?category=Plastic" class="cs-btn">Join Challenge →</a>
    </div>
  </div>

  <!-- IDEAS SECTION -->
  <section class="section" id="ideas">
    <div class="reveal" style="margin-bottom:28px">
      <div class="sec-tag">✦ Community Ideas</div>
      <h2 class="sec-title">Discover <em>Creative Reuse</em></h2>
      <p class="sec-sub">Browse ideas from makers, crafters, and eco-warriors. Filter by what you have at home.</p>
    </div>

    <!-- FILTER BAR -->
    <div class="filter-bar reveal">
      <div class="filter-row-1">
        <div class="search-wrap">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
          </svg>
          <input class="search-inp" id="searchInput" type="text" placeholder="Search ideas, materials, authors…" oninput="debounceFilter()">
        </div>
        <select class="sort-sel" id="sortSel" onchange="loadIdeas(1)">
          <option value="newest">✦ Newest First</option>
          <option value="popular">🔥 Most Popular</option>
        </select>
      </div>
      <div class="filter-row-2">
        <span class="filter-label">Category:</span>
        <button class="pill active" onclick="setFilter('category','',this)">All</button>
        <?php foreach (categories() as $c): ?>
          <button class="pill" onclick="setFilter('category','<?= $c ?>',this)"><?= $c ?></button>
        <?php endforeach; ?>
      </div>
      <div class="filter-row-2" style="margin-top:10px">
        <span class="filter-label">Difficulty:</span>
        <button class="pill active" onclick="setFilter('difficulty','',this)">All</button>
        <button class="pill" onclick="setFilter('difficulty','Easy',this)">😊 Easy</button>
        <button class="pill" onclick="setFilter('difficulty','Medium',this)">🔧 Medium</button>
        <button class="pill" onclick="setFilter('difficulty','Hard',this)">💪 Hard</button>
      </div>
    </div>

    <!-- GRID META -->
    <div class="grid-meta reveal">
      <span class="grid-count"><strong id="ideaCount">—</strong> ideas found</span>
      <div class="sort-pills">
        <button class="sort-pill active" onclick="setSortPill('newest',this)">Newest</button>
        <button class="sort-pill" onclick="setSortPill('popular',this)">Most Liked</button>
      </div>
    </div>

    <!-- GRID -->
    <div id="ideasGrid">
      <div class="loading-state">
        <div class="spinner"></div>
        <div style="color:var(--soft);font-size:.9rem">Loading ideas…</div>
      </div>
    </div>

    <!-- PAGINATION -->
    <div id="pagination"></div>
  </section>

  <!-- WHY REUSE -->
  <section class="section why-section" id="why">
    <div class="reveal" style="text-align:center;max-width:500px;margin:0 auto 40px">
      <div class="sec-tag">✦ The Mission</div>
      <h2 class="sec-title">Why <em>Reuse</em> Matters</h2>
      <p class="sec-sub">Every object reused is a small act of revolution against throwaway culture.</p>
    </div>
    <div class="why-grid reveal">
      <div class="why-card">
        <div class="why-icon">🌍</div>
        <div class="why-title">Reduce Landfill Waste</div>
        <div class="why-desc">Over 2 billion tonnes of solid waste is generated globally each year. Reusing even 10% could transform our planet.</div>
        <div class="why-stat">2B+ tonnes/yr</div>
      </div>
      <div class="why-card">
        <div class="why-icon">💰</div>
        <div class="why-title">Save Real Money</div>
        <div class="why-desc">The average household can save ₹18,000–₹36,000 per year by creatively repurposing items instead of buying new ones.</div>
        <div class="why-stat">Save ₹18k+/yr</div>
      </div>
      <div class="why-card">
        <div class="why-icon">🎨</div>
        <div class="why-title">Build Creative Skills</div>
        <div class="why-desc">DIY reuse projects teach design thinking, craftsmanship, problem-solving, and patience — skills that last a lifetime.</div>
        <div class="why-stat">Skills × Creativity</div>
      </div>
      <div class="why-card">
        <div class="why-icon">🌱</div>
        <div class="why-title">Protect Nature</div>
        <div class="why-desc">Manufacturing new goods consumes 6× more energy than reusing existing materials. Every project fights climate change.</div>
        <div class="why-stat">6× less energy</div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="ft-top">
      <div class="ft-brand">
        <div class="ft-logo">🌿 Ecosphere</div>
        <p class="ft-tagline">A community platform inspiring millions to transform waste into wonder, one project at a time.</p>
      </div>
      <div class="ft-col">
        <h4>Explore</h4><a href="#ideas">All Ideas</a><a href="#ideas">Trending</a><a href="add_idea.php">Share Idea</a>
      </div>
      <div class="ft-col">
        <h4>Categories</h4><?php foreach (array_slice(categories(), 0, 4) as $c): ?><a href="#ideas"><?= $c ?></a><?php endforeach; ?>
      </div>
      <div class="ft-col">
        <h4>Platform</h4><a href="recycle.php">Recycle Module</a><a href="#">Guidelines</a><a href="#">Contact</a>
      </div>
    </div>
    <div class="ft-bottom">
      <div>© 2025 Ecosphere Reuse Hub. All rights reserved.</div>
      <div>Made with ♥ for the planet 🌍</div>
    </div>
  </footer>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <!-- AVATAR COLOURS -->
  <script>
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

    // ── State ──────────────────────────────────────────────────────
    let state = {
      category: '',
      difficulty: '',
      search: '',
      sort: 'newest',
      page: 1
    };
    let debounceTimer = null;

    // ── Toast ──────────────────────────────────────────────────────
    function toast(msg, dur = 2600) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur);
    }

    // ── Filter helpers ─────────────────────────────────────────────
    function setFilter(key, val, btn) {
      state[key] = val;
      const group = btn.closest('.filter-row-2');
      group.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      loadIdeas(1);
    }

    function setSortPill(sort, btn) {
      state.sort = sort;
      document.querySelectorAll('.sort-pill').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('sortSel').value = sort;
      loadIdeas(1);
    }

    function debounceFilter() {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        state.search = document.getElementById('searchInput').value.trim();
        loadIdeas(1);
      }, 320);
    }

    // ── Load ideas from backend ────────────────────────────────────
    async function loadIdeas(page = 1) {
      state.page = page;
      const sort = document.getElementById('sortSel').value || state.sort;
      const params = new URLSearchParams({
        search: state.search,
        category: state.category,
        difficulty: state.difficulty,
        sort,
        page,
        limit: 9
      });
      document.getElementById('ideasGrid').innerHTML =
        '<div class="loading-state"><div class="spinner"></div><div style="color:var(--soft);font-size:.9rem">Loading…</div></div>';

      try {
        const res = await fetch('api/fetch_ideas.php?' + params.toString());
        const data = await res.json();
        if (data.error) {
          showEmpty('Error loading ideas: ' + data.error);
          return;
        }
        renderGrid(data);
        renderPagination(data.page, data.pages);
        document.getElementById('ideaCount').textContent = data.total;
        document.getElementById('heroCount').textContent = data.total + '+';
      } catch (e) {
        showEmpty('Could not connect to server. Is XAMPP running?');
      }
    }

    // ── Render grid ────────────────────────────────────────────────
    function renderGrid(data) {
      const grid = document.getElementById('ideasGrid');
      if (!data.ideas.length) {
        showEmpty('No ideas found. Try different filters, or be the first to share one!');
        return;
      }

      grid.innerHTML = data.ideas.map((idea, i) => {
        const catEmoji = CAT_EMOJI[idea.category] || '✦';
        const bg = getCatGradient(idea.category);
        const avColor = AV_COLORS[idea.id % AV_COLORS.length];
        const trending = idea.likes > 150;
        const delay = (i % 9) * 0.04;

        const mediaHTML = idea.image ?
          `<img src="${idea.image}" alt="${escHtml(idea.title)}" loading="lazy">` :
          `<div class="card-media-emoji" style="background:${bg}">${catEmoji}</div>`;

        return `
    <div class="idea-card ${trending ? 'trending-card' : ''}" style="animation-delay:${delay}s">
      <div class="card-media">
        ${mediaHTML}
        <span class="card-cat-badge">${escHtml(idea.category)}</span>
        <span class="card-diff-badge diff-${idea.difficulty}">${idea.difficulty}</span>
        <div class="card-overlay">
          <button class="overlay-btn" onclick="goToDetail(${idea.id})">▶ View Tutorial</button>
        </div>
      </div>
      <div class="card-body">
        <div class="card-title">${escHtml(idea.title)}</div>
        <div class="card-meta">
          <span class="card-meta-item">⏱ ${escHtml(idea.time_required)}</span>
          <span class="card-meta-item">${idea.difficulty === 'Easy' ? '😊' : idea.difficulty === 'Medium' ? '🔧' : '💪'} ${idea.difficulty}</span>
        </div>
        <div class="card-desc">${escHtml(idea.description_short)}</div>
        <div class="card-footer">
          <div class="card-author">
            <div class="author-av" style="background:${avColor}">${escHtml(idea.author[0] || '?')}</div>
            <span class="author-name">${escHtml(idea.author)}</span>
          </div>
          <div class="card-actions">
            <button class="card-act-btn" onclick="quickLike(event,${idea.id},this)">❤️ <span>${idea.likes}</span></button>
            <button class="card-act-btn" onclick="quickSave(event,${idea.id},this)">🔖 Save</button>
          </div>
        </div>
      </div>
    </div>`;
      }).join('');
    }

    function showEmpty(msg) {
      document.getElementById('ideasGrid').innerHTML =
        `<div class="empty-state"><div class="es-icon">🔍</div><p>${msg}</p><br><a href="add_idea.php" style="color:var(--leaf);font-weight:600">+ Share the first idea →</a></div>`;
    }

    // ── Pagination ─────────────────────────────────────────────────
    function renderPagination(current, total) {
      const pg = document.getElementById('pagination');
      if (total <= 1) {
        pg.innerHTML = '';
        return;
      }
      let html = `<button class="page-btn" onclick="loadIdeas(${current - 1})" ${current === 1 ? 'disabled' : ''}>‹</button>`;
      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || Math.abs(i - current) <= 1) {
          html += `<button class="page-btn ${i === current ? 'active' : ''}" onclick="loadIdeas(${i})">${i}</button>`;
        } else if (Math.abs(i - current) === 2) {
          html += `<button class="page-btn" disabled>…</button>`;
        }
      }
      html += `<button class="page-btn" onclick="loadIdeas(${current + 1})" ${current === total ? 'disabled' : ''}>›</button>`;
      pg.innerHTML = html;
    }

    // ── Quick actions ──────────────────────────────────────────────
    async function quickLike(e, id, btn) {
      e.stopPropagation();
      try {
        const res = await fetch('api/like.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idea_id: id
          })
        });
        const data = await res.json();
        if (data.success) {
          btn.querySelector('span').textContent = data.likes;
          btn.classList.add('liked');
          toast('❤️ Liked!');
        }
      } catch {
        toast('⚠️ Server error');
      }
    }

    async function quickSave(e, id, btn) {
      e.stopPropagation();
      try {
        const res = await fetch('api/save.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            idea_id: id
          })
        });
        const data = await res.json();
        if (data.success) {
          btn.classList.toggle('saved', data.saved);
          btn.textContent = data.saved ? '🔖 Saved' : '🔖 Save';
          toast(data.saved ? '🔖 Saved to your collection!' : 'Removed from saved');
        }
      } catch {
        toast('⚠️ Server error');
      }
    }

    function goToDetail(id) {
      window.location.href = 'reuse_details.php?id=' + id;
    }

    // ── Helpers ────────────────────────────────────────────────────
    function escHtml(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function getCatGradient(cat) {
      const m = {
        Plastic: 'linear-gradient(135deg,#3d6b8c,#5a9ec4)',
        Clothes: 'linear-gradient(135deg,#6b5a4e,#9e8870)',
        Paper: 'linear-gradient(135deg,#5a4a2d,#8c7a50)',
        Glass: 'linear-gradient(135deg,#2d5a6b,#4a8c9e)',
        Wood: 'linear-gradient(135deg,#5c4a2d,#8b6f47)',
        Metal: 'linear-gradient(135deg,#3a3a5c,#5a5a8c)',
        Garden: 'linear-gradient(135deg,#2d5a3d,#4a8c5c)',
        Electronics: 'linear-gradient(135deg,#2c2c2c,#555)'
      };
      return m[cat] || 'linear-gradient(135deg,var(--moss),var(--leaf))';
    }

    // ── Scroll reveal ──────────────────────────────────────────────
    const obs = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible');
    }), {
      threshold: .1
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    // ── Init ───────────────────────────────────────────────────────
    loadIdeas(1);
  </script>
</body>

</html>