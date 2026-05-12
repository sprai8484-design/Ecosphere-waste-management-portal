<?php
session_start();

/* Admin-only access */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Recycle Centers</title>
</head>
<body>

<h1>Recycle Centers – Admin Panel</h1>

<p>If you can see this page, routing & session are working ✅</p>

<a href="dashboard_admin.php">⬅ Back to Admin Dashboard</a>

</body>
</html>
