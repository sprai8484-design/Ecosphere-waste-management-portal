<?php
session_start();
require '../../config/db.php';
require '../../auth/auth_check.php';

/* Only user access */
if ($_SESSION['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Fetch user reports (INCLUDING DESCRIPTION) */
$stmt = $conn->prepare(
    "SELECT 
        title,
        description,
        waste_type,
        location,
        image,
        status,
        created_at
     FROM waste_reports 
     WHERE user_id = ? 
     ORDER BY created_at DESC"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$reports = $stmt->get_result();
?>
<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard | Ecosphere</title>
    <link rel="stylesheet" href="../../assets/css/manage_report.css">
</head>

<body>

    <div class="dashboard">

        <header class="dash-header">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h2>
            <a href="../../auth/logout.php" class="logout-btn">Logout</a>
        </header>

        <section class="stats">
            <a href="../../report.php" class="btn">+ New Report</a>
        </section>

        <section class="reports">
            <h3>My Reports</h3>

            <?php if ($reports->num_rows === 0): ?>
                <p>No reports submitted yet.</p>
            <?php else: ?>

                <table>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Waste Type</th>
                        <th>Location</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                    <?php while ($row = $reports->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>

                            <td>
                                <?= nl2br(htmlspecialchars($row['description'])) ?>
                            </td>

                            <td><?= htmlspecialchars($row['waste_type']) ?></td>
                            <td><?= htmlspecialchars($row['location']) ?></td>

                            <!-- IMAGE COLUMN -->
                            <td>
                                <?php if (!empty($row['image'])): ?>
                                    <img
                                        src="../../uploads/<?= htmlspecialchars($row['image']) ?>"
                                        alt="My Report Image"
                                        style="width:70px; height:50px; object-fit:cover; border-radius:6px;">
                                <?php else: ?>
                                    <small>No Image</small>
                                <?php endif; ?>
                            </td>

                            <td class="status <?= strtolower($row['status']) ?>">
                                <?= ucfirst($row['status']) ?>
                            </td>

                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>



                    <?php endwhile; ?>
                </table>

            <?php endif; ?>
        </section>

    </div>

</body>

</html>