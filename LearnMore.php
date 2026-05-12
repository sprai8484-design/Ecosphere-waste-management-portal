<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
?>

<link rel="stylesheet" href="assets/css/LearMore.css"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

<?php include 'includes/header.php'; ?>

<main class="learn-more-container">
    <section class="section">
        <h2 class="section-title">Our Sustainable Ecosystem</h2>
        <div class="cards">
            <div class="card">
                <img src="/Project/assets/images/webreuse1.jpg" alt="Smart Recycling">
                <div class="card-content">
                    <h3>Smart Recycling</h3>
                    <p>We bridge the gap between households and verified scrap dealers in Mumbai. Transform your paper, plastic, and metal waste into revenue while contributing to environmental conservation.</p>
                </div>
            </div>

            <div class="card">
                <img src="assets/images/reshop.jpg" alt="Marketplace">
                <div class="card-content">
                    <h3>Community Marketplace</h3>
                    <p>Give your pre-loved electronics, furniture, and clothing a second life. Our platform provides a secure and eco-friendly marketplace to trade quality used goods within your community.</p>
                </div>
            </div>

            <div class="card">
                <img src="assets/images/webreuse.jpg" alt="Upcycling Hub">
                <div class="card-content">
                    <h3>The Reuse Hub</h3>
                    <p>Discover innovative upcycling projects and practical zero-waste lifestyle hacks. Learn how to repurpose everyday items effectively to minimize your environmental footprint.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section light-bg">
        <div class="two-column">
            <div class="text-content">
                <h2>The Ecosphere Vision</h2>
                <p>Ecosphere Mumbai was established on a fundamental principle: waste is not waste until we waste its potential value.</p>
                <p>We have engineered a platform where technology meets sustainability to foster a <strong>Circular Economy</strong>. Our mission is to ensure that every discarded item finds a path toward value recovery and reuse.</p>
            </div>
            <div class="image-content">
                <img src="assets/images/volunteers.jpg" alt="Ecosphere Vision">
            </div>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Our Environmental Impact</h2>
        <div class="impact-box">
            <div class="impact-item">
                <h3>✔ Waste Diversion</h3>
                <p>Redirecting tons of household and electronic waste away from Mumbai's saturated landfills.</p>
            </div>
            <div class="impact-item">
                <h3>✔ Resource Recovery</h3>
                <p>Facilitating the systematic recovery of raw materials through organized and ethical recycling channels.</p>
            </div>
            <div class="impact-item">
                <h3>✔ Conscious Consumerism</h3>
                <p>Cultivating a community that prioritizes sustainable acquisition over new manufacturing.</p>
            </div>
        </div>
    </section>

    <section class="section light-bg">
        <div class="two-column reverse">
            <div class="image-content">
                <img src="assets/images/ecosphere.jpg" alt="Get Started">
            </div>
            <div class="text-content">
                <h2>How You Can Contribute</h2>
                <p>Becoming a part of the Ecosphere movement is simple. You can initiate positive environmental change right from your home:</p>
                <ul class="styled-list">
                    <li>Schedule a pickup for your recyclable materials (Scrap).</li>
                    <li>List your pre-owned items on our Community Marketplace.</li>
                    <li>Adopt sustainable practices through our educational blog.</li>
                    <li>Support local eco-conscious brands and upcycled products.</li>
                </ul>
                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="resell/resell.php" class="btn-secondary">Explore Marketplace</a>
                    <a href="recycle/recycle.php" class="btn-secondary" style="background: var(--forest); color: white;">Recycle Now</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section final-call">
        <h2>Ready to make a difference?</h2>
        <p class="center-text">
            Join thousands of Mumbaikars dedicated to making our city cleaner and more sustainable, one transaction at a time. <br>
            <strong>Ecosphere: Empowering a Greener Today for a Cleaner Tomorrow.</strong>
        </p>
    </section>
</main>

<?php include 'includes/footer.php'; ?>