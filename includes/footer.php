<style>
    /* ── UPDATED FOOTER STYLES ─────────────────────────────────────── */
    footer {
        background: var(--forest);
        color: rgba(255, 255, 255, 0.7);
        padding: 60px clamp(20px, 5vw, 80px) 30px;
        margin-top: 80px;
        font-family: 'DM Sans', sans-serif;
    }

    .ft-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .ft-logo-area h4 {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .ft-tagline {
        font-size: 0.9rem;
        line-height: 1.6;
        max-width: 300px;
    }

    .ft-col h4 {
        color: var(--mint);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 20px;
    }

    .ft-col a {
        display: block;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }

    .ft-col a:hover {
        color: #fff;
        padding-left: 5px;
    }

    .social-icons {
        display: flex;
        gap: 16px;
        margin-top: 20px;
    }

    .social-icons img {
        filter: invert(1);
        transition: transform 0.3s ease, opacity 0.3s;
        opacity: 0.8;
    }

    .social-icons a:hover img {
        transform: translateY(-3px);
        opacity: 1;
    }

    .ft-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 0.8rem;
    }

    @media (max-width: 768px) {
        .ft-grid { grid-template-columns: 1fr; gap: 30px; }
        .ft-bottom { flex-direction: column; text-align: center; }
    }
</style>

<footer>
    <div class="ft-grid">
        <div class="ft-logo-area">
            <h4>🌿 Ecosphere</h4>
            <p class="ft-tagline">A digital initiative promoting responsible waste management and sustainable living in Mumbai.</p>
            
            <div class="social-icons">
                <a href="#" aria-label="Instagram">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/instagram.svg" width="22">
                </a>
                <a href="#" aria-label="Twitter">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/twitter.svg" width="22">
                </a>
                <a href="#" aria-label="LinkedIn">
                    <img src="https://cdn.jsdelivr.net/npm/simple-icons@v9/icons/linkedin.svg" width="22">
                </a>
            </div>
        </div>

        <div class="ft-col">
            <h4>Services</h4>
            <a href="/Project/recycle/recycle.php">Recycle Center</a>
            <a href="/Project/resell/resell.php">Marketplace</a>
            <a href="/Project/reuse/Reuse.php">Reuse Hub</a>
            <a href="/Project/blog/blog.php">Eco Blog</a>
        </div>

        <div class="ft-col">
            <h4>Company</h4>
            <a href="/Project/AboutUs.php">About Us</a>
            
            <a href="/Project/AboutUs.php">Privacy Policy</a>
            
        </div>
    </div>

    <div class="ft-bottom">
        <div>© <?= date('Y') ?> <strong>Ecosphere</strong>. Built for a Greener Tomorrow.</div>
        
    </div>
</footer>

<script src="assets/js/script.js"></script>