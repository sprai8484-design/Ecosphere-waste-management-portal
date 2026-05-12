<?php
// recycle.php — Main Ecosphere Recycle Module Page
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recycle — Ecosphere</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono&display=swap" rel="stylesheet">
  <style>
    /* ===== TOKENS ===== */
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
      --white: #ffffff;
      --r: 14px;
      --rs: 8px;
      --shadow: 0 6px 28px rgba(26, 58, 42, .1);
      --shadow-h: 0 12px 40px rgba(26, 58, 42, .18);
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

    ::-webkit-scrollbar {
      width: 5px
    }

    ::-webkit-scrollbar-track {
      background: var(--cream)
    }

    ::-webkit-scrollbar-thumb {
      background: var(--sage);
      border-radius: 3px
    }

    /* ===== NAV ===== */
    nav {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(245, 240, 232, .92);
      backdrop-filter: blur(18px);
      border-bottom: 1px solid rgba(74, 140, 92, .13);
      padding: 0 clamp(16px, 4vw, 60px);
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 64px
    }

    .nav-logo {
      font-family: 'Playfair Display', serif;
      font-size: 1.3rem;
      color: var(--forest);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 9px
    }

    .nav-logo-dot {
      width: 32px;
      height: 32px;
      background: var(--moss);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .95rem
    }

    .nav-links {
      display: flex;
      gap: 24px;
      list-style: none
    }

    .nav-links a {
      text-decoration: none;
      color: var(--soft);
      font-size: .88rem;
      font-weight: 500;
      transition: color .2s
    }

    .nav-links a:hover,
    .nav-links a.active {
      color: var(--forest)
    }

    .nav-cta {
      background: var(--forest);
      color: #fff;
      padding: 8px 20px;
      border-radius: 50px;
      font-size: .85rem;
      font-weight: 500;
      transition: all .2s
    }

    .nav-cta:hover {
      background: var(--moss)
    }

    /* ===== HERO ===== */
    .hero {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      min-height: 72vh;
      display: flex;
      align-items: center;
      padding: clamp(50px, 8vh, 100px) clamp(16px, 6vw, 100px);
      position: relative;
      overflow: hidden
    }

    .hero::before {
      content: '';
      position: absolute;
      top: -30%;
      right: -15%;
      width: 55vw;
      height: 55vw;
      border-radius: 50%;
      background: rgba(255, 255, 255, .03);
      pointer-events: none
    }

    .hero::after {
      content: '';
      position: absolute;
      bottom: -20%;
      left: -10%;
      width: 40vw;
      height: 40vw;
      border-radius: 50%;
      background: rgba(212, 168, 67, .07);
      pointer-events: none
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 600px
    }

    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255, 255, 255, .11);
      border: 1px solid rgba(255, 255, 255, .18);
      color: var(--mint);
      padding: 5px 14px;
      border-radius: 50px;
      font-size: .78rem;
      font-weight: 600;
      letter-spacing: .07em;
      text-transform: uppercase;
      margin-bottom: 20px
    }

    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.4rem, 5.5vw, 4.2rem);
      color: #fff;
      line-height: 1.1;
      margin-bottom: 16px
    }

    .hero h1 em {
      color: var(--gold);
      font-style: italic
    }

    .hero-sub {
      color: rgba(255, 255, 255, .7);
      font-size: 1rem;
      max-width: 460px;
      margin-bottom: 32px;
      line-height: 1.65
    }

    .hero-btns {
      display: flex;
      gap: 12px;
      flex-wrap: wrap
    }

    .btn-primary {
      background: var(--gold);
      color: var(--forest);
      padding: 13px 28px;
      border-radius: 50px;
      font-weight: 600;
      font-size: .9rem;
      transition: all .25s;
      display: inline-flex;
      align-items: center;
      gap: 7px
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 22px rgba(212, 168, 67, .4)
    }

    .btn-outline {
      background: transparent;
      color: #fff;
      padding: 13px 28px;
      border-radius: 50px;
      border: 1.5px solid rgba(255, 255, 255, .3);
      font-weight: 500;
      font-size: .9rem;
      transition: all .25s
    }

    .btn-outline:hover {
      background: rgba(255, 255, 255, .1);
      border-color: #fff
    }

    .hero-stats {
      display: flex;
      gap: 36px;
      margin-top: 44px
    }

    .h-stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      color: #fff;
      display: block
    }

    .h-stat-lbl {
      color: rgba(255, 255, 255, .5);
      font-size: .75rem;
      letter-spacing: .05em;
      text-transform: uppercase
    }

    /* ===== SECTION ===== */
    .section {
      padding: clamp(50px, 7vh, 90px) clamp(16px, 6vw, 80px);
      position: relative
    }

    .sec-tag {
      display: inline-block;
      color: var(--leaf);
      font-size: .75rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-bottom: 8px
    }

    .sec-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(1.6rem, 3vw, 2.5rem);
      color: var(--forest);
      line-height: 1.2
    }

    .sec-title em {
      font-style: italic;
      color: var(--leaf)
    }

    .sec-sub {
      color: var(--soft);
      margin-top: 10px;
      font-size: .93rem;
      max-width: 500px
    }

    /* ===== SEARCH SECTION ===== */
    .search-section {
      background: var(--cream)
    }

    .search-box {
      background: #fff;
      border-radius: var(--r);
      box-shadow: var(--shadow);
      padding: 28px 32px;
      border: 1px solid rgba(74, 140, 92, .1);
      margin-bottom: 32px
    }

    .search-row {
      display: grid;
      grid-template-columns: 1fr auto auto auto;
      gap: 12px;
      align-items: end;
      flex-wrap: wrap
    }

    .form-field {
      display: flex;
      flex-direction: column;
      gap: 6px
    }

    .form-field label {
      font-size: .78rem;
      font-weight: 600;
      color: var(--forest);
      text-transform: uppercase;
      letter-spacing: .05em
    }

    .inp {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: var(--rs);
      padding: 11px 16px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s, box-shadow .2s;
      width: 100%
    }

    .inp:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1)
    }

    .inp::placeholder {
      color: rgba(90, 90, 90, .45)
    }

    .btn-search {
      background: var(--forest);
      color: #fff;
      padding: 12px 26px;
      border-radius: var(--rs);
      font-weight: 600;
      font-size: .9rem;
      transition: all .2s;
      white-space: nowrap;
      height: 46px
    }

    .btn-search:hover {
      background: var(--moss)
    }

    .btn-clear {
      background: var(--cream);
      color: var(--soft);
      padding: 12px 18px;
      border-radius: var(--rs);
      font-size: .85rem;
      height: 46px;
      border: 1px solid rgba(74, 140, 92, .15);
      transition: all .2s
    }

    .btn-clear:hover {
      border-color: var(--terra);
      color: var(--terra)
    }

    .filter-row {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 16px
    }

    .filter-pill {
      padding: 7px 16px;
      border-radius: 50px;
      font-size: .82rem;
      font-weight: 500;
      cursor: pointer;
      transition: all .2s;
      background: #fff;
      border: 1.5px solid rgba(74, 140, 92, .18);
      color: var(--soft)
    }

    .filter-pill:hover {
      border-color: var(--leaf);
      color: var(--leaf)
    }

    .filter-pill.active {
      background: var(--forest);
      color: #fff;
      border-color: var(--forest)
    }

    /* ===== CENTERS GRID ===== */
    #centersGrid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
      gap: 20px
    }

    .center-card {
      background: #fff;
      border-radius: var(--r);
      padding: 22px;
      border: 1px solid rgba(74, 140, 92, .09);
      box-shadow: var(--shadow);
      transition: all .3s;
      position: relative;
      overflow: hidden
    }

    .center-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--leaf), var(--sage))
    }

    .center-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-h)
    }

    .cc-name {
      font-family: 'Playfair Display', serif;
      font-size: 1.05rem;
      color: var(--forest);
      margin-bottom: 6px
    }

    .cc-addr {
      font-size: .83rem;
      color: var(--soft);
      margin-bottom: 12px;
      display: flex;
      align-items: flex-start;
      gap: 5px
    }

    .cc-types {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-bottom: 14px
    }

    .waste-tag {
      background: rgba(74, 140, 92, .08);
      color: var(--leaf);
      border-radius: 50px;
      padding: 3px 10px;
      font-size: .72rem;
      font-weight: 600
    }

    .cc-contact {
      font-size: .82rem;
      color: var(--soft);
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 6px
    }

    .cc-timings {
      font-size: .78rem;
      color: var(--clay);
      display: flex;
      align-items: center;
      gap: 5px;
      margin-bottom: 14px
    }

    .btn-send {
      width: 100%;
      background: linear-gradient(135deg, var(--forest), var(--moss));
      color: #fff;
      padding: 10px;
      border-radius: var(--rs);
      font-weight: 600;
      font-size: .87rem;
      transition: all .2s
    }

    .btn-send:hover {
      opacity: .9;
      transform: translateY(-1px)
    }

    .no-results {
      text-align: center;
      padding: 48px;
      color: var(--soft);
      grid-column: 1/-1
    }

    .no-results .nr-icon {
      font-size: 3rem;
      margin-bottom: 12px
    }

    .loading-spinner {
      text-align: center;
      padding: 40px;
      grid-column: 1/-1;
      color: var(--leaf)
    }

    /* ===== SEND ITEM FORM ===== */
    .send-section {
      background: var(--parchment)
    }

    .form-panel {
      background: #fff;
      border-radius: var(--r);
      box-shadow: var(--shadow);
      max-width: 780px;
      margin: 0 auto;
      overflow: hidden;
      border: 1px solid rgba(74, 140, 92, .1)
    }

    .form-panel-header {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      padding: 28px 36px;
      display: flex;
      align-items: center;
      gap: 14px
    }

    .fph-icon {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, .14);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem
    }

    .fph-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1.2rem
    }

    .fph-sub {
      color: rgba(255, 255, 255, .65);
      font-size: .83rem;
      margin-top: 2px
    }

    .form-body {
      padding: 32px 36px
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      margin-bottom: 18px
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 7px
    }

    .form-group.full {
      grid-column: 1/-1
    }

    .form-label {
      font-size: .78rem;
      font-weight: 600;
      color: var(--forest);
      text-transform: uppercase;
      letter-spacing: .05em
    }

    .form-label span {
      color: var(--terra)
    }

    .form-inp,
    .form-sel,
    .form-ta {
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .16);
      border-radius: var(--rs);
      padding: 11px 16px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s, box-shadow .2s;
      width: 100%
    }

    .form-inp:focus,
    .form-sel:focus,
    .form-ta:focus {
      border-color: var(--leaf);
      box-shadow: 0 0 0 3px rgba(74, 140, 92, .1)
    }

    .form-inp::placeholder,
    .form-ta::placeholder {
      color: rgba(90, 90, 90, .4)
    }

    .form-ta {
      resize: vertical;
      min-height: 88px
    }

    .method-row {
      display: flex;
      gap: 14px
    }

    .method-opt {
      flex: 1;
      border: 2px solid rgba(74, 140, 92, .18);
      border-radius: var(--rs);
      padding: 14px;
      text-align: center;
      cursor: pointer;
      transition: all .2s;
      background: var(--cream)
    }

    .method-opt input {
      display: none
    }

    .method-opt .mo-icon {
      font-size: 1.5rem;
      display: block;
      margin-bottom: 6px
    }

    .method-opt .mo-label {
      font-size: .85rem;
      font-weight: 600;
      color: var(--soft)
    }

    .method-opt .mo-desc {
      font-size: .75rem;
      color: rgba(90, 90, 90, .6);
      margin-top: 3px
    }

    .method-opt.selected {
      border-color: var(--leaf);
      background: rgba(74, 140, 92, .06)
    }

    .method-opt.selected .mo-label {
      color: var(--forest)
    }

    .upload-zone {
      border: 2px dashed rgba(74, 140, 92, .25);
      border-radius: var(--rs);
      padding: 28px;
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
      font-size: 2rem;
      margin-bottom: 8px
    }

    .uz-text {
      color: var(--soft);
      font-size: .85rem
    }

    .uz-text strong {
      color: var(--leaf)
    }

    .btn-submit-form {
      width: 100%;
      background: linear-gradient(135deg, var(--forest), var(--moss));
      color: #fff;
      padding: 15px;
      border-radius: 50px;
      font-weight: 600;
      font-size: .97rem;
      transition: all .2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 8px
    }

    .btn-submit-form:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(26, 58, 42, .28)
    }

    .btn-submit-form:disabled {
      opacity: .6;
      cursor: not-allowed;
      transform: none
    }

    .success-msg {
      display: none;
      background: rgba(74, 140, 92, .07);
      border: 1px solid rgba(74, 140, 92, .2);
      border-radius: var(--rs);
      padding: 20px 24px;
      margin-top: 20px;
      text-align: center
    }

    .success-msg .sm-icon {
      font-size: 2.2rem;
      margin-bottom: 8px
    }

    .success-msg .sm-title {
      color: var(--forest);
      font-weight: 700;
      font-size: 1.05rem;
      margin-bottom: 4px
    }

    .success-msg .sm-tid {
      font-family: 'DM Mono', monospace;
      background: var(--forest);
      color: #fff;
      display: inline-block;
      padding: 4px 14px;
      border-radius: 50px;
      font-size: .9rem;
      margin-top: 6px
    }

    .err-msg {
      display: none;
      background: rgba(212, 116, 90, .07);
      border: 1px solid rgba(212, 116, 90, .2);
      border-radius: var(--rs);
      padding: 14px 18px;
      margin-top: 12px;
      color: var(--terra);
      font-size: .88rem
    }

    /* ===== TRACK SECTION ===== */
    .track-section {
      background: var(--cream)
    }

    .track-widget {
      max-width: 680px;
      margin: 0 auto;
      background: #fff;
      border-radius: var(--r);
      box-shadow: var(--shadow);
      overflow: hidden;
      border: 1px solid rgba(74, 140, 92, .1)
    }

    .track-header {
      background: linear-gradient(135deg, var(--forest), var(--moss));
      padding: 26px 32px;
      display: flex;
      align-items: center;
      gap: 14px
    }

    .th-icon {
      width: 42px;
      height: 42px;
      background: rgba(255, 255, 255, .14);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem
    }

    .th-title {
      color: #fff;
      font-family: 'Playfair Display', serif;
      font-size: 1.15rem
    }

    .th-sub {
      color: rgba(255, 255, 255, .6);
      font-size: .82rem;
      margin-top: 2px
    }

    .track-body {
      padding: 28px 32px
    }

    .track-input-row {
      display: flex;
      gap: 10px;
      margin-bottom: 20px
    }

    .track-inp {
      flex: 1;
      background: var(--cream);
      border: 1.5px solid rgba(74, 140, 92, .18);
      border-radius: var(--rs);
      padding: 12px 18px;
      font-size: .9rem;
      color: var(--charcoal);
      transition: border-color .2s
    }

    .track-inp:focus {
      border-color: var(--leaf)
    }

    .track-inp::placeholder {
      color: rgba(90, 90, 90, .4)
    }

    .btn-track {
      background: var(--forest);
      color: #fff;
      padding: 12px 24px;
      border-radius: var(--rs);
      font-weight: 600;
      font-size: .9rem;
      transition: all .2s;
      white-space: nowrap
    }

    .btn-track:hover {
      background: var(--moss)
    }

    .track-result {
      display: none
    }

    .tr-info {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 24px
    }

    .tr-badge {
      padding: 4px 12px;
      border-radius: 50px;
      font-size: .75rem;
      font-weight: 600
    }

    .tr-badge.cat {
      background: rgba(74, 140, 92, .1);
      color: var(--leaf)
    }

    .tr-badge.method {
      background: rgba(212, 168, 67, .1);
      color: var(--earth)
    }

    .tr-name {
      font-weight: 600;
      font-size: .95rem;
      color: var(--forest);
      margin-bottom: 6px
    }

    /* Timeline */
    .timeline {
      position: relative;
      padding-left: 0;
      margin-top: 8px
    }

    .tl-item {
      display: flex;
      gap: 0;
      margin-bottom: 0;
      position: relative
    }

    .tl-left {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 44px;
      flex-shrink: 0
    }

    .tl-dot {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      z-index: 2;
      flex-shrink: 0;
      transition: all .3s
    }

    .tl-dot.done {
      background: var(--leaf);
      color: #fff;
      box-shadow: 0 0 0 4px rgba(74, 140, 92, .15)
    }

    .tl-dot.current {
      background: var(--gold);
      color: var(--forest);
      box-shadow: 0 0 0 4px rgba(212, 168, 67, .2);
      animation: pulse 2s infinite
    }

    .tl-dot.pending {
      background: var(--parchment);
      color: rgba(90, 90, 90, .4);
      border: 2px solid rgba(74, 140, 92, .15)
    }

    .tl-line {
      width: 2px;
      flex: 1;
      min-height: 28px;
      background: rgba(74, 140, 92, .12)
    }

    .tl-line.done {
      background: var(--sage)
    }

    .tl-line.last {
      display: none
    }

    .tl-right {
      padding: 4px 0 28px 14px;
      flex: 1
    }

    .tl-stage {
      font-weight: 600;
      font-size: .9rem;
      margin-bottom: 2px
    }

    .tl-stage.done {
      color: var(--forest)
    }

    .tl-stage.current {
      color: var(--earth);
      font-weight: 700
    }

    .tl-stage.pending {
      color: rgba(90, 90, 90, .4)
    }

    .tl-date {
      font-size: .75rem;
      color: var(--soft)
    }

    .tl-desc {
      font-size: .78rem;
      color: var(--soft);
      margin-top: 3px
    }

    @keyframes pulse {

      0%,
      100% {
        box-shadow: 0 0 0 4px rgba(212, 168, 67, .2)
      }

      50% {
        box-shadow: 0 0 0 8px rgba(212, 168, 67, .1)
      }
    }

    .track-not-found {
      display: none;
      text-align: center;
      padding: 28px;
      color: var(--soft)
    }

    .track-not-found .tnf-icon {
      font-size: 2.5rem;
      margin-bottom: 10px
    }

    /* ===== EDUCATION ===== */
    .edu-section {
      background: var(--parchment)
    }

    .edu-why-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 18px;
      margin-bottom: 56px
    }

    .edu-card {
      background: #fff;
      border-radius: var(--r);
      padding: 26px 22px;
      border: 1px solid rgba(74, 140, 92, .08);
      box-shadow: var(--shadow);
      transition: all .3s;
      position: relative;
      overflow: hidden
    }

    .edu-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-h)
    }

    .edu-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 3px;
      border-radius: 0 0 var(--r) var(--r)
    }

    .edu-card:nth-child(1)::after {
      background: var(--leaf)
    }

    .edu-card:nth-child(2)::after {
      background: var(--gold)
    }

    .edu-card:nth-child(3)::after {
      background: var(--terra)
    }

    .edu-card:nth-child(4)::after {
      background: var(--sage)
    }

    .edu-icon {
      width: 50px;
      height: 50px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      margin-bottom: 16px
    }

    .edu-card-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.02rem;
      color: var(--forest);
      margin-bottom: 8px
    }

    .edu-card-desc {
      font-size: .84rem;
      color: var(--soft);
      line-height: 1.6
    }

    .process-steps {
      display: flex;
      flex-direction: column;
      gap: 0;
      max-width: 680px;
      margin: 0 auto
    }

    .ps-item {
      display: flex;
      gap: 0;
      align-items: stretch
    }

    .ps-left {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 52px;
      flex-shrink: 0
    }

    .ps-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--forest);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: .9rem;
      z-index: 1
    }

    .ps-vline {
      width: 2px;
      flex: 1;
      min-height: 20px;
      background: rgba(74, 140, 92, .15)
    }

    .ps-item:last-child .ps-vline {
      display: none
    }

    .ps-right {
      padding: 0 0 28px 18px;
      flex: 1
    }

    .ps-title {
      font-weight: 600;
      color: var(--forest);
      font-size: .95rem;
      margin-bottom: 4px
    }

    .ps-desc {
      font-size: .84rem;
      color: var(--soft);
      line-height: 1.55
    }

    /* ===== FOOTER ===== */
    footer {
      background: var(--forest);
      color: rgba(255, 255, 255, .55);
      padding: 44px clamp(16px, 6vw, 80px) 28px
    }

    .ft-top {
      display: flex;
      gap: 50px;
      flex-wrap: wrap;
      margin-bottom: 36px
    }

    .ft-brand {
      max-width: 240px
    }

    .ft-logo {
      font-family: 'Playfair Display', serif;
      color: #fff;
      font-size: 1.2rem;
      margin-bottom: 10px
    }

    .ft-tagline {
      font-size: .83rem;
      line-height: 1.6
    }

    .ft-col h4 {
      color: rgba(255, 255, 255, .8);
      font-size: .8rem;
      text-transform: uppercase;
      letter-spacing: .09em;
      margin-bottom: 14px
    }

    .ft-col a {
      display: block;
      color: rgba(255, 255, 255, .45);
      font-size: .83rem;
      text-decoration: none;
      margin-bottom: 7px;
      transition: color .2s
    }

    .ft-col a:hover {
      color: var(--mint)
    }

    .ft-bottom {
      border-top: 1px solid rgba(255, 255, 255, .07);
      padding-top: 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px
    }

    .ft-copy {
      font-size: .8rem
    }

    /* ===== TOAST ===== */
    .toast {
      position: fixed;
      bottom: 28px;
      right: 28px;
      z-index: 999;
      background: var(--forest);
      color: #fff;
      padding: 13px 20px;
      border-radius: 12px;
      font-size: .88rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 9px;
      box-shadow: 0 8px 28px rgba(26, 58, 42, .28);
      transform: translateY(18px);
      opacity: 0;
      transition: all .3s;
      pointer-events: none;
      max-width: 320px
    }

    .toast.show {
      transform: translateY(0);
      opacity: 1
    }

    /* ===== REVEAL ===== */
    .reveal {
      opacity: 0;
      transform: translateY(22px);
      transition: opacity .55s, transform .55s
    }

    .reveal.visible {
      opacity: 1;
      transform: none
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width:768px) {
      .search-row {
        grid-template-columns: 1fr;
        gap: 10px
      }

      .form-grid {
        grid-template-columns: 1fr
      }

      .hero-stats {
        display: none
      }

      .nav-links {
        display: none
      }

      .form-body {
        padding: 22px 20px
      }

      .track-body {
        padding: 22px 20px
      }
    }
  </style>
</head>

<body>

  <!-- NAV -->
  <nav>
    <a class="nav-logo" href="#">
      <div class="nav-logo-dot">🌿</div>Ecosphere
    </a>
    <ul class="nav-links">
      <li><a href="/Project/index.php">Home</a></li>
      <li><a href="#send">Send Item</a></li>
      <li><a href="#track">Track</a></li>
      <li><a href="#learn">Learn</a></li>
    </ul>
    <button class="nav-cta" onclick="smoothTo('send')">+ Recycle Now</button>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-tag">♻️ Recycle Module</div>
      <h1>Close the Loop<br>on <em>Waste</em></h1>
      <p class="hero-sub">Find certified recycling centers near you, schedule a pickup, and track your item every step of the way — from submission to returned product.</p>
      <div class="hero-btns">
        <button class="btn-primary" onclick="smoothTo('centers')">🔍 Find Centers</button>
        <button class="btn-outline" onclick="smoothTo('track')">📦 Track Request</button>
      </div>
      <div class="hero-stats">
        <div><span class="h-stat-num">1,240+</span><span class="h-stat-lbl">Requests Processed</span></div>
        <div><span class="h-stat-num">8</span><span class="h-stat-lbl">Partner Centers</span></div>
        <div><span class="h-stat-num">12t</span><span class="h-stat-lbl">Waste Recycled</span></div>
      </div>
    </div>
  </section>

  <!-- FIND CENTERS -->
  <section class="section search-section" id="centers">
    <div class="sec-header reveal" style="margin-bottom:28px">
      <div class="sec-tag">✦ Locate</div>
      <h2 class="sec-title">Find Nearby <em>Recycling Centers</em></h2>
      <p class="sec-sub">Search by city or pincode and filter by the type of waste you need to recycle.</p>
    </div>
    <div class="search-box reveal">
      <div class="search-row">
        <div class="form-field">
          <label>City or Pincode</label>
          <input class="inp" type="text" id="locationInput" placeholder="e.g. Mumbai or 400050">
        </div>
        <button class="btn-search" onclick="searchCenters()">🔍 Search</button>
        <button class="btn-clear" onclick="clearSearch()">✕ Clear</button>
      </div>
      <div class="filter-row" id="filterRow">
        <span style="font-size:.78rem;font-weight:600;color:var(--soft);align-self:center;text-transform:uppercase;letter-spacing:.05em">Waste:</span>
        <?php foreach (wasteTypes() as $wt): ?>
          <button class="filter-pill" onclick="toggleWasteFilter('<?= $wt ?>', this)"><?= $wt ?></button>
        <?php endforeach; ?>
      </div>
    </div>
    <div id="centersGrid" class="reveal">
      <div class="loading-spinner">Loading centers…</div>
    </div>
  </section>

  <!-- SEND ITEM -->
  <section class="section send-section" id="send">
    <div class="sec-header reveal" style="text-align:center;max-width:520px;margin:0 auto 36px">
      <div class="sec-tag">✦ Submit</div>
      <h2 class="sec-title">Send Your <em>Item</em></h2>
      <p class="sec-sub">Fill in the form below to schedule a pickup or plan a drop-off at a recycling center.</p>
    </div>
    <div class="form-panel reveal">
      <div class="form-panel-header">
        <div class="fph-icon">📦</div>
        <div>
          <div class="fph-title">Recycling Request Form</div>
          <div class="fph-sub">We'll handle the rest — you'll receive a tracking ID immediately.</div>
        </div>
      </div>
      <div class="form-body">
        <form id="sendForm" enctype="multipart/form-data">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Full Name <span>*</span></label>
              <input class="form-inp" type="text" name="user_name" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email <span>*</span></label>
              <input class="form-inp" type="email" name="email" placeholder="you@example.com" required>
            </div>
            <div class="form-group">
              <label class="form-label">Phone <span>*</span></label>
              <input class="form-inp" type="tel" name="phone" placeholder="+91 98765 43210" required>
            </div>
            <div class="form-group">
              <label class="form-label">Waste Type <span>*</span></label>
              <select class="form-sel" name="waste_type" required>
                <option value="">Select waste type</option>
                <?php foreach (wasteTypes() as $wt): ?>
                  <option value="<?= $wt ?>"><?= $wt ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group full">
              <label class="form-label">Pickup / Drop-off Address <span>*</span></label>
              <input class="form-inp" type="text" name="address" placeholder="Street address" required>
            </div>
            <div class="form-group">
              <label class="form-label">City <span>*</span></label>
              <input class="form-inp" type="text" name="city" placeholder="City" required>
            </div>
            <div class="form-group">
              <label class="form-label">Pincode <span>*</span></label>
              <input class="form-inp" type="text" name="pincode" placeholder="e.g. 400050" required>
            </div>
            <div class="form-group full">
              <label class="form-label">Item Description <span>*</span></label>
              <textarea class="form-ta" name="description" placeholder="Describe the item — type, condition, approximate weight…" required></textarea>
            </div>
            <div class="form-group full">
              <label class="form-label">Custom Request <span style="color:var(--soft);font-weight:400;text-transform:none">(optional)</span></label>
              <textarea class="form-ta" name="custom_request" placeholder="e.g. Convert old saree into curtain fabric, keep the original colour…" style="min-height:68px"></textarea>
            </div>
            <div class="form-group full">
              <label class="form-label">Upload Photo <span style="color:var(--soft);font-weight:400;text-transform:none">(optional)</span></label>
              <div class="upload-zone" onclick="document.getElementById('imgInput').click()" id="uploadZone">
                <div class="uz-icon">📷</div>
                <div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.75rem;color:rgba(90,90,90,.4)">JPG, PNG up to 5MB</span></div>
              </div>
              <input type="file" id="imgInput" name="image" accept="image/*" style="display:none" onchange="previewImg(event)">
            </div>
            <div class="form-group full">
              <label class="form-label">Collection Method <span>*</span></label>
              <div class="method-row">
                <label class="method-opt selected" id="opt-pickup">
                  <input type="radio" name="method" value="pickup" checked>
                  <span class="mo-icon">🚚</span>
                  <span class="mo-label">Pickup</span>
                  <span class="mo-desc">We collect from your door</span>
                </label>
                <label class="method-opt" id="opt-drop">
                  <input type="radio" name="method" value="drop">
                  <span class="mo-icon">🚶</span>
                  <span class="mo-label">Self Drop</span>
                  <span class="mo-desc">You bring it to the center</span>
                </label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn-submit-form" id="submitBtn">
            ♻️ Submit Recycling Request
          </button>
        </form>
        <div class="err-msg" id="errMsg"></div>
        <div class="success-msg" id="successMsg">
          <div class="sm-icon">🎉</div>
          <div class="sm-title">Request Submitted Successfully!</div>
          <p style="font-size:.85rem;color:var(--soft);margin-top:6px">Your Tracking ID:</p>
          <div class="sm-tid" id="trackingIdDisplay">ECO-2025-XXXX</div>
          <p style="font-size:.82rem;color:var(--soft);margin-top:8px">Save this ID to track your request status.</p>
          <button onclick="smoothTo('track')" style="margin-top:14px;background:var(--forest);color:#fff;padding:9px 22px;border-radius:50px;font-size:.85rem;font-weight:600">📦 Track It Now →</button>
        </div>
      </div>
    </div>
  </section>

  <!-- TRACK -->
  <section class="section track-section" id="track">
    <div class="sec-header reveal" style="text-align:center;max-width:500px;margin:0 auto 36px">
      <div class="sec-tag">✦ Track</div>
      <h2 class="sec-title">Track Your <em>Request</em></h2>
      <p class="sec-sub">Enter your tracking ID to see the real-time status of your recycling request.</p>
    </div>
    <div class="track-widget reveal">
      <div class="track-header">
        <div class="th-icon">📦</div>
        <div>
          <div class="th-title">Recycling Tracker</div>
          <div class="th-sub">Enter your ECO-XXXX-XXXX tracking ID below</div>
        </div>
      </div>
      <div class="track-body">
        <div class="track-input-row">
          <input class="track-inp" type="text" id="trackInput" placeholder="ECO-2025-0001" oninput="this.value=this.value.toUpperCase()">
          <button class="btn-track" onclick="trackRequest()">Track →</button>
        </div>
        <div id="trackResult" class="track-result"></div>
        <div class="track-not-found" id="trackNotFound">
          <div class="tnf-icon">🔍</div>
          <p>No request found for this tracking ID.<br><span style="font-size:.8rem">Try <strong>ECO-2025-0001</strong> as a demo.</span></p>
        </div>
      </div>
    </div>
  </section>

  <!-- EDUCATION -->
  <section class="section edu-section" id="learn">
    <div class="sec-header reveal" style="text-align:center;max-width:520px;margin:0 auto 40px">
      <div class="sec-tag">✦ Learn</div>
      <h2 class="sec-title">Why <em>Recycling</em> Matters</h2>
      <p class="sec-sub">Every kilogram recycled is a choice for the future.</p>
    </div>
    <div class="edu-why-grid reveal">
      <div class="edu-card">
        <div class="edu-icon" style="background:rgba(74,140,92,.1)">🌍</div>
        <div class="edu-card-title">Reduce Landfill Load</div>
        <div class="edu-card-desc">Over 60% of landfill waste can be recycled or composted. Every item you send us is kept out of the ground for generations.</div>
      </div>
      <div class="edu-card">
        <div class="edu-icon" style="background:rgba(212,168,67,.1)">⚡</div>
        <div class="edu-card-title">Save Energy</div>
        <div class="edu-card-desc">Recycling aluminium uses 95% less energy than making it from ore. Recycled paper cuts energy use by 40%.</div>
      </div>
      <div class="edu-card">
        <div class="edu-icon" style="background:rgba(212,116,90,.1)">💧</div>
        <div class="edu-card-title">Protect Water & Soil</div>
        <div class="edu-card-desc">Improper e-waste and plastic disposal leaches toxins into groundwater. Certified recycling prevents this entirely.</div>
      </div>
      <div class="edu-card">
        <div class="edu-icon" style="background:rgba(122,184,138,.15)">🔄</div>
        <div class="edu-card-title">Circular Economy</div>
        <div class="edu-card-desc">Recycled materials feed back into manufacturing — creating jobs, reducing imports of raw materials, and shrinking our collective footprint.</div>
      </div>
    </div>

    <div style="margin-top:56px" class="reveal">
      <div style="text-align:center;margin-bottom:36px">
        <div class="sec-tag">✦ Process</div>
        <h2 class="sec-title">How It <em>Works</em></h2>
      </div>
      <div class="process-steps">
        <?php
        $steps = [
          ['01', 'Submit Your Request', 'Fill out the form with your item details and choose pickup or drop-off. You get a unique tracking ID instantly.'],
          ['02', 'Item Collection', 'Our partner picks up your item or you drop it at the nearest certified center — whichever you chose.'],
          ['03', 'Sorting & Assessment', 'Trained technicians sort, assess, and categorize your item to determine the best recycling pathway.'],
          ['04', 'Recycling & Processing', 'Your item is processed using eco-certified methods — shredded, melted, composted, or refurbished as appropriate.'],
          ['05', 'Quality Check', 'Recycled material is tested for quality. Custom requests (like fabric conversion) are crafted at this stage.'],
          ['06', 'Return to You', 'If you opted for a product return (like a converted item), it is packaged and delivered back to your address.'],
        ];
        foreach ($steps as $s): ?>
          <div class="ps-item">
            <div class="ps-left">
              <div class="ps-circle"><?= $s[0] ?></div>
              <div class="ps-vline"></div>
            </div>
            <div class="ps-right">
              <div class="ps-title"><?= $s[1] ?></div>
              <div class="ps-desc"><?= $s[2] ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="ft-top">
      <div class="ft-brand">
        <div class="ft-logo">🌿 Ecosphere</div>
        <p class="ft-tagline">Building a circular economy through community recycling, creative reuse, and environmental education.</p>
      </div>
      <div class="ft-col">
        <h4>Recycle</h4>
        <a href="#centers">Find Centers</a>
        <a href="#send">Send Item</a>
        <a href="#track">Track Request</a>
      </div>
      <div class="ft-col">
        <h4>Learn</h4>
        <a href="#learn">Why Recycle?</a>
        <a href="#learn">How It Works</a>
        <a href="#">Reuse Hub</a>
      </div>
      <div class="ft-col">
        <h4>Admin</h4>
        <a href="admin/index.php">Dashboard</a>
        <a href="admin/centers.php">Manage Centers</a>
        <a href="admin/requests.php">All Requests</a>
      </div>
    </div>
    <div class="ft-bottom">
      <div class="ft-copy">© 2025 Ecosphere. All rights reserved.</div>
      <div style="font-size:.8rem">Made for the planet 🌍</div>
    </div>
  </footer>

  <div class="toast" id="toast"><span id="toastMsg"></span></div>

  <script>
    // ===== GLOBALS =====
    let activeWasteFilters = [];
    let allCenters = [];

    // ===== SMOOTH SCROLL =====
    function smoothTo(id) {
      document.getElementById(id)?.scrollIntoView({
        behavior: 'smooth'
      });
    }

    // ===== TOAST =====
    function toast(msg, dur = 2800) {
      const t = document.getElementById('toast');
      document.getElementById('toastMsg').textContent = msg;
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), dur);
    }

    // ===== SCROLL REVEAL =====
    const obs = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting) e.target.classList.add('visible')
    }), {
      threshold: .1
    });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

    // ===== WASTE FILTER TOGGLE =====
    function toggleWasteFilter(type, btn) {
      if (activeWasteFilters.includes(type)) {
        activeWasteFilters = activeWasteFilters.filter(t => t !== type);
        btn.classList.remove('active');
      } else {
        activeWasteFilters.push(type);
        btn.classList.add('active');
      }
      renderCenters();
    }

    // ===== SEARCH CENTERS =====
    function searchCenters() {
      const loc = document.getElementById('locationInput').value.trim();
      const grid = document.getElementById('centersGrid');
      grid.innerHTML = '<div class="loading-spinner">🔍 Searching centers…</div>';
      const params = new URLSearchParams();
      if (loc) params.append('location', loc);
      if (activeWasteFilters.length) params.append('waste_types', activeWasteFilters.join(','));
      fetch('fetch_centers.php?' + params.toString())
        .then(r => r.json())
        .then(data => {
          if (data.error) {
            grid.innerHTML = `<div class="no-results"><div class="nr-icon">⚠️</div><p>${data.error}</p></div>`;
            return;
          }
          allCenters = data;
          renderCenters();
        })
        .catch(() => {
          grid.innerHTML = '<div class="no-results"><div class="nr-icon">⚠️</div><p>Could not load centers. Is the server running?</p></div>';
        });
    }

    function renderCenters() {
      const grid = document.getElementById('centersGrid');
      let data = allCenters;
      if (activeWasteFilters.length) {
        data = data.filter(c => activeWasteFilters.every(f => c.waste_types.includes(f)));
      }
      if (!data.length) {
        grid.innerHTML = '<div class="no-results"><div class="nr-icon">🔍</div><p>No centers found. Try a different location or filter.</p></div>';
        return;
      }
      grid.innerHTML = data.map(c => {
        const types = c.waste_types.split(',').map(t => `<span class="waste-tag">${t.trim()}</span>`).join('');
        return `
    <div class="center-card">
      <div class="cc-name">${c.name}</div>
      <div class="cc-addr">📍 ${c.address}, ${c.city} — ${c.pincode}</div>
      <div class="cc-types">${types}</div>
      <div class="cc-contact">📞 ${c.contact}</div>
      ${c.email?`<div class="cc-contact" style="margin-bottom:4px">✉️ ${c.email}</div>`:''}
      <div class="cc-timings">🕐 ${c.timings}</div>
      <button class="btn-send" onclick="prefillCenter('${c.city}')">📦 Send Item Here</button>
    </div>`;
      }).join('');
    }

    function clearSearch() {
      document.getElementById('locationInput').value = '';
      activeWasteFilters = [];
      document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
      searchCenters();
    }

    function prefillCenter(city) {
      document.querySelector('[name="city"]').value = city;
      smoothTo('send');
      toast('✅ Center selected — complete your form below!');
    }

    // ===== SEND FORM =====
    document.getElementById('sendForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const btn = document.getElementById('submitBtn');
      const err = document.getElementById('errMsg');
      const suc = document.getElementById('successMsg');
      err.style.display = 'none';
      suc.style.display = 'none';
      btn.disabled = true;
      btn.textContent = 'Submitting…';
      const fd = new FormData(this);
      try {
        const res = await fetch('send_request.php', {
          method: 'POST',
          body: fd
        });
        const data = await res.json();
        if (data.success) {
          document.getElementById('trackingIdDisplay').textContent = data.tracking_id;
          suc.style.display = 'block';
          this.reset();
          document.getElementById('uploadZone').innerHTML = '<div class="uz-icon">📷</div><div class="uz-text"><strong>Click to upload</strong> or drag & drop<br><span style="font-size:.75rem;color:rgba(90,90,90,.4)">JPG, PNG up to 5MB</span></div>';
          document.getElementById('opt-pickup').classList.add('selected');
          document.getElementById('opt-drop').classList.remove('selected');
          suc.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
          toast('🎉 Request submitted! Tracking ID: ' + data.tracking_id);
        } else {
          err.textContent = '⚠️ ' + (data.error || 'Submission failed. Please try again.');
          err.style.display = 'block';
        }
      } catch (ex) {
        err.textContent = '⚠️ Network error. Please check the server is running.';
        err.style.display = 'block';
      } finally {
        btn.disabled = false;
        btn.innerHTML = '♻️ Submit Recycling Request';
      }
    });

    // ===== METHOD SELECTION =====
    document.querySelectorAll('.method-opt').forEach(opt => {
      opt.addEventListener('click', () => {
        document.querySelectorAll('.method-opt').forEach(o => o.classList.remove('selected'));
        opt.classList.add('selected');
      });
    });

    // ===== IMAGE PREVIEW =====
    function previewImg(e) {
      const f = e.target.files[0];
      if (!f) return;
      const url = URL.createObjectURL(f);
      document.getElementById('uploadZone').innerHTML = `
    <img src="${url}" style="max-height:120px;border-radius:8px;margin-bottom:8px">
    <div class="uz-text" style="font-size:.78rem">${f.name}</div>`;
    }

    // ===== DRAG UPLOAD =====
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
        previewImg({
          target: {
            files: [f]
          }
        });
      }
    });

    // ===== TRACK =====
    async function trackRequest() {
      const id = document.getElementById('trackInput').value.trim().toUpperCase();
      if (!id) {
        toast('⚠️ Please enter a tracking ID');
        return;
      }
      const res = document.getElementById('trackResult');
      const nf = document.getElementById('trackNotFound');
      res.style.display = 'none';
      nf.style.display = 'none';
      res.innerHTML = '<div style="text-align:center;padding:20px;color:var(--leaf)">🔍 Looking up…</div>';
      res.style.display = 'block';
      try {
        const r = await fetch('track.php?id=' + encodeURIComponent(id));
        const data = await r.json();
        if (data.error || !data.tracking_id) {
          res.style.display = 'none';
          nf.style.display = 'block';
          return;
        }
        renderTrackResult(data);
      } catch (ex) {
        res.innerHTML = '<div style="color:var(--terra);padding:16px">⚠️ Server error. Is XAMPP running?</div>';
      }
    }

    const stages = [{
        icon: '📋',
        label: 'Request Submitted',
        desc: 'Your request has been received and logged.'
      },
      {
        icon: '🚚',
        label: 'Item Picked Up',
        desc: 'The item has been collected from your address.'
      },
      {
        icon: '🔧',
        label: 'Processing Started',
        desc: 'Sorting and initial processing underway.'
      },
      {
        icon: '♻️',
        label: 'Recycling Completed',
        desc: 'Material has been fully recycled.'
      },
      {
        icon: '🎁',
        label: 'Product Ready',
        desc: 'Your recycled product or certificate is ready.'
      },
      {
        icon: '🏠',
        label: 'Delivered Back',
        desc: 'Item delivered back to you. Thank you!'
      },
    ];

    function renderTrackResult(d) {
      const current = parseInt(d.status);
      const tl = stages.map((s, i) => {
        const sn = i + 1;
        const st = sn < current ? 'done' : sn === current ? 'current' : 'pending';
        const isLast = i === stages.length - 1;
        return `
    <div class="tl-item">
      <div class="tl-left">
        <div class="tl-dot ${st}">${st==='done'?'✓':st==='current'?s.icon:sn}</div>
        <div class="tl-line ${st==='done'?'done':''} ${isLast?'last':''}"></div>
      </div>
      <div class="tl-right">
        <div class="tl-stage ${st}">${s.label}${st==='current'?' ← Current':''}</div>
        <div class="tl-date">${st==='done'?'Completed':st==='current'?'In Progress':'Pending'}</div>
        <div class="tl-desc">${st!=='pending'?s.desc:''}</div>
      </div>
    </div>`;
      }).join('');

      document.getElementById('trackResult').innerHTML = `
    <div class="tr-name">📦 ${d.user_name}'s Request</div>
    <div class="tr-info">
      <span class="tr-badge cat">♻️ ${d.waste_type}</span>
      <span class="tr-badge method">${d.method==='pickup'?'🚚 Pickup':'🚶 Drop-off'}</span>
      <span class="tr-badge" style="background:rgba(26,58,42,.07);color:var(--forest)">🗓 ${d.created_at.slice(0,10)}</span>
    </div>
    <div class="timeline">${tl}</div>
    ${d.admin_notes?`<div style="margin-top:16px;padding:12px 16px;background:rgba(212,168,67,.08);border-radius:8px;font-size:.84rem;color:var(--earth)">📝 Note: ${d.admin_notes}</div>`:''}
  `;
      document.getElementById('trackResult').style.display = 'block';
      document.getElementById('trackNotFound').style.display = 'none';
    }

    // Allow Enter key on track input
    document.getElementById('trackInput').addEventListener('keydown', e => {
      if (e.key === 'Enter') trackRequest();
    });

    // ===== LOAD CENTERS ON INIT =====
    window.addEventListener('DOMContentLoaded', searchCenters);
  </script>
</body>

</html>