<?php
// admin/auth.php — Include at top of every admin page
session_start();
require_once '../config.php';

if (empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: login.php');
    exit;
}

function adminLogout(): void {
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    adminLogout();
}

$adminUser = $_SESSION['admin_user'] ?? 'Admin';
