<?php
$servername = "localhost"; // Change if necessary
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP has no password
$database = "project1"; // Change this prto your actual database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
