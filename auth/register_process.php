<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$password = $_POST['password'];

if (empty($name) || empty($email) || empty($password)) {
    header('Location: register.php?error=All fields are required');
    exit;
}

/* Check if email exists */
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    header('Location: register.php?error=Email already registered');
    exit;
}

/* Hash password */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* Insert user */
$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password) VALUES (?, ?, ?)"
);
$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    header('Location: login.php?success=Account created successfully');
} else {
    header('Location: register.php?error=Registration failed');
}

$stmt->close();
$conn->close();
