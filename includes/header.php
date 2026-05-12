<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? 'Ecosphere | Clean Today, Green Tomorrow';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
            --soft: #5a5a5a;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--warm); }

        nav {
    /* Purani position sticky ko hata kar fixed kar dein */
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%; /* Puri width cover karne ke liye */
    
    z-index: 9999; /* Isse nav hamesha sabse upar rahega */
    background: rgba(245, 240, 232, 0.98); /* Transparency thodi kam ki hai readable rakhne ke liye */
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(74, 140, 92, 0.1);
    padding: 0 clamp(20px, 5vw, 80px);
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 70px;
}

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--forest);
            text-decoration: none;
        }

        .nav-dot {
            width: 36px; height: 36px;
            background: var(--moss);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }

        .nav-links {
            display: flex;
            gap: 25px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--soft);
            text-decoration: none;
            transition: color 0.3s;
            position: relative;
            padding: 5px 0;
        }

        .nav-links a:hover { color: var(--forest); }
        
        /* Active line effect */
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 2px;
            background: var(--leaf);
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after { width: 100%; }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-auth {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--forest);
            text-decoration: none;
            padding: 8px 16px;
        }

        .nav-cta {
            background: var(--forest);
            color: #fff !important;
            padding: 10px 22px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(26, 58, 42, 0.1);
        }

        .nav-cta:hover {
            background: var(--moss);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(26, 58, 42, 0.15);
        }

        @media (max-width: 1024px) {
            .nav-links { gap: 15px; }
            .nav-links a { font-size: 0.85rem; }
        }

        @media (max-width: 900px) {
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

<header>
    <nav>
        <a href="/Project/index.php" class="nav-logo">
            <div class="nav-dot">🌿</div>
            <span>Ecosphere</span>
        </a>

        <ul class="nav-links">
            <li><a href="/Project/index.php">Home</a></li>
            <li><a href="/Project/reuse/Reuse.php">Reuse</a></li>
            <li><a href="/Project/resell/resell.php">Resell</a></li>
            <li><a href="/Project/recycle/recycle.php">Recycle</a></li>
            <li><a href="/Project/blog/blog.php">Blog</a></li>
            <li><a href="/Project/AboutUs.php">About Us</a></li>
        </ul>

        <div class="nav-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/Project/dashboard/admin/super_admin_hubb.php" class="btn-auth">Admin Panel</a>
                
                
                    <?php else: ?>
                    
                <?php endif; ?>

                <a href="/Project/auth/logout.php" class="nav-cta">Logout</a>
            <?php else: ?>
                <a href="/Project/auth/login.php" class="btn-auth">Login</a>
                <a href="/Project/auth/register.php" class="nav-cta">Get Started</a>
            <?php endif; ?>
        </div>
    </nav>
</header>