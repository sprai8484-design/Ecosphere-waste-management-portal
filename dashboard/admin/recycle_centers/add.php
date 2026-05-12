<?php
session_start();

// Include DB connection and auth check
require '../../../config/db.php';
require '../../../auth/auth_check.php';

// Admin only check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../auth/login.php");
    exit;
}

// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and sanitize input
    $name    = trim($_POST['name']);
    $area    = trim($_POST['area']);
    $address = trim($_POST['address']);
    $timings = trim($_POST['timings']);
    $status  = trim($_POST['status']);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO recycle_centers (name, area, address, timings, status) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $area, $address, $timings, $status);

    try {
        $stmt->execute();
        $success = true;
    } catch (Exception $e) {
        die("Insert failed: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Recycle Center | Ecosphere</title>
    <link rel="stylesheet" href="../../../assets/css/manage_report.css"> <!-- same CSS folder as my_reports.php -->
</head>
<body>

<div class="dashboard">

    <header class="dash-header">
        <h2>Add Recycle Center</h2>
        <a href="../../../auth/logout.php" class="logout-btn">Logout</a>
    </header>

    <section class="reports">
        <?php if ($success): ?>
            <p style="color:green;">Recycle center added successfully.</p>
        <?php endif; ?>

        <form method="POST">
            <label>Center Name</label><br>
            <input type="text" name="name" required><br><br>

            <label>Area / City</label><br>
            <input type="text" name="area" required><br><br>

            <label>Full Address</label><br>
            <textarea name="address" required></textarea><br><br>

            <label>Timings</label><br>
            <input type="text" name="timings" placeholder="9 AM – 6 PM (Mon–Sat)" required><br><br>

            <label>Status</label><br>
            <select name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select><br><br>

            <button type="submit">Add Center</button>
        </form>
    </section>

</div>

</body>
</html>
