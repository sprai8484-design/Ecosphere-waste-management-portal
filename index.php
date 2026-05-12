<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<link rel="stylesheet" href="assets/css/index.css">

<section class="hero-slider swiper" id="home">
    <div class="swiper-wrapper">
        <div class="swiper-slide slide-1">
            <div class="slide-content">
                <span class="slide-subtitle">Responsible Disposal</span>
                <h1>Smart Recycling for Mumbai</h1>
                <p>Turn your household waste into valuable resources. Let's keep our landfills empty and our city clean.</p>
                <a href="/Project/recycle/recycle.php" class="nav-cta">Start Recycling</a>
            </div>
        </div>

        <div class="swiper-slide slide-2">
            <div class="slide-content">
                <span class="slide-subtitle">Sustainable Marketplace</span>
                <h1>Pre-loved Items, New Stories</h1>
                <p>Buy and sell quality used goods within your community. Save money and reduce production waste.</p>
                <a href="/Project/resell/resell.php" class="nav-cta">Browse Marketplace</a>
            </div>
        </div>

        <div class="swiper-slide slide-3">
            <div class="slide-content">
                <span class="slide-subtitle">Creative Upcycling</span>
                <h1>The Art of Reuse</h1>
                <p>Discover innovative ways to repurpose everyday objects. Small changes lead to a massive impact.</p>
                <a href="/Project/reuse/Reuse.php" class="nav-cta">Explore Reuse Hub</a>
            </div>
        </div>

        <div class="swiper-slide slide-4">
            <div class="slide-content">
                <span class="slide-subtitle">Eco-Education</span>
                <h1>Knowledge for a Greener Future</h1>
                <p>Read expert tips on urban farming, zero-waste living, and the latest environmental news.</p>
                <a href="/Project/blog/blog.php" class="nav-cta">Read Our Blog</a>
            </div>
        </div>
    </div>
    
    <div class="swiper-pagination"></div>
</section>

<style>
    /* Slider Content Styling to match your aesthetic */
    .slide-content {
        text-align: center;
        max-width: 800px;
        padding: 20px;
    }

    .slide-subtitle {
        display: block;
        font-family: 'DM Mono', monospace;
        color: var(--sage);
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .slide-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.5rem, 5vw, 4rem);
        color: #fff;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .slide-content p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    /* Background overlay for better text readability */
    .swiper-slide::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(26, 58, 42, 0.4), rgba(26, 58, 42, 0.7));
        z-index: -1;
    }
</style>

<section class="why-ecosphere-section">
  <div class="container">
    <div class="section-title">
      <h2>Why Ecosphere?</h2>
      <p>Because clean surroundings begin with responsible individuals.</p>
    </div>
    
    <div class="strict-grid">
      <div class="strict-card">
        <div class="strict-image-box">
          <img src="assets/images/reusee.jpg" alt="Waste reporting">
        </div>
        <div class="strict-content">
          <h3>Reuse</h3>
          <p>Give everyday items a new purpose—discover creative ways to upcycle materials and reduce waste through sustainable innovation.</p>
          <a href="reuse/Reuse.php" class="vibe-link">Reuse Now →</a>
        </div>
      </div>

      <div class="strict-card">
        <div class="strict-image-box">
          <img src="assets/images/resell.jpg" alt="Reuse and resell items">
        </div>
        <div class="strict-content">
          <h3>Resell</h3>
          <p>One person's trash is another's treasure—declutter your space and give pre-loved items a second life by connecting with buyers.</p>
          <a href="resell/resell.php" class="vibe-link">Start Reselling →</a>
        </div>
      </div>

      <div class="strict-card">
        <div class="strict-image-box">
          <img src="assets/images/recycle1.jpg" alt="Recycle center">
        </div>
        <div class="strict-content">
          <h3>Recycle</h3>
          <p>Ensure your waste ends up in the right place—easily locate verified recycling centers and drop-off points in your local area.</p>
          <a href="recycle/recycle.php" class="vibe-link">Recycle Centers →</a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about-ecosphere">
    <div class="container">
        <div class="about-flex">
            <div class="about-image">
                <img src="assets/images/resell.jpg" alt="Ecosphere Movement">
            </div>
            <div class="about-text">
                <span class="subtitle">A Movement for Change</span>
                <h2>Clean Today, Green Tomorrow</h2>
                <p>
                    Ecosphere is a youth-led movement dedicated to transforming waste management in India. 
                    Through smart reporting and community mobilization, we aim to bridge the gap between 
                    responsible citizens and efficient waste disposal authorities.
                </p>
                <a href="LearnMore.php" class="btn-minimal">Learn More</a>
            </div>
        </div>
    </div>
</section>

<section class="waste-types-section">
  <div class="container">
    <div class="section-title">
      <h2>Types of Waste We Handle</h2>
      <p>Focused solutions for different categories of waste.</p>
    </div>

    <div class="waste-grid">
      <div class="waste-item">
        <div class="waste-icon"><i class="fas fa-bottle-water"></i></div>
        <h3>Plastic Waste</h3>
        <p>Recycling and reuse solutions for single-use and hard plastics.</p>
      </div>

      <div class="waste-item">
        <div class="waste-icon"><i class="fas fa-leaf"></i></div>
        <h3>Organic Waste</h3>
        <p>Composting and eco-friendly disposal of biodegradable waste.</p>
      </div>

      <div class="waste-item">
        <div class="waste-icon"><i class="fas fa-laptop"></i></div>
        <h3>E-Waste</h3>
        <p>Safe collection and recycling of electronic and electrical waste.</p>
      </div>

      <div class="waste-item">
        <div class="waste-icon"><i class="fas fa-building"></i></div>
        <h3>Construction Waste</h3>
        <p>Responsible handling of construction and demolition debris.</p>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="assets/js/home.js"></script>

<?php include 'includes/footer.php'; ?>