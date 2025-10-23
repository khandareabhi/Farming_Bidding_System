<?php
session_start();

// Check if session 'a' exists and contains the farmer's email
if (!isset($_SESSION['a']) || !isset($_SESSION['a']['email'])) {
    echo "<script>
        alert('You need to log in first!');
        window.location.href='login.php';
    </script>";
    exit();
}

$user = $_SESSION['a'];
$farmer_email = $user['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "project1");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data without sanitization
    $p_name = $_POST['p_name'];
    $status = 'pending';
    $min_bidding = $_POST['min_bidding'];
    $time_limit = $_POST['time_limit'];
    $quantity = $_POST['quantity'];

    // Set current timestamp
    date_default_timezone_set('Asia/Kolkata');  
    $timestamp = date('Y-m-d H:i:s'); 

    // Calculate bid_end_time
    $bid_end_time = date('Y-m-d H:i:s', strtotime($time_limit));

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo1"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is valid
    $check = getimagesize($_FILES["photo1"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.'); window.location.href='farmer_dashboard.php';</script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='farmer_dashboard.php';</script>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["photo1"]["tmp_name"], $target_file)) {
            // Secure query using prepared statements
            $sql = "INSERT INTO products (p_name, status, min_bidding, timestamp, time_limit, quantity, bid_end_time, farmer_email, photo1)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("SQL error: " . $conn->error);
            }

            // Fixing time_limit data type
            $stmt->bind_param("ssdssisss", $p_name, $status, $min_bidding, $timestamp, $time_limit, $quantity, $bid_end_time, $farmer_email, $target_file);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Product added successfully with image. Bidding will start after admin approval.');
                    window.location.href='farmer_dashboard.php';
                </script>";
            } else {
                echo "<script>
                    alert('Error: Product not added.');
                    window.location.href='farmer_dashboard.php';
                </script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error uploading image.'); window.location.href='farmer_dashboard.php';</script>";
        }
    }
    $conn->close();
}
?>
