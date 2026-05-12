<?php
session_start();
require '../../config/db.php';
require '../../auth/auth_check.php';

/* Only user access */
if ($_SESSION['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}

/* Fetch approved & active reports with uploader name */
$stmt = $conn->prepare(
    "SELECT w.*, u.name 
     FROM waste_reports w
     JOIN users u ON w.user_id = u.id
     WHERE w.status IN ('Approved','In Progress','Resolved')
     ORDER BY w.created_at DESC"
);

$stmt->execute();
$reports = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Community Feed | Ecosphere</title>
    <link rel="stylesheet" href="../../assets/css/manage_report.css">
</head>
<body>

<div class="dashboard">

    <header class="dash-header">
        <h2>Community Reports</h2>
        <a href="../../auth/logout.php" class="logout-btn">Logout</a>
    </header>

    <section class="stats">
        <a href="my_reports.php" class="btn">← Back to My Reports</a>
    </section>

    <section class="reports">
        <h3>Approved & Active Reports</h3>

        <?php if ($reports->num_rows === 0): ?>
            <p>No community reports available yet.</p>
        <?php else: ?>

            <?php while ($row = $reports->fetch_assoc()): ?>

                <div style="background:#fff; padding:20px; margin-bottom:20px; border-radius:10px; box-shadow:0 5px 15px rgba(0,0,0,0.05);">

                    <!-- Title -->
                    <h4><?= htmlspecialchars($row['title']) ?></h4>

                    <!-- Uploaded by -->
                    <p><strong>Uploaded By:</strong> <?= htmlspecialchars($row['name']) ?></p>

                    <!-- Description -->
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

                    <!-- Details -->
                    <p>
                        <strong>Waste Type:</strong> <?= htmlspecialchars($row['waste_type']) ?><br>
                        <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
                        <strong>Date:</strong> <?= date('d M Y', strtotime($row['created_at'])) ?>
                    </p>

                    <!-- Before Image -->
                    <?php if (!empty($row['image'])): ?>
                        <div style="margin-top:10px;">
                            <strong>Before:</strong><br>
                            <img src="../../uploads/<?= htmlspecialchars($row['image']) ?>"
                                 style="width:250px; border-radius:8px; margin-top:5px;">
                        </div>
                    <?php endif; ?>

                    <!-- Status -->
                    <p class="status <?= strtolower($row['status']) ?>">
                        <strong>Status:</strong> <?= ucfirst($row['status']) ?>
                    </p>

                    <!-- Resolution Image (if resolved) -->
                    <?php if ($row['status'] === 'Resolved' && !empty($row['resolution_image'])): ?>
                        <div style="margin-top:10px;">
                            <strong>After (Resolved Proof):</strong><br>
                            <img src="../../uploads/resolutions/<?= htmlspecialchars($row['resolution_image']) ?>"
                                 style="width:250px; border-radius:8px; margin-top:5px;">
                        </div>

                        <?php if (!empty($row['admin_note'])): ?>
                            <p style="margin-top:10px;">
                                <strong>Admin Note:</strong>
                                <?= nl2br(htmlspecialchars($row['admin_note'])) ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>

        <?php endif; ?>
    </section>

</div>

</body>
</html>
