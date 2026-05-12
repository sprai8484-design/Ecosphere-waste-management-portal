<?php
session_start();

/* Admin-only access */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecosphere | Admin Dashboard</title>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="../../assets/css/dashboard_adminn.css">

</head>
<body>

<!-- HEADER -->
<header class="admin-header">
    <h2><i class="fas fa-shield-alt"></i> Master Admin Panel</h2>

    <div class="admin-actions">
        <span><i class="fas fa-user-circle"></i> Admin</span>
        <a href="/Project/index.php" class="btn-outline">
    <i class="fas fa-home"></i> Back to Website
</a>

        </a>
        <a href="/Project/auth/logout.php" class="btn-outline logout">
            Logout
        </a>
    </div>
</header>

<!-- DASHBOARD GRID -->
<section class="admin-grid">


    <div class="admin-card">
        <i class="fas fa-recycle fa-3x"></i>
        <h3>Recycle Centers</h3>
        <p>Add and manage nearby recycling facilities.</p>
        <a href="../../recycle/admin/index.php" class="admin-btn">Open Module</a>
    </div>

    <div class="admin-card">
        <i class="fas fa-shopping-cart fa-3x"></i>
        <h3>Resell Items</h3>
        <p>Approve or remove items listed by users.</p>
        <a href="../../resell/admin/dashboard.php" class="admin-btn">Open Module</a>

    </div>

    <div class="admin-card">
        <i class="fas fa-file-signature fa-3x"></i>
        <h3>Blog Approvals</h3>
        <p>Approve community blogs before publishing.</p>
        <a href="../../blog/admin/manage.php" class="admin-btn">Open Module</a>
    </div>

    <div class="admin-card">
        <i class="fas fa-hands-helping fa-3x"></i>
        <h3>Reuse Items</h3>
        <p>Approve reuse Items for the items </p>
        <a href="../../reuse/admin/manage_reuse.php" class="admin-btn">Open Module</a>
    </div>

</section>

</body>
</html>
