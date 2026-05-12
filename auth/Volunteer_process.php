<?php
session_start();
include '../config/db.php'; // Path to your connection file

if (isset($_POST['submit_volunteer'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO volunteers (fullname, email, phone, city, message) 
              VALUES ('$fullname', '$email', '$phone', '$city', '$message')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Thank you for volunteering!'); window.location.href='../Volunteer.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>