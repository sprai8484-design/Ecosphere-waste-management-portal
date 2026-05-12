<?php
session_start();
require '../../config/db.php';
require '../../auth/auth_check.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Fetch all reports
$query = "SELECT waste_reports.*, users.name AS user_name, users.email AS user_email 
          FROM waste_reports 
          LEFT JOIN users ON waste_reports.user_id = users.id 
          ORDER BY waste_reports.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ecosphere | Central Admin</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <style>
        /* Basic layout styling */
        body { display: flex; margin: 0; font-family: Arial, sans-serif; background: #f4f7f6; }
        
        /* Sidebar Styling */
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; position: fixed; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 20px; color: #27ae60; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; padding: 15px 25px; text-decoration: none; transition: 0.3s; border-left: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; border-left: 4px solid #27ae60; }
        
        /* Main Content Styling */
        .main-content { margin-left: 250px; width: calc(100% - 250px); padding: 20px; }
        header.dash-header { display: flex; justify-content: space-between; align-items: center; background: white; padding: 15px 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .logout-btn { background: #e74c3c; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
        
        /* Table Styling */
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #27ae60; color: white; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>ECOSPHERE</h2>
        <a href="admin_dashboard.php" class="active">📊 Waste Reports</a>
        <a href="manage_resell.php">💰 Resell Items</a>
        <a href="manage_reuse.php">🔄 Reuse Guides</a>
        <a href="manage_recycle.php">♻️ Recycle Blog</a>
        <a href="manage_volunteers.php">🤝 Volunteers</a>
        <a href="settings.php">⚙️ Settings</a>
    </div>

    <div class="main-content">
        <header class="dash-header">
            <h2>Admin Control Panel</h2>
            <div>
                <span>Welcome, <strong>Admin</strong></span>
                <a href="../../auth/logout.php" class="logout-btn" style="margin-left:15px;">Logout</a>
            </div>
        </header>

        <section class="reports">
            <h3>Recent Waste Reports</h3>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Location</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['user_name'] ?? 'Unknown') ?></td>
                        <td><?= htmlspecialchars($row['waste_type']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="../../uploads/<?= htmlspecialchars($row['image']) ?>" style="width:60px; border-radius:4px;">
                            <?php else: ?>
                                <small>No Image</small>
                            <?php endif; ?>
                        </td>
                        <td><span class="status-badge"><?= ucfirst($row['status']) ?></span></td>
                        <td>
                            <form action="update_status.php" method="POST" style="display:flex; gap:5px;">
                                <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="approved" <?= $row['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>

</body>
</html>