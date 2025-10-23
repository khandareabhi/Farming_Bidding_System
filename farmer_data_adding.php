<?php
session_start();
$conn = new mysqli("localhost", "root", "", "project1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$a = $_POST['name'];        // Full name
$b = $_POST['city'];        // City
$c = $_POST['pincode'];     // Pincode
$d = $_POST['state'];       // State
$e = $_POST['country'];     // Country
$f = $_POST['email'];       // Email
$g = $_POST['password'];    // Password
$aa = $_POST['c_password']; // Confirm password
$role = "Farmer";           // Set role as 'Farmer'

// Validate pincode (must be exactly 6 digits)
if (!preg_match("/^\d{6}$/", $c)) {
    die("<script>alert('Pincode must be exactly 6 digits.'); window.location.href='a.html';</script>");
}

// Validate email format
if (!filter_var($f, FILTER_VALIDATE_EMAIL)) {
    die("<script>alert('Invalid email format. Please enter a valid email address.'); window.location.href='a.html';</script>");
}

// Check if passwords match
if ($g !== $aa) {
    die("<script>alert('Password does not match.'); window.location.href='a.html';</script>");
}

// Check if Full Name and Pincode already exist
$duplicate_check_query = "SELECT * FROM registration WHERE full_name = ? AND pincode = ?";
$stmt_check = $conn->prepare($duplicate_check_query);
$stmt_check->bind_param("ss", $a, $c);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    die("<script>alert('This Full Name and Pincode combination already exists.'); window.location.href='a.php';</script>");
}
$stmt_check->close();

// Check if email already exists
$email_check_query = "SELECT * FROM registration WHERE email = ?";
$stmt_email = $conn->prepare($email_check_query);
$stmt_email->bind_param("s", $f);
$stmt_email->execute();
$result = $stmt_email->get_result();

if ($result->num_rows > 0) {
    die("<script>alert('This email is already registered.'); window.location.href='a.php';</script>");
}
$stmt_email->close();

// Handle Profile Photo Upload
$targetDir = "uploads/"; // Directory to store images
$photoName = basename($_FILES["photo"]["name"]); // Get uploaded file name
$targetFilePath = $targetDir . $photoName; // Full path
$imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

// Check if image file is a real image
$check = getimagesize($_FILES["photo"]["tmp_name"]);
if ($check === false) {
    die("<script>alert('File is not an image.'); window.location.href='a.php';</script>");
}

// Check file size (max 2MB)
if ($_FILES["photo"]["size"] > 2000000) {
    die("<script>alert('File is too large. Max size: 2MB'); window.location.href='a.php';</script>");
}

// Allowed file formats
$allowedFormats = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowedFormats)) {
    die("<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.'); window.location.href='a.html';</script>");
}

// Move uploaded file to target directory
if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
    die("<script>alert('Failed to upload photo. Try again.'); window.location.href='a.php';</script>");
}

// Prepare and bind SQL query for insertion (with photo)
$stmt = $conn->prepare("INSERT INTO registration (full_name, city, pincode, state, country, email, password, role, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

// Bind parameters
$stmt->bind_param("sssssssss", $a, $b, $c, $d, $e, $f, $g, $role, $targetFilePath);

// Execute the statement
if ($stmt->execute()) {
    echo "<script>alert('Registration Successful'); window.location.href='a.php';</script>";
    exit();
} else {
    echo "<script>alert('Failed to register. Please try again.'); window.location.href='a.php';</script>";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
