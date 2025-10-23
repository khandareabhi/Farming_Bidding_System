<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "project1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$a = $_POST['email']; // assuming Email_id  is coming from a form
$b = $_POST['password']; // assuming Password is coming from a form

// Use prepared statements to prevent SQL injection
$sql = "SELECT full_name, city, pincode, state,country,email,password ,role,photo  FROM registration WHERE email = ? AND password = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
}

mysqli_stmt_bind_param($stmt, "ss", $a, $b);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['a'] = [
        'full_name' => $row['full_name'],
        'city' => $row['city'],
        'pincode' => $row['pincode'],
        'state' => $row['state'],
        'country' =>$row['country'],
        'email'=>$row['email'],
        'password'=>$row['password'],
        'photo' => $row['photo'], 
    ]; // Store the user data as an array
    $_SESSION['role'] = $row['role']; // Add the user's role to the session

    // Define expected roles
    $expectedRoles = ['Admin', 'user']; // Adjust as per your needs

    if (in_array($row['role'], $expectedRoles)) {
        echo "<script>
            alert('Login successful');
            window.location.href='a.php';
        </script>";
    } else {
        echo "<script>
            alert('Invalid role');
            window.history.back(); // Redirect to the previous page
        </script>";
    }
    exit();
} else {
    echo "<script>
        alert('Login failed');
        window.location.href='a.php';
    </script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
