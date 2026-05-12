<?php
session_start();

/* User-only access */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../../auth/login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'User';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ecosphere | User Dashboard</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../../assets/css/dashboard_user.css">
</head>
<body>

<header class="user-header">
    <h2><i class="fas fa-leaf"></i> My Dashboard</h2>

    <div class="user-actions">
        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?></span>

        <a href="/Project/index.php" class="btn-outline">Home</a>
        <a href="/Project/auth/logout.php" class="btn-outline logout">Logout</a>
    </div>
</header>

<section class="user-grid">

    <div class="user-card">
        <i class="fas fa-trash-alt"></i>
        <h3>My Waste Reports</h3>
        <p>Track the status of reports you’ve submitted.</p>
        <a href="my_reports.php" class="user-btn">View Reports</a>
    </div>

    <div class="user-card">
        <i class="fas fa-plus-circle"></i>
        <h3>Submit New Report</h3>
        <p>Report unclean places around you.</p>
        <a href="my_listingsblog.php" class="user-btn">Create Report</a>
    </div>

    <div class="user-card">
        <i class="fas fa-user-cog"></i>
        <h3>My Profile</h3>
        <p>View and manage your profile details.</p>
        <a href="profile.php" class="user-btn">Open Profile</a>
    </div>

</section>

</body>
</html>
