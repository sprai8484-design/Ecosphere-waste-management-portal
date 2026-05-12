<?php
$conn = new mysqli("localhost", "root", "", "ecosphere");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


