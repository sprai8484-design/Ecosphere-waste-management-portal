<?php
session_start();
include '../../config/db.php'; 

// 1. Auth Guard
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Data Fetching with Safety Checks
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = ($user_q && mysqli_num_rows($user_q) > 0) ? mysqli_fetch_assoc($user_q) : [];

// Reports Count & List
$rep_q = mysqli_query($conn, "SELECT * FROM reports WHERE user_id = '$user_id' ORDER BY id DESC");
$total_reports = ($rep_q) ? mysqli_num_rows($rep_q) : 0;

// Volunteer Status check
$email = $user['email'] ?? '';
$vol_q = mysqli_query($conn, "SELECT * FROM volunteers WHERE email = '$email'");
$vol_count = ($vol_q) ? mysqli_num_rows($vol_q) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecosphere India | User Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/Profile.css">
</head>
<body>

<div class="app-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <span class="logo-text">ECOSPHERE</span>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-group">Navigate</div>
            <a href="profile.php" class="nav-item active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="my_reports.php" class="nav-item"><i class="fas fa-file-contract"></i> My Reports</a>
            <a href="#" class="nav-item"><i class="fas fa-pen-nib"></i> My Blogs <span class="badge-soon">Soon</span></a>
            <a href="#" class="nav-item"><i class="fas fa-medal"></i> Impact Stats</a>
            
            <div class="nav-group" style="margin-top:20px;">Account Management</div>
            <a href="edit_profile.php" class="nav-item"><i class="fas fa-user-cog"></i> Profile Settings</a>
            <a href="../../auth/logout.php" class="nav-item logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="top-bar">
            <h1>User Dashboard</h1>
            <div class="user-profile-pill">
                <i class="fas fa-user-circle"></i>
                <span><?php echo htmlspecialchars($user['fullname'] ?? 'Eco Member'); ?></span>
            </div>
        </header>

        <div class="stats-row">
            <div class="stat-card">
                <div class="icon-wrap rep"><i class="fas fa-trash-restore"></i></div>
                <div class="stat-info"><h3><?php echo $total_reports; ?></h3><p>Reports Made</p></div>
            </div>
            <div class="stat-card">
                <div class="icon-wrap vol"><i class="fas fa-hands-helping"></i></div>
                <div class="stat-info"><h3><?php echo ($vol_count > 0) ? 'Active' : 'N/A'; ?></h3><p>Volunteerships</p></div>
            </div>
            <div class="stat-card">
                <div class="icon-wrap blog"><i class="fas fa-newspaper"></i></div>
                <div class="stat-info"><h3>0</h3><p>Blogs Posted</p></div>
            </div>
        </div>

        <div class="content-grid">
            <section class="card section-reports">
                <div class="card-header">
                    <h3>My Reported Waste Locations</h3>
                    <a href="../../report.php" class="btn-primary">+ Report New</a>
                </div>
                <div class="table-container">
                    <table class="hub-table">
                        <thead>
                            <tr>
                                <th>Location Address</th>
                                <th>Waste Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($total_reports > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($rep_q)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(substr($row['address'], 0, 40)); ?>...</td>
                                    <td><?php echo $row['waste_type']; ?></td>
                                    <td><span class="status-pill <?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                                    <td><a href="#" class="btn-link">Edit</a> | <a href="#" class="btn-link delete">Delete</a></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="empty-msg">No reports found. Help us clean India!</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="card section-blogs">
                <div class="card-header">
                    <h3>My Blogs</h3>
                    <button class="btn-secondary" disabled>Add Blog</button>
                </div>
                <div class="empty-placeholder">
                    <i class="fas fa-edit"></i>
                    <p>Coming Soon: Share your eco-stories with the community.</p>
                </div>
            </section>
        </div>
    </main>
</div>

</body>
</html>