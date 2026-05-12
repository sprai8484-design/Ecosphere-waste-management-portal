<?php
session_start();
require '../../config/db.php';
require '../../auth/auth_check.php';

if ($_SESSION['role'] !== 'user') {
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT * FROM waste_reports 
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track My Reports</title>
</head>
<body>

<h2>My Report Status</h2>

<?php while ($row = $result->fetch_assoc()): ?>

    <div style="border:1px solid #ccc; padding:15px; margin-bottom:20px;">
        <h3><?= htmlspecialchars($row['title']) ?></h3>

        <p><strong>Description:</strong><br>
            <?= nl2br(htmlspecialchars($row['description'])) ?>
        </p>

        <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>

        <?php if (!empty($row['image'])): ?>
            <p><strong>Before:</strong><br>
                <img src="../../uploads/<?= htmlspecialchars($row['image']) ?>" width="200">
            </p>
        <?php endif; ?>

        <?php if ($row['status'] === 'Resolved'): ?>

            <?php if (!empty($row['resolution_image'])): ?>
                <p><strong>After:</strong><br>
                    <img src="../../uploads/resolutions/<?= htmlspecialchars($row['resolution_image']) ?>" width="200">
                </p>
            <?php endif; ?>

            <?php if (!empty($row['admin_note'])): ?>
                <p><strong>Admin Note:</strong><br>
                    <?= nl2br(htmlspecialchars($row['admin_note'])) ?>
                </p>
            <?php endif; ?>

            <p><strong>Resolved At:</strong>
                <?= date("d M Y H:i", strtotime($row['resolved_at'])) ?>
            </p>

        <?php endif; ?>

    </div>

<?php endwhile; ?>

</body>
</html>
