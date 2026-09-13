<?php

$conn = new mysqli(
    "sql200.infinityfree.com",
    "if0_42907870",
    "YOUR_DATABASE_PASSWORD",
    "if0_42907870_project1"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
